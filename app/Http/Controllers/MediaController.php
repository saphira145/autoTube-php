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
    
    public function upload(Request $request) {
        try {
            $files = $request->file('files');
            
            foreach ($files as $file) {
                
                $ext = $file->getClientOriginalExtension();
                $mimeType = $file->getMimeType();
                $uniqueId = uniqid();
                $fileName = $uniqueId . '.' .  $ext;
                
                if ( in_array( $ext, $this->media->imageType() )) {
                    $file->move('uploads/images', $fileName);
                    
                    $fileInfo = [
                        'filePath' => "/uploads/images/{$fileName}",
                        'fileName' => $fileName,
                        'mime' => $mimeType,
                        'type' => 'image'
                    ];
                }
                
                if ( in_array($ext, $this->media->audioType()) ) {
                    $file->move('uploads/audio', $fileName);
                    $fileInfo = [
                        'filePath' => "/uploads/audio/{$fileName}",
                        'fileName' => $fileName,
                        'mime' => $mimeType,
                        'type' => 'audio'
                    ];
                }

            }

            return response()->json(['status' => 1, 'message' => 'Upload successfully', 'fileInfo' => $fileInfo]);
            
        } catch (\Exception $ex) {
            echo $ex;
            return response()->json(['status' => 0, 'message' => 'Server error']);
        }
        
    }
    
    public function remove(Request $request) {
        $fileName = $request->input('fileName');
        $type = $request->input('type');
        
        if ($type == 'image') {
            $file = public_path() . '/uploads/images/' . $fileName;
        }
        
        if ($type == 'audio') {
            $file = public_path() . '/uploads/audio/' . $fileName;
        }
        
        
        try {
            if ( unlink($file) ) {
                return response()->json(['status' => 1, 'message' => 'Delete successfully']);
            }
            
            throw new Exception('Can not delete');
            
        } catch (Exception $ex) {
            return response()->json(['status' => 0, 'message' => 'Server error']);
        }
    }
}
