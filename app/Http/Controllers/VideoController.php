<?php

namespace App\Http\Controllers;

use App\Video;
use App\Media;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory as Validator;
use App\Log;
use Carbon\Carbon;
use Illuminate\Session\Store as Session;
use Google_Client as GoogleClient;
use Intervention\Image\ImageManager;

class VideoController extends Controller
{
    
    protected $video;
    
    protected $validator;
    
    protected $media;
    
    protected $carbon;
    
    protected $log;
    
    protected $session;
    
    protected $client;
    
    protected $imageManager;

    public function __construct(Video $video, Validator $validator, Media $media,
        Carbon $carbon, Log $log, Session $session, GoogleClient $client, ImageManager $imageManager) 
    {
        $this->video = $video;
        $this->validator = $validator;
        $this->media = $media;
        $this->carbon = $carbon;
        $this->log = $log;
        $this->session = $session;
        $this->client = $client;
        $this->imageManager = $imageManager;
        $this->client->setAuthConfigFile(public_path('client_secret.json'));
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
            
            $imagesPath = $request->imagesPath;
            $realPathInput = public_path(ltrim($imagesPath[0], '/'));
            $name = uniqid() . '.jpg';
            $realPathOutput = public_path('uploads/thumbnails/' . $name);
            $pathOutput = '/uploads/thumbnails/' . $name;
            $input = $this->imageManager->make($realPathInput);

            $image = $this->media->createThumbnail($input, $params['thumbnail_text']);
            $image->save($realPathOutput);
            
            $this->video->create([
                'title' => $params['title'],
                'loop' => $params['loop'],
                'fade' => $params['fade'],
                'description' => $params['description'],
                'images' => implode(',', $imageIds),
                'audio' => implode(',', $audioIds),
                'thumbnail_text' => $params['thumbnail_text'],
                'thumbnail' => $pathOutput
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
    
    public function upload(Request $request) {
        $videoId = $request->input('id');
        
        $video = $this->video->findById($videoId);
        $videoPath = $video->store_at;
        $videoRealPath = public_path(ltrim($videoPath, '/'));
        $client = $this->session->get('client');
        $thumbnailRealPath = public_path(ltrim($video->thumbnail, '/'));
        $youtubeService = new \Google_Service_YouTube($client);
        
        if ( $client->getAccessToken() ) {
            
            try {
                $snippet = new \Google_Service_YouTube_VideoSnippet();
                $snippet->setTitle($video->title);
                $snippet->setDescription($video->description);
    //            $snippet->setTags($tags)
                $snippet->setCategoryId(22);

                $status = new \Google_Service_YouTube_VideoStatus();
                $status->privacyStatus = 'public';

                $videoYoutube = new \Google_Service_YouTube_Video();
                $videoYoutube->setSnippet($snippet);
                $videoYoutube->setStatus($status);
                $chunkSizeBytes = 1 * 1024 * 1024;

                // Setting the defer flag to true tells the client to return a request which can be called
                // with ->execute(); instead of making the API call immediately.
                $client->setDefer(true);

                // Log
                $this->log->create(['content' => 'start uploading video', 'type' => 'start', 'video_id' => $video->id]);    
                
                // Create a request for the API's videos.insert method to create and upload the video.
                $insertRequest = $youtubeService->videos->insert("status,snippet", $videoYoutube);

                // Create a MediaFileUpload object for resumable uploads.
                $media = new \Google_Http_MediaFileUpload($client, $insertRequest, 'video/*', null, true, $chunkSizeBytes);
                $media->setFileSize(filesize($videoRealPath));

                // Read the media file and upload it.
                $status = false;
                $handle = fopen($videoRealPath, "rb");
                while (!$status && !feof($handle)) {
                  $chunk = fread($handle, $chunkSizeBytes);
                  $status = $media->nextChunk($chunk);
                }
                fclose($handle);

                // If you want to make other calls after the file upload, set setDefer back to false
//                $client->setDefer(false);
                // Create a MediaFileUpload with resumable uploads
                
                $setRequest = $youtubeService->thumbnails->set($status['id']);

                // Create a MediaFileUpload object for resumable uploads.
                $media = new \Google_Http_MediaFileUpload($client, $setRequest, 'image/jpeg', null, true, $chunkSizeBytes);
                $media->setFileSize(filesize($thumbnailRealPath));

                // Read the media file and upload it chunk by chunk.
                $status = false;
                $handle = fopen($thumbnailRealPath, "rb");
                while (!$status && !feof($handle)) {
                  $chunk = fread($handle, $chunkSizeBytes);
                  $status = $media->nextChunk($chunk);
                }

                fclose($handle);

                // If you want to make other calls after the file upload, set setDefer back to false
                $client->setDefer(false);

                
                // Log
                $this->log->create(['content' => 'Upload video successully', 'type' => 'done', 'video_id' => $video->id]);
                
                // update store at for video    
                $this->video->where('id', $video->id)->update(['status' => 2]);
                
                return response()->json(['status' => 1, 'message' => 'Upload successfully']);
                
            } catch (Exception $ex) {
                echo $ex;
                return response()->json(['status' => 0, 'message' => 'Cannot upload']);
            }
            
        }
        
        return response()->json(['status' => 0, 'redirect_url' => '/auth']);
        
    }
}
