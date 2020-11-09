<?php

namespace App\Http\Controllers\Users;

use App\Core\Tools\ApiMessage;
use App\Http\Requests\RegistroUsuariosRequest;
use App\Models\User;
use Auth;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Hash;
use Intervention\Image\Facades\Image;
use Storage;

class UsersController extends Controller
{
    const DEFAULT_AVATAR_FILENAME = "default_avatar.png";
    const AVATARS_FOLDER = 'avatars';

    public function index(Request $request)
    {
        $res = new ApiMessage($request);

        $all = User::all();
        $res->setData($all);

        return $res->send();
    }


    public function show(Request $request, $id)
    {
        $res = new ApiMessage($request);
        # buscamos el usuario
        $user = User::find($id);
        if(!$user){
            # el usuario no existe
            return $res->setCode(404)->setMessage("El usuario no existe.")->send();
        }
        # encontrado, devolvemos en $data
        $res->setData($user);

        return $res->send();
    }

    public function updateAvatarImage(Request $request)
    {
        $res = new ApiMessage($request);
        /** @var User $user */
        $user = Auth::user();
        $res->addLog("Updating avatar of {$user->username}");

        # Verificamos si se envió en la request un archivo con el nombre 'image'
        if($request->hasFile('image')){
            # obtenemos el archivo 'image' desde la request
            $img = $request->file('image');


            # Opcionalmente, podríamos redimencionar las imágenes
//            Image::make($img)
//                ->resize(300,300)
//                ->save(storage_path('/app/public/avatars/' . $filename));


            # Definimos la carpeta en la que guardaremos la imagen..
            # Siempre sera en /strorage/app/ -> $path
            # --> como debe ser accedida por url, la debemos guardar en la carpeta storage/app/public
            $path = "public/" . self::AVATARS_FOLDER;

            # Guardamos la imagen subida en la carpeta del Storage indicada, nos devuelve el fullpath con el nuevo nombre que se le asigna al archivo
            $filename = Storage::putFile($path, $img);
            if($filename === false){
                return $res->setCode(409)->setMessage("No fué posible actualizar la imagen")->send();
            }

            # Para guardar en la db, lo ideal es guardar el link publico de la foto, es decir, un link que este listo para acceder a la foto por el cliente.
            # Para ello, vamos a obtener el link correspondiente a nuestro archivo


            # extraemos solo el nombre del archivo con su extensión. Ej: example.jpg
            $filename = basename($filename);

            // Antes de que actualicemos el modelo, extraemos el nombre del archivo anterior, desde la url
            $old_image = basename($user->photo);

            # Obtenemos el link publico para la imagen
            $storage_url = Storage::url(self::AVATARS_FOLDER .'/' . $filename);
            $public_url = \URL::to($storage_url);

            # Seteamos el link en el modelo y lo guardamos
            $user->photo = $public_url;
            $user->save();

            $res->setData([
                'link' => $public_url
            ]);

            # Eliminamos la foto anterior
            if($old_image !== self::DEFAULT_AVATAR_FILENAME){
                Storage::delete($path. '/' . $old_image);
                $res->addLog("Se eliminó el archivo {$old_image}");
            }
        }

        return $res->send();
    }

    public function changeUserPassword(Request $request )
    {
        $res = new ApiMessage($request);
        $request->validate([
            'password' => ['required', 'string', 'min:6','max:100', 'confirmed'],
            'old_password' => ['required', 'string'],
        ]);

        /** @var User $user */
        $user = Auth::user();




        // Validamos el password del usuario actual
        if (!Hash::check($request->input('old_password'), $user->password)) {
            return $res->setCode(401)->setMessage("Contraseña incorrecta")->send();
        }

        // update password
        $user->password = Hash::make($request->input('password'));;
        try {
            $user->saveOrFail();

            $res->setMessage("Contraseña actualizada");
        } catch (\Throwable $e) {
            return $res->setCode(409)->setMessage("No fué posible realizar la operación")->send();
        }
        return $res->send();
    }

    public function registrarUsuario(RegistroUsuariosRequest $request )
    {
        $res = new ApiMessage($request);
        /** @var User $currentUser */
        $currentUser = Auth::user();
        if (!$currentUser->isRoot()) {
            return $res->setCode(403)
                ->setMessage("No tiene permisos para realizar esta operación.")->send();
        }
        $data = $request->validated();


        try {
            $user = new User($data);
            $user->password = Hash::make($data['password']);
            $user->saveOrFail();
            $res->setMessage("Usuario registrado");
        } catch (\Throwable $e) {
            return $res->setCode(409)->setMessage("Se ha producido un error.")
                ->addError($e->getMessage())
                ->send();
        }







        return $res->send();
    }
}
