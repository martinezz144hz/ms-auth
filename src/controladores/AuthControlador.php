<?php
 
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
 
require __DIR__ . '/../modelos/Usuario.php';
 
class AuthControlador {
 
    // iniciar secion
    
    public function login(Request $request, Response $response): Response {
        $datos = $request->getParsedBody();
 
        $usuario  = $datos['usuario'] ?? '';
        $password = $datos['password'] ?? '';
 
        // Validar que vengan los datos
        if (empty($usuario) || empty($password)) {
            return $this->respuesta($response, [
                'message' => 'Usuario y contraseña son requeridos.'
            ], 400);
        }
 
        // Buscar usuario en la base
        $user = Usuario::where('usuario', $usuario)
                       ->orWhere('email', $usuario)
                       ->first();
 
        if (!$user || !password_verify($password, $user->password)) {
            return $this->respuesta($response, [
                'message' => 'Credenciales incorrectas.'
            ], 401);
        }
 
        // Generar token simple
        $token = bin2hex(random_bytes(32));
 
        // Guardar token en la base
        $user->token      = $token;
        $user->token_exp  = date('Y-m-d H:i:s', strtotime('+8 hours'));
        $user->save();
 
        return $this->respuesta($response, [
            'token'   => $token,
            'usuario' => $user->usuario,
            'message' => 'Sesión iniciada correctamente.'
        ], 200);
    }
 
    // cerrar secion
    public function logout(Request $request, Response $response): Response {
        $token = $request->getHeaderLine('Authorization');
 
        if (empty($token)) {
            return $this->respuesta($response, [
                'message' => 'Token no proporcionado.'
            ], 400);
        }
 
        $user = Usuario::where('token', $token)->first();
 
        if ($user) {
            $user->token     = null;
            $user->token_exp = null;
            $user->save();
        }
 
        return $this->respuesta($response, [
            'message' => 'Sesión cerrada correctamente.'
        ], 200);
    }
 
   // validar token

    public function validar(Request $request, Response $response): Response {
        $token = $request->getHeaderLine('Authorization');
 
        if (empty($token)) {
            return $this->respuesta($response, [
                'message' => 'Token no proporcionado.'
            ], 401);
        }
 
        $user = Usuario::where('token', $token)->first();
 
        if (!$user) {
            return $this->respuesta($response, [
                'message' => 'Token inválido.'
            ], 401);
        }
 
        // Verificar que el token no haya expirado
        if (strtotime($user->token_exp) < time()) {
            $user->token     = null;
            $user->token_exp = null;
            $user->save();
 
            return $this->respuesta($response, [
                'message' => 'Token expirado.'
            ], 401);
        }
 
        return $this->respuesta($response, [
            'usuario' => $user->usuario,
            'message' => 'Token válido.'
        ], 200);
    }
 
    // respuesata de json

    private function respuesta(Response $response, array $datos, int $codigo): Response {
        $response->getBody()->write(json_encode($datos));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($codigo);
    }
}
 


