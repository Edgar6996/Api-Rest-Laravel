<?php

namespace App\Http\Controllers\Auth;


use App\Core\Tools\ApiMessage;

use App\Enums\TipoEventos;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AltaUsuariosRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\UpdateUserRequest;
use App\Models\UsersActivityModel;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{

    public function login(LoginRequest $request)
    {
        $res = new ApiMessage();

        $login = $request->input('user');
        // Comprobar si el input coincide con el formato de E-mail
        $field = 'email';

        // $credentials = request(['user', 'password']);
        $credentials = [
            $field => $login,
            'password' => $request->input('password')
        ];

        if (!Auth::attempt($credentials)) {
            return $res->setCode(401)->setMessage("Unauthorized")->send();

        }
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();



        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse(
                $tokenResult->token->expires_at)
                ->toDateTimeString(),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' =>
            'Successfully logged out']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
