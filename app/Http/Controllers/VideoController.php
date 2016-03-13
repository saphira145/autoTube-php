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
}
