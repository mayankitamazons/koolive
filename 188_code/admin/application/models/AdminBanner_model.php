<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminBanner_model extends CI_Model {

	var $table = 'adminbanner';
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
	public function update($id, $data)
	{	
		$this->db->where('id', $id);
		$this->db->update($this->table, $data);
		return $this->db->affected_rows();
	}
	public function delete($id)
	{
		$query = $this->db->delete($this->table, array('id'=>$id));
	}
	public function getBannerById($banner_id){
		$query = $this->db->get_where($this->table, array('id'=>$banner_id));
		return $query->result();
	}
	public function getAll(){
		$query = $this->db->get($this->table);
		return $query->result();
	}
}
