<?php 

namespace App\Controllers;

use App\Models\ClienteModel;
use CodeIgniter\Controller;

class ClienteControlador extends BaseController
{
    public function index()
    {
        
        $clienteModel = new ClienteModel();
        $query = $clienteModel;
        $nombre = $this->request->getVar('NOMBRE'); // Obtener el nombre desde la URL
    
        if ($nombre) {
            $query = $query->like('NOMBRE', $nombre);
        }
    
        $perPage = 3; // Número de elementos por página
        $data['clientes'] = $query->paginate($perPage); // Obtener clientes paginados
        $data['pager'] = $clienteModel->pager; // Pasar el objeto del paginador a la vista
        $data['nombre'] = $nombre ?? ''; // Asegurar que se pase a la vista
    
        return view('lista_cliente', $data); // Cargar la vista con los datos
    }
    

    public function guardarCliente($id = null)
    {
        $clienteModel = new ClienteModel();
        helper(['form', 'url']);
        // Cargar datos del cliente si es edición
        $data['cliente'] = $id ? $clienteModel->find($id) : null;

        if ($this->request->getMethod() == 'POST') {
            // Reglas de validación
            $validation = \Config\Services::validation();
            $validation->setRules([
                'NOMBRE' => 'required|min_length[3]|max_length[50]',
                'CORREO_ELECTRONICO' => 'required|valid_email',
                'CONTRASEÑA' => 'required|min_length[6]'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                // Mostrar errores de validación
                $data['validation'] = $validation;
            } else {
                // Preparar datos del formulario
                $clienteData = [
                    'NOMBRE' => $this->request->getPost('NOMBRE'),
                    'CORREO_ELECTRONICO' => $this->request->getPost('CORREO_ELECTRONICO'),
                    'CONTRASEÑA' => password_hash($this->request->getPost('CONTRASEÑA'), PASSWORD_DEFAULT),
                    'FECHA_BAJA' => null // Se puede manejar posteriormente si se requiere
                ];

                if ($id) {
                    // Actualizar cliente existente
                    $clienteModel->update($id, $clienteData);
                    $message = 'Cliente actualizado correctamente.';
                } else {
                    // Crear nuevo cliente
                    $clienteModel->save($clienteData);
                    $message = 'Cliente creado correctamente.';
                }

                // Redirigir al listado con un mensaje de éxito
                return redirect()->to('/clientes')->with('success', $message);
            }
        }

        // Cargar la vista del formulario (crear/editar)
        return view('cliente_form', $data);
    }

    public function borrar($id)
    {
        $clienteModel = new ClienteModel();
        $clienteModel->delete($id); // Eliminar cliente
        return redirect()->to('/clientes')->with('success', 'Cliente eliminado correctamente.');
    }
}
