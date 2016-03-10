<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Media extends Model {
    
    /**
     * Create thumbnail by resize, add text
     * @param type $image
     * @param type $text
     * @return type
     */
    public function createThumbnail($image, $text) {
        $image->resize(1280, 720);
        
        $height = $image->height();
        $width = $image->width();
       
        $image->rectangle(15, $height - 300, $width - 15, $height - 100, function($draw) {
            $draw->background('rgba(255, 255, 255, 0.5)');
        });

        $image->text($text, 600, $height - 150, function($font) {
            $font->file(public_path('fonts/VHBAHAB.TTF'));
            $font->size(150);
            $font->color('#fff');
            $font->align('center');
        });
        
        return $image;
    }
    
    
    /**
     * Create video from image and audio
     * @param type $imageUrl
     * @param type $audioUrl
     * @param type $output
     * @return boolean
     * @throws \Exception
     */
    public function createVideo($imageUrl, $audioUrl, $output) {
        $command = "ffmpeg -loop 1 -i {$imageUrl} -i {$audioUrl} -c:v libx264 -c:a aac -strict experimental -b:a 192k -shortest {$output}";
        
        if ( exec($command, $response) ) {
            return true;
        } else {
            throw new \Exception(implode("\n", $response));
        }
        
    }
}
