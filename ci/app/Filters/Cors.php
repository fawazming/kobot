<?php

// namespace App\Filters;

// use CodeIgniter\Filters\FilterInterface;
// use CodeIgniter\HTTP\RequestInterface;
// use CodeIgniter\HTTP\ResponseInterface;

// class Cors implements FilterInterface
// {
//     public function before(RequestInterface $request, $arguments = null)
//     {
//         header('Access-Control-Allow-Origin: *');
//         header('Access-Control-Allow-Headers: X-API-Key, X-API-Secret, X-Payment-Signature, Content-Type, Authorization');
//         header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
//         header('Access-Control-Max-Age: 86400');

//        // 2. Handle HTTP OPTIONS preflight request
//         $method = $_SERVER['REQUEST_METHOD'] ?? $request->getMethod();
//         if (strtoupper($method) === "OPTIONS") {
//             // Exit early for preflight checks with a 200 OK status
//             header("HTTP/1.1 200 OK");
//             exit();
//         }
//     }

//     public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
//     {
//     }
// }
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Cors implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Handle incoming preflight OPTIONS requests instantly
        $method = $_SERVER['REQUEST_METHOD'] ?? $request->getMethod();
        if (strtoupper($method) === "OPTIONS") {
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PATCH, PUT, DELETE");
            header("HTTP/1.1 200 OK");
            exit();
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Inject headers into the actual POST/GET/PUT response object before it goes to the browser
        $response->setHeader('Access-Control-Allow-Origin', '*');
        $response->setHeader('Access-Control-Allow-Headers', 'X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization');
        $response->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PATCH, PUT, DELETE');

        return $response;
    }
}

?>