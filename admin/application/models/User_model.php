<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

	var $table = 'user_admin';
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
	}
	public function login_check($email, $password){
		$this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('email', $email);
        $this->db->where('password', md5($password));
        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            $row = $query->row();
            
            $sess_data = array(
                'user_id' => $row->id,
                'email' => $row->email
            );                
            $this->session->set_userdata('logged_in', $sess_data);
            return true;
        }
        else return false;
	}
    public function save($data){
        $query = $this->db->get_where("users", array('email'=>$data['email']));
        if($query->num_rows()==0){
            $this->db->insert($this->table, $data);
            return $this->db->insert_id();
        }
    }
    public function update($email, $data){
        $this->db->get("users");
        $this->db->where('email', $email);
        $this->db->update("users", $data);
        return $this->db->affected_rows();
    }
    public function getUserByEmail($email){
        $query = $this->db->get_where('users', array('email'=>$email));
        return $query->result();
    }
    public function delete($email){
       $this->db->delete("users", array('email'=>$email));
    }
}
