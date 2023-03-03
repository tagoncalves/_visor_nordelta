<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Nombre:  Agenda Model
* Version: 1.0
* Autor:  Facundo Ezequiel Albesa
* Created:  18.08.2015

* Descripcion:  Modelo para Agenda de pacientes del sistema de Historia Clinica Digital
*
* Changelog: 
*
* *  18.08.2015 : Startup
* *  15.09.2015 : Se agregaron funciones getPaciente, getAntecedentes y getDiagnostico
*
*/

class Agenda_model extends CI_Model
{
	public function __construct(){
		parent::__construct();		

		$config = array(
			'mserver' => MSERVER,
			'namespace' => MNAMESPACE, 			
			'http_server' => IP_WS,
			'port' => RandPortWS()
		);
		
		$this->load->library('VisM',$config);
	}
	
	public function getPregunta(){
		$this->vism->borrar_cache();
		$this->vism->P0 = $this->session->userdata('matricula');
		$this->vism->P1 = $this->session->userdata('id_servicio');
		
		$this->vism->execute("D PREGUNTA^HCDIGITAL(P0,P1,.P8,.P9)");

		$ret["pregunta"] = utf8_encode($this->vism->P8);
		$ret["comentario"] = utf8_encode($this->vism->P9);
		
		return $ret; 
	}
	
	public function setRespuesta($res, $text){
		$this->vism->borrar_cache();
		$this->vism->P0 = $this->session->userdata('matricula');
		$this->vism->P1 = $this->session->userdata('id_servicio');
		$this->vism->P2 = $res;
		$this->vism->P3 = $text;
		
		$this->vism->execute("D SETRESPUESTA^HCDIGITAL(P0,P1,P2,P3,.P9)");

		$ret["error"] = utf8_encode($this->vism->P9);
		
		return $ret; 
	}
	
	public function cargarAgenda(){
		$this->vism->borrar_cache();
		$this->vism->P0 = $this->session->userdata('matricula');
		$this->vism->P1 = date("Ymd");
		$this->vism->P2 = $this->session->userdata('id_servicio');

		# MATRICULA, FECHA, ID_SERV, DATOS, CANT
		$this->vism->execute("D AGENDA^WEBAGENDA(P0,P1,P2,.P8,.P9)","^WEBAGENDA",$this->session->userdata('matricula'));

		
		//print_r($this->vism->global);
		//$ret = array ();
		//if($this->vism-> != ""){
		//	if($this->vism->P9 != "0"){
		//		$agenda = explode("|", utf8_encode($this->vism->global));
		//	
		//		foreach($agenda as $paciente){
		//			$datosPac = explode("^", $paciente);
		//			array_push($ret, $datosPac);
		//		}
		//	}
		//}

		return $this->vism->global;
	}
	
	public function imagenVM($id){
		$param = '{
					"datosEstudioABuscar": {
						"estudioaccessionnumber": "' . $id . '"
					}
				}';		
		$urlVM = "http://10.11.0.44/webservicewl/index.php/acciones/url1turno";
		$data = $this->curl->simple_post($urlVM, $param);		
		return json_decode(utf8_encode($data));		
	}

	public function getIngreso($ingreso = '', $hc = ''){
		$this->vism->borrar_cache();
		$this->vism->P0 = $hc;
		$this->vism->P1 = $ingreso;

		# HC, INGRESO, DATOS
		$this->vism->execute("D PACIENTE^VBTURSLL(P0,P1,.P9)");

		return explode("^", utf8_encode($this->vism->P9));
	}
	
	public function getAntecedentes($hc = ''){
		$this->vism->borrar_cache();
		$this->vism->P0 = $hc;

		# HC, DATOS, CANT
		$this->vism->execute("D GETANTECEDENTES^HCDIGITAL(P0,.P8,.P9)");

		$ret = array ();
		if($this->vism->P9 != ""){
			if($this->vism->P9 != "0"){
				$antecedentes = explode("|", utf8_encode($this->vism->P8));
				foreach($antecedentes as $antecedente){
					$data = explode("^", $antecedente);
					//$data[0] = date("Y-m-d", strtotime($data[0]));
					//parche
					$temp = explode("/", $data[0]);
					$data[0] = $temp[2]."-".$temp[1]."-".$temp[0];
					array_push($ret, $data);
				}
			}
		}

		return $ret;
	}

	public function getAntecedentesFull($hc){
		$matricula = $this->session->userdata('matricula');
		
		$this->vism->borrar_cache();
		$this->vism->P0 = $hc;
		$this->vism->P1 = $matricula; 

		# HC, DATOS, CANT
		$this->vism->execute("D ANTECEDENTESFULL^HCDIGITAL(P0,P1)", "^HCDIGITAL", $matricula);
		
		return $this->vism->global;
	}
	
	public function getInforme($id = ''){
		# En donde tire error hay que pasar la llamada al modelo de Estudios
		/*$param = array('id' => $id);
		$data = $this->curl->simple_post(getWebService() . 'GetInforme', $param);		
		return json_decode(utf8_encode($data));*/
	}
	
	public function getProblemas($hc = ''){
		$this->vism->borrar_cache();
		$this->vism->P0 = $hc;

		# HC, DATOS, CANT
		$this->vism->execute("D GETPROBLEMAS3^HCDIGITAL(P0,.P8,.P9)");

		if($this->vism->P9 != "0"){
			$ret = array ();
			$problemas = explode("|", utf8_encode($this->vism->P8));
			foreach($problemas as $problem){
				$data = explode("^", $problem);
				array_push($ret, $data);
			}
		}else{
			$ret = null;
		}

		return $ret; 
	}
	
	// TODO: PURGAR LO COMENTADO.
	//! VERSION VIEJA, SI CUANDO ESTAS LEYENDO ESTE COMENTARIO, EL CODIGO DE ABAJO ESTA COMENTADO ENTONCES BORRALO.
	//public function getDiagnostico($id = ''){
	public function getDiagnostico($id = '', $sector = '', $sede = ''){
		$this->vism->borrar_cache();
		//$this->vism->P0 = $id;
		$this->vism->P0 = $id . "^" . $sede . "^" . $sector;
		//$this->vism->execute("D GETCONSULTAWEB^HCDIGITAL(P0,.P6,.P7,.P8,.P9)");
		$this->vism->execute("D GETCONSULTAFULL^HCDIGITAL(P0,.P6,.P7,.P8,.P9)");
		
		$ret = Array();
		//$this->vism->P6 = str_replace("\n", "",str_replace("\r", "", trim(utf8_encode($this->vism->P6))));
		//$this->vism->P7 = str_replace("\n", "",str_replace("\r", "", trim(utf8_encode($this->vism->P7))));
		//$this->vism->P8 = str_replace("\n", "",str_replace("\r", "", trim(utf8_encode($this->vism->P8))));
		//$this->vism->P9 = str_replace("\n", "",str_replace("\r", "", trim(utf8_encode($this->vism->P9))));

		$this->vism->P6 = trim($this->vism->P6);
		$this->vism->P7 = trim($this->vism->P7);
		$this->vism->P8 = trim($this->vism->P8);
		$this->vism->P9 = trim($this->vism->P9);
		
		if($this->vism->P6 != ""){
			$tmp = explode("^", utf8_encode($this->vism->P6));
			array_push($ret, $tmp[0]);
			array_push($ret, $tmp[1]);
		}else{
			array_push($ret, "");
			array_push($ret, "");
		}
		array_push($ret, $this->vism->P7);
		array_push($ret, $this->vism->P8);
		array_push($ret, $this->vism->P9);
		
       return $ret;
	}

	public function listaMamoHC($ingreso, $hc){
		$this->vism->borrar_cache();
		$this->vism->P0 = $hc."^".$ingreso;
		
		$this->vism->execute("D LISTAMAMOHC^HCDIGITAL(P0,.P9)");
		
		return explode("|", $this->vism->P9);
	}

	public function grabaMamoHC($cab, $ant, $eg, $fc, $punc, $cir, $est){
		$this->vism->borrar_cache();
		$this->vism->P0 = $cab;
		$this->vism->P1 = $ant;
		$this->vism->P2 = $eg;
		$this->vism->P3 = $fc;
		$this->vism->P4 = $punc;
		$this->vism->P5 = $cir;
		$this->vism->P6 = $est;
		
		$this->vism->execute("D GRABAMAMOHC^HCDIGITAL(P0,P1,P2,P3,P4,P5,P6,.P9)");

		$res = [];
		if($this->vism->P9 == "0"){
			$res["ban"] = true;
			$res["res"] = "";
		}else{
			$res["ban"] = false;
			$res["res"] = $this->vism->P9;
		}

		return $res;
	}

	public function getMamoHC($cab){
		$this->vism->borrar_cache();
		$this->vism->P0 = $cab;
		
		$this->vism->execute("D GETMAMOHC^HCDIGITAL(P0,.P1,.P2,.P3,.P4,.P5,.P6,.P7,.P9)");
		
		$res["antecedentes"] = $this->vism->P1;
		$res["eg"] = $this->vism->P2;
		$res["fc"] = $this->vism->P3;
		$res["puncion"] = $this->vism->P4;
		$res["cirugia"] = $this->vism->P5;
		$res["estudios"] = $this->vism->P6;
		$res["matricula"] = $this->vism->P7;

		return $res;
	}
	
	public function getDiagnosticoMamografia($id = ''){
		$this->vism->borrar_cache();
		$this->vism->P0 = $id;
		
		$this->vism->execute("D GETCONSULTAMAMO^HCDIGITAL(P0,.P8,.P9)");
		
		$data = array(
			'diagnostico' => array(),
			'preguntas' => array(),
			'ban' => "0"
		);
		
		if ($this->vism->P8 != "" && $this->vism->P9 != "")
		{
			$data = array(
				'diagnostico' => explode("|",$this->vism->P8),
				'preguntas' => explode("|",$this->vism->P9),
				'ban' => "1"
			);
			
			$arr = explode("^",$data["preguntas"][5]);
			$arr[1] = $this->jul2fec($arr[1]);
			$data["preguntas"][5] = $arr[0]."^".$arr[1]."^".$arr[2]."^".$arr[3]."^".$arr[4];
			
			$arr = explode("^",$data["preguntas"][6]);
			$arr[1] = $this->jul2fec($arr[1]);
			$data["preguntas"][6] = $arr[0]."^".$arr[1]."^".$arr[2]."^".$arr[3];
			
			return $data;
		}
		else
		{
			return $data;
		}
		
	}
	
	public function grabarDiagnostico($tipo,$motivo,$diagnostico,$plan,$turno,$ingreso,$hc,$id,$objetivos){
		$this->vism->borrar_cache();
		
		$this->vism->P1 = $tipo."|".$motivo."|".$plan."|".$diagnostico."|".$turno;
		$this->vism->P1 .= "|".$ingreso."|".$this->session->userdata('id_servicio')."|".$objetivos;
		if ($id == ""){
			$this->vism->P0 = $hc;
			$this->vism->P2 = $this->session->userdata('matricula');
			$this->vism->execute("D INSCONSULTA^CSPHC001(P0,P1,P2,.P9)");
		}else{
			$this->vism->P0 = $id;
			$this->vism->execute("D UPDCONSULTA^CSPHC001(P0,P1,.P9)");
		}
		
		$ret["id"] = "";
		if($this->vism->P9 <> ""){
			if($this->vism->P9 <> "0"){
				$ret["id"] = $this->vism->P9;
			}else{
				$ret["errores"] = "Error al grabar en la base de datos.";
			}
		}else{
			$ret["errores"] = "Se produjo un error interno. " . $this->vism->error_name; ;
		}

		return $ret;
	}	

	public function grabarDiagnosticoMamo($diagnostico,$antecedentes,$preguntas,$ingreso,$hc,$id){
		
		$this->vism->borrar_cache();
		$this->vism->P0 = $ingreso . "^" . $hc . "^" . $this->session->userdata('matricula') . "^" . $this->session->userdata('id_servicio') . "^" . $id;
		$this->vism->P1 = $diagnostico . "|" . $antecedentes;
		$this->vism->P2 = $preguntas;

		$this->vism->execute("D INSCONSULTAMAMO^HCDIGITAL(P0,P1,P2,.P9)");
		
		return $this->vism->P9;
	}
	
	public function fec2jul($fecha){	
		#Recibe fecha en formato yyyymmdd y la pasa a juliano
		
		$this->vism->borrar_cache();
		$this->vism->P0 = $fecha;
		
		if($this->vism->P0 != ""){ 
			$this->vism->execute('S P9=$$JUL^%ZUDI(P0)');
		}
		
		return $this->vism->P9;
	}
	
	public function jul2fec($jul){	
		#Recibe fecha en formato juliano y la pasa a formato yyyymmdd
		
		$this->vism->borrar_cache();
		$this->vism->P0 = $jul;
		
		if($this->vism->P0 != ""){ 
			$this->vism->execute('S P9=$$FEC^%ZUDI(P0)');
		}
		
		return $this->vism->P9;
	}
	
	public function grabarProblema($fecha,$texto,$estado,$hc,$id) {
		$this->vism->borrar_cache();
		$this->vism->P0 = $hc;
		$this->vism->P1 = $fecha."^".$texto."^".$estado;
		$this->vism->P2 = $this->session->userdata('matricula');
		$this->vism->P3 = $id;
		$this->vism->execute("D INSPROBLEMA^HCDIGITAL(P0,P1,P2,P3,.P9)");
		
		$ret["id"] = "";
		if($this->vism->P9 <> ""){
			if($this->vism->P9 <> "0"){
				$ret["id"] = $this->vism->P9;
			}else{
				$ret["errores"] = "Error al grabar en la base de datos.";
			}
		}else{
			$ret["errores"] = "Se produjo un error interno.";
		}

		return $ret;
	}

	public function filtroPaciente($hc, $nombre, $dni){
		$this->vism->borrar_cache();
		$this->vism->P0 = $hc;
		$this->vism->P1 = $nombre;
		$this->vism->P2 = $dni;

		# MATRICULA, FECHA, ID_SERV, DATOS, CANT
		$this->vism->execute("D BUSCAPACWEB^WEBAGENDA(P0,P1,P2,.P8,.P9)");

		$agenda = explode("|", utf8_encode($this->vism->P8));
		$ret = array ();
		foreach($agenda as $paciente){
			$datosPac = explode("^", $paciente);
			array_push($ret, $datosPac);
		}

		return $ret;
	}
	
	public function llamarPac($comp, $mat, $box, $serv){
		$this->vism->borrar_cache();
		$this->vism->P0 = $comp;
		$this->vism->P1 = $mat;
		$this->vism->P2 = $box;
		$this->vism->P3 = $serv;

		$this->vism->execute("D LLAMAPAC^WEBAGENDA(P0,P1,P2,P3,.P9)");
		$ret["ban"] = utf8_encode($this->vism->P9);
		$ret["error"] = utf8_encode($this->vism->error_name);
		return $ret;
	}

	public function noLlamarPac($comp, $mat, $box, $serv){
		$this->vism->borrar_cache();
		$this->vism->P0 = $comp;
		$this->vism->P1 = $mat;
		$this->vism->P2 = $box;
		$this->vism->P3 = $serv;
	
		$this->vism->execute("D NOLLAMAPAC^WEBAGENDA(P0,P1,P2,P3,.P9)");
		return utf8_encode($this->vism->P9);	
	}

	public function getArchivosAnt($hc, $json = true)
	{
		$this->vism->borrar_cache();
		$this->vism->P0 = $hc;
		$this->vism->P1 = $this->session->userdata('matricula');

		$this->vism->execute("D GETARCHIVOSHC^HCDIGITALWEB(P0,P1,.P9)", "^HCDIGITALWEB", $this->session->userdata('matricula'));

		$data = [];
		if($this->vism->P9 == "0")
		{
			$data = $this->vism->global;
		}

		if($json)
		{
			return json_encode($data);
		}
		else
		{
			return $data;
		}
	}
}
