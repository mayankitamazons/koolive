<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Girl extends CI_Controller {

	function __construct() {
        parent::__construct();

        $this->load->helper('url');
        $this->load->model('User_model', 'user');
        $this->load->model('Girl_model', 'girl');
        $this->load->model('File_model','file');
        if(!$this->session->has_userdata('logged_in'))
            redirect('/');
    }
	public function index()
	{
        $data['role'] = $this->session->userdata('logged_in')['role'];
        $data['girls'] = $this->girl->getAll();
        /*var_dump($data['girls']);
        exit;*/
        //$data['agencies'] = $this->
        $this->load->view('template/header');
        $this->load->view('template/navbar', $data);
		$this->load->view('girl/index', $data);
        $this->load->view('template/footer');
	}
    public function add()
    {
        $data['role'] = $this->session->userdata('logged_in')['role'];
        $data['girls'] = $this->girl->getAll();
        $this->load->view('template/header');
        $this->load->view('template/navbar', $data);
        $this->load->view('girl/add', $data);
        $this->load->view('template/footer');
    }
    public function add_logo($username){
        /*if(isset($_FILES["file"]))
        {*/
            //echo $_FILES['file']['tmp_name'];
            $target_dir = "uploads/";
            if(!is_dir($target_dir))
                mkdir($target_dir, 0755, true);
            if(isset($_FILES["file"])){
                $target_file = $target_dir.uniqid().$_FILES["file"]["name"];
                move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
            }
            else $target_file = $target_dir."default_girl_logo.png";
            //echo $target_file;
            $girl_id = $this->girl->getGirlByUsername($username)[0]->id;
            $data = array(
                'girl_id' => $girl_id,
                'username' => $username,
                'logo_path' => $target_file
            );
            $this->file->save($data);
            echo 'success';
            //move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . $_FILES['file']['name']);
        /*}
        else echo 'fail';*/
    }
    public function edit_logo(/*$agencydata*/){
        $id = $this->input->get('id');
        $username = $this->input->get('username');
        /*echo $id;
        echo $username;*/
        $target_dir = "uploads/";
        if(!is_dir($target_dir))
            mkdir($target_dir, 0755, true);
        if(isset($_FILES["file"])){
            $target_file = $target_dir.uniqid().$_FILES["file"]["name"];
            move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);    
        }
        else $target_file = $target_dir."default_girl_logo.png";
        $role = $this->user->getUserByUsername($username)[0]->role;
        $data = array(
            'username' => $username,
            'logo_path' => $target_file,
        );
        $this->file->update($id, $role, $data);
        echo 'success';
    }
    public function addGirl(){

        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('girl_pwd', 'Password', 'required');
        $this->form_validation->set_rules('girl_conpwd', 'Password Confirmation', 'required|matches[girl_pwd]');
        if ($this->form_validation->run() == FALSE)
           $res = array('state' => false, 'msg' => "Please check your password again!!!");
        else{
            $girl_name = $this->input->post('girl_name');
            $girl_email = $this->input->post('girl_email');
            $girl_mobile = $this->input->post('girl_mobile');
            $girl_birthday = $this->input->post('girl_birthday');
            $girl_address = $this->input->post('girl_address');
            $girl_pwd = $this->input->post('girl_pwd');
            $girl_status = $this->input->post('girl_status');
            $newGirl = array(
                "girl_name" => $girl_name,
                "girl_email" => $girl_email,
                "girl_mobile" => $girl_mobile,
                "girl_birthday" => $girl_birthday,
                "girl_address" => $girl_address,
                "girl_pwd" => md5($girl_pwd),
                "girl_status" => $girl_status
            );
            $newGirlId = $this->girl->save($newGirl);
            //echo $newgirlId;
            if($newGirlId){
                $newUser = array(
                    "girl_id"=>$newGirlId,
                    "username"=>$girl_name,
                    "email"=>$girl_email,
                    "password"=>md5($girl_pwd),
                    "status"=>$girl_status,
                    "role"=>"3"
                );
                if($this->user->save($newUser))
                    $res = array('state' => true, 'msg' => "Successfully saved!");
            }
            else $res = array('state' => false, 'msg' => "Can't save this girl. Try again!");
        }
        return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($res));
    }
    public function editGirl($girl_id){
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('girl_pwd', 'Password', 'required');
        $this->form_validation->set_rules('girl_conpwd', 'Password Confirmation', 'required|matches[girl_pwd]');
        if ($this->form_validation->run() == FALSE)
           $res = array('state' => false, 'msg' => "Please check your password again!!!");
        else{
            $girl_name = $this->input->post('girl_name');
            $girl_email = $this->input->post('girl_email');
            $girl_mobile = $this->input->post('girl_mobile');
            $girl_birthday = $this->input->post('girl_birthday');
            $girl_address = $this->input->post('girl_address');
            $girl_pwd = $this->input->post('girl_pwd');
            $girl_status = $this->input->post('girl_status');
            $thisGirl = array(
                "girl_name" => $girl_name,
                "girl_email" => $girl_email,
                "girl_mobile" => $girl_mobile,
                "girl_birthday" => $girl_birthday,
                "girl_address" => $girl_address,
                "girl_pwd" => md5($girl_pwd),
                "girl_status" => $girl_status
            );
            $this->girl->update($girl_id, $thisGirl);
            $thisUser = array(
                "username"=>$girl_name,
                "email"=>$girl_email,
                "password"=>md5($girl_pwd),
                "status"=>$girl_status,
                "role"=>"3"
            );
            $this->user->update($girl_id, $thisUser);
            $res = array('state' => true, 'msg' => "Successfully Updated!");
        }
        return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($res));
    }
    public function getGirlById($girl_id){
        $select = $this->girl->getGirlById($girl_id)[0];
        return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($select));
    }
    public function deleteGirl($girl_id){
        $select = $this->girl->getGirlById($girl_id)[0];
        $username = $select->girl_name;
        $this->user->delete($username);
        $this->file->delete($username);
        $this->girl->delete($girl_id);
        echo "success";
    }
}
 