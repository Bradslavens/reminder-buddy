<?php
class Login_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}

	public function get_login()
	{
		$query= $this->db->get_where('contacts', array('email'=>$this->input->post('email')));
		$row=$query->result_array();
		//get the first row  should just return one row
		return $row[0];
	}

}