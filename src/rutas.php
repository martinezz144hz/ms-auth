<?php
 
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
 
require __DIR__ . '/controladores/AuthControlador.php';
 
$authControlador = new AuthControlador();
 
//rutas de autenticacion
 
$app->post('/login', function (Request $request, Response $response) use ($authControlador) {
    return $authControlador->login($request, $response);
});
 
$app->post('/logout', function (Request $request, Response $response) use ($authControlador) {
    return $authControlador->logout($request, $response);
});
 
//validar token
$app->get('/validate', function (Request $request, Response $response) use ($authControlador) {
    return $authControlador->validar($request, $response);
});
 



