<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	function __construct() {
        parent::__construct();

        $this->load->helper('url');
        $this->load->model('User_model');
        $this->load->library('session');
    }
	public function index()
	{
		redirect(base_url('auth/login'));
	}
	public function login(){

        $this->load->view('auth/login');
	}
    public function login_check(){
        $data = $this->input->post();
        $email = $data['email'];
        $pwd = $data['password'];
        if($this->User_model->login_check($email, $pwd)){
            echo "success";
        }
        else echo "fail";
    }
    public function logout(){
        $this->session->unset_userdata('logged_in');
        //$this->session->sess_destroy();
        redirect(base_url('auth/login'));
    }
    public function password_check($str) {
        if (preg_match('#[0-9]#', $str) && preg_match('#[a-zA-Z]#', $str)) {
            return true;
        }
        return false;
    }
}
