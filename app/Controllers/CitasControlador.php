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
        
        $fecha = $this->request->getVar('FECHA');
        $diagnostico = $this->request->getVar('DIAGNOSTICO');
        $tratamiento = $this->request->getVar('TRATAMIENTO');
        $fecha_baja = $this->request->getVar('FECHA_BAJA');
        $filtroFechaBaja = $this->request->getVar('filtro_fecha_baja') ?? '1'; // Por defecto muestra activos
    
        // Construir la consulta
        $query = $citasModel->select('cita.*, cliente.NOMBRE as CLIENTE_NOMBRE, mascota.NOMBRE as MASCOTA_NOMBRE, medicamento.NOMBRE as MEDICAMENTO_NOMBRE')
                            ->join('mascota', 'cita.MASCOTA_ID = mascota.PK_ID_MASCOTA', 'left')
                            ->join('cliente', 'mascota.CLIENTE_ID = cliente.PK_ID_CLIENTE', 'left')
                            ->join('medicamento', 'cita.PK_ID_CITA = medicamento.CITA_ID', 'left');
    
        if (!empty($fecha)) {
            $query->like('cita.FECHA', $fecha);
        }
    
        if (!empty($diagnostico)) {
            $query->like('cita.DIAGNOSTICO', $diagnostico);
        }
    
        if (!empty($tratamiento)) {
            $query->like('cita.TRATAMIENTO', $tratamiento);
        }
    
        // Aplicar filtro según el valor del selector
        switch ($filtroFechaBaja) {
            case '1': // Activos
                $query->where('cita.FECHA_BAJA', null);
                break;
            case '2': // Dados de baja
                $query->where('cita.FECHA_BAJA IS NOT NULL');
                break;
            case '3': // Todos (sin filtro)
                break;
            default:
                $query->where('cita.FECHA_BAJA', null); // Por defecto, activos
                break;
        }
    
        $perPage = 3; // Número de elementos por página
        $data['citas'] = $query->paginate($perPage);
        $data['pager'] = $citasModel->pager;
        $data['fecha'] = $fecha ?? '';
        $data['diagnostico'] = $diagnostico ?? '';
        $data['tratamiento'] = $tratamiento ?? '';
        $data['fecha_baja'] = $fecha_baja ?? '';
        $data['filtro_fecha_baja'] = $filtroFechaBaja;
    
        return view('lista_cita', $data);
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
    $citaModel = new CitaModel();
    $fecha_baja = date('Y-m-d H:i:s'); // Fecha actual
    $citaModel->update($id, ['FECHA_BAJA' => $fecha_baja]);

    return redirect()->to('/citas')->with('success', 'Cita marcada como dada de baja correctamente.');
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
