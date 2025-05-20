<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Cors implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $response = service('response');

        $response->setHeader('Access-Control-Allow-Origin', '*');
        $response->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');

        // Si es una preflight OPTIONS, devolvemos una respuesta vacía con 200
        if ($request->getMethod() === 'options') {
            return $response->setStatusCode(200)->setBody('');
        }
    }


    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
