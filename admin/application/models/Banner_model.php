<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banner_model extends CI_Model {

	var $table = 'banners';
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		    $this->search = '';
	}

	public function save($data)
	{
		$query = $this->db->get_where($this->table, array('email'=>$data['email']));
		if($query->num_rows()==0)
		{
			$this->db->insert($this->table, $data);
			return $this->db->insert_id();
		}
	}
	public function update($oldemail, $data)
	{
		$this->db->where('email', $oldemail);
		$this->db->update($this->table, $data);
		return $this->db->affected_rows();
	}
	public function getBannerByEmail($email){
		$query = $this->db->get_where($this->table, array('email'=>$email));
		return $query->result();
	}
	public function delete($email)
	{
		$this->db->delete($this->table, array('email'=>$email));
	}
}
