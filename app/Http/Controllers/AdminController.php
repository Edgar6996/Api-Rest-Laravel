<?php

namespace App\Http\Controllers;

use App\Core\Tools\ApiMessage;
use App\Enums\TiposUsuarios;
use App\Models\AppLogs;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function indexAppLogs(Request $request )
    {
        return AppLogs::orderBy('id','desc')
            ->paginate();

    }
}
