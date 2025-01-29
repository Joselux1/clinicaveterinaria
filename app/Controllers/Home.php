<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('index');
    }

    public function obtenerCliente(): string
    {
        $clienteModel = new \App\Models\ClienteModel();
        $clientes = $clienteModel->findAll();
        
        return view('lista_cliente', ['clientes' => $clientes]);
    }

   
}
