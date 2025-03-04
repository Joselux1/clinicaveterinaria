<?php

namespace App\Controllers;

use App\Models\ClienteModel;

class Home extends BaseController
{
    public function index()
    {
        if (!session()->has('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }
        return view('index');
    }

    public function obtenerCliente()
    {
        $clienteModel = new ClienteModel();
        $clientes = $clienteModel->findAll();
        
        return view('lista_cliente', ['clientes' => $clientes]);
    }
}
