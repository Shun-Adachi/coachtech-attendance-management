<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RouteBasedOnRole
{

    public function handle(Request $request, Closure $next,  $adminController, $userController, $adminAction, $userAction)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }
        session(['key' => 'value']);
/*
        if (Auth::user()->role_id == config('constants.ROLE_ADMIN')) {
            $response = app()->call([$adminController, $adminAction]);
        } elseif (Auth::user()->role_id == config('constants.ROLE_USER')) {
            $response = app()->call([$userController, $userAction]);
        } else {
            abort(403, 'Unauthorized');
        }*/
/*
        if (Auth::user()->role_id == config('constants.ROLE_ADMIN')) {
            $controller = app()->make($adminController);
            $response = $controller->$adminAction($request);
        } elseif (Auth::user()->role_id == config('constants.ROLE_USER')) {
            $controller = app()->make($userController);
            $response = $controller->$userAction($request);
        } else {
            abort(403, 'Unauthorized');
        }
*/
if (Auth::user()->role_id == config('constants.ROLE_ADMIN')) {
    // コントローラーのインスタンスを生成する
    $controllerInstance = app()->make($adminController);
    // Laravel のコンテナに任せてメソッドを呼び出す（必要な依存性は自動解決される）
    $response = app()->call([$controllerInstance, $adminAction]);
} elseif (Auth::user()->role_id == config('constants.ROLE_USER')) {
    $controllerInstance = app()->make($userController);
    $response = app()->call([$controllerInstance, $userAction]);
} else {
    abort(403, 'Unauthorized');
}
        // レスポンスを返す前にクッキーを設定
        return response($response)->cookie('key', 'value', 60);
    }
}
