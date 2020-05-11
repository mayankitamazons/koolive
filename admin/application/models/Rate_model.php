<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rate_model extends CI_Model {

	var $rates_table = 'rates';
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		    $this->search = '';
	}
	public function save($data)
	{
		$this->db->insert($this->rates_table, $data);
		return $this->db->insert_id();
	}
	public function update($id, $data)
	{	
		$this->db->get($this->rates_table);
		$this->db->where('id', $id);
		$this->db->update($this->rates_table, $data);
		return $this->db->affected_rows();
	}
	public function delete($email)
	{
		$query = $this->db->delete($this->rates_table, array('email'=>$email));
	}
	public function getRatesByEmail($email){
		$query = $this->db->get_where($this->rates_table, array('email'=>$email));
		return $query->result();
	}
	public function getAll(){
		$query = $this->db->get($this->rates_table);
		return $query->result();
	}
}
