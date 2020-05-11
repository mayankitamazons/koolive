<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Escort_model extends CI_Model {

	var $escorts_table = 'escorts';
	var $logo_table = 'logos';
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		    $this->search = '';
	}
	public function save($data)
	{
		$query = $this->db->get_where("escorts", array('email'=>$data['email']));
		if($query->num_rows()==0){
			$this->db->insert($this->escorts_table, $data);
			return $this->db->insert_id();
		}
	}
	public function update($escort_id, $data)
	{	
		$this->db->get('escorts');
		$this->db->where('id', $escort_id);
		$this->db->update('escorts', $data);
		return $this->db->affected_rows();
	}
	public function delete($escort_id)
	{
		$query = $this->db->delete('escorts', array('id'=>$escort_id));
	}
	public function getEscortById($escort_id){
		$this->db->select('escorts.id, logos.logo_path, escorts.agency_id, escorts.name, escorts.email, escorts.masseuse, escorts.url, escorts.bio, escorts.full_bio, escorts.age, escorts.nationality, escorts.language, escorts.incall_location, escorts.outcall_location, escorts.hair, escorts.eyes, escorts.height, escorts.weight, escorts.statistics, escorts.active, escorts.category');
		$this->db->from("escorts");
		$this->db->join('logos', 'escorts.email = logos.email', 'left');
		$this->db->where('escorts.id', $escort_id);
		$query = $this->db->get();
		return $query->result();
	}
	public function getEscortByEmail($email){
		$this->db->select('escorts.id, logos.logo_path, escorts.agency_id, escorts.name, escorts.email, escorts.masseuse, escorts.url, escorts.bio, escorts.full_bio, escorts.age, escorts.nationality, escorts.language, escorts.incall_location, escorts.outcall_location, escorts.hair, escorts.eyes, escorts.height, escorts.weight, escorts.statistics, escorts.active, escorts.category');
		$this->db->from("escorts");
		$this->db->join('logos', 'escorts.email = logos.email', 'left');
		$this->db->where('escorts.email', $email);
		$query = $this->db->get();
		return $query->result();
	}
	public function getAll(){
		$this->db->select('escorts.id, logos.logo_path, agencies.name as agency_name, escorts.name, escorts.email, escorts.masseuse, escorts.url, escorts.bio, escorts.full_bio, escorts.age, escorts.nationality, escorts.language, escorts.incall_location, escorts.outcall_location, escorts.hair, escorts.eyes, escorts.height, escorts.weight, escorts.statistics, escorts.active, escorts.category');
		$this->db->from("escorts");
		$this->db->join('logos', 'escorts.email = logos.email', 'left');
		$this->db->join('agencies', 'escorts.agency_id = agencies.id', 'left');
		$query = $this->db->get();
		return $query->result();
	}
	public function getAllByAgency($agency_id){
		$this->db->select('escorts.id, logos.logo_path, agencies.name as agency_name, escorts.name, escorts.email, escorts.masseuse, escorts.url, escorts.bio, escorts.full_bio, escorts.age, escorts.nationality, escorts.language, escorts.incall_location, escorts.outcall_location, escorts.hair, escorts.eyes, escorts.height, escorts.weight, escorts.statistics, escorts.active, escorts.category');
		$this->db->from("escorts");
		$this->db->join('logos', 'escorts.email = logos.email', 'left');
		$this->db->join('agencies', 'escorts.agency_id = agencies.id', 'left');
		$this->db->where('escorts.agency_id', $agency_id);
		$query = $this->db->get();
		return $query->result();
	}
}
