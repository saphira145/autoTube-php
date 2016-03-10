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
        
//        $path = public_path('images/test1.jpg');
//        $input = $this->imageManager->make($path);
//        
//        $image = $this->media->createThumbnail($input, 'DRUM');
//        
//        return $image->response();
        
//        try {
//            $this->media->createVideo('C:\xampp\htdocs\autoTube\public\images\img.jpg', 
//                'C:\xampp\htdocs\autoTube\public\images\lost-star.mp3',
//                'C:\xampp\htdocs\autoTube\public\images\output.mp4'
//            );
//            
//        } catch (Exception $ex) {
////            dd($ex);
//        }
    }
}
