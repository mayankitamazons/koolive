<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	function __construct() {
        parent::__construct();

        $this->load->helper('url');
        $this->load->model('User_model', 'user');
        $this->load->model('Agency_model', 'agency');
        $this->load->model('Escort_model', 'escort');
        $this->load->model('Logo_model','logo');
        $this->load->model('Banner_model','banner');
        $this->load->model('Category_model','category');
        $this->load->model('Thumb_model','thumb');
        $this->load->model('Rate_model','rate');
        if(!$this->session->has_userdata('logged_in'))
            redirect('/');
    }
	public function index()
	{
        $data['role'] = $this->session->userdata('logged_in')['role'];
        $this->load->view('template/header');
        if($data['role']==0){
            $data['agencies'] = $this->agency->getAll();
            $this->load->view('template/navbar', $data);
            $this->load->view('dashboard/index', $data);
        }else if($data['role']==1){
            $email = $this->session->userdata('logged_in')['email'];
            $agency_id = $this->agency->getAgencyByEmail($email)[0]->id;
            $data['escorts'] = $this->escort->getAllByAgency($agency_id);
            $this->load->view('template/navbar', $data);
            $this->load->view('escort/index', $data);
        }else{
            $email = $this->session->userdata('logged_in')['email'];
            if(isset($this->escort->getEscortByEmail($email)[0])){
                $data['escort'] = $this->escort->getEscortByEmail($email)[0];
                $data['escort_id'] = $data['escort']->id;
                $data['agencies'] = $this->agency->getAll();
                $data['categories'] = $this->category->getAll();
                $email = $this->escort->getEscortById($data['escort_id'])[0]->email;
                $data['thumbnails'] = $this->thumb->getThumbnailsByEmail($email);
                $data['rates'] = $this->rate->getRatesByEmail($email);
            }
            $this->load->view('template/navbar', $data);
            $this->load->view('escort/viewProfile', $data);
        }
        $this->load->view('template/footer');
	}
    public function add(){
        $data['role'] = $this->session->userdata('logged_in')['role'];
        
        $this->load->view('template/header');
        $this->load->view('template/navbar', $data);
        $this->load->view('dashboard/add', $data);
        $this->load->view('template/footer');
    }
    public function edit(){
        $data['role'] = $this->session->userdata('logged_in')['role'];
        $data['agency_id'] = $this->input->get('agency_id');
        if(isset($this->agency->getAgencyById($data['agency_id'])[0]))
            $data['agency'] = $this->agency->getAgencyById($data['agency_id'])[0];
        $this->load->view('template/header');
        $this->load->view('template/navbar', $data);
        $this->load->view('dashboard/edit', $data);
        $this->load->view('template/footer');
    }
    public function viewProfile(){
        $data['role'] = $this->session->userdata('logged_in')['role'];
        $email = $this->session->userdata('logged_in')['email'];
        if(isset($this->agency->getAgencyByEmail($email)[0]))
            $data['agency'] = $this->agency->getAgencyByEmail($email)[0];
        $data['agency_id'] = $data['agency']->id;
        $this->load->view('template/header');
        $this->load->view('template/navbar', $data);
        $this->load->view('dashboard/viewProfile', $data);
        $this->load->view('template/footer');
    }
    public function editProfile(){
        $data['role'] = $this->session->userdata('logged_in')['role'];
        $email = $this->input->get('email');
        if(isset($this->agency->getAgencyByEmail($email)[0]))
            $data['agency'] = $this->agency->getAgencyByEmail($email)[0];
        $data['agency_id'] = $data['agency']->id;
        $this->load->view('template/header');
        $this->load->view('template/navbar', $data);
        $this->load->view('dashboard/editProfile', $data);
        $this->load->view('template/footer');
    }
    public function add_logo(){
        $email = $this->input->get('email');
        $target_dir = "uploads/agency/logo/";
        if(!is_dir($target_dir))
            mkdir($target_dir, 0755, true);
        if(isset($_FILES["file"])){
            $target_file = $target_dir.$_FILES["file"]["name"];
            move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
        }
        else $target_file = $target_dir."default_agency_logo.png";
        //echo $target_file;
        $agency_id = $this->agency->getAgencyByEmail($email)[0]->id;
        $data = array(
            'email' => $email,
            'logo_path' => $target_file
        );
        $this->logo->save($data);
        echo 'success';
    }
    public function add_banner(){
        $email = $this->input->get('email');
        $target_dir = "uploads/agency/banner/";
        if(!is_dir($target_dir))
            mkdir($target_dir, 0755, true);
        if(isset($_FILES["file"])){
            $target_file = $target_dir.$_FILES["file"]["name"];
            move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
        }
        else $target_file = $target_dir."default_agency_banner.png";
        //echo $target_file;
        $data = array(
            'email' => $email,
            'banner_path' => $target_file
        );
        $this->banner->save($data);
        echo 'success';
    }
    public function edit_logo(){
        $id = $this->input->get('id');
        $email = $this->input->get('email');
        $oldemail = $this->input->get('oldemail');
        $target_dir = "uploads/agency/logo/";
        if(!is_dir($target_dir))
            mkdir($target_dir, 0755, true);
        if(isset($_FILES["file"])){
            $target_file = $target_dir.$_FILES["file"]["name"];
            move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);    
        }
        else{
            $path = $this->logo->getLogoByEmail($oldemail)[0]->logo_path;
            $target_file = $path;
        }
        //echo $target_file;   
        $role = $this->user->getUserByEmail($email)[0]->role;
        $data = array(
            'email' => $email,
            'logo_path' => $target_file
        );
        $this->logo->update($oldemail, $data);
        echo 'success';
    }
    public function edit_banner(){
        $id = $this->input->get('id');
        $email = $this->input->get('email');
        $oldemail = $this->input->get('oldemail');
        $target_dir = "uploads/agency/banner/";
        if(!is_dir($target_dir))
            mkdir($target_dir, 0755, true);
        if(isset($_FILES["file"])){
            $target_file = $target_dir.$_FILES["file"]["name"];
            move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);    
        }
        else{
            $path = $this->banner->getBannerByEmail($oldemail)[0]->banner_path;
            $target_file = $path;
        }
        //echo $target_file;   
        $data = array(
            'email' => $email,
            'banner_path' => $target_file
        );
        $this->banner->update($oldemail, $data);
        echo 'success';
    }
    public function addAgency(){
        $agency_name = $this->input->post('name');
        $agency_website = $this->input->post('website');
        $agency_email = $this->input->post('email');
        $agency_mobile = $this->input->post('mobile');
        $agency_info = $this->input->post('info');
        $agency_password = $this->input->post('password');
        $newAgency = array(
            'name'=>$agency_name,
            'website'=>$agency_website,
            'email'=>$agency_email,
            'mobile'=>$agency_website,
            'info'=>$agency_info,
            'password'=>md5($agency_password)
        );
        $newAgencyId = $this->agency->save($newAgency);
        //echo $newAgencyId;
        if($newAgencyId){
            $newUser = array(
                "email"=>$this->input->post('email'),
                "password"=>md5($this->input->post('password')),
                "role"=>"1"
            );
            if($this->user->save($newUser))
                $res = array('state' => true, 'msg' => "Successfully saved!");
        }
        else $res = array('state' => false, 'msg' => "Can't save this agency. Try again!");
        return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($res));
    }
    public function editAgency(){
        $agency_id = $this->input->post('id');
        $agency_name = $this->input->post('name');
        $agency_website = $this->input->post('website');
        $agency_email = $this->input->post('email');
        $agency_mobile = $this->input->post('mobile');
        $agency_info = $this->input->post('info');
        $agency_password = $this->input->post('password');
        $thisAgency = array(
            'name'=>$agency_name,
            'website'=>$agency_website,
            'email'=>$agency_email,
            'mobile'=>$agency_website,
            'info'=>$agency_info,
            'password'=>md5($agency_password)
        );
        $this->agency->update($agency_id, $thisAgency);
        $thisUser = array(
            "email"=>$this->input->post('email'),
            "password"=>md5($this->input->post('password')),
            "role"=>"1"
        );
        $this->user->update($agency_id, $thisUser);
        $res = array('state' => true, 'msg' => "Successfully Updated!");
        return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($res));
    }
    public function getAgencyById($agency_id){

        $select = $this->agency->getAgencyById($agency_id)[0];
        return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($select));
    }
    public function delete(){
        $agency_id = $this->input->get('agency_id');
        if(isset($this->agency->getAgencyById($agency_id)[0])){
            $select = $this->agency->getAgencyById($agency_id)[0];
            $email = $select->email;
            $this->user->delete($email);
            $this->logo->delete($email);
            $this->banner->delete($email);
            $this->agency->delete($email);
            $this->index();
        }
        //echo $agency_id;
    }
}
