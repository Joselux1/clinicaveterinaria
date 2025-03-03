<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\Services;

class AdminFiltros implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // Verificar si el usuario está logueado y es administrador
        if (!$session->has('isLoggedIn') || $session->get('ROL') !== 'Administrador') {
            return redirect()->to('/home')->with('error', 'No tienes permiso para acceder a esta página.');
        }
        
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    
    }
}
