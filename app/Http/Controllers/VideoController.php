<?php

namespace App\Http\Controllers;

use App\Video;
use App\Media;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory as Validator;

class VideoController extends Controller
{
    
    protected $video;
    
    protected $validator;
    
    protected $media;

    public function __construct(Video $video, Validator $validator, Media $media) {
        $this->video = $video;
        $this->validator = $validator;
        $this->media = $media;
    }

    public function index() {
        
        return view('video.index', ['test' => 3]);
    }
    
    public function getVideoList(Request $request) {
        
        $params = $request->all();
        $sortOrder = $params['order'][0];
        $paginate = ['offset' => $params['start'], 'limit' => $params['length']];
        $search = $params['search']['value'];
        
        $videoList = $this->video->getVideoList($sortOrder, $paginate, $search);
        
        return response()->json([
            'data' => $videoList['records'], 
            'totalRecords' => $videoList['totalRecords'],
            'recordsFiltered' => $videoList['totalRecords']
        ]);
    }
    
    public function saveVideo(Request $request) {
       
        try {
            $params = $request->all();
            
            $validator = $this->validator->make($params, $this->video->validateCreate());
        
            if ($validator->fails()) {
                return response()->json(['status' => 0, 'errors' => $validator->getMessageBag()]);
            }

            $imageIds = [];
            $audioIds = [];

            foreach ($request->imagesPath as $path) {
                $media = $this->media->create(['file_path' => $path, 'type' => 'image']);
                $imageIds[] = $media->id;
            }

            foreach ($request->audioPath as $path) {
                $media = $this->media->create(['file_path' => $path, 'type' => 'audio']);
                $audioIds[] = $media->id;
            }
            
            $this->video->create([
                'title' => $params['title'],
                'loop' => $params['loop'],
                'fade' => $params['fade'],
                'description' => $params['description'],
                'images' => implode(',', $imageIds),
                'audio' => implode(',', $audioIds)
            ]);
            
            return response()->json(['status' => 1, 'message' => 'Save video successfully']);
            
        } catch (Exception $ex) {
            return response()->json(['status' => 0, 'message' => 'Server error']);
        }
        
    }
    
    public function encode(Request $requset) {
        $id = $requset->input('id');
        $video = $this->video->findById($id);
        
        try {
            // Set timeout to unlimited;
            set_time_limit(0);
            
            // Get all image image in array
            $imagesInArray = explode(',', $video->images);
            $image = $this->media->findById($imagesInArray[0]);

            // Get image url
            $imageUrl = public_path(ltrim($image->file_path, '/'));

            // Get audio in array
            $audioInArray = explode(',', $video->audio);
            $audioCollection = $this->media->whereIn('id', $audioInArray)->get();

            $inputArray = [];
            foreach($audioCollection as $audio) {
                $inputArray[] = '-i ' . public_path( ltrim($audio->file_path) );
            }

            // Get merge audio
            $mergeAudioPath = $this->media->mergeAudio($inputArray);
            
            // output video
            $output = public_path('videos/' . uniqid() . '.mp4');
            
            // Encode video 
            $this->media->createVideo($imageUrl, $mergeAudioPath, $output);
            
            // Set timeout back to default;
            set_time_limit(300);
            
            return response()->json(['status' => 1, 'message' => 'Encode video successfully']);
            
        } catch (\Exception $ex) {
//            echo $ex;
            return response()->json(['status' => 0, 'message' => 'Server error']);
        }
    }
}
