<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function successResponse($data, $code = 200)
    {
        return response()->json(['data' => $data], $code);
    }

    protected function errorResponse($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }
}
