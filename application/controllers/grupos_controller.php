<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Grupos_controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if($this->session->userdata('logged')==0){
            redirect(base_url().'index.php/login','refresh');
            $this->load->model('grupos_model');
        }
    }
    public function index() {    
        $data['agentes'] = $this->grupo_model->listarAgentes();
        $data['grupos'] = $this->grupo_model->listarGrupos();
        $this->load->view('header/header');
        $this->load->view('pages/menu');
        $this->load->view('pages/grupos/grupos', $data);
        $this->load->view('footer/footer');
        $this->load->view('jsview/js_grupos');
    }
    public function gestionandoGrupo() {
        $idGrupo = $this->input->post('idGrupoBD2');
        $nombreGrupo = $this->input->post('nombreGrupoBD');
        $agenteResponsable = $this->input->post('agenteBD');
        $estado = $this->input->post('grupoEstadoBD');

        $this->grupo_model->actualizarInfoGrupo($idGrupo, $nombreGrupo, $agenteResponsable, $estado);
        redirect('grupos','refresh');
    }
    public function buscandoGrupo($idGrupo) {
        $this->grupo_model->editarGrupo($idGrupo);
    }

    public function listarVendedoresAct($idGrupo) {
        $this->grupo_model->listandoVendedoresAct($idGrupo);
    }

    public function listarVendedoresAgregados($idGrupo) {
        $this->grupo_model->listandoVendedoresAgregados($idGrupo);
    }

    public function agregandoInfoGrupo() {
        $this->grupo_model->guardarInfoGrupo($this->input->post('grupo'));
    }

    public function guardarNuevoGrupo() {
        $idGrupo = $this->input->post('idGrupo');
        $nombreGrupo = $this->input->post('nombreGrupo');
        $agenteResponsable = $this->input->post('agente');
        $estado = $this->input->post('grupoEstado');

        $this->grupo_model->guardarGrupos($idGrupo,$nombreGrupo,$agenteResponsable,$estado);
        redirect('grupos', 'refresh');
    }

}
?>