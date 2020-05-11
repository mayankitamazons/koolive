<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Broadcast extends CI_Controller {

	function __construct() {
        parent::__construct();

        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Agency_model', 'agency');
        $this->load->model('Escort_model', 'escort');
        $this->load->model('Broadcast_model', 'broadcast');
        if(!$this->session->has_userdata('logged_in'))
            redirect('/');
    }
	public function index()
	{
        $data['role'] = $this->session->userdata('logged_in')['role'];
        $data['broadcasts'] = $this->broadcast->getAll();
        $this->load->view('template/header');
        $this->load->view('template/navbar', $data);
		$this->load->view('broadcast/index', $data);
        $this->load->view('template/footer');
	}
    public function add(){
        $data['role'] = $this->session->userdata('logged_in')['role'];
        $data['agencies'] = $this->agency->getAll();
        $data['escorts'] = $this->escort->getAll();
        $this->load->view('template/header');
        $this->load->view('template/navbar', $data);
        $this->load->view('broadcast/add', $data);
        $this->load->view('template/footer');
    }
    public function edit(){
        $data['role'] = $this->session->userdata('logged_in')['role'];
        $data['broadcast_id'] = $this->input->get('broadcast_id');
        if(isset($this->broadcast->getBroadcastById($data['broadcast_id'])[0]))
            $data['broadcast'] = $this->broadcast->getBroadcastById($data['broadcast_id'])[0];
        $data['agencies'] = $this->agency->getAll();
        $data['escorts'] = $this->escort->getAll();
        $this->load->view('template/header');
        $this->load->view('template/navbar', $data);
        $this->load->view('broadcast/edit', $data);
        $this->load->view('template/footer');
    }
    public function addBroadcast(){
        $title = $this->input->post('title');
        $agency_id = $this->input->post('agency_id');
        $escort_id = $this->input->post('escort_id');
        $text = $this->input->post('text');
        $newBroadcast = array(
            'title'=>$title,
            'agency_id'=>$agency_id,
            'escort_id'=>$escort_id,
            'text'=>$text,
            'created_date'=>date('Y-m-d H:i:s'),
        );
        $newBroadcastId = $this->broadcast->save($newBroadcast);
        //echo $newAgencyId;
        if($newBroadcastId)
            $res = array('state' => true, 'msg' => "Successfully saved!");
        else 
            $res = array('state' => false, 'msg' => "Can't save this broadcast. Try again!");
        return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($res));
    }
    public function editBroadcast(){
        $id = $this->input->post('id');
        $title = $this->input->post('title');
        $agency_id = $this->input->post('agency_id');
        $escort_id = $this->input->post('escort_id');
        $text = $this->input->post('text');
        $thisBroadcast = array(
            'title'=>$title,
            'agency_id'=>$agency_id,
            'escort_id'=>$escort_id,
            'text'=>$text,
            'created_date'=>date('Y-m-d H:i:s'),
        );
        $this->broadcast->update($id, $thisBroadcast);
        $res = array('state' => true, 'msg' => "Successfully Updated!");
        return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($res));
    }
    public function getBroadcastById($broadcast_id){
        $select = $this->broadcast->getBroadcastById($broadcast_id)[0];
        return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($select));
    }
    public function delete(){
        $broadcast_id = $this->input->get('broadcast_id');
        $this->broadcast->delete($broadcast_id);
        $this->index();
    }
}
