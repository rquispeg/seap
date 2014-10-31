<?php


App::uses('AppController', 'Controller');
App::uses('HttpSocket', 'Network/Http');
/**
 */
class CursosController extends AppController{
    var $name = 'Estudiantes';//inicializacion de variables
    public $components = array('RequestHandler');
    public $uses = array('Curso');


    public function index() {

        $datas = $this->Curso->query('SELECT cursos.id as id, cursos.gestion_id as gestion_id, gestiones.gestion as gestion,
cursos.unidad_educativa_id as unidad_educativa_id, unidades_educativas.descripcion as unidad_educativa,
cursos.grado_id as grado_id, niveles.descripcion as nivel, grados.descripcion as grado,
cursos.paralelo_id as paralelo_id, paralelos.descripcion as paralelo,
cursos.turno_id as turno_id, turnos.descripcion as turno FROM cursos 
                                      INNER JOIN gestiones ON cursos.gestion_id = gestiones.id
INNER JOIN unidades_educativas ON cursos.unidad_educativa_id = unidades_educativas.id
INNER JOIN grados ON cursos.grado_id = grados.id
INNER JOIN niveles ON grados.nivel_id = niveles.id
INNER JOIN paralelos ON cursos.paralelo_id = paralelos.id
INNER JOIN turnos ON cursos.turno_id = turnos.id');
        /*$datas = $this->Curso->find('all',array('fields'=>array('Curso.id as id_curso',
                                                                'Curso.gestion_id','Gestion.gestion',
                                                                'Curso.unidad_educativa_id','UnidadEducativa.descripcion as unidad_educativa',
                                                                'Curso.grado_id','Grado.descripcion as grado',
                                                                'Curso.paralelo_id','Paralelo.descripcion as paralelo',
                                                                'Curso.turno_id','Turno.descripcion as turno'),
                                                'recursive'=>2));
        */
        //print_r($datas);
        //die();
        $cursos = array();
        foreach($datas as $data):
            $curso = array();
            foreach ($data as $key => $val):                
                foreach ($val as $key => $value):
                    $curso[$key] = $value;
                endforeach;                
            endforeach;
            array_push($cursos, $curso);
        endforeach;
        $this->set(array(
            'cursos' => $cursos,
            '_serialize' => array('cursos')
        ));       
    }

    public function add(){
        $datasource = $this->Curso->getDataSource();
        $datasource->useNestedTransactions = TRUE;
        $datasource->begin();
        try{
            $this->Curso->create();
            $_curso = array();
            $_curso['gestion_id'] = $this->request->data['gestion_id']['id'];
            $_curso['unidad_educativa_id'] = $this->request->data['unidad_educativa_id']['id'];
            $_curso['grado_id'] = $this->request->data['grado_id']['id'];
            $_curso['paralelo_id'] = $this->request->data['paralelo_id']['id'];
            $_curso['turno_id'] = $this->request->data['turno_id']['id'];           
            $this->Curso->save($_curso);
            $datasource->commit();
            $message = 'Guardado';
        }catch(Exception $e) {
            $datasource->rollback();
            $message = 'Error al Guardar los datos';
        }       

        $this->set(array(
            'message' => $message,
            '_serialize' => array('message')
        ));
    }
}
?>