<?php

namespace App\Http\Middleware;

use App\Core\Tools\ApiMessage;
use App\Enums\TiposUsuarios;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class OnlyAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $res = new ApiMessage($request);

        /** @var User $user */
        $user = \Auth::user();
        if(!$user || !in_array($user->rol, [TiposUsuarios::ADMINISTRADOR, TiposUsuarios::ROOT])){
            // no es dmin
            return $res->setCode(403)->setMessage("Acceso denegado.")->send();
        }
        return $next($request);
    }
}
