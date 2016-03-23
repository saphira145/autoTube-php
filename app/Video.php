<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model {
    
    protected static $VIDEO_STATUS = [
        0 => ['id' => 0, 'name' => 'Raw', 'class' => 'raw'],
        1 => ['id' => 1, 'name' => 'Encoded', 'class' => 'encoded'],
        2 => ['id' => 2, 'name' => 'Uploaded', 'class' => 'uploaded']
    ];

    protected $fillable = ['title', 'description', 'loop', 'fade', 'audio', 'images', 'thumbnail_text', 'thumbnail', 'status'];
    

    /**
     * Get video list
     * @param type $sortOrder
     * @param type $paginate
     * @param type $search
     * @return type
     */
    public function getVideoList($sortOrder, $paginate, $search) {
        $query = $this;
        
        if (trim($search) != '') {
            $query = $query->where('title', 'like', $search);
        }
        $totalRecords = count($query->get());
        
        if ( isset($sortOrder['column']) && isset($sortOrder['dir']) ) {
            $query = $query->orderBy($this->getColumn($sortOrder['column']), $sortOrder['dir']);
        }
        
        if ( isset($paginate['offset']) && isset($paginate['limit']) ) {
            $query = $query->skip($paginate['offset'])->take($paginate['limit']);
        }
        $videoList = $query->get()->toArray();
        
        return ['records' => $this->addStatusData($videoList), 'totalRecords' => $totalRecords];
        
    }
        
    public function addStatusData(&$videoList) {
        $status = Video::$VIDEO_STATUS;
        foreach ($videoList as &$video) {
            $video['status_detail'] = $status[$video['status']];
        }
        return $videoList;
    }
    /**
     * Get column
     * @param type $index
     * @return string
     */
    public function getColumn($index) {
        $column = ['title', 'store_at'];
        return $column[$index];
    }
    
    /**
     * Validaet setting
     * @return type
     */
    public function validateCreate() {
        return [ 
            'title' => 'required',
            'description' => 'required',
            'imagesPath' => 'required',
            'audioPath' => 'required',
            'thumbnail_text' => 'required'
        ];
    }
    
    public function findById($id) {
        return $this->where('id', $id)->first();
    }
    
    public function removeById($id) {
        $this->findById($id)->delete();
    }
}
