<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {

	function __construct() {
        parent::__construct();

        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Category_model', 'category');
        if(!$this->session->has_userdata('logged_in'))
            redirect('/');
    }
	public function index()
	{
        $data['role'] = $this->session->userdata('logged_in')['role'];
        $data['categories'] = $this->category->getAll();
        $this->load->view('template/header');
        $this->load->view('template/navbar', $data);
		$this->load->view('category/index', $data);
        $this->load->view('template/footer');
	}
    public function add(){
        $data['role'] = $this->session->userdata('logged_in')['role'];
        
        $this->load->view('template/header');
        $this->load->view('template/navbar', $data);
        $this->load->view('category/add', $data);
        $this->load->view('template/footer');
    }
    public function edit(){
        $data['role'] = $this->session->userdata('logged_in')['role'];
        $data['cat_id'] = $this->input->get('cat_id');
        $this->load->view('template/header');
        $this->load->view('template/navbar', $data);
        $this->load->view('category/edit', $data);
        $this->load->view('template/footer');
    }
    public function addCategory(){
        $category = $this->input->post('category');
        $url = $this->input->post('url');
        $newCategory = array(
            'category'=>$category,
            'url'=>$url,
            'created_date'=>date('Y-m-d H:i:s'),
        );
        /*var_dump($newCategory);
        exit;*/
        $newCatId = $this->category->save($newCategory);
        //echo $newAgencyId;
        if($newCatId)
            $res = array('state' => true, 'msg' => "Successfully saved!");
        else 
            $res = array('state' => false, 'msg' => "Can't save this category. Try again!");
        return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($res));
    }
    public function editCategory(){
        $id = $this->input->post('id');
        $category = $this->input->post('category');
        $url = $this->input->post('url');
        $thisCategory = array(
            'category'=>$category,
            'url'=>$url,
            'created_date'=>date('Y-m-d H:i:s'),
        );
        $this->category->update($id, $thisCategory);
        $res = array('state' => true, 'msg' => "Successfully Updated!");
        return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($res));
    }
    public function getCategoryById($cat_id){
        $select = $this->category->getCategoryById($cat_id)[0];
        return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($select));
    }
    public function delete(){
        $cat_id = $this->input->get('cat_id');
        $this->category->delete($cat_id);
        $this->index();
    }
}
