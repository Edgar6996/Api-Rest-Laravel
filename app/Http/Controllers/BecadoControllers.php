<?php

namespace App\Http\Controllers;

use App\Core\Tools\ApiMessage;
use App\Enums\EstadoBecados;
use App\Enums\TiposUsuarios;
use App\Http\Requests\Becados\BecadosRequest;
use App\Http\Requests\Request\UpdateBecadoRequest;
use App\Models\Becado;
use App\Models\Calendario;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Storage;

class BecadoControllers extends Controller
{
    const FOTOS_FOLDER = 'fotos';

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $res = new ApiMessage();

        # Pensar en agregar opcion de filtro
        $perPage = $request->get('per_page',10) ; // items por pagina

        $consulta = Becado::query();


        $lista = $consulta->paginate($perPage);

        $res->setData($lista);
        return  $res->send();

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
        $res->addLog("Iniciando proceso de registro");

        try {
            $becadoRequest = $request->validated();

            // Crea el usuario de becado
            $user = [
                'name' => $becadoRequest['nombres'],
                'email' => $request->get('email'),
                'username' => strval($request->get('dni')),
                'password' => Hash::make($request->get('password')),
                'rol' => TiposUsuarios::BECADO,
            ];

            // Iniciamos la transaccion para que todos los inserts se registren o si falla, no se registre ninguno
            # Las transacciones debemos emplear siempre que se registren o actualizen mas de un modelo en la base de datos.
            \DB::beginTransaction();


            # El metodo create ya registra el usuario y devuelve la instancia
            $usuario =  User::create($user);
            $res->addLog("Usuario registrado. Id: ". $usuario->id);



            # 2. Creamos becado en db
            $becado = new Becado($becadoRequest); # No guarda los cambios

            $becado->user_id = $usuario->id;

            # guardamos el modelo
            $becado->saveOrFail(); # Se le asigna automaticamente el id, si se registra correctamente
            $res->addLog("Becado registrado. Id:" . $becado->id);


            # 3. Generamos el calendario para el becado
//            $calendario = Calendario::create([
//                'becado_id' => $becado->id
//            ]);

            # El metodo de crear a partir de una realcion no requiere indicar el valor de la clave foranea
            $calendario = $becado->calendario()->create();


            $res->addLog("Se genero el calendario con id: ". $calendario->id);

            // Cerramos la transaccion / Confirmamos los cambios
            \DB::commit();

            # Devolvemos el becado
            $res->setData($becado);


//            \DB::transaction(function () use( $calendario) {
//                $calendario->save();
//            });

            $res->setMessage("El usuario ha sido registrado exitosamente");
        } catch (\Throwable $th) {
            \DB::rollBack();

            $res->setCode(500);
            $res->setMessage("No se registro el usuario");
            $res->addError($th->getMessage());
        }

        return $res->send();
    }

    /**
     * Update photo to specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cargarFoto(Request $request, $becado)
    {
        $res = new ApiMessage($request);
        $becado = Becado::findOrFail($becado);
        $res->addLog("Obtenido becado: {$becado->nombres}");
        
        // Vemos si se envio la foto en el $request
        if($request->hasFile('foto')) {
            $img = $request->file('foto');

            // Definimos donde se guarda la imagen
            $path = "public/" . self::FOTOS_FOLDER;

            // Verificamos que la extension sea png|jpg|jpeg - *Ver validaciones Laravel
            $extensiones = ['png', 'jpg', 'jpeg'];
            if (in_array($img->extension(), $extensiones)) {
                // Guarda la imagen y devuelve el fullpath
                $filename = Storage::putFile($path, $img);
                if ($filename === false) {
                    return $res->setCode(409)->setMessage('No fue posible cargar la foto')->send();
                }
            } else {
                return $res->setCode(409)->setMessage('La extensión de la imagen no es válida')->send();
            }

            // Guardamos el nombre del archivo con extension
            $filename = basename($filename);
            // Obtenemos link publico
            $storage_url = Storage::url(self::FOTOS_FOLDER .'/'. $filename);
            $public_url = \URL::to($storage_url);

            // guardamos foto en becado
            $becado->foto = $public_url;
            $becado->save();
            $res->addLog("Foto guardada, public_url:{$becado->foto}");
            // Enviamos la url de la foto
            $res->setData([
                'foto' => $becado->foto
            ]);

            $res->setMessage('Foto cargada correctamente');
        }

        return $res->send();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Becado  $becado)
    {
        // Crea la instancia de apiMessage
        $res = new ApiMessage();


        # Con load, le podemos indicar que nos envie una relacion
        $becado->load('calendario');

        // Carga el dato en el response
        $res->setData($becado);

        // Envia el response
        return $res->send();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBecadoRequest $request, $id)
    {
        $res = new ApiMessage();
        $datos = $request->validated();

        # obtengo el becacdo
        $becado = Becado::findOrFail($id);

        # Actualizamos los datos
        $becado->update($datos);

        # Guardamos los cambios
        try {
            $becado->saveOrFail();

            # en algunas ocaciones, podremos necesitar hacer un refresh() de un modelo para actualizar sus datos desde la db
            # $becado->refresh();
            $res->setData($becado);
            $res->setMessage("Los datos han sido actualizados correctamente.");

        } catch (\Throwable $e) {
            $res->setMessage("No fue posible actualizar el becado");
            $res->addError($e->getMessage());
            $res->setCode(409);
        }

        return  $res->send();
    }


    public function deshabilitarBecado(  $id)
    {
        $res = new ApiMessage();

        # obtengo el becacdo
        $becado = Becado::find($id);

        if(!$becado){
            return $res->setCode(409)->setMessage("El becado ya esta deshabilitado.")->send();
        }

        # Guardamos los cambios
        try {
            # le asignamos el nuevo estado
            $becado->estado = EstadoBecados::DESHABILITADO;
            $becado->saveOrFail();

            $res->setMessage("Se ha deshabilitado el becado");

        } catch (\Throwable $e) {
            $res->setMessage("No fue posible deshabilitar el becado");
            $res->addError($e->getMessage());
            $res->setCode(409);
        }
        return $res->send();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $res = new ApiMessage();

        # obtengo el becacdo
        $becado = Becado::findOrFail($id);

        # Guardamos los cambios
        try {
            # 1. Eliminamos
            $becado->delete();


            $res->setMessage("Los datos han sido actualizados correctamente.");

        } catch (\Throwable $e) {
            $res->setMessage("No fue posible actualizar el becado");
            $res->addError($e->getMessage());
            $res->setCode(409);
        }

        return  $res->send();
    }
}