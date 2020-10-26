<?php

namespace App\Http\Controllers;

use App\Core\Tools\ApiMessage;
use App\Enums\TiposUsuarios;
use App\Models\User;
use App\Providers\AuthServiceProvider;
use Illuminate\Http\Request;
use Laravel\Passport\Passport;
use Laravel\Passport\Token;

class LectorController extends Controller
{
    public function crearNuevoToken(Request $request )
    {
        $res = new ApiMessage($request);
        $user = User::getLectorUser();

        if($request->get('mantener_actuales') != 1){
            # Anulamos los tokens actuales
            $currentTokens = $user->tokens()->get();
            foreach ($currentTokens as $token) {
                $token->revoke();
            }
        }

        Passport::personalAccessTokensExpireIn(now()->addYear());
        $nuevoToken = $user->createToken('token_lector');
        AuthServiceProvider::setPassportConfig();

        $res->setData([
            'token' => $nuevoToken->accessToken
        ]);

        return $res->send();
    }



    public function nuevoRegistroHuella(Request $request, $becado_id)
    {
        $res = new ApiMessage($request);

        // TODO:
        // 1. Buscar en el Diario Actual, el DetalleDiario correspondiente al becado_id.
        //  -> Si no existe, significa que el becado no tiene reserva para el Diario actual, devolvemos un error

        // 2 Si existe, entonces actualizamos el DetalleDiario indicando que retiró sus raciones


        // 3. Crear un registro en el modelo Registro




        return $res->send();
    }
}
