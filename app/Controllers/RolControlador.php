<?php

namespace App\Controllers;

use App\Models\RolModel;
use App\Models\ClienteModel;
use CodeIgniter\Controller;

class RolControlador extends Controller
{
    public function index()
    {
        $rolModel = new RolModel();
        $clienteModel = new ClienteModel();

        // Obtener todos los roles y clientes
        $roles = $rolModel->findAll();
        $clientes = $clienteModel->obtenerClientesConRol();

        return view('listar_rol', [
            'roles' => $roles,
            'clientes' => $clientes,
            'selectedRol' => null
        ]);
    }

    public function filtrarPorRol()
    {
        $rolId = $this->request->getPost('ROL_ID');
        $clienteModel = new ClienteModel();

        $clientes = ($rolId && $rolId !== "") ? 
            $clienteModel->filtrarPorRol($rolId) : 
            $clienteModel->obtenerClientesConRol();

        // Obtener roles nuevamente para mantener el select funcional
        $rolModel = new RolModel();
        $roles = $rolModel->findAll();

        return view('listar_rol', [
            'roles' => $roles,
            'clientes' => $clientes,
            'selectedRol' => $rolId
        ]);
    }
}
