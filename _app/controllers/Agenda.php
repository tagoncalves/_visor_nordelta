<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agenda extends CI_Controller {

/*
* Nombre:  Agenda
* Version: 1.0
* Autor:  Facundo Ezequiel Albesa
* Created:  18.08.2015
* Descripcion:  Controlador para Agenda de pacientes del sistema de Historia Clinica Digital
* Changelog: 
* * 18.08.2015: Startup
* * 15.09.2015: Se agregaron funciones getPaciente, getAntecedentes y getDiagnostico
* * 16.09.2015: Se agrega funcion grabarDiagnostico
* * 18.09.2015: Se agrega funcion cargarDiagnostico
*/
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('Agenda_model','agenda');
		$this->load->library('form_validation');
		
		if ($this->session->userdata('login') == null) 
		{
			redirect('user/login');			
		}
	} 

	public function index()
	{
		if ($this->session->userdata('login') == null) {
			redirect('user/login');
		} else {
			redirect('agenda/consultaHC');
		}
	}
	
	public function getPregunta(){
		$data = $this->agenda->getPregunta(); 		
		
		echo json_encode($data);
	}
	
	public function setRespuesta(){
		$res = $this->input->post('res');
		$txt = $this->input->post('txt');
		
		$data = $this->agenda->setRespuesta($res, $txt); 		
		
		echo json_encode($data);
	}
	
	public function consultaHc()
	{
		$data['page'] = 'consultahc';
		$data['page_title'] = 'Historia Cl&iacute;nica';
		$data['user'] = $this->session->userdata('username');
				
		$this->load->view('inc/header',$data);
		$this->load->view('inc/menu',$data);
		
		$this->load->view('agenda/consultaHc');
		$this->load->view('inc/footer',$data);
	}
	
	public function buscarPaciente(){
		$hc = $this->input->post('hc');
		$nombre = $this->input->post('nombre');
		$dni = $this->input->post('dni');
		
		$data['pacientes'] = $this->agenda->filtroPaciente($hc,$nombre,$dni); 		
		
		echo json_encode($data);
	}

	public function getImagenVM($id){
		$data = $this->agenda->imagenVM($id); 							
		
		if (isset($data->url))
		{
			echo $this->curl->simple_post(urldecode($data->url));
		}
	}

	public function cargarAgenda()
	{
		$datos = $this->agenda->cargarAgenda();
		
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($datos));
	}
	
	public function cargarPaciente()
	{	
		$ingreso = $this->input->post('ingreso');
		$hc = $this->input->post('hc');
		$diagnostico = $this->input->post('diagnostico');
		$turno = $this->input->post('turno');
		
		$data['hc'] = $hc;
		$data['id'] = $diagnostico;
		$data['turno'] = $turno;
		
		$data['ingreso'] = $this->agenda->getIngreso($ingreso, $hc);
		//$data['antecedentes'] = $this->agenda->getAntecedentes($hc);
		$data['antecedentes'] = $this->agenda->getAntecedentesFull($hc);
		$data['diagnostico'] = $this->agenda->getDiagnostico($diagnostico);
		
		$this->load->view('inc/datos_paciente',$data);
	}
	
	public function cargarDatosPac()
	{
		$ingreso = $this->input->post('ingreso');
		$hc = $this->input->post('hc');
		
		$data['ingreso'] = $this->agenda->getIngreso($ingreso,$hc);
		$data['saf'] = $ingreso;

		echo json_encode($data);
	}
	
	public function cargarDiagnosticoPac()
	{
		$ingreso = $this->input->post('ingreso');
		$hc = $this->input->post('hc');
		$diagnostico = $this->input->post('diagnostico');
		$turno = $this->input->post('turno');
		
		$data['hc'] = $hc;
		$data['saf'] = $ingreso;
		$data['id'] = $diagnostico;
		$data['turno'] = $turno;
		$data['diagnostico'] = $this->agenda->getDiagnostico($diagnostico);
		
		echo json_encode($data);
	}
	
	public function cargarDiagnosticoMamografia()
	{
		$ingreso = $this->input->post('ingreso');
		$hc = $this->input->post('hc');
		$diagnostico = $this->input->post('diagnostico');
		$turno = $this->input->post('turno');
		
		$data = $this->agenda->getDiagnosticoMamografia($diagnostico);
		$data['hc'] = $hc;
		$data['saf'] = $ingreso;
		$data['id'] = $diagnostico;
		$data['turno'] = $turno;
		
		echo json_encode($data);
	}
	
	public function cargarAntecedentesPac()
	{
		$hc = $this->input->post('hc');
		
		//! Obsoleto
		//$data['archivos'] = $this->getArchivos($hc);
		//$data['adjuntos'] = $this->getAdjuntos($hc);
		//$data['informes'] = $this->agenda->getInformes($hc);
		//$data['antecedentes'] = $this->agenda->getAntecedentes($hc);

		$data['antecedentes'] = $this->agenda->getAntecedentesFull($hc);
		$data['archivosant'] = $this->agenda->getArchivosAnt($hc, false);
		
		echo json_encode($data);
	}
	
	public function getInformesPac()
	{
		$this->load->model('estudios_model');
		$hc = $this->input->post('hc');
		$datos = $this->estudios_model->getEstudios($hc);
		
		echo json_encode($datos);
	}
	
	public function cargarInforme($id)
	{
		$data['informe'] = $this->agenda->getInforme($id);
		
		$this->load->view('inc/informe',$data);
	}
	
	public function cargarProblemas()
	{
		$hc = $this->input->post('hc');
		$data['problemas'] = $this->agenda->getProblemas($hc);
		
		echo json_encode($data);
	}
	
	// TODO: PURGAR.
	//! VERSION VIEJA, SI CUANDO ESTAS LEYENDO ESTE COMENTARIO, EL CODIGO DE ABAJO ESTA COMENTADO ENTONCES BORRALO.
	/*
	public function cargarDiagnostico($ingreso)
	{
		$data['diagnostico'] = $this->agenda->getDiagnostico($ingreso); 		
		$this->load->view('inc/diagnostico',$data);
	}
	*/

	public function cargarDiagnostico($ingreso)
	{
		$sector = $this->input->post('sector');
		$sede = $this->input->post('sede');
		$data['diagnostico'] = $this->agenda->getDiagnostico($ingreso, $sector, $sede); 		
		$this->load->view('inc/diagnostico',$data);
	}
	
	public function cargarDiagnosticoMamografiaVisor($ingreso)
	{
		$data['diagnostico'] = $this->agenda->getDiagnosticoMamografia($ingreso); 		
		$this->load->view('inc/diagnosticomamografia',$data);
	}
	public function cargarNota($hc)
	{
		$data['nota'] = $this->agenda->getNota($hc); 		
		echo json_encode($data);
	}
	
	public function getServicios()
	{
		return null;	
	}
	
	public function cambiarServicio($servicio)
	{
		$this->session->set_userdata('id_servicio', $servicio);
		redirect('agenda');		
	}

	public function grabarDiagnosticoMamografia()
	{
		$this->form_validation->set_error_delimiters('<div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>','</div>');	
		$this->form_validation->set_rules('mamo-diagnostico','motivo','trim|required');
		$this->form_validation->set_rules('mamo-antecedentes','antecedentes','trim|required');
		
		
		$this->form_validation->set_message('required', 'Campo <strong>%s</strong> es obligatorio');
		
		$errores = "";
		if (!$this->form_validation->run())
		{
			$errores = validation_errors();
			
			$datos['errores'] = $errores;
			
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($datos));
		}		
		else 
		{
			
			$diagnostico = strtoupper($this->input->post('mamo-diagnostico',FALSE));
			$diagnostico = str_replace("|","/",$diagnostico);
			$diagnostico = str_replace("^","'",$diagnostico);
			$diagnostico = str_replace("\"","'",$diagnostico);
			$diagnostico = str_replace("\\","/",$diagnostico);
			
			$antecedentes = strtoupper($this->input->post('mamo-antecedentes',FALSE));
			$antecedentes = str_replace("|","/",$antecedentes);
			$antecedentes = str_replace("^","'",$antecedentes);
			$antecedentes = str_replace("\"","'",$antecedentes);
			$antecedentes = str_replace("\\","/",$antecedentes);
			
			$ingreso = $this->input->post('mamo-ingreso');
			$hc = $this->input->post('mamo-hc');
			$id = $this->input->post('mamo-id');

			$hijos = ($this->input->post('mamo-hijos') == 'on' ? '1' : '0') ;
			$lactancia = ($this->input->post('mamo-lactancia') == 'on' ? '1' : '0');
			$hormonas = ($this->input->post('mamo-hormonas') == 'on' ? '1' : '0');
			$menopaucia = ($this->input->post('mamo-menopausia') == 'on' ? '1' : '0');
			
			$operaciones = ($this->input->post('mamo-operaciones') == 'on' ? '1' : '0');
			if($operaciones === '1'){
				if(trim($this->input->post('mamo-fecoper')) == ""){
					$errores = "Debe ingresar fecha de operacion.";
				}else{
					$temp = explode("/",$this->input->post('mamo-fecoper'));
					$temp2 = $this->agenda->fec2jul(date('Ymd', strtotime($temp[2].$temp[1].$temp[0])));
					
					$operaciones .= "^".$temp2."^";
					$operaciones .= ($this->input->post('mamo-operiz') == 'on' ? '1' : '0')."^";
					$operaciones .= ($this->input->post('mamo-operder') == 'on' ? '1' : '0');
				}
			}else{
				$operaciones .= "^^0^0";
			}
			
			$punciones = ($this->input->post('mamo-punciones') == 'on' ? '1' : '0');
			if($punciones === '1'){
				
				if(trim($this->input->post('mamo-fecpun')) == ""){
					$errores = "Debe ingresar fecha de puncion.";
				}else{
					$temp = explode("/",$this->input->post('mamo-fecpun'));
					$temp2 = $this->agenda->fec2jul(date('Ymd', strtotime($temp[2].$temp[1].$temp[0])));
					
					$punciones .= "^".$temp2."^";
					$punciones .= ($this->input->post('mamo-puniz') == 'on' ? '1' : '0')."^";
					$punciones .= ($this->input->post('mamo-punder') == 'on' ? '1' : '0')."^";
					$punciones .= strtoupper($this->input->post('mamo-puncionObs'));
				}
			}else{
				$punciones .= "^^0^0^";
			}
			
			$tratamiento = ($this->input->post('mamo-tratamiento') == 'on' ? '1' : '0');
			if($tratamiento === '1'){
				$tratamiento .= "^".($this->input->post('mamo-tamoxifeno') == 'on' ? '1' : '0')."^";
				$tratamiento .= ($this->input->post('mamo-quimioterapia') == 'on' ? '1' : '0')."^";
				$tratamiento .= ($this->input->post('mamo-radioterapia') == 'on' ? '1' : '0')."^";
				$tratamiento .= ($this->input->post('mamo-acelerador') == 'on' ? '1' : '0')."^";
				$tratamiento .= ($this->input->post('mamo-neoadyuvancia') == 'on' ? '1' : '0')."^";
				$tratamiento .= strtoupper($this->input->post('mamo-otros'));
			}else{
				$tratamiento .= "^0^0^0^0^0^";
			}
			
			
			if($errores != ""){
				$datos['errores'] = "<div class='alert'><button type='button' class='close' data-dismiss='alert'>×</button>$errores</div>";
				$this->output->set_content_type('application/json');
				$this->output->set_output(json_encode($datos));
			}else{
				$preguntas = "$hijos|$lactancia|$tratamiento|$hormonas|$menopaucia|$punciones|$operaciones";
			
			
				$datos = $this->agenda->grabarDiagnosticoMamo($diagnostico,$antecedentes,$preguntas,$ingreso,$hc,$id);
			
				$this->output->set_content_type('application/json');
				$this->output->set_output(json_encode($datos));
			}			
		}
	}
	
	public function grabarDiagnostico()
	{
		$this->form_validation->set_error_delimiters('<div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>','</div>');	
		$this->form_validation->set_rules('diag-motivo','motivo','trim|required');
		$this->form_validation->set_rules('diag-diagnostico','diagnostico','trim|required');
		$this->form_validation->set_rules('diag-plan','plan','trim|required');
		$this->form_validation->set_rules('diag-objetivos','datos-objetivos','trim|required');
		
		
		$this->form_validation->set_message('required', 'Campo <strong>%s</strong> es obligatorio');
		
		if (!$this->form_validation->run())
		{
			$errores = validation_errors();
			
			$datos['errores'] = $errores;
			
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($datos));
		}		
		else 
		{
			
			$tipo = $this->input->post('diag-tipo');
			
			$motivo = $this->input->post('diag-motivo',FALSE);
			$motivo = str_replace("|","/",$motivo);
			$motivo = str_replace("^","'",$motivo);
			$motivo = str_replace("\"","'",$motivo);
			$motivo = str_replace("\\","/",$motivo);
			
			$diagnostico = $this->input->post('diag-diagnostico',FALSE);
			$diagnostico = str_replace("|","/",$diagnostico);
			$diagnostico = str_replace("^","'",$diagnostico);
			$diagnostico = str_replace("\"","'",$diagnostico);
			$diagnostico = str_replace("\\","/",$diagnostico);
			
			$objetivos = $this->input->post('diag-objetivos',FALSE);
			$objetivos = str_replace("|","/",$objetivos);
			$objetivos = str_replace("^","'",$objetivos);
			$objetivos = str_replace("\"","'",$objetivos);
			$objetivos = str_replace("\\","/",$objetivos);
			
			$plan = $this->input->post('diag-plan',FALSE);
			$plan = str_replace("|","/",$plan);
			$plan = str_replace("^","'",$plan);
			$plan = str_replace("\"","'",$plan);
			$plan = str_replace("\\","/",$plan);
			
			$turno = $this->input->post('diag-turno');
			$ingreso = $this->input->post('diag-ingreso');
			$hc = $this->input->post('diag-hc');
			$id = $this->input->post('diag-id');		

			$datos = $this->agenda->grabarDiagnostico($tipo,$motivo,$diagnostico,$plan,$turno,$ingreso,$hc,$id,$objetivos);
		
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($datos));
			
		}
	}
	
	public function grabarProblema()
	{
		$this->form_validation->set_error_delimiters('<div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>','</div>');	
		$this->form_validation->set_rules('fecha','fecha','trim|required');
		$this->form_validation->set_rules('texto','diagnostico','trim|required');
		
		$this->form_validation->set_message('required', 'Campo <strong>%s</strong> es obligatorio');
		
		if (!$this->form_validation->run())
		{
			$errores = validation_errors();
			
			$datos['errores'] = $errores;
			
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($datos));
		}		
		else 
		{
			
			$fecha = $this->input->post('fecha');
			$texto =  urldecode($this->input->post('texto'));

			$texto = str_replace("|","/",$texto);
			$texto = str_replace("^","'",$texto);
			$texto = str_replace("\"","'",$texto);
			$texto = str_replace("\\","/",$texto);

			$hc = $this->input->post('hc');
			$estado = $this->input->post('estado');
			$id = $this->input->post('id');		

			$datos = $this->agenda->grabarProblema($fecha,$texto,$estado,$hc,$id);
		
			if (isset($datos->errores))
			{
				$datos->errores = '<div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>' . $datos->errores . '</div>';
			}
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($datos));
			
		}
	}
	
	public function getAdjuntos($hc)
	{
		$arr = null;
		
		$map = directory_map(RUTA_ADJUNTOS.$hc);
		
		if(is_array($map)){
			foreach($map as $key => $valor)
			{
				$datos = get_file_info(RUTA_ADJUNTOS.$hc ."\\".$valor,'date' );
				$arr[$key] = array('archivo' => $valor,'fecha' => date("Y-m-d",$datos['date']));
			}
			
			if (isset($arr))
			{
				usort($arr, array($this,'compararFechas'));	
			}
		}
		
		return $arr;
	}
	
	
	public function getArchivos($hc)
	{
		$arr = null;
		
		$map = directory_map(RUTA_HC . $hc);
		
		if(is_array($map)){
			foreach($map as $key => $valor)
			{
				$datos = get_file_info(RUTA_HC .'cmn'. $hc ."\\cmn".$valor,'date' );
				$arr[$key] = array('archivo' => $valor,'fecha' => date("Y-m-d",$datos['date']));
			}
			
			if (isset($arr))
			{
				usort($arr, array($this,'compararFechas'));	
			}
		}		
		return $arr;
	}
	
	private function compararFechas($a, $b)
	{
		return strnatcmp($b['fecha'], $a['fecha']);
	}

	public function getArchivo($hc,$archivo)
	{
		$archivo = str_replace("%20"," ",$archivo);
		$archivo = RUTA_HC . "cmn" . $hc . "\\cmn" . $archivo; /*urlencode*/
		
		$contenido = file_get_contents($archivo) or die ('error al abrir archivo');
		header("Content-Type: application/pdf");
	
		echo $contenido;
	}

	public function PDF()
	{
		$hc = $_POST['hc'];
		$archivo = $_POST['archivo'];
		
		$data = array('contenido' => base_url('agenda/getArchivo/cmn'.$hc.'/cmn'.$archivo));
	
		$this->load->view('inc/PDF', $data);
	}
	
	public function do_upload(){
		
        // Detect form submission.
		$tipo = $this->input->post('tipo-estudio');
		$hc = $this->input->post('hc-adjuntos');
		$saf = $this->input->post('ingreso-adjuntos');
		
		$nombre = $tipo;
		
		if (!is_dir(RUTA_ADJUNTOS.$hc)) {
			mkdir(RUTA_ADJUNTOS. $hc, 0777, TRUE);
		}
		
		$path = RUTA_ADJUNTOS.$hc;
		$this->load->library('upload');
	   
		/**
		 * Refer to https://ellislab.com/codeigniter/user-guide/libraries/file_uploading.html
		 * for full argument documentation.
		 *
		 */
		 
		// Define file rules
		$this->upload->initialize(array(
			"upload_path"       =>  $path,
			"allowed_types"     =>  "pdf|jpg|png|doc|jpeg|docx|xls|xlsx",
			"max_size"          =>  '20480',
			"file_name"			=>	array($nombre)
		));
		$html = "";

		if($this->upload->do_multi_upload("files")){
			$archivos = $this->upload->get_multi_upload_data();
			foreach($archivos as $k => $archivo){
				$html .= '<li class="alert alert-success"><span class="badge badge-success pull-right">Subido</span>'. $archivo['file_name'] .'</li>';				
			}
		} else {   
			// Output the errors
			$errors = array('error' => $this->upload->display_errors('<li class="alert alert-error"><span class="badge badge-error pull-right">Error</span>', '</li>'));              
			foreach($errors as $k => $error){
				$html .= $error;
			}
		}
	   
		echo $html;

        exit();
    }
	
	public function llamarPaciente(){
		$comp = $_POST['comp'];
		$mat = $this->session->userdata('matricula');
		$box = $this->session->userdata('consul');
		$serv = $this->session->userdata('id_servicio');

		echo json_encode($this->agenda->llamarPac($comp,$mat,$box,$serv));
	}

	public function noLlamarPaciente(){
		$comp = $_POST['comp'];
		$mat = $this->session->userdata('matricula');
		$box = $this->session->userdata('consul');
		$serv = $this->session->userdata('id_servicio');

		echo $this->agenda->noLlamarPac($comp,$mat,$box,$serv);
	}

	public function getArchivoPDF()
	{
		$archivo = $_GET["path"];
		$contenido = file_get_contents($archivo) or die ('error al abrir archivo');
		header("Content-Type: application/pdf");
	
		echo $contenido;
	}

	public function descargarArchivoPDF(){
		$archivo = $_GET["path"];	
		force_download($archivo, NULL);
	}

	public function PDFwithpath()
	{
		$archivo = $_POST['archivo'];

		$data = array(
			'contenido' => base_url('agenda/getArchivoPDF/?path='.$archivo),
			'url' => base_url('agenda/descargarArchivoPDF/?path='.$archivo)
		);
		
		$this->load->view('inc/PDF', $data);
	}
}
