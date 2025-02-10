<?php
namespace App\Controllers;

use App\Models\MascotaModel;
use CodeIgniter\Controller;

class MascotaControlador extends BaseController
{
    public function index()
    {
        $mascotaModel = new MascotaModel();
        $query = $mascotaModel;
        $nombre = $this->request->getVar('NOMBRE');  // Obtener el nombre desde la URL
        $especie = $this->request->getVar('ESPECIE');
        $raza = $this->request->getVar('RAZA');
        $edad = $this->request->getVar('EDAD');
        $fecha_baja = $this->request->getVar('FECHA_BAJA');

      

        if (!empty($nombre)) {
            $query->like('NOMBRE', $nombre);
        }
        if (!empty($especie)) {
            $query->like('ESPECIE', $especie);
        }
        if (!empty($raza)) {
            $query->like('RAZA', $raza);
        }
        if (!empty($edad)) {
            $query->where('EDAD', $edad);
        }
        if (!empty($fecha_baja)) {
            $query->where('FECHA_BAJA', $fecha_baja);
        }

        $perPage = 3;  // Número de elementos por página
        $data['mascotas'] = $query->paginate($perPage);  // Obtener mascotas paginadas
        $data['pager'] = $mascotaModel->pager;  // Pasar el objeto del paginador a la vista
        $data['nombre'] = $nombre ?? '';  // Asegurar que se pase a la vista
        $data['especie'] = $especie ?? '';
        $data['raza'] = $raza ?? '';
        $data['edad'] = $edad ?? '';
        $data['fecha_baja'] = $fecha_baja ?? '';

        return view('lista_mascota', $data);  // Cargar la vista con los datos
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
        $mascotaModel->delete($id);  // Eliminar mascota
        return redirect()->to('/mascotas')->with('success', 'Mascota eliminada correctamente.');
    }
}
