<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comment_model extends CI_Model {

	var $comment_table = 'rating';
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
	public function getCategoryById($cat_id){
		$query = $this->db->get_where($this->cat_table, array('id'=>$cat_id));
		return $query->result();
	}
	public function getAll(){
		$sql = "
			SELECT a.*, users.name merchant_name
			FROM (
			SELECT rating.*, users.name user_name
			FROM rating INNER JOIN users ON users.id = rating.user_id ) a INNER JOIN users ON a.merchant_id = users.id
		";
		$query = $this->db->query($sql);
		return $query->result();
	}
	public function delete($id){
		$query = $this->db->delete('rating', array('id'=>$id));
		return;
	}
}
