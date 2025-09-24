<?php

namespace App\Traits;

trait ApiResponder
{
    protected function success($data, $message = 'Success', $status = 200)
    {
        return response()->json([
            'success'  => true,
            'message' => $message,
            'data'    => $data
        ], $status);
    }

    protected function delete($message = 'Success', $status = 204)
    {
        return response()->json([
            'success'  => true,
            'message' => $message,
        ], $status);
    }

    protected function created($data, $message = 'Success')
    {
        return response()->json([
            'success'  => true,
            'message' => $message,
            'data'    => $data
        ], 201);
    }

    protected function error($message, $status = 400, $errors = [])
    {
        return response()->json([
            'success'  => false,
            'message' => $message,
            'data'  => $errors
        ], $status);
    }
}
