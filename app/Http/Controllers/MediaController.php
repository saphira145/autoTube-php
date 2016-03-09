<?php

namespace App\Http\Controllers;

use App\Media;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;

class MediaController extends Controller {
    
    protected $imageManager;
    
    protected $media;


    public function __construct(ImageManager $imageManager, Media $media) {
        $this->media = $media;
        $this->imageManager = $imageManager;
    }
    public function index(Request $request) {
        echo 'start';
        
        echo exec('ffmpeg -i ' . public_path('video/1.mp4') . ' ' . public_path('video/2.mp4'));
        
//        ffmpeg -loop 1 -i img.jpg -i lost-star.mp3 -c:v libx264 -c:a aac -strict experimental -b:a 192k -shortest out.mp4
//        
//        
//        if (shell_exec('D:\ffmpeg-20160301-git-1c7e2cf-win64-static\bin ffmpeg -i 1.mp4 2.mp4') ) {
//            echo 'success';
//        } else {
//            echo 'fails';
//        }
        
//        $path = public_path('images/test.jpg');
//        $input = $this->imageManager->make($path);
//        
//        $image = $this->media->createThumbnail($input, 'DRUM');
//        
//        return $image->response();
    }
}
