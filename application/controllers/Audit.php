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

			$_SESSION['transaction_id'] = $this->input->post('transaction');
			// validate erros
			// $this->form_validation->set_rules('first_name', 'First Name', 'required');
			// duplicate duplicate the tips list
			$this->audit_model->create_audit_list($this->input->post('transaction'), 1); // enter transaction id and audit number
			// present the checklist

			// delete item 
			$this->load->helper('url');

			if($this->uri->segment(4) === "del_item")
			{
				$this->transactions_model->delete_item('transaction_items', $this->uri->segment(5)  );
			}

			if($this->uri->segment(5) === "add_form")
			{
				$item_id = (int)$this->input->post('item_id');


				if($item_id === 1)
				{
					$item_id = $this->transactions_model->add_item();

				}

				$this->transactions_model->add_transaction_item($item_id);

			}

			// update checklist
			if($this->uri->segment(5) === "update_checklist_status")
			{
				// udate checklist signers
				$this->transactions_model->update_checklist_status();
			}
			
			// get available date types
			$data['date_types'] = $this->transactions_model->get_item_by_id('date_types');
			// get parties
			$data['parties'] = $this->transactions_model->get_item_by_id('parties');
			$data['checklist_items'] = $this->transactions_model->get_transaction_items(2, $_SESSION['transaction_id'], 1);// get checklist items   should be forms only
			$this->load->view('proc/checklist', $data);
			// add a compare button
		}


		$this->load->view('templates/footer');

	}

	public function audit_report(){
		echo "here is yoru report";
	}
}
