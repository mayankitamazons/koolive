<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model {

	var $cat_table = 'categories';
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		    $this->search = '';
	}
	public function save($data)
	{
		$this->db->insert($this->cat_table, $data);
		return $this->db->insert_id();
	}
	public function update($id, $data)
	{	
		$this->db->get($this->cat_table);
		$this->db->where('id', $id);
		$this->db->update($this->cat_table, $data);
		return $this->db->affected_rows();
	}
	public function delete($id)
	{
		$query = $this->db->delete($this->cat_table, array('id'=>$id));
	}
	public function getCategoryById($cat_id){
		$query = $this->db->get_where($this->cat_table, array('id'=>$cat_id));
		return $query->result();
	}
	public function getAll(){
		$query = $this->db->get($this->cat_table);
		return $query->result();
	}
}
