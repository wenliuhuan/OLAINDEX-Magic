<?php

namespace App\Http\Middleware;

use App\Helpers\Tool;
use Closure;
use Illuminate\Support\Facades\Session;

class HandleEncryptDir
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $realPath = $request->route()->parameter('query') ?? '/';
        $encryptDir = Tool::handleEncryptDir(Tool::config('encrypt_path'));
        if (Session::has('password:'.$realPath)) {
            // todo:密码判断
            return $next($request);
        } else {
            foreach ($encryptDir as $key => $item) {
                if (starts_with(Tool::getAbsolutePath($realPath), $key)) {
                    $encryptKey = $key;

                    return response()->view(
                        config('olaindex.theme').'password',
                        compact('realPath', 'encryptKey')
                    );
                }
            }
        }
    }
}
