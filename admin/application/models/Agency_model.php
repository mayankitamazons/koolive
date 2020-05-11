<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agency_model extends CI_Model {

	var $agencies_table = 'agencies';
	var $logo_table = 'logos';
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		    $this->search = '';
	}
	public function save($data)
	{
		$query = $this->db->get_where($this->agencies_table, array('email'=>$data['email']));
		if($query->num_rows()==0){
			$this->db->insert($this->agencies_table, $data);
			return $this->db->insert_id();
		}
	}
	public function update($agency_id, $data)
	{	
		$this->db->get('agencies');
		$this->db->where('id', $agency_id);
		$this->db->update('agencies', $data);
		return $this->db->affected_rows();
	}
	public function delete($email)
	{
		$query = $this->db->delete('agencies', array('email'=>$email));
	}
	public function getAgencyById($agency_id){
		$this->db->select('agencies.id, logos.logo_path, banners.banner_path, agencies.name, agencies.email, agencies.mobile, agencies.website, agencies.info, agencies.password');
		$this->db->from("agencies");
		$this->db->join('logos', 'agencies.email = logos.email', 'left');
		$this->db->join('banners', 'agencies.email = banners.email', 'left');
		$this->db->where(array('agencies.id'=>$agency_id));
		$query = $this->db->get();
		return $query->result();
	}
	public function getAgencyByEmail($email){
		$this->db->select('agencies.id, logos.logo_path, banners.banner_path, agencies.name, agencies.email, agencies.mobile, agencies.website, agencies.info, agencies.password');
		$this->db->from("agencies");
		$this->db->join('logos', 'agencies.email = logos.email', 'left');
		$this->db->join('banners', 'agencies.email = banners.email', 'left');
		$this->db->where('agencies.email', $email);
		$query = $this->db->get();
		return $query->result();
	}
	public function getAll(){
		$this->db->select('agencies.id, logos.logo_path, banners.banner_path, agencies.name, agencies.email, agencies.mobile, agencies.website, agencies.info, agencies.password');
		$this->db->from("agencies");
		$this->db->join('logos', 'agencies.email = logos.email', 'left');
		$this->db->join('banners', 'agencies.email = banners.email', 'left');
		$query = $this->db->get();
		return $query->result();
	}
}
