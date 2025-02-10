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
        $query = $clienteModel;

        // Obtener todos los roles y clientes
        $roles = $rolModel->findAll();
        $clientes = $clienteModel->obtenerClientesConRol();
      

        $perPage = 3; // Número de elementos por página
        $data['clientes'] = $query->paginate($perPage); // Obtener clientes paginados
        $data['pager'] = $clienteModel->pager; // Pasar el objeto del paginador a la vista
        $data['nombre'] = $nombre ?? ''; // Asegurar que se pase a la vista
      
        return view('listar_rol', [
            'roles' => $roles,
            'clientes' => $clientes,
            'pager' => $data['pager'],
            'nombre' => $data['nombre'],
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
