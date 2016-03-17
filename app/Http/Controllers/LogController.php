<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Log;
use Carbon\Carbon;

class LogController extends Controller
{
    protected $log;
    
    protected $carbon;

    public function __construct(Log $log, Carbon $carbon) {
        $this->log = $log;
        $this->carbon = $carbon;
    }

    public function getLogs(Request $request) {
        set_time_limit(0);
        
        $lastTimeOpened = $request->input('lastTimeOpened');
        
        while(1) {
            $logs = $this->log->where('created_at', '>' , $lastTimeOpened)->get();
            
            if ( !empty($logs->toArray()) ) {
                return response()->json(['status' => 1, 'logs' => $logs, 'newLastTimeOpened' => $this->carbon->now()->toDateTimeString()]);
            } 
            
            sleep(1);
        }
    }
}
