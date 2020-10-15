<?php

namespace App\Http\Controllers;

use App\Core\Tools\ApiMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EjemploController extends Controller
{


    # Ejemplo basico de ApiMessage
    # Enviar datos (ej: un objeto)
    public function ejemploAction(Request $request )
    {
        # 1. Definimos la instancia de la clase ApiMessage
        $res = new ApiMessage();

        # Creo un objeto de prueba
        $datos = [
            "nombre" => "Juan",
            "edad" => 123,
            "notas" => [1,2,5,1]
        ];


        # Cargamos el objeto en la respuesta
        $res->setData($datos);
        # Toda ruta que deba devolver datos, se denben enviar con setData()


        # Juntamente con los datos, en una misma respuesta, peude ser util devolver un mensaje. Esto lo hacemos con setMessage:
        $res->setMessage("Usuario registrado!");


        # Enviamos la respuesta
        return $res->send();
    }


    # Ejemplo basico de ApiMessage SIN BODY
    # Muchas rutas realizan cambios en el backend pero no necesitan devolver datos, solo codigo de resultado y un mensaje informativo
    public function ejemplo2Action(Request $request )
    {
        # 1. Definimos la instancia de la clase ApiMessage
        $res = new ApiMessage();


        # Generalmente cuando hacemos un update de algun modelo no necesitamos devolver datos al cliente,
        # en esos casos, basta con devolver un mensaje con el resultado del a operacion.

        $res->setMessage("El usuario ha sido registrado exitosamente");

        # El codigo Http que indica que el resutlado de una operación ha sido exitoso es el 200 - OK, y es el que se envía por defecto
        # en todas las respuestas, por eso, no es necesario indicarlo.
        # Por lo mismo, solo indicaremos el codigo cuando necesitamos especificar uno diferente al 200


        # Logs
        # ----------------------
        # Tambien tenemos la opcion de ir agregando logs en la respuesta con propositos de depuracion..
        # Ej:
        $res->addLog("Obteniendo el usuario de la db....");
        $res->addLog("el usuario obtenido es el 35");
        $res->addLog("Actualizando los registros de ...");
        $res->addLog("Se obtuvieron n registros para eliminar");




        # Enviamos la respuesta
        return $res->send();
    }


    # Ejemplo de envio de error ApiMessage
    # Enviar un mensaje de error
    public function ejemplo3Action(Request $request )
    {
        # 1. Definimos la instancia de la clase ApiMessage
        $res = new ApiMessage();

        # Si debemos devolver una respuesta de error, no enviamos datos, solo un mensaje, una lista de errores e indicamos el codigo de estado Http correspondiente

        # Mensaje general del error (Para el usuario)
        $res->setMessage("Los datos ingresados son incorrectos");

        # Opcionalmente podemos ir agregando mensajes de errore a la respuesta

        $res->addError("El email no existe");
        $res->addError("Descripcion del error 2");
        $res->addError("Descripcion del error n");

        # Indicamos el codigo de estado
        $res->setCode(409);
        # Algunos codigos comunes que nosotros deberemos devolver:
        /**
         * 400 - Parametros incorrectos (se usa para indicar que faltan parametros o son incorrectos)
         * 404 - El recurso no existe (Se devuelve cuando no existe algun recurso solicitado, por ejemplo cunado se intenta obtener un usuario y este no existe)
         * 409 - Conflicto (cuabndo pasa cualquier problema semi grave)
         * 401 - No Autorizado (El usuario no tiene acceso al recurso pero podría si se loguea)
         * 403 - Prohibido - El usuario esta logeado y no tiene permisos para acceder al recurso
         */


        # Enviamos la respuesta
        return $res->send();
    }



}
