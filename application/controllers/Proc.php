<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proc extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('date');
		
		// check login
		if($_SESSION['is_logged_in'] !== TRUE)
		{
			redirect('welcome/index');
		}
		$this->load->model('transactions_model');
		$this->load->model('user_model');
	}

	// public function test()
	// {
	// 	// for testing
	// }

	public function processing($section = 'cover')
	{
		$this->load->view('templates/header');

		$data['transaction_name'] = "";

		if(isset($_SESSION['transaction_id']))
		{
			$transaction1 = $this->transactions_model->get_item_by_id('transactions', array('id' => $_SESSION['transaction_id']));
			$data['transaction_name'] = $transaction1[0]['name'];
		}
		$this->load->view('proc/nav', $data);

		$this->load->helper('file');
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('email');

		if($section == "add_update_cover")
		{
			if(isset($_SESSION['transaction_id']))
			{
				$this->transactions_model->update_transaction_name();
				$data['transaction'] = $this->transactions_model->get_item_by_id('transactions', array('id'=> $_SESSION['transaction_id']));
				$data['templates'] = $this->transactions_model->get_item_by_id('templates');
				$data['sides'] = $this->transactions_model->get_item_by_id('sides');
				$data['transaction_status'] = $this->transactions_model->get_item_by_id('transaction_status');
				$this->load->view('proc/cover', $data);
			}
			else
			{
				$_SESSION['transaction_id'] = $this->transactions_model->add();
				$data['transaction'] = $this->transactions_model->get_item_by_id('transactions', array('id' => $_SESSION['transaction_id']));
				$data['templates'] = $this->transactions_model->get_item_by_id('templates');
				$data['sides'] = $this->transactions_model->get_item_by_id('sides');
				$data['transaction_status'] = $this->transactions_model->get_item_by_id('transaction_status');
				$this->load->view('proc/cover', $data);
			}
		}

		if($section== "apply_templates")
		{
			$this->transactions_model->apply_templates();
			$data['templates'] = $this->transactions_model->get_item_by_id('templates');
			$data['sides'] = $this->transactions_model->get_item_by_id('sides');
			$data['transaction_status'] = $this->transactions_model->get_item_by_id('transaction_status');
			$data['transaction'] = $this->transactions_model->get_item_by_id('transactions', array('id' => $_SESSION['transaction_id']));
			$this->load->view('proc/cover', $data);
		}

		//load cover info page
		if($section == 'cover')
		{
			$_SESSION['transaction_id'] = $this->uri->segment(4);
			if(isset($_SESSION['transaction_id']))
			{
				$data['transaction'] = $this->transactions_model->get_item_by_id('transactions', array('id' => $_SESSION['transaction_id']) );
			}
			else
			{
				// mail debbie instructions

				$this->load->library('email');
				$this->email->set_mailtype("html");

				$this->email->from(TC_EMAIL, 'Brad :)');
				$this->email->to(TC_EMAIL); 
				$this->email->bcc(BROKER_COPY_EMAIL); 
				$data['name'] = "Debbie";
				$msg  = $this->load->view('mail/new_instructions', $data, TRUE);
				$msg .= $this->load->view('mail/brad_sig',$data,TRUE);

				$this->email->subject('New Transaction Instructions - see manual for details');
				$this->email->message($msg); 
				$this->email->set_alt_message('This is the alternative message, call Brad');
				// echo $msg;
				$this->email->send();

			}

			$data['templates'] = $this->transactions_model->get_item_by_id('templates');
			$data['sides'] = $this->transactions_model->get_item_by_id('sides');
			$data['transaction_status'] = $this->transactions_model->get_item_by_id('transaction_status');
			$this->load->view('proc/cover', $data);
		}

		// load page
		if($section == 'checklist')
		{
			// populate the checklist with 
			// get complete status from transaction item parties
			//$this->transactions_model->get_transaction_item_parties();

			// delete item 
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
			$data['checklist_items'] = $this->transactions_model->get_transaction_items(2, $_SESSION['transaction_id'], 0);// get checklist items   should be forms only aud = 0
			$this->load->view('proc/checklist', $data);
		}

		// load page
		if($section == 'reminders')
		{

			if($this->uri->segment(4) === "del_item")
			{
				$this->transactions_model->delete_item('transaction_items', $this->uri->segment(5)  );
			}


			if($this->uri->segment(5) === "add_form")
			{
				$item_id = $this->input->post('item_id');
				if($item_id === '1')
				{
					$item_id = $this->transactions_model->add_item();

				}
				$this->transactions_model->add_transaction_item($item_id);

			}
			// get available date types
			$data['date_types'] = $this->transactions_model->get_item_by_id('date_types');
			// get parties
			$data['parties'] = $this->transactions_model->get_item_by_id('parties');
			$data['items'] = $this->transactions_model->get_transaction_items(1, $_SESSION['transaction_id'],0); // one = reminders
			$this->load->view('proc/reminders', $data);
		}

		// load page
		if($section == 'contacts')
		{
			if($this->uri->segment(5) === "add_transaction_party")
			{
				$party_id = $this->input->post('party_id');
				if($party_id === '1')  // party does not exist already
				{
					$party_id = $this->transactions_model->add_party();
				}
				// add contact
				$this->transactions_model->add_transaction_party($party_id);

				// mail welcome message to new contact
				// check if email exists
				if($this->input->post('email'))
				{
					if($this->input->post('email'))
					{
						// email the new contact
						$this->load->library('email');
						$this->email->set_mailtype("html");

						$this->email->from(TC_EMAIL, TC_NAME);
						$this->email->to($this->input->post('email')); 
						// $this->email->to('bradslavens@gmail.com'); 
						$this->email->cc(TC_EMAIL); 
						$this->email->bcc(BROKER_COPY_EMAIL); 

						$data['first_name'] = $this->input->post('first_name');
						$data['last_name'] = $this->input->post('last_name');
						$msg = $this->load->view('mail/contact', $data, TRUE);
						$msg .= $this->load->view('mail/deb_sig', $data, TRUE);

						$t = $this->transactions_model->get_item_by_id('transactions', array('id' => $_SESSION['transaction_id']));
						$s = 'Transactin Coordinator Info '. $t[0]['name'];
						$this->email->subject($s);
						$this->email->message($msg); 
						$this->email->set_alt_message('This is the alternative message');
						$this->email->send();
						// echo $msg;
					}
				}
			}
			if($this->uri->segment(4) === "delete_contact")
			{
				$this->transactions_model->delete_item('transaction_parties',$this->uri->segment(5) );
			}

			// get parties
			$data['parties'] = $this->transactions_model->get_item_by_id('parties');
			$data['transaction_parties'] = $this->transactions_model->get_transaction_parties($_SESSION['transaction_id']);
			$this->load->view('proc/contacts',$data);
		}

		// load page
		if($section == 'dates')
		{

			if($this->uri->segment(4) === "del_item")
			{
				$this->transactions_model->delete_item('transaction_dates', $this->uri->segment(5)  );
			}

			if($this->uri->segment(5) === "add_date")
			{
				$this->transactions_model->add_transaction_date();
			}
			$data['date_types'] = $this->transactions_model->get_item_by_id('date_types');
			$data['dates'] = $this->transactions_model->get_transaction_dates($_SESSION['transaction_id']);
			$this->load->view('proc/dates', $data);
		}

		// mail reminders
		if($section == 'mail_reminders')
		{
			$msg = "";
			$sub = "";
			$fn = "";
			$email = "";
			// post is the transaction item id			// 
			foreach ($this->input->post('reminder') as $reminder_id) 
			{
				//foreach transaction item id get reminder info
				$reminder_info = $this->transactions_model->get_item_by_id('transaction_items', array('id' => $reminder_id));
				// echo "reminder info";
				// convert that id to  item info message etc by taking the item id from transaction item id and
				// getting the items
				foreach ($reminder_info as $item_id) 
				{
					// get item details
					$reminder_details = $this->transactions_model->get_item_by_id('items', array('id' => $item_id['item_id']));
					// echo "reminder details";

					$msg = $reminder_details[0]['body'];
					$sub = $reminder_details[0]['heading'];

				}


				// then we need the recipients info
				// look up the transaction party id by using the transaction item id in table 
				// transaction item parties
				$transaction_item_party_info = $this->transactions_model->get_transaction_item_party_by_transaction_item_id($reminder_id);
				// echo "transaction party info";
				foreach ($transaction_item_party_info as $tip) 
				{
					// echo "transaction party id";

					// now get contact id from transaction parties based on transaction party id
					$contact_id = $this->transactions_model->get_item_by_id('transaction_parties', array('id' => $tip['transaction_party_id']));
					// echo "contact id";

					// now get contact info from contacts by contact id
					$contact_info = $this->transactions_model->get_item_by_id('contacts', array('id' => $contact_id[0]['contact_id']));
					// echo "contact info";

					$this->load->library('email');
					$this->email->set_mailtype("html");

					$fn = $contact_info[0]['first_name'];
					$email = $contact_info[0]['email'];

					$this->email->from('tcslavens@gmail.com', 'Debbie Slavens');
					// $this->email->to($email);  
					$this->email->to('bradslavens@gmail.com'); // testing
					$this->email->bcc('brad_slavens@yahoo.com'); 

					$this->email->subject($sub);
					$data['message'] = $msg;
					$message = $this->load->view('mail/reminder', $data, TRUE);
					$message .= $this->load->view('mail/deb_sig', $data, TRUE);
					// echo $message;
					$this->email->message($message); 
					$this->email->set_alt_message($msg);

					$this->email->send();

					echo $this->email->print_debugger();

				}
			}

		}


		$this->load->view('templates/footer');
	}

	public function form_list()
	{
		$forms = $this->transactions_model->get_items($this->input->get('short_name'));
		// output json
				$this->output
					->set_content_type("application/json")
					->set_output(json_encode($forms));

	}

	public function contact_list()
	{
		$contacts = $this->transactions_model->get_contacts($this->input->get('first_name'));
		// output json
				$this->output
					->set_content_type("application/json")
					->set_output(json_encode($contacts));

	}
	public function select_template()
	{
		$q = $this->transactions_model->get_selected_template_list();
		// output json
		$this->output
			->set_content_type("application/json")
			->set_output(json_encode($q));
	}
}
