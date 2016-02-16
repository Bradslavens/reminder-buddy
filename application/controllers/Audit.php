<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		// check login
		if($_SESSION['is_logged_in'] !== TRUE)
		{
			redirect('welcome/index');
		}

		$this->load->library('session');
	}

	public function home($page = 'home'){

		$this->load->model('audit_model');

		$this->load->helper('form');
		$this->load->library('form_validation');
		

		$this->load->view('templates/header');

		// get transaction list
		$t = $this->audit_model->get_transactions(1);

		$data['t'] = $t;

		$this->load->view('audit/home', $data);

		if($page == 'start_audit'){

			$this->load->model('transactions_model');

			$this->session->set_userdata(array('transaction_id'=>$this->input->post('transaction')));

			$aud_number = $this->audit_model->get_new_audit($this->session->userdata('transaction_id'));
			$this->session->set_userdata(array('aud_number' => $aud_number));
			$this->audit_model->create_audit_list($this->input->post('transaction'), $this->session->userdata('aud_number')); // enter transaction id and audit number
			// present the checklist

			// delete item 
			$this->load->helper('url');

			// get available date types
			$data['date_types'] = $this->transactions_model->get_item_by_id('date_types');
			// get parties
			$data['parties'] = $this->transactions_model->get_item_by_id('parties');
			$data['checklist_items'] = $this->transactions_model->get_transaction_items(2, $this->session->userdata('transaction_id'), $this->session->userdata('aud_number'));// get checklist items   should be forms only
	
			$this->load->view('audit/checklist', $data);
			// add a compare button
		}

		$this->load->view('templates/footer');

	}


	public function del_item($id = NULL){
		$this->load->model('audit_model');
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->load->model('audit_model');
		$this->load->model('transactions_model');

		$this->audit_model->delete_aud_item($id, $this->session->userdata('aud_number'));


		$this->load->view('templates/header');

		// get transaction list
		$t = $this->audit_model->get_transactions(1);

		$data['t'] = $t;

		$this->load->view('audit/home', $data);

		if($this->input->post('transaction')){

			$this->session->set_userdata(array('transaction_id'=>$this->input->post('transaction')));

		}

		// validate erros
		// $this->form_validation->set_rules('first_name', 'First Name', 'required');
		
		// get available date types
		$data['date_types'] = $this->transactions_model->get_item_by_id('date_types');
		// get parties
		$data['parties'] = $this->transactions_model->get_item_by_id('parties');
		$data['checklist_items'] = $this->transactions_model->get_transaction_items(2, $this->session->userdata('transaction_id'), $this->session->userdata('aud_number'));// get checklist items   should be forms only
		$this->load->view('audit/checklist', $data);
		// add a compare button

		$this->load->view('templates/footer');


	}

	public function add_form(){
		$this->load->model('audit_model');
		$this->load->helper('form');
		$this->load->library('form_validation');

		$item_id = (int)$this->input->post('item_id');

		$this->load->model('audit_model');
		$this->load->model('transactions_model');

		if($item_id === 1)
		{
			$item_id = $this->transactions_model->add_item();

		}

		$this->audit_model->add_transaction_item($item_id, $this->session->userdata('aud_number')); // 1 for audit

		$this->load->view('templates/header');

		// get transaction list
		$t = $this->audit_model->get_transactions(1);

		$data['t'] = $t;

		$this->load->view('audit/home', $data);

		if($this->input->post('transaction')){

			$this->session->set_userdata(array('transaction_id'=>$this->input->post('transaction')));

		}

		// validate erros
		// $this->form_validation->set_rules('first_name', 'First Name', 'required');
		
		// get available date types
		$data['date_types'] = $this->transactions_model->get_item_by_id('date_types');
		// get parties
		$data['parties'] = $this->transactions_model->get_item_by_id('parties');
		$data['checklist_items'] = $this->transactions_model->get_transaction_items(2, $this->session->userdata('transaction_id'), $this->session->userdata('aud_number'));// get checklist items   should be forms only
		$this->load->view('audit/checklist', $data);
		// add a compare button

		$this->load->view('templates/footer');



	}

	public function update_checklist_status(){
		$this->load->model('audit_model');
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$this->load->model('transactions_model');

				// udate checklist signers
		$this->audit_model->update_checklist_status();
		$this->load->view('templates/header');

		// get transaction list
		$t = $this->audit_model->get_transactions(1);

		$data['t'] = $t;

		$this->load->view('audit/home', $data);

		if($this->input->post('transaction')){

			$this->session->set_userdata(array('transaction_id'=>$this->input->post('transaction')));

		}

		// validate erros
		// $this->form_validation->set_rules('first_name', 'First Name', 'required');
		
		// get available date types
		$data['date_types'] = $this->transactions_model->get_item_by_id('date_types');
		// get parties
		$data['parties'] = $this->transactions_model->get_item_by_id('parties');
		$data['checklist_items'] = $this->transactions_model->get_transaction_items(2, $this->session->userdata('transaction_id'), $this->session->userdata('aud_number'));// get checklist items   should be forms only 
		$this->load->view('audit/checklist', $data);
		// add a compare button

		$this->load->view('templates/footer');

	}

	public function audit_report(){
		$this->load->model('audit_model');

		$data['audit_list'] = $this->audit_model->compare_audit($this->session->userdata('transaction_id')); 
		
		$this->load->view('templates/header');

		$this->load->view('audit/disc_list', $data);

		$this->load->view('templates/footer');
	}


	// add transaction item party
	private function add_tip($transaction_item_id, $transaction_party_id, $audit = 0)
	{

		$data5 = array
		(
			'transaction_item_id' => $transaction_item_id,
			'transaction_party_id' => $transaction_party_id,
			'audit' => $audit
		);

		$this->db->insert('transaction_item_parties', $data5);	
	}

}

