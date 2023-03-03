<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Turnos extends CI_Controller {

/**
* Nombre:  Agenda
* Version: 1.0
* Autor:  Facundo Ezequiel Albesa
* Created:  12.08.2016

* Descripcion:  Controlador para Turnos de pacientes del sistema de Historia Clinica Digital
*
* Changelog: 
*
* *  12.08.2016 : Startup
*
*/
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('Turnos_model','turnos');
		$this->load->library('form_validation');
		
		if ($this->session->userdata('login') == null) 
		{
			redirect('user/login');			
		}
	} 
	 
	 
	public function index()
	{
		
	}
	
	public function disponibilidad()
	{
		$data['disponibilidad'] = $this->turnos->getDisponibilidad();
		$data['servicio'] =  $this->session->userdata('id_servicio') . ' - ' .  $this->session->userdata('servicio');
		$this->load->view('turnos/disponibilidad',$data);		
	}
	
	
	public function getDisponibilidad()
	{
		$desde = $this->input->post('fecha-desde');
		$hasta = $this->input->post('fecha-hasta');
		
		$data['turnos'] = $this->turnos->getDisponibilidad($desde,$hasta);
		$data['servicio'] = $this->session->userdata('id_servicio') . ' - ' .  $this->session->userdata('servicio');
		echo json_encode($data);
		
	}	
		
	public function grabarTurno()
	{
		
		$fecha = $this->input->post('fecha');
		$hora = $this->input->post('hora');
		$reg = $this->input->post('reg');
		
		$datos = $this->turnos->grabarTurno($reg,$fecha,$hora);
	
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($datos));		
	
	}
		
	
	
	
}