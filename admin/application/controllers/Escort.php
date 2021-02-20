<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Escort extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->helper('url');
        $this->load->model('User_model', 'user');
        $this->load->model('Agency_model', 'agency');
        $this->load->model('Escort_model', 'escort');
        $this->load->model('Logo_model','logo');
        $this->load->model('Thumb_model','thumb');
        $this->load->model('Category_model','category');
        $this->load->model('Rate_model','rate');
        if(!$this->session->has_userdata('logged_in'))
            redirect('/');
    }
    public function index()
    {
        $data['role'] = $this->session->userdata('logged_in')['role'];
        $data['escorts'] = $this->escort->getAll();
        $this->load->view('template/header');
        $this->load->view('template/navbar', $data);
        $this->load->view('escort/index', $data);
        $this->load->view('template/footer');
    }
    public function add()
    {
        $data['role'] = $this->session->userdata('logged_in')['role'];
        $data['escorts'] = $this->escort->getAll();
        $data['agencies'] = $this->agency->getAll();
        $data['categories'] = $this->category->getAll();
        
        $this->load->view('template/header');
        $this->load->view('template/navbar', $data);
        $this->load->view('escort/add', $data);
        $this->load->view('template/footer');
    }
     public function edit()
    {
        $data['role'] = $this->session->userdata('logged_in')['role'];
        $data['escort_id'] = $this->input->get('id');
        if(isset($this->escort->getEscortById($data['escort_id'])[0]))
            $data['escort'] = $this->escort->getEscortById($data['escort_id'])[0];
        $data['agencies'] = $this->agency->getAll();
        $data['categories'] = $this->category->getAll();
        $email = $this->escort->getEscortById($data['escort_id'])[0]->email;
        $data['thumbnails'] = $this->thumb->getThumbnailsByEmail($email);
        $data['rates'] = $this->rate->getRatesByEmail($email);

        $this->load->view('template/header');
        $this->load->view('template/navbar', $data);
        $this->load->view('escort/edit', $data);
        $this->load->view('template/footer');
    }
    public function viewProfile(){
        $data['role'] = $this->session->userdata('logged_in')['role'];
        $email = $this->session->userdata('logged_in')['email'];
        $this->load->view('template/header');
        if(isset($this->escort->getEscortByEmail($email)[0])){
            $data['escort'] = $this->escort->getEscortByEmail($email)[0];
            $data['escort_id'] = $data['escort']->id;
        }
        $data['agencies'] = $this->agency->getAll();
        $data['categories'] = $this->category->getAll();
        $data['thumbnails'] = $this->thumb->getThumbnailsByEmail($email);
        $data['rates'] = $this->rate->getRatesByEmail($email);
        $this->load->view('template/navbar', $data);
        $this->load->view('escort/viewProfile', $data);
        $this->load->view('template/footer');
    }
    public function editProfile(){
        $data['role'] = $this->session->userdata('logged_in')['role'];
        $data['email'] = $this->input->get('email');
        if(isset($this->escort->getEscortByEmail($data['email'])[0])){
            $data['escort'] = $this->escort->getEscortByEmail($data['email'])[0];
            $data['escort_id'] = $this->escort->getEscortByEmail($data['email'])[0]->id;
        }
        $data['agencies'] = $this->agency->getAll();
        $data['categories'] = $this->category->getAll();
        $data['thumbnails'] = $this->thumb->getThumbnailsByEmail($data['email']);
        $data['rates'] = $this->rate->getRatesByEmail($data['email']);

        $this->load->view('template/header');
        $this->load->view('template/navbar', $data);
        $this->load->view('escort/editProfile', $data);
        $this->load->view('template/footer');
    }
    public function add_logo(){
        $email = $this->input->get("email");
        $target_dir = "uploads/escort/logo/";
        if(!is_dir($target_dir))
            mkdir($target_dir, 0755, true);
        if(isset($_FILES["file"])){
            $target_file = $target_dir.$_FILES["file"]["name"];
            move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
        }
        else $target_file = $target_dir."default_escort_logo.png";
        //echo $target_file;
        $escort_id = $this->escort->getEscortByEmail($email)[0]->id;
        $data = array(
            'email' => $email,
            'logo_path' => $target_file
        );
        $this->logo->save($data);
        echo 'success';
    }
    public function edit_logo(){
        $id = $this->input->get('id');
        $email = $this->input->get('email');
        $oldemail = $this->input->get('oldemail');
        $target_dir = "uploads/escort/logo/";
        if(!is_dir($target_dir))
            mkdir($target_dir, 0755, true);
        if(isset($_FILES["file"])){
            $target_file = $target_dir.$_FILES["file"]["name"];
            move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);    
        }
        else{
            $path = $this->logo->getLogoByEmail($email)[0]->logo_path;
            $target_file = $path;
        } 
        //echo $target_file;   
        $data = array(
            'email' => $email,
            'logo_path' => $target_file
        );
        $this->logo->update($oldemail, $data);
        echo 'success';
    }
    public function upload_thumbnails(){
        $target_dir = 'uploads/escort/thumbnails/';
        if(!is_dir($target_dir))
            mkdir($target_dir, 0755, true);
        $target_file = $target_dir .$_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $target_file);
    }                                                                                                         
    public function delete_thumbnail(){
        $name = $this->input->post('name');
        $directory = "uploads/escort/thumbnails/";
        $path = $directory.$name;
        if(file_exists($path)){
            unlink($path);
            echo "success";
        }
        else echo "fail";
    }
    public function addEscort(){
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('conpassword', 'Password Confirmation', 'required|matches[password]');
        if ($this->form_validation->run() == FALSE)
           $res = array('state' => false, 'msg' => "Please check escort password again!!!");
        else{
            $name = $this->input->post('name');
            $agency_id = $this->input->post('agency');
            $email = $this->input->post('email');
            $url = $this->input->post('url');
            $age = $this->input->post('age');
            $statistics = $this->input->post('statistics');
            $height = $this->input->post('height');
            $weight = $this->input->post('weight');
            $hair = $this->input->post('hair');
            $eyes = $this->input->post('eyes');
            $nationality = $this->input->post('nationality');
            $language = $this->input->post('language');
            $incall_location = $this->input->post('incall_location');
            $outcall_location = $this->input->post('outcall_location');
            $password = $this->input->post('password');
            $masseuse = $this->input->post('masseuse');
            $active = $this->input->post('active');
            $bio = $this->input->post('bio');
            $full_bio = $this->input->post('full_bio');
            $category = $this->input->post('category');
            $thumbnails = $this->input->post('thumbnails');
            $thumbnails = json_decode($thumbnails);
            $duration = $this->input->post('duration');
            $rate = $this->input->post('rate');
            foreach ($duration as $due) {
                # code...
                $i = array_search($due, $duration);
                //echo $rate[$i];
                $rat = array(
                    "email"=> $email,
                    "duration"=> $due,
                    "rate"=> $rate[$i]
                );
                $this->rate->save($rat);
            }
            $newEscort = array(
                "name" => $name,
                "agency_id" => $agency_id,
                "email" => $email,
                "url" => $url,
                "age" => $age,
                "statistics" => $statistics,
                "height" => $height,
                "weight" => $weight,
                "hair"=>$hair,
                "eyes"=>$eyes,
                "nationality" => $nationality,
                "language" => $language,
                "incall_location" =>$incall_location,
                "outcall_location" =>$outcall_location,
                "password" => md5($password),
                "active" => $active,
                "masseuse" => $masseuse,
                "category"=>json_encode($category),
                "bio"=>$bio,
                "full_bio"=>$full_bio
            );
            if(isset($thumbnails)){
                foreach ($thumbnails as $thumb) {
                    $newThumb = array(
                        "email"=> $email,
                        "path"=>"uploads/escort/thumbnails/".$thumb,
                        'created_date'=>date('Y-m-d H:i:s'),
                    );
                    $this->thumb->save($newThumb);
                }
            }
            $newEscortId = $this->escort->save($newEscort);
            //echo $newAgencyId;
            if($newEscortId){
                $newUser = array(
                    "email"=>$email,
                    "password"=>md5($password),
                    "role"=>"2"
                );
                if($this->user->save($newUser))
                    $res = array('state' => true, 'msg' => "Successfully saved!");
            }
            else $res = array('state' => false, 'msg' => "Can't save this agency. Try again!");
        }
        return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($res));
    }
    public function editEscort(){

        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('conpassword', 'Password Confirmation', 'required|matches[password]');
        if ($this->form_validation->run() == FALSE)
           $res = array('state' => false, 'msg' => "Please check escort info again!!!");
        else{
            $id = $this->input->post('id');
            $name = $this->input->post('name');
            $agency_id = $this->input->post('agency');
            $email = $this->input->post('email');
            $url = $this->input->post('url');
            $age = $this->input->post('age');
            $statistics = $this->input->post('statistics');
            $height = $this->input->post('height');
            $weight = $this->input->post('weight');
            $hair = $this->input->post('hair');
            $eyes = $this->input->post('eyes');
            $nationality = $this->input->post('nationality');
            $language = $this->input->post('language');
            $incall_location = $this->input->post('incall_location');
            $outcall_location = $this->input->post('outcall_location');
            $password = $this->input->post('password');
            $masseuse = $this->input->post('masseuse');
            $active = $this->input->post('active');
            $bio = $this->input->post('bio');
            $full_bio = $this->input->post('full_bio');
            $category = $this->input->post('category');
            $thumbnails = $this->input->post('thumbnails');
            $thumbnails = json_decode($thumbnails);
            $duration = $this->input->post('duration');
            $rate = $this->input->post('rate');
            $this->rate->delete($email);
            foreach ($duration as $due) {
                # code...
                $i = array_search($due, $duration);
                //echo $rate[$i];
                $rat = array(
                    "email"=> $email,
                    "duration"=> $due,
                    "rate"=> $rate[$i]
                );
                $this->rate->save($rat);
            }
            $thisEscort = array(
                "name" => $name,
                "agency_id" => $agency_id,
                "email" => $email,
                "url" => $url,
                "age" => $age,
                "statistics" => $statistics,
                "height" => $height,
                "weight" => $weight,
                "hair"=>$hair,
                "eyes"=>$eyes,
                "nationality" => $nationality,
                "language" => $language,
                "incall_location" =>$incall_location,
                "outcall_location" =>$outcall_location,
                "password" => md5($password),
                "active" => $active,
                "masseuse" => $masseuse,
                "category"=>json_encode($category),
                "bio"=>$bio,
                "full_bio"=>$full_bio
            );
            if(isset($thumbnails)){
                $this->thumb->delete($email);
                foreach ($thumbnails as $thumb) {
                    $newThumb = array(
                        "email"=> $email,
                        "path"=>"uploads/escort/thumbnails/".$thumb,
                        'created_date'=>date('Y-m-d H:i:s'),
                    );
                    $this->thumb->save($newThumb);
                }
            }
            $this->escort->update($id, $thisEscort);
            $thisUser = array(
                "email"=>$email,
                "password"=>md5($password),
                "role"=>"2"
            );
            $this->user->update($id, $thisUser);
            $res = array('state' => true, 'msg' => "Successfully Updated!");
        }
        return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($res));
    }
    public function getEscortById($escort_id){
        $select = $this->escort->getEscortById($escort_id)[0];
        return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($select));
    }
    public function delete(){
       /* var_dump($this->escort->getEscortById($escort_id)[0]);
        exit;*/
        $escort_id = $this->input->get('id');
        if(isset($this->escort->getEscortById($escort_id)[0])){
            $select = $this->escort->getEscortById($escort_id)[0];
            $email = $select->email;
            $this->user->delete($email);
            $this->logo->delete($email);
            $this->thumb->delete($email);
            $this->escort->delete($escort_id);
            $this->index();
        }
    }
}
