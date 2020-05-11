<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends CI_Controller {

	function __construct() {
        parent::__construct();

        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Blog_model', 'blog');
        if(!$this->session->has_userdata('logged_in'))
            redirect('/');
    }
	public function index()
	{
        $data['role'] = $this->session->userdata('logged_in')['role'];
        $data['blogs'] = $this->blog->getAll();
        $this->load->view('template/header');
        $this->load->view('template/navbar', $data);
		$this->load->view('blog/index', $data);
        $this->load->view('template/footer');
	}
    public function add(){
        $data['role'] = $this->session->userdata('logged_in')['role'];
        
        $this->load->view('template/header');
        $this->load->view('template/navbar', $data);
        $this->load->view('blog/add', $data);
        $this->load->view('template/footer');
    }
    public function edit(){
        $data['role'] = $this->session->userdata('logged_in')['role'];
        $data['blog_id'] = $this->input->get('blog_id');
        if(isset($this->blog->getBlogById($data['blog_id'])[0]))
            $data['blog'] = $this->blog->getBlogById($data['blog_id'])[0];
        $this->load->view('template/header');
        $this->load->view('template/navbar', $data);
        $this->load->view('blog/edit', $data);
        $this->load->view('template/footer');
    }
    public function upload(){
        $blog_id = $this->input->get('id');
        $target_dir = "uploads/blog/";
        if(!is_dir($target_dir))
            mkdir($target_dir, 0755, true);
        if(isset($_FILES["file"])){
            $target_file = $target_dir.$_FILES["file"]["name"];
            move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
        }
        else{
            if($blog_id) $target_file = $this->blog->getBlogById($blog_id)[0]->blog_image;
            else $target_file = $target_dir."default_blog_image.png";
        }
        echo $target_file;
    }
    public function addBlog(){
        $title = $this->input->post('title');
        $url = $this->input->post('url');
        $content = $this->input->post('info');
        $path = $this->input->post('path');
        $newBlog = array(
            'title'=>$title,
            'url'=>$url,
            'content'=>$content,
            'blog_image'=>$path,
            'created_date'=>date('Y-m-d H:i:s'),
        );
        $newBlogId = $this->blog->save($newBlog);
        //echo $newAgencyId;
        if($newBlogId)
            $res = array('state' => true, 'msg' => "Successfully saved!");
        else 
            $res = array('state' => false, 'msg' => "Can't save this blog. Try again!");
        return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($res));
    }
    public function editBlog(){
        $id = $this->input->post('id');
        $title = $this->input->post('title');
        $url = $this->input->post('url');
        $content = $this->input->post('info');
        $path = $this->input->post('path');
        $thisBlog = array(
            'title'=>$title,
            'url'=>$url,
            'content'=>$content,
            'blog_image'=>$path,
            'created_date'=>date('Y-m-d H:i:s'),
        );
        $this->blog->update($id, $thisBlog);
        $res = array('state' => true, 'msg' => "Successfully Updated!");
        return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($res));
    }
    public function getBlogById($blog_id){
        $select = $this->blog->getBlogById($blog_id)[0];
        return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($select));
    }
    public function delete(){
        $blog_id = $this->input->get('blog_id');
        $this->blog->delete($blog_id);
        $this->index();
    }
}
