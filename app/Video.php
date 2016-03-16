<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model {
    
    protected $fillable = ['title', 'description', 'loop', 'fade', 'audio', 'images'];

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
        
        return ['records' => $query->get(), 'totalRecords' => $totalRecords];
        
    }
        
    public function getColumn($index) {
        $column = ['title', 'store_at'];
        return $column[$index];
    }
    
    public function validateCreate() {
        return [ 
            'title' => 'required',
            'description' => 'required',
            'imagesPath' => 'required',
            'audioPath' => 'required'
        ];
    }
    
    public function findById($id) {
        return $this->where('id', $id)->first();
    }
}
