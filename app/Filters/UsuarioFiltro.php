<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class UsuarioFiltro implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Verifica si el usuario está logueado y tiene el rol de "Usuario"
        if (!$session->has('isLoggedIn') || $session->get('ROL') !== 'Usuario') {
            return redirect()->to('/home')->with('error', 'Acceso denegado.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No se requiere acción después
    }
}
