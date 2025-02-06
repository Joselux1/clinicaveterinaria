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
        $data['queryString'] = !empty($_GET) ? '?' . http_build_query($_GET) : '';
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
            }else{
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
    public function register()
    {
        return view('register');
    }

    public function Registro()
    {   

      
        helper(['form', 'url']);

        $rules = [
            'NOMBRE' => 'required|min_length[3]|max_length[50]',
            'CORREO_ELECTRONICO' => 'required|valid_email|is_unique[cliente.CORREO_ELECTRONICO]', // Tabla corregida
            'CONTRASEÑA' => 'required|min_length[6]', // Se cambia a minúscula para coincidir con el input del formulario
            'password_confirm' => 'required|matches[CONTRASEÑA]', // Debe coincidir con el input del formulario
        ];

        if (!$this->validate($rules)) {
            return view('register', [
                'validation' => $this->validator,
            ]);
        }

        $clienteModel = new ClienteModel();
        $clienteModel->save([
            'NOMBRE' => $this->request->getPost('NOMBRE'),
            'CORREO_ELECTRONICO' => $this->request->getPost('CORREO_ELECTRONICO'),
            'CONTRASEÑA' => password_hash($this->request->getPost('CONTRASEÑA'), PASSWORD_DEFAULT), // Encriptación correcta
        ]);

        return redirect()->to('/login')->with('success', 'Usuario registrado correctamente.');
    }

    public function login()
    {
        return view('/login');
    }

    public function InicioSesion()
    {

        helper(['form', 'url']);
        $session = session();

        $rules = [
            'CORREO_ELECTRONICO' => 'required|valid_email',
            'CONTRASEÑA' => 'required', 
        ];

        if (!$this->validate($rules)) {
            return view('login', [
                'validation' => $this->validator,
            ]);
        }

        $clienteModel = new ClienteModel();
        $cliente = $clienteModel->where('CORREO_ELECTRONICO', $this->request->getPost('CORREO_ELECTRONICO'))->first();

        if ($cliente && password_verify($this->request->getPost('CONTRASEÑA'), $cliente['CONTRASEÑA'])) {
            $session->set([
                'PK_ID_CLIENTE' => $cliente['PK_ID_CLIENTE'],
                'NOMBRE' => $cliente['NOMBRE'],
                'CORREO_ELECTRONICO' => $cliente['CORREO_ELECTRONICO'],
                'isLoggedIn' => true,
            ]);

            return redirect()->to('/dashboard')->with('success', 'Inicio de sesión exitoso.');
        }

        return redirect()->to('/login')->with('error', 'Correo o contraseña incorrectos.');
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/login')->with('success', 'Has cerrado sesión correctamente.');
    }

    public function dashboard()
    {
        return view('dashboard');
    }


    public function AgregarUsuario()
    {   
        helper(['form', 'url']);  
    
        $rules = [
            'NOMBRE' => 'required|min_length[3]|max_length[50]',
            'CORREO_ELECTRONICO' => 'required|valid_email|is_unique[cliente.CORREO_ELECTRONICO]',
          
         
        ];
    
     
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
    
        // Guardar el nuevo usuario
        $clienteModel = new ClienteModel();
        $clienteModel->save([
            'NOMBRE' => $this->request->getPost('NOMBRE'),
            'CORREO_ELECTRONICO' => $this->request->getPost('CORREO_ELECTRONICO'),
            'CONTRASEÑA' => $this->request->getPost('CONTRASEÑA'),
           
            
        ]);
    
     
        return redirect()->to('clientes')->with('success', 'Usuario agregado correctamente.');
    }
    

}

    

