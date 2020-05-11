<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Comments extends CI_Controller {

	function __construct() {
        parent::__construct();

        $this->load->helper('url');
        $this->load->model('User_model');
        $this->load->library('session');
        $this->load->model('Comment_model','comment');
    }
	public function index()
	{
		//redirect(base_url('auth/login'));
        $data['comments'] = $this->comment->getAll();
	    $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('comments/index', $data);
        $this->load->view('template/footer');
    }
    public function delete(){
        $id = $this->input->post('id');
        $this->comment->delete($id);
        return "success";
    }
}
