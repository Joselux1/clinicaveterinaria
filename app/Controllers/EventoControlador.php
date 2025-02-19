<?php
 
namespace App\Controllers;
 
use App\Models\EventModel;
use CodeIgniter\Database\Query;

class EventoControlador extends BaseController
{
    public function index()
    {
        return view('/eventos');
    }   
    public function busquedaEventos()
    {
        $eventModel = new EventModel();
        $query = $eventModel->select('eventos.PK_ID_EVENTO, eventos.TITULO, eventos.FECHA_INICIO, eventos.FECHA_FIN, eventos.DESCRIPCION_ES, eventos.DESCRIPCION_ENG, eventos.FECHA_ELIMINACION, tipo_evento.titulo')
                            ->join('tipo_evento', 'eventos.ID_TIPO_EVENTO = tipo_evento.PK_ID_TIPO_EVENTO', 'left');
        
        $nombre = $this->request->getVar('nombre');
        $fecha = $this->request->getVar('fecha');
    
        if (!empty($nombre)) {
            $query->like('eventos.TITULO', $nombre);
        }
        if (!empty($fecha)) {
            $query->like('eventos.FECHA_INICIO', $fecha);
        }
    
        $data = $query->findAll();
        return $this->response->setJSON($data);
    }
    
    
 
    public function añadirEventos()
    {
        helper(['form', 'url']); 

        $eventModel = new EventModel();
        $eventModel ->save(row : [
        'TITULO' => $this->request->getPost('TITULO'),
        'FECHA_INICIO' => $this->request->getPost('FECHA_INICIO'),
        'FECHA_FIN' => $this->request->getPost('FECHA_FIN'),
        'DESCRIPCION_ES' => $this->request->getPost('DESCRIPCION_ES'),
        'DESCRIPCION_ENG' => $this->request->getPost('DESCRIPCION_ENG'),
        ]);
 
    }
 
    public function borrarEventos($id)
    {
        $eventModel = new EventModel();
        $eventModel->delete($id);
    }
}