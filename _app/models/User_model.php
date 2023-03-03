<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
- 10/04/2018: ACTUALIZO FUNCIONES A WS UNIVERSAL. (CUNDA)
*/

class User_model extends CI_Model {
	public function __construct() {
		
		parent::__construct();

		$config = array(
			'mserver' => MSERVER,
			'namespace' => MNAMESPACE, 
			'http_server' => IP_WS,
			'port' => RandPortWS()
		);
		
		$this->load->library('VisM',$config);
		//$this->load->database();
	}

	public function userLogin($username, $password) {
		$this->vism->borrar_cache();
		$this->vism->P0 = $username;
		$this->vism->P1 = $password;

		# USU, PASS, RET, SER, CAN
		# RET=2 OK, RET=1 ERROR
		$this->vism->execute("D LOGIN^WEBAGENDA(P0,P1,.P7,.P8,.P9)");

		$res["estado"] = $this->vism->P7;
		$cantidad = intval($this->vism->P9);
		$arr = array();

		if($res["estado"] == "2" || $res["estado"] == "3"){
			$servicios = explode("|", $this->vism->P8);
			
			for($i=0; $i<$cantidad; $i++){
				$registro = explode("^", $servicios[$i]);

				$datos["id"] = $registro[0];
				$datos["sector"] = $registro[1];
				$datos["username"] = $registro[2];

				array_push($arr, $datos);
			}
		}

		$res["datos"] = $arr;
		return $res;

	}
	
	public function GetServiciosMatricula($matricula) {
		$this->vism->borrar_cache();
		$this->vism->P0 = $matricula;

		# USU, SERVICIOS
		$this->vism->execute("D SERVXMAT^WEBAGENDA(P0,.P9)");

		$arr = array();
		if($this->vism->P9 != "")
		{
			$servicios = explode("|", $this->vism->P9);
		
			foreach($servicios as $serv){
				$data = explode("^", $serv);

				$datos["id"] = $data[0];
				$datos["sector"] = $data[1];

				array_push($arr, $datos);
			}
		}
		
		return $arr;
	}
	
	public function GetConsultorios() {
		$this->vism->borrar_cache();
		# DATOS, CANT
		$this->vism->execute("D GETOFICINAS^HCDIGITAL(.P8,.P9)");

		$arr = array();
		$consultorios = explode("|", $this->vism->P8);
		
		foreach($consultorios as $consul){
			$data = explode("^", $consul);

			$datos["id"] = $data[0];
			$datos["oficina"] = $data[1];

			array_push($arr, $datos);
		}

		return $arr;
	}
	
	public function GetBoxes($id) {
		$this->vism->borrar_cache();
		$this->vism->P0 = $id;
		
		# ID, DATOS, CANT
		$this->vism->execute("D GETBOXES^HCDIGITAL(P0,.P8,.P9)");

		$boxes = explode("|", $this->vism->P8);
		$temp = "";
		foreach($boxes as $box){
			$data = explode("^", $box);
			$temp .= '<option value="'.$data[1].'">'.$data[1].'</option>';
		}

		return $temp;
	}

	public function cambioPassword($user, $password) {
		$this->vism->borrar_cache();
		$this->vism->P0 = $user;
		$this->vism->P1 = $password;
		
		// SEC, DATOS, CANTIDAD
		$this->vism->execute("D CAMBIOPASS^HCDIGITAL(P0,P1,.P9)");

		return $this->vism->P9;	
	}
	
	
}
