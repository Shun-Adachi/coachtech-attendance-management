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
        if (Auth::user()->role_id == 1) {
            $response = app()->call([$adminController, $adminAction], ['request' => $request]);
        } elseif (Auth::user()->role_id == 2) {
            $response = app()->call([$userController, $userAction], ['request' => $request]);
        } else {
            abort(403, 'Unauthorized');
        }

        // レスポンスを返す前にクッキーを設定
        return response($response)->cookie('key', 'value', 60);
    }
}
