<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estudios extends CI_Controller {

	public function __construct() 
	{		
		parent::__construct();
		
		$this->load->model('estudios_model');
		
		if ($this->session->userdata('login') == null) 
		{
			redirect('user/login');			
		}	
	}
	
	public function index()
	{
		$data['page'] = 'agenda';
		$data['page_title'] = 'Historia Cl&iacute;nica';
		$data['user'] = $this->session->userdata('username');
	
		$this->load->view('inc/header',$data);
		$this->load->view('inc/menu',$data);
		$this->load->view('agenda/agenda');
		$this->load->view('inc/footer',$data);
	}

	public function getEstudiosPaciente($hc)
	{
		$datos = "";
		//$hc = "64020";
		//$hcs = explode("-", $hc);
		//$pasaje = $this->estudios_model->getPasaje($hc);
		$datos['solicitudes'] = $this->estudios_model->getPeticionesLaboratorio($hc);
		//if($pasaje != ""){
		//	$datos['solPasaje'] = $this->estudios_model->getPeticionesLaboratorio($pasaje->PASAJE);
		//}
		
		$datos['estudios'] = $this->estudios_model->getEstudios($hc);
		
		$this->load->view('labo/solicitudesEstudios',$datos);
	}
	
	public function getInforme(){
		$id = $_POST['id'];
		$sector = $_POST['sector'];
		
		$datos['informe'] = $this->estudios_model->getInforme($id, $sector);
		
		$this->load->view('labo/visorInforme',$datos);
	}
	
	public function PDF()
	{
		$archivo = $_POST['archivo'];
             
		$data = array('url' => base_url('assets/js/pdfjs/web/viewer.html?file='.$archivo));
		$this->load->view('labo/PDF', $data);
	}
	
	public function getArchivoEstudio($archivo)
	{
		$archivo = str_replace("%20"," ",$archivo);
		$archivo = RUTA_ESTUDIO . $archivo;
		
		$contenido = file_get_contents($archivo) or die ('error al abrir archivo');
		header("Content-Type: application/pdf");
	
		echo $contenido;
	}
	
	public function getResultado($peticion){
        $sector = substr($peticion, 0, 2);

		if($sector == 89){
			$datos = "";
			$datos['cabecera'] = $this->estudios_model->getPeticionxProtocolo(substr($peticion, -6));
			$datos['resultado'] = $this->estudios_model->getResultadoLaboratorio($peticion);
			
			$this->load->view('labo/detalleSolicitud',$datos);
			$this->load->view('labo/resultadoLabo',$datos);
		}elseif($sector == 92){
			$datos = "";
			$datos['cabecera'] = $this->estudios_model->getPeticionxProtocolo(substr($peticion, -6));
			$datos['resultado'] = $this->estudios_model->getResultadoMicrobiologia($peticion);
			
			$this->load->view('labo/detalleSolicitud',$datos);
			$this->load->view('labo/resultadoMicro',$datos);
		}else{
			echo "Se produjo un error al intentar cargar la peticion: " + $peticion;
		}
	}

	public function ViewSolEstudios(){
		/*$datos["toolbar"] = $this->estudios_model->getToolbarEstudios();
		$toolbar = explode("|",$datos["toolbar"]);
		$primerSec = explode("^",$toolbar[0]);
		$datos["estudio"] = $this->estudios_model->listaEstudios($primerSec[0]);
		$view = $this->load->view("estudios/interfazSolEstudios",$datos,true);
		echo $view;*/
		
		echo "<br>";
	}
}