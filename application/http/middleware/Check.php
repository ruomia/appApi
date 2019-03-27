<?php

namespace app\http\middleware;

use \Firebase\JWT\JWT as JWTCHECK;
class Check
{
    public function handle($request, \Closure $next)
    {
        $jwt = substr($request->server('HTTP_AUTHORIZATION'), 7);
        try
        {
            $request->jwt = JWTCHECK::decode($jwt, Env::get('jwt.key'), array('HS256'));
            return $next($request);
        }
        catch(\Exception $e)
        {
            return json([
                'code'=>'403',
                'message'=>'HTTP/1.1 403 Forbidden'
            ]);
        }
      
    
        return $next($request);
    }
}
