<?php


namespace App\Core\Tools;


use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use JsonSerializable;

class ApiMessage implements JsonSerializable
{
    protected $status = 200;
    public $message;

    protected $errors = [];
    protected $logs = [];
    public $data;

    public function __construct(\Illuminate\Http\Request $request = null)
    {
        if($request !== null && $request->has('API_MESSAGE')){
            $this->initFromRequest($request);
        }
    }

    private function initFromRequest(\Illuminate\Http\Request $request)
    {
        $res = $request->get('API_MESSAGE');
        $vars = get_object_vars($res);
        foreach ($vars as $key => $value) {
            $this->$key = $value;
        }
    }

    public function setCode(int $code)
    {
        $this->status = $code;
        return $this;
    }
    public function setMessage(string $message)
    {
        $this->message = $message;
        return $this;
    }
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function addError(string $errorMessage)
    {
        $this->errors[] = $errorMessage;
        return $this;
    }
    public function addErrors(array $errores)
    {
        foreach ($errores as $err) {
            $this->errors[] = $err;
        }
        return $this;
    }

    public function addLog(string $logMessage)
    {
        $this->logs[] = $logMessage;
        return $this;
    }


    public function jsonSerialize()
    {
        $response = get_object_vars($this);
        # no enviamos el campo status
        unset($response['status']);
        // Eliminamos los campos vacíos
        if(!$this->data){
            unset($response['data']);
        }
        if(!$this->errors){
            unset($response['errors']);
        }
        if(!$this->logs){
            unset($response['logs']);
        }
        if(!$this->message){
            unset($response['message']);
        }



        return $response;
    }

    /***
     * Devuelve una respose para enviar al cliente
     * @return ResponseFactory|Response
     */
    public function send()
    {
        return response($this,$this->status);
    }
}
