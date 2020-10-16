<?php

namespace App\Http\Controllers;

use App\Core\Tools\ApiMessage;
use App\Http\Requests\Becados\BecadosRequest;
use App\Models\Becado;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;

class BecadoControllers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return 'index';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BecadosRequest $request)
    {   
        $res = new ApiMessage();
        
        try {

            // Crea el usuario de becado
            $user = [
                'name' => $request->get('nombres'),
                'email' => $request->get('email'),
                'username' => strval($request->get('dni')),
                'password' => Hash::make($request->get('password'))
            ];
            User::create($user);
            // Creamos becado en db
            $becadoRequest = $request->all();
            $becadoRequest += intval(User::latest('id')->first()->id);
            return $becadoRequest;
            // $becado = Becado::create($becadoRequest);

            // Crea el calendario del becado
            // $becado->calendario->create([]);

            $res->setMessage("El usuario ha sido registrado exitosamente");
        } catch (\Throwable $th) {
            $res->setCode(500);
            $res->setMessage("No se registro el usuario");
            $res->addError($th);
        }

        return $res->send();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $becado)
    {   
        // Crea la instancia de apiMessage
        $res = new ApiMessage();

        // Carga el dato en el response
        $res->setData($becado);

        // Envia el response
        $res->send();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return "update $id";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return "delete $id";
    }
}
