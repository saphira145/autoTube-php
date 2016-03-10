<?php

namespace App\Http\Controllers;

use App\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    
    protected $video;

    public function __construct(Video $video) {
        $this->video = $video;
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
}
