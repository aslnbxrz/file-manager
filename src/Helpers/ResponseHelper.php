<?php

use Illuminate\Http\JsonResponse;

function successResponse($message, $data = [], $status = null, $statusCode = 200): JsonResponse
{
    return response()->json([
        "status" => $status,
        "message" => $message,
        "data" => $data
    ], $statusCode);
}

function errorResponse($message, $data = [], $status = null, $statusCode = 500): JsonResponse
{
    return response()->json([
        "status" => $status,
        "message" => $message,
        "data" => $data
    ], $statusCode);
}
