<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorController extends Controller
{
    public function showErrorPage($exception)
    {
        return view('errors.error', ['exception' => $exception]);
    }
}