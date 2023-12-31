<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function responseToJSON($response)
    {
        try {
            if (isset($response['error'])) {
                return response()->json(['errors' => $response['error']], $response['code']);
            }

            if (isset($response['message'])) {
                return response()->json(['message' => $response['message']], 200);
            }

            if ($response) {
                return response()->json($response, 200);
            }

            return response()->json(['message' => 'success'], 200);
        } catch (Throwable $error) {
            return response()->json(['errors' => $error['error']], 500);
        }
    }

    protected function handleServiceCall(callable $serviceFunction)
    {
        try {
            $response = $serviceFunction();
            return $this->responseToJSON($response);
        } catch (HttpException $exception) {
            return $this->responseToJSON([
                'error' => $exception->getMessage(),
                'code' => $exception->getStatusCode()
            ]);
        } catch (Throwable $exception) {
            return $this->responseToJSON([
                'error' => $exception->getMessage(),
                'code' => Response::HTTP_BAD_GATEWAY
            ]);
        }
    }
}
