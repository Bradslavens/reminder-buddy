<?php
class User_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}

	public function get_user($email = NULL)
	{
		// if email verify contact does not exist
		$query= $this->db->get_where('contacts', array('email' => $email));
		return $query->result_array();
	}

	public function set_user()
	{

		if($this->input->post('email'))
			{ 
				echo "1 or more";
				exit();
			}


			// cryp the password
			$hash_password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);

			// set input data
			$data = array(
				'first_name'=>$this->input->post('first_name'),
				'last_name'=>$this->input->post('last_name'),
				'email'=>$this->input->post('email'),
				'password'=>$hash_password,
				'mail_list'=>$this->input->post('check_box')
				);

			return $this->db->insert('contacts',$data);
	}

	public function get_login()
	{
		$query= $this->db->get_where('contacts', array('email'=>$this->input->post('email')));
		$row=$query->result_array();
		//get the first row  should just return one row
		return $row[0];
	}

}