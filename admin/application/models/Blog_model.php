<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog_model extends CI_Model {

	var $blogs_table = 'blogs';
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		    $this->search = '';
	}
	public function save($data)
	{
		$this->db->insert($this->blogs_table, $data);
		return $this->db->insert_id();
	}
	public function update($id, $data)
	{	
		$this->db->get($this->blogs_table);
		$this->db->where('id', $id);
		$this->db->update($this->blogs_table, $data);
		return $this->db->affected_rows();
	}
	public function delete($id)
	{
		$query = $this->db->delete($this->blogs_table, array('id'=>$id));
	}
	public function getBlogById($blog_id){
		$query = $this->db->get_where($this->blogs_table, array('id'=>$blog_id));
		return $query->result();
	}
	public function getAll(){
		$query = $this->db->get($this->blogs_table);
		return $query->result();
	}
}
