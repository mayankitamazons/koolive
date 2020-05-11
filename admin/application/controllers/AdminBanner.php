<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class AdminBanner extends CI_Controller {

	function __construct() {
        parent::__construct();

        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('AdminBanner_model', 'banner');
        if(!$this->session->has_userdata('logged_in'))
            redirect('/');
    }
	public function index()
	{
        $data['role'] = $this->session->userdata('logged_in')['role'];
        $data['banners'] = $this->banner->getAll();
        $this->load->view('template/header');
        $this->load->view('template/navbar', $data);
		$this->load->view('adminbanner/index', $data);
        $this->load->view('template/footer');
	}
    public function add(){
        $data['role'] = $this->session->userdata('logged_in')['role'];
        
        $this->load->view('template/header');
        $this->load->view('template/navbar', $data);
        $this->load->view('adminbanner/add', $data);
        $this->load->view('template/footer');
    }
    public function edit(){
        $data['role'] = $this->session->userdata('logged_in')['role'];
        $data['banner_id'] = $this->input->get('banner_id');
        if(isset($this->banner->getBannerById($data['banner_id'])[0]))
            $data['banner'] = $this->banner->getBannerById($data['banner_id'])[0];
        $this->load->view('template/header');
        $this->load->view('template/navbar', $data);
        $this->load->view('adminbanner/edit', $data);
        $this->load->view('template/footer');
    }
    public function upload(){
        $banner_id = $this->input->get('id');
        $target_dir = "uploads/banner/";
        if(!is_dir($target_dir))
            mkdir($target_dir, 0755, true);
        if(isset($_FILES["file"])){
            $target_file = $target_dir.$_FILES["file"]["name"];
            move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
        }
        else{
            if($banner_id) $target_file = $this->banner->getBannerById($banner_id)[0]->banner_image;
            else $target_file = $target_dir."default_banner_image.png";
        }
        echo $target_file;
    }
    public function addBanner(){
        $title = $this->input->post('title');
        $position = $this->input->post('position');
        $text = $this->input->post('text');
        $path = $this->input->post('path');
        $active = $this->input->post('active');
        $newBanner = array(
            'title'=>$title,
            'position'=>$position,
            'text'=>$text,
            'image_path'=>$path,
            'active'=>$active,
            'created_date'=>date('Y-m-d H:i:s'),
        );
        $newBannerId = $this->banner->save($newBanner);
        //echo $newAgencyId;
        if($newBannerId)
            $res = array('state' => true, 'msg' => "Successfully saved!");
        else 
            $res = array('state' => false, 'msg' => "Can't save this banner. Try again!");
        return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($res));
    }
    public function editBanner(){
        $id = $this->input->post('id');
        $title = $this->input->post('title');
        $position = $this->input->post('position');
        $text = $this->input->post('text');
        $path = $this->input->post('path');
        $active = $this->input->post('active');
        $thisBanner = array(
            'title'=>$title,
            'position'=>$position,
            'text'=>$text,
            'image_path'=>$path,
            'active'=>$active,
            'created_date'=>date('Y-m-d H:i:s'),
        );
        $this->banner->update($id, $thisBanner);
        $res = array('state' => true, 'msg' => "Successfully Updated!");
        return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($res));
    }
    public function getBannerById($banner_id){
        $select = $this->banner->getBannerById($banner_id)[0];
        return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($select));
    }
    public function delete(){
        $banner_id = $this->input->get('banner_id');
        $this->banner->delete($banner_id);
        $this->index();
    }
}
