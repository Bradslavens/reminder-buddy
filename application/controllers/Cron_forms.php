<?php
class Cron_forms extends CI_Controller {


	public function __construct()
	{
		parent::__construct();
		$this->load->model('transactions_model');
		$this->load->helper('date');
	}

	public function send ($token = 1)
	{
		// transactions status = active '1'
		$transactions = $this->transactions_model->get_item_by_id('transactions', array('status' => 1));

		foreach ($transactions as $transaction) 
		{// for each transaction get transaction contacts join contacts
		   $t_contacts = $this->transactions_model->get_transaction_contacts($transaction['id']);
		   // set transaction info for subject
		   $data['transaction_name'] = $transaction['name'];

		   foreach ($t_contacts as $t_contact) 
		   {
		   		// set buyer tc if no tc set it to agent
		   		if($t_contact['party'] == 6 )
		   		{
		   			$buyer_tc = $t_contact['email'];
		   		}
		   		elseif($t_contact['party']== 3)
		   		{
		   			// see if there is a buyers's agent
		   			if(empty($this->transactions_model->get_item_by_id('transaction_parties', array('party' => 6)))){
		   					$buyer_tc = $t_contact['party']; 
		   			}
		   		}

		   		// set buyer tc if no tc set it to agent
		   		if($t_contact['party'] == 7 )
		   		{
		   			$seller_tc = $t_contact['email'];
		   		}
		   		elseif($t_contact['party'] == 4)
		   		{
		   			// see if there is a buyers's agent
		   			if(empty($this->transactions_model->get_item_by_id('transaction_parties', array('party' => 7)))){
		   					$seller_tc = $t_contact['party']; 
		   			}
		   		}

		   		// set seller tc if no tc set it to agent
		   		if($t_contact['party'] == 5 )
		   		{
		   			$escrow = $t_contact['email'];
		   		}
			}

			// get headings by side
			// get sides and then for each side make a category of forms due for that side
			// get available sides
			$sides = $this->transactions_model->get_item_by_id('sides');
			$x = 0;
			foreach ($sides as $s) {
				# code...
				// get forms that belong on each side using queue and signers
				// select transaction items
				// which are incomplete
				$tis = $this->transactions_model->get_transaction_items(2, $transaction['id']);
				foreach ($tis as $ti) {

					if($ti['queue_id'] == $s['id'] ){
					// 		// add to list
							$sides[$x]['item'][] = $ti;
					// 		// see if items are signed by everyone else
					// 		// check if all signed except party

					}elseif(empty($this->transactions_model->get_items_not_complete($ti['id'], $s['id']))){

							$sides[$x]['item'][] = $ti;
					}
				}

				$x++;
			}

				// mail debbie instructions
				$data['sides'] = $sides;
				$data['transaction'] = $transaction;

				$this->load->library('email');
				$this->email->set_mailtype("html");

				$this->email->from(TC_EMAIL, TC_NAME);
				$this->email->to(TC_EMAIL); 
				$this->email->bcc(BROKER_COPY_EMAIL); 
				$msg  = $this->load->view('mail/forms', $data, TRUE);
				$msg .= $this->load->view('mail/deb_sig',$data,TRUE);

				$this->email->subject($data['transaction_name']);
				$this->email->message($msg); 
				$this->email->set_alt_message('error');
				echo $msg;
				// $this->email->send();
		}
	}
}
