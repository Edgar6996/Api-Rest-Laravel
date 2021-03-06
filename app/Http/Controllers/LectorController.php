<?php

namespace App\Http\Controllers;

use App\Core\Tools\ApiMessage;
use App\Enums\TiposUsuarios;
use App\Models\Diario;
use App\Models\Registro;
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

        # Ahora, por defecto, mantenemos los tokens previos
        if($request->get('eliminar_actuales') == 1){
            # Anulamos los tokens actuales
            $currentTokens = $user->tokens()->get();
            foreach ($currentTokens as $token) {
                $token->revoke();
            }
            $res->addLog("Se han revocado los tokens previos");
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

        // 1. Buscar en el Diario Actual, el DetalleDiario correspondiente al becado_id.
        $diario_actual = Diario::diarioActual();

        $detalle = $diario_actual->detalleDiario()->where('becado_id',$becado_id)->first();
        //  -> Si no existe, significa que el becado no tiene reserva para el Diario actual, devolvemos un error
       if(!$detalle){
            return $res->setMessage("No comes hoy ")
                ->setCode(400)
                ->send();
       }

       if($detalle->retirado){
            return $res->setMessage("Ya se registro")
                ->setCode(400)
                ->send();
       }
        // 2 Si existe, entonces actualizamos el DetalleDiario indicando que retiró sus raciones
       $detalle->retirado = true;

       $detalle->save();

        // 3. Crear un registro en el modelo Registro
       Registro::create([
            'becado_id' => $becado_id,
            'diario_id' => $diario_actual->id,
            'fecha_hora' => now(),
       ]);

        return $res->setMessage("Se registro el acceso del becado")->send();
    }
}
