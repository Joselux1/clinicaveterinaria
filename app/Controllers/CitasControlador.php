<?php

namespace App\Controllers;

use App\Models\CitaModel;

class CitasControlador extends BaseController
{
    protected $citaModel;

    public function __construct()
    {
        $this->citaModel = new CitaModel();
    }

    public function index()
    {
        $citasModel = new CitaModel();
        $query = $citasModel;
        $fecha = $this->request->getVar('FECHA'); // Obtener el nombre desde la URL
        $diagnostico = $this->request->getVar('DIAGNOSTICO');
        $tratamiento = $this->request->getVar('TRATAMIENTO');
        $fecha_baja = $this->request->getVar('FECHA_BAJA');
    
        if (!empty($fecha)) {
            $query->like('FECHA', $fecha);
        }
        if (!empty($diagnostico)) {
            $query->like('DIAGNOSTICO', $diagnostico);
        }
        if (!empty($tratamiento)) {
            $query->like('TRATAMIENTO', $tratamiento);
        }
        if (!empty($veterinario_id)) {
            $query->where('VETERINARIO_ID', $veterinario_id);
        }
        if (!empty($mascota_id)) {
            $query->where('MASCOTA_ID', $mascota_id);
        }
        if (!empty($fecha_baja)) {
            $query->where('FECHA_BAJA', $fecha_baja);
        }
    
        $perPage = 3; // Número de elementos por página
        $data['citas'] = $query->paginate($perPage); // Obtener clientes paginados
        $data['pager'] = $citasModel->pager; // Pasar el objeto del paginador a la vista
        $data['fecha'] = $fecha ?? '';
        $data['diagnostico'] = $diagnostico ?? '';
        $data['tratamiento'] = $tratamiento ?? '';
        $data['fecha_baja'] = $fecha_baja ?? '';
        return view('lista_cita', $data);  // Aquí correspondería a tu vista de listado de citas
    }

    public function crear()
    {
        return view('citas_form');  // Vista para crear una nueva cita
    }

    public function guardar()
    {
        // Aquí podrías validar los datos y luego guardarlos
        $data = [
            'FECHA' => $this->request->getPost('FECHA'),
            'DIAGNOSTICO' => $this->request->getPost('DIAGNOSTICO'),
            'TRATAMIENTO' => $this->request->getPost('TRATAMIENTO'),
            'VETERINARIO_ID' => $this->request->getPost('VETERINARIO_ID'),
            'MASCOTA_ID' => $this->request->getPost('MASCOTA_ID'),
        ];
        
        $this->citaModel->insert($data);
        return redirect()->to('citas')->with('success', 'Cita creada con éxito');
    }

    public function editar($id)
    {
        $data['cita'] = $this->citaModel->find($id);
        return view('citas_form', $data);  // Vista para editar una cita existente
    }

    public function actualizar($id)
    {
        // Actualiza la cita con el ID correspondiente
        $data = [
            'FECHA' => $this->request->getPost('FECHA'),
            'DIAGNOSTICO' => $this->request->getPost('DIAGNOSTICO'),
            'TRATAMIENTO' => $this->request->getPost('TRATAMIENTO'),
            'VETERINARIO_ID' => $this->request->getPost('VETERINARIO_ID'),
            'MASCOTA_ID' => $this->request->getPost('MASCOTA_ID'),
        ];

        $this->citaModel->update($id, $data);
        return redirect()->to('/citas')->with('success', 'Cita actualizada con éxito');
    }

    public function eliminar($id)
    {
        $this->citaModel->delete($id);
        return redirect()->to('/citas')->with('success', 'Cita eliminada con éxito');
    }

    public function baja($id)
    {
        // Aquí puedes marcar la cita como baja, por ejemplo, actualizando la fecha de baja
        $data = [
            'FECHA_BAJA' => date('Y-m-d H:i:s'),
        ];
        
        $this->citaModel->update($id, $data);
        return redirect()->to('/citas')->with('success', 'Cita marcada como baja');
    }
}
