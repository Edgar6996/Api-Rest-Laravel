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

    public function signup(AltaUsuariosRequest $request)
    {
        $res = new ApiMessage();

        $params = $request->validated();
        $params['password'] = Hash::make($request['password']);

        $user = new User($params);

        # Guardamos el usuasrio
        if (!$user->save()) {
            // error
            return $res
                ->setCode(409)
                ->setMessage("No fué posible registrar el usuario")
                ->send();
        }

        UsersActivityModel::addLog("Ha registrado dado de alta la cuenta de usuario '{$user->username}'",TipoEventos::GESTION_USUARIOS,"",$request->validated());

        $res->message = "Usuario registrado correctamente.";
        # en data, devolvemos el id
        $res->data = [
            'id' => $user->id
        ];

        return $res->send();
    }


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

    /**
     * @OA\Get(
     *     path="/auth/logout",
     *     description="Revocar token",
     *     @OA\Response(
     *          response="200",
     *     description="OK"
     * )
     *
     * )
     */
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



    public function cambiarClaveSistemaViejo(Request $request, $id )
    {
        $res = new ApiMessage($request);
        $res->addLog("Actualizando clave del usuario..");
        $request->validate([
            'password' => ['required', 'string', 'min:6','max:100', 'confirmed'],
            'up' => ['required', 'string'],
        ]);

        $userPassword = $request['up'];

        // Validamos el password del usuario actual
        if (!Auth::user()->validatePassword($userPassword)) {
            return $res->setCode(401)->setMessage("Operación denegada")->send();
        }


        # obtenemos el usuario
        /** @var UserLegacy $user */
        $user = UserLegacy::find($id);
        if(!$user){
            return $res->setCode(404)->setMessage("La cuenta no existe")->send();
        }
        $copia = "[{$user->tx_password}]";

        $psw = $request['password'];
        $user->tx_password = md5($psw);
        try {
            $user->saveOrFail();

            UsersActivityModel::addLog("Ha actualizado la contraseña del usuario #{$id}",TipoEventos::GESTION_USUARIOS,'',[
                'username' => $user->tx_username,
                'psw_old' => $copia,
                'user_documento' => $user->tx_documento
            ]);

        } catch (\Throwable $e) {
            return $res->setCode(409)->setMessage("No fué posible actualizar la contraseña del usuario.")->send();
        }
        $res->setData("Usuario actualizado");

        return $res->send();
    }

    public function blanquearClaveSistemaViejo(Request $request, $id )
    {
        $res = new ApiMessage($request);
        $res->addLog("Blanqueando clave del usuario..");
        $request->validate([
            'up' => ['required', 'string'],
        ]);

        $userPassword = $request['up'];

        // Validamos el password del usuario actual
        if (!Auth::user()->validatePassword($userPassword)) {
            return $res->setCode(401)->setMessage("Operación denegada")->send();
        }

        # obtenemos el usuario
        /** @var UserLegacy $user */
        $user = UserLegacy::find($id);
        if(!$user){
            return $res->setCode(404)->setMessage("La cuenta no existe")->send();
        }
        $copia = "[{$user->tx_password}]";

        // Le asignamos como clave, los 3 últimos dígitos del DNI
        $psw = substr($user->tx_documento, -3);
        $user->tx_password = md5($psw);
        try {
            $user->saveOrFail();

            UsersActivityModel::addLog("Ha realizado un blanqueo de clave del usuario #{$id}",TipoEventos::GESTION_USUARIOS,'',[
                'username' => $user->tx_username,
                'psw_old' => $copia,
                'user_documento' => $user->tx_documento
            ]);

        } catch (\Throwable $e) {
            return $res->setCode(409)->setMessage("No fué posible actualizar la contraseña del usuario.")->send();
        }
        $res->setData("Usuario actualizado");

        return $res->send();
    }

}
