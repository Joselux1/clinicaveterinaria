<?php 

namespace App\Controllers;

use App\Models\ClienteModel;
use CodeIgniter\Controller;

class ClienteControlador extends BaseController
{
    public function index()
    {
        $clienteModel = new ClienteModel();
    
        $nombre = $this->request->getVar('NOMBRE');
        $correo = $this->request->getVar('CORREO_ELECTRONICO');
        $fecha_baja = $this->request->getVar('FECHA_BAJA');
        $filtroFechaBaja = $this->request->getVar('filtro_fecha_baja') ?? '1'; // Por defecto muestra activos
    
        // Construir la consulta
        $query = $clienteModel->select('cliente.*, rol.ROL')
                              ->join('rol', 'cliente.ID_ROL = rol.PK_ID_ROL', 'left');
    
        if (!empty($nombre)) {
            $query->like('cliente.NOMBRE', $nombre);
        }
    
        if (!empty($correo)) {
            $query->like('cliente.CORREO_ELECTRONICO', $correo);
        }
    
        // Aplicar filtro según el valor del selector
        switch ($filtroFechaBaja) {
            case '1': // Activos
                $query->where('cliente.FECHA_BAJA', null);
                break;
            case '2': // Dados de baja
                $query->where('cliente.FECHA_BAJA IS NOT NULL');
                break;
            case '3': // Todos (sin filtro)
                break;
            default:
                $query->where('cliente.FECHA_BAJA', null); // Por defecto, activos
                break;
        }
    
        $perPage = 3; // Número de elementos por página
        $data['clientes'] = $query->paginate($perPage);
        $data['pager'] = $clienteModel->pager;
        $data['nombre'] = $nombre ?? '';
        $data['correo'] = $correo ?? '';
        $data['fecha_baja'] = $fecha_baja ?? '';
        $data['filtro_fecha_baja'] = $filtroFechaBaja;
    
        return view('lista_cliente', $data);
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
    $fecha_baja = date('Y-m-d H:i:s'); // Fecha actual
    $clienteModel->update($id, ['FECHA_BAJA' => $fecha_baja]);

    return redirect()->to('/clientes')->with('success', 'Cliente marcado como dado de baja correctamente.');
    }
    public function register()
    {
        return view('register');
    }

    public function reactivar($id)
{
    $clienteModel = new ClienteModel();
    $clienteModel->update($id, ['FECHA_BAJA' => null]);

    return redirect()->to('/clientes')->with('success', 'Cliente reactivado correctamente.');
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

    $cliente = $clienteModel->select('cliente.*, rol.ROL')
                            ->join('rol', 'cliente.ID_ROL = rol.PK_ID_ROL', 'left')
                            ->where('cliente.CORREO_ELECTRONICO', $this->request->getPost('CORREO_ELECTRONICO'))
                            ->first();

    if ($cliente && password_verify($this->request->getPost('CONTRASEÑA'), $cliente['CONTRASEÑA'])) {
 
        $session->set([
            'PK_ID_CLIENTE' => $cliente['PK_ID_CLIENTE'],
            'NOMBRE' => $cliente['NOMBRE'],
            'CORREO_ELECTRONICO' => $cliente['CORREO_ELECTRONICO'],
            'ROL' => $cliente['ROL'], // Guardamos el rol en sesión
            'isLoggedIn' => true,
        ]);

        return redirect()->to('/home')->with('success', 'Inicio de sesión exitoso.');
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

    

