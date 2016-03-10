<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class VideoController extends Controller
{
    public function index() {
        
        return view('video.index', ['test' => 3]);
    }
    
    public function getVideoList(Request $request) {
        
    }
}
