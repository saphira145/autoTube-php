<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Google_Client as GoogleClient;
use Google_Service_Drive;
use Illuminate\Session\Store as Session;
use Google_Service_Oauth2 as Oauth2;


class AuthController extends Controller {
    
    protected $client;
    
    protected $session;
    
    protected $plus;

    protected $json;
    
    protected $redirectUri = 'http://autoTube.com/video/index';

    public function __construct(GoogleClient $client, Session $session) {
        $this->client = $client;
        $this->oauth2 = new Oauth2($this->client);
        $this->session = $session;
        $this->json = public_path('client_secret.json');
        $this->client->setAuthConfigFile($this->json);
        $client->addScope("email", "profile");
    }
     
    public function index() {

        if ($this->session->has('access_token')) {
            $this->client->setAccessToken($this->session->get('access_token'));
            
            return redirect($this->redirectUri);
        } else {
            
            $redirect_uri = 'http://autoTube.com/auth/callback';
            
            return redirect($redirect_uri);
        }
    }
    public function callback(Request $request) {
        $params = $request->all();
        
        $this->client->setRedirectUri('http://autoTube.com/auth/callback');
//        $this->client->addScope(Google_Service_Drive::DRIVE_METADATA_READONLY, 'email');
        
        if (! isset($params['code'])) {
            $auth_url = $this->client->createAuthUrl();
            return redirect($auth_url);
        } else {
            $this->client->authenticate($params['code']);
            $this->session->put('access_token', $this->client->getAccessToken());
            $this->session->put('user', $this->oauth2->userinfo->get());
            $this->session->put('client', $this->client);
            return redirect($this->redirectUri);
        }
    }
    
    public function logout(Request $request) {
        $this->session->flush();
            
        return redirect($this->redirectUri);
    }
}
