<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Broadcast_model extends CI_Model {

	var $broadcast_table = 'broadcasts';
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		    $this->search = '';
	}
	public function save($data)
	{
		$this->db->insert($this->broadcast_table, $data);
		return $this->db->insert_id();
	}
	public function update($id, $data)
	{	
		$this->db->get($this->broadcast_table);
		$this->db->where('id', $id);
		$this->db->update($this->broadcast_table, $data);
		return $this->db->affected_rows();
	}
	public function delete($id)
	{
		$query = $this->db->delete($this->broadcast_table, array('id'=>$id));
	}
	public function getBroadcastById($broadcast_id){
		$query = $this->db->get_where($this->broadcast_table, array('id'=>$broadcast_id));
		return $query->result();
	}
	public function getAll(){
		$this->db->select('broadcasts.id, agencies.name as agency, escorts.name as escort, broadcasts.title, broadcasts.text, broadcasts.created_date');
		$this->db->from("broadcasts");
		$this->db->join('agencies', 'broadcasts.agency_id = agencies.id', 'left');
		$this->db->join('escorts', 'broadcasts.escort_id = escorts.id', 'left');
		$query = $this->db->get();
		return $query->result();
	}
}
