<?php

namespace App\Controllers;

use App\Models\MedicamentoModel;
use CodeIgniter\Controller;

class MedicamentoControlador extends BaseController
{
    public function index()
    {
        $medicamentoModel = new MedicamentoModel();

        // Obtener los filtros desde la URL
        $nombre = $this->request->getVar('NOMBRE');
        $descripcion = $this->request->getVar('DESCRIPCION');
        $fecha_baja = $this->request->getVar('FECHA_BAJA');

        // Construir la consulta
        $query = $medicamentoModel->select('*');

        if (!empty($nombre)) {
            $query->like('NOMBRE', $nombre);
        }
        if (!empty($descripcion)) {
            $query->like('DESCRIPCION', $descripcion);
        }
        if (!empty($fecha_baja)) {
            $query->where('FECHA_BAJA', $fecha_baja);
        }

        // Paginación
        $perPage = 5;  // Número de elementos por página
        $data['medicamentos'] = $query->paginate($perPage);
        $data['pager'] = $medicamentoModel->pager;

        // Pasar filtros a la vista para que los inputs no se borren al recargar
        $data['nombre'] = $nombre ?? '';
        $data['descripcion'] = $descripcion ?? '';
        $data['fecha_baja'] = $fecha_baja ?? '';

        return view('lista_medicamento', $data);
    }
    public function guardarMedicamento($id = null)
    {
        $medicamentoModel = new MedicamentoModel();
        helper(['form', 'url']);
        // Cargar datos del medicamento si es edición
        $data['medicamento'] = $id ? $medicamentoModel->find($id) : null;

        if ($this->request->getMethod() == 'POST') {
            // Reglas de validación
            $validation = \Config\Services::validation();
            $validation->setRules([
                'NOMBRE' => 'required|min_length[3]|max_length[50]',
                'DESCRIPCION' => 'required|min_length[3]|max_length[255]',
                //'CITA_ID' => 'required|integer',
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                // Mostrar errores de validación
                $data['validation'] = $validation;
            } else {
                // Preparar datos del formulario
                $medicamentoData = [
                    'NOMBRE' => $this->request->getPost('NOMBRE'),
                    'DESCRIPCION' => $this->request->getPost('DESCRIPCION'),
                    'CITA_ID' => $this->request->getPost('CITA_ID'),
                    'FECHA_BAJA' => null // Se puede manejar posteriormente si se requiere
                ];

                if ($id) {
                    // Actualizar medicamento existente
                    $medicamentoModel->update($id, $medicamentoData);
                    $message = 'Medicamento actualizado correctamente.';
                } else {
                    // Crear nuevo medicamento
                    $medicamentoModel->save($medicamentoData);
                    $message = 'Medicamento creado correctamente.';
                }

                // Redirigir al listado con un mensaje de éxito
                return redirect()->to('/medicamentos')->with('success', $message);
            }
        }

        // Cargar la vista del formulario (crear/editar)
        return view('medicamento_form', $data);
    }

    public function borrar($id)
    {
        $medicamentoModel = new MedicamentoModel();
        $medicamentoModel->delete($id); // Eliminar medicamento
        return redirect()->to('/medicamentos')->with('success', 'Medicamento eliminado correctamente.');
    }
}
