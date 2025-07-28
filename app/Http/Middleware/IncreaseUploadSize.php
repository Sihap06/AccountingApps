<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IncreaseUploadSize
{
    public function handle(Request $request, Closure $next)
    {
        // Try to increase limits at runtime
        @ini_set('upload_max_filesize', '10M');
        @ini_set('post_max_size', '15M');
        @ini_set('max_execution_time', '300');
        @ini_set('max_input_time', '300');
        
        return $next($request);
    }
}