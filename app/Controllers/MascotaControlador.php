<?php
namespace App\Controllers;

use App\Models\MascotaModel;
use CodeIgniter\Controller;

class MascotaControlador extends BaseController
{
    public function index()
    {
        $mascotaModel = new MascotaModel();
    
        $nombre = $this->request->getVar('NOMBRE');
        $especie = $this->request->getVar('ESPECIE');
        $raza = $this->request->getVar('RAZA');
        $edad = $this->request->getVar('EDAD');
        $fecha_baja = $this->request->getVar('FECHA_BAJA');
        $filtroFechaBaja = $this->request->getVar('filtro_fecha_baja') ?? '1'; // Por defecto muestra activos
    
        // Construir la consulta
        $query = $mascotaModel->select('mascota.*, cliente.NOMBRE as CLIENTE_NOMBRE')
                              ->join('cliente', 'mascota.CLIENTE_ID = cliente.PK_ID_CLIENTE', 'left');
    
        if (!empty($nombre)) {
            $query->like('mascota.NOMBRE', $nombre);
        }
    
        if (!empty($especie)) {
            $query->like('mascota.ESPECIE', $especie);
        }
    
        if (!empty($raza)) {
            $query->like('mascota.RAZA', $raza);
        }
    
        if (!empty($edad)) {
            $query->where('mascota.EDAD', $edad);
        }
    
        // Aplicar filtro según el valor del selector
        switch ($filtroFechaBaja) {
            case '1': // Activos
                $query->where('mascota.FECHA_BAJA', null);
                break;
            case '2': // Archivados
                $query->where('mascota.FECHA_BAJA IS NOT NULL');
                break;
            case '3': // Todos (sin filtro)
                break;
            default:
                $query->where('mascota.FECHA_BAJA', null); // Por defecto, activos
                break;
        }
    
        $perPage = 3; // Número de elementos por página
        $data['mascotas'] = $query->paginate($perPage);
        $data['pager'] = $mascotaModel->pager;
        $data['nombre'] = $nombre ?? '';
        $data['especie'] = $especie ?? '';
        $data['raza'] = $raza ?? '';
        $data['edad'] = $edad ?? '';
        $data['fecha_baja'] = $fecha_baja ?? '';
        $data['filtro_fecha_baja'] = $filtroFechaBaja;
    
        return view('lista_mascota', $data);
    }
    
    public function guardarMascota($id = null)
    {
        $mascotaModel = new MascotaModel();
        helper(['form', 'url']);
        // Cargar datos de la mascota si es edición
        $data['mascota'] = $id ? $mascotaModel->find($id) : null;

        if ($this->request->getMethod() == 'POST') {
            // Reglas de validación
            $validation = \Config\Services::validation();
            $validation->setRules([
                'NOMBRE' => 'required|min_length[3]|max_length[50]',
                'ESPECIE' => 'required|min_length[3]|max_length[50]',
                'RAZA' => 'required|min_length[3]|max_length[50]',
                'EDAD' => 'required|integer',
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                // Mostrar errores de validación
                $data['validation'] = $validation;
            } else {
                // Preparar datos del formulario
                $mascotaData = [
                    'NOMBRE' => $this->request->getPost('NOMBRE'),
                    'ESPECIE' => $this->request->getPost('ESPECIE'),
                    'RAZA' => $this->request->getPost('RAZA'),
                    'EDAD' => $this->request->getPost('EDAD'),
                    'CLIENTE_ID' => $this->request->getPost('CLIENTE_ID'),
                    'FECHA_BAJA' => null  // Si es necesario gestionar fecha baja, se puede manejar aquí
                ];

                if ($id) {
                    // Actualizar mascota existente
                    $mascotaModel->update($id, $mascotaData);
                    $message = 'Mascota actualizada correctamente.';
                } else {
                    // Crear nueva mascota
                    $mascotaModel->save($mascotaData);
                    $message = 'Mascota creada correctamente.';
                }

                // Redirigir al listado con un mensaje de éxito
                return redirect()->to('/mascotas')->with('success', $message);
            }
        }

        // Cargar la vista del formulario (crear/editar)
        return view('mascota_form', $data);
    }

    public function borrar($id)
    {
        $mascotaModel = new MascotaModel();
        $fecha_baja = date('Y-m-d H:i:s'); // Fecha actual
        $mascotaModel->update($id, ['FECHA_BAJA' => $fecha_baja]);
    
        return redirect()->to('/mascotas')->with('success', 'Mascota marcada como dada de baja correctamente.');
    }
}