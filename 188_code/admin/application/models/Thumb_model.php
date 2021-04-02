<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Thumb_model extends CI_Model {

	var $table = 'thumbnails';
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		    $this->search = '';
	}

	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();	
	}
	public function getThumbnailsByEmail($email){
		$query = $this->db->get_where($this->table, array('email'=>$email));
		return $query->result();
	}
	public function update($id, $role, $data)
	{
		/*$this->db->get("logos");
		if($role==1)
			$this->db->where('agency_id', $id);
		else if($role==2)
			$this->db->where('escort_id', $id);
		else if($role==3)
			$this->db->where('girl_id', $id);
		$this->db->update($this->table, $data);
		return $this->db->affected_rows();*/
	}
	public function delete($email)
	{
		$this->db->delete($this->table, array('email'=>$email));
	}
}
