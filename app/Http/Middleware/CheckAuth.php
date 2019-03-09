<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->token == null || $request->id == null)
            return response()->json(['message','Error: please use credidentials.'],500);

        $user = User::where('id',$request->id)->get()->first();
        if($user != null && !strcmp($user->remember_token,$request->token))
            return $next($request);

        return response()->json(['message','Error: wrong credidentials.'],500);
    }
}
