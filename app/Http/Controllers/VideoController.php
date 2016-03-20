<?php

namespace App\Http\Controllers;

use App\Video;
use App\Media;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory as Validator;
use App\Log;
use Carbon\Carbon;
use Illuminate\Session\Store as Session;

class VideoController extends Controller
{
    
    protected $video;
    
    protected $validator;
    
    protected $media;
    
    protected $carbon;
    
    protected $log;
    
    protected $session;

    public function __construct(Video $video, Validator $validator, Media $media, Carbon $carbon, Log $log, Session $session) {
        $this->video = $video;
        $this->validator = $validator;
        $this->media = $media;
        $this->carbon = $carbon;
        $this->log = $log;
        $this->session = $session;
    }

    public function index() {
        
        return view('video.index', ['lastTimeOpened' => $this->carbon->now()->toDateTimeString()]);
    }
    
    public function getVideoList(Request $request) {
        
        $params = $request->all();
        $sortOrder = $params['order'][0];
        $paginate = ['offset' => $params['start'], 'limit' => $params['length']];
        $search = $params['search']['value'];
        
        $videoList = $this->video->getVideoList($sortOrder, $paginate, $search);
        
        return response()->json([
            'data' => $videoList['records'], 
            'recordsTotal' => $videoList['totalRecords'],
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
                'audio' => implode(',', $audioIds),
                'thumbnail_text' => $params['thumbnail_text']
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
            if ( count($audioInArray) != 1 ) {
                $audioCollection = $this->media->whereIn('id', $audioInArray)->get();

                $inputArray = [];
                foreach($audioCollection as $audio) {
                    $inputArray[] = '-i ' . public_path( ltrim($audio->file_path, '/') );
                }
                // Get merge audio
                $mergeAudioPath = $this->media->mergeAudio($inputArray);
            } else {
                $audio = $this->media->where('id', $audioInArray[0])->first();
                $mergeAudioPath = public_path( ltrim($audio->file_path, '/') );
            }
            
            // Log process;
            $this->log->create(['content' => 'Start creating video', 'type' => 'start', 'video_id' => $id]);
            
            // Encode video 
            $filePath = $this->media->encodeVideo($imageUrl, $mergeAudioPath);
            
            // Log process;
            $this->log->create(['content' => 'Done creating video', 'type' => 'done', 'video_id' => $id]);
            
            // update store at for video
            $this->video->where('id', $id)->update(['store_at' => $filePath, 'status' => 1]);
            
            return response()->json(['status' => 1, 'message' => 'Encode video successfully']);
            
        } catch (\Exception $ex) {
            echo $ex;
            return response()->json(['status' => 0, 'message' => 'Server error']);
        }
    }
    
    public function remove(Request $request, $id) {
        
        try {
            $this->video->removeById($id);
            return response()->json(['status' => 1, 'message' => 'Delete successfully']);
            
        } catch (Exception $ex) {
            return response()->json(['status' => 0, 'message' => 'Server error']);
        }
        
    }
}
