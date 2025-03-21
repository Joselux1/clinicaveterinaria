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
        if(!session()->has('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }
        $citasModel = new CitaModel();
        
        // Obtener filtros
        $fecha = $this->request->getVar('FECHA');
        $diagnostico = $this->request->getVar('DIAGNOSTICO');
        $tratamiento = $this->request->getVar('TRATAMIENTO');
        $fecha_baja = $this->request->getVar('FECHA_BAJA');
        $filtroFechaBaja = $this->request->getVar('filtro_fecha_baja') ?? '1'; // Por defecto muestra activos
    
        // Obtener parámetros de ordenación
        $ordenar_por = $this->request->getVar('ordenar_por') ?? 'FECHA';
        $ordenar_direccion = $this->request->getVar('ordenar_direccion') ?? 'asc';
    
        // Columnas permitidas para ordenar
        $columnas_permitidas = ['FECHA', 'DIAGNOSTICO', 'TRATAMIENTO'];
        
        if (!in_array($ordenar_por, $columnas_permitidas)) {
            $ordenar_por = 'FECHA'; // Valor por defecto
        }
    
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
    
        // Aplicar filtro de fecha de baja
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
    
        // Aplicar ordenación
        $query->orderBy('cita.' . $ordenar_por, $ordenar_direccion);
    
        // Paginación
        $perPage = 3;
        $data['citas'] = $query->paginate($perPage);
        $data['pager'] = $citasModel->pager;
    
        // Enviar parámetros a la vista
        $data['fecha'] = $fecha ?? '';
        $data['diagnostico'] = $diagnostico ?? '';
        $data['tratamiento'] = $tratamiento ?? '';
        $data['fecha_baja'] = $fecha_baja ?? '';
        $data['filtro_fecha_baja'] = $filtroFechaBaja;
        $data['ordenar_por'] = $ordenar_por;
        $data['ordenar_direccion'] = $ordenar_direccion;
    
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
    public function reactivar($id)
    {
        $citaModel = new CitaModel();
        $citaModel->update($id, ['FECHA_BAJA' => null]);
    
        return redirect()->to('/citas')->with('success', 'Citas reactivado correctamente.');
    }
    public function exportarCSV()
{
    $citaModel = new CitaModel();

    // Obtener citas con información del cliente, mascota y medicamento
    $citas = $citaModel->select('cita.FECHA, cita.DIAGNOSTICO, cita.TRATAMIENTO, 
                                 cliente.NOMBRE as CLIENTE, 
                                 mascota.NOMBRE as MASCOTA, 
                                 medicamento.NOMBRE as MEDICAMENTO')
                       ->join('mascota', 'cita.MASCOTA_ID = mascota.PK_ID_MASCOTA', 'left')
                       ->join('cliente', 'mascota.CLIENTE_ID = cliente.PK_ID_CLIENTE', 'left')
                       ->join('medicamento', 'cita.PK_ID_CITA = medicamento.CITA_ID', 'left')
                       ->findAll();

    // Nombre del archivo CSV
    $filename = 'citas_' . date('Ymd') . '.csv';

    // Configurar cabeceras para la descarga
    header("Content-Type: text/csv; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");

    // Abrir el archivo de salida
    $output = fopen('php://output', 'w');

    // Escribir la cabecera del CSV
    fputcsv($output, ['Fecha', 'Diagnóstico', 'Tratamiento', 'Cliente', 'Mascota', 'Medicamento']);

    // Escribir cada fila con datos
    foreach ($citas as $cita) {
        fputcsv($output, [
            $cita['FECHA'], 
            $cita['DIAGNOSTICO'], 
            $cita['TRATAMIENTO'], 
            $cita['CLIENTE'], 
            $cita['MASCOTA'], 
            $cita['MEDICAMENTO']
        ]);
    }

    fclose($output);
    exit;
}

}
