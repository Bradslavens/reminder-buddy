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
		
		if($token == '677'){

			// transactions status = active '1'
			$transactions = $this->transactions_model->get_item_by_id('transactions', array('status' => 1));

			foreach ($transactions as $transaction) 
			{// for each transaction get transaction contacts join contacts
				   // set transaction info for subject
			   $data['transaction_name'] = $transaction['name'];

			   $form_list = array();
				// get past due forms 
				$forms = $this->transactions_model->get_past_due_forms($transaction['id']);
				foreach ($forms as $form) {
					# code...
					// get partys who havent signed
					$parties = $this->transactions_model->get_unsigned_parties($form['id']);
					if(!empty($parties)){

						$form['parties'] = $parties;
						$form_list[] = $form;

					}
				}

				$data['form_list'] = $form_list;

				$t_contacts = $this->transactions_model->get_transaction_contacts($transaction['id']);

				// get our agent our tc and there tc or agent and send each one a copy

				$send_to = array();

				if($transaction['side'] == 1){ // 1 is buyers side
					// set a send to to buyer's agent
					$send_tos[] = $this->transactions_model->get_party($transaction['id'], 1);
					// find out if there is a seller's tc
					$seller_side = $this->transactions_model->get_party($transaction['id'], 7);
						// if yes send to seller's tc
					if(!empty($seller_side)){
						$send_tos[] = $seller_side;
					}else{
						// else send to seller's agent
						$sa = $this->transactions_model->get_party($transaction['id'],4);
						if(!empty($sa)){
							$send_tos[] = $sa;
						}
					}
				}

				if($transaction['side'] == 4){  // 4 is sellers side
					// set a send to to seller's agent
					$send_tos[] = $this->transactions_model->get_party($transaction['id'], 4);
					// find out if there is a buyer's tc
					$buyer_side = $this->transactions_model->get_party($transaction['id'], 6);
						// if yes send to buyer's tc
					if(!empty($buyer_side)){
						$send_tos[] = $buyer_side;
					}else{
						// else send to seller's agent
						$ba = $this->transactions_model->get_party($transaction['id'],3);
						if(!empty($ba)){
							$send_tos[] = $ba;
						}
					}
				}
			   foreach ($send_tos as $send_to) 
			   {
			   		$data['contact'] = $send_to[0];
					// mail debbie instructions
					$data['transaction'] = $transaction;

					$this->load->library('email');
					$this->email->set_mailtype("html");

					$this->email->from(TC_EMAIL, TC_NAME);
					$this->email->to($send_to[0]['email']); 
					$this->email->bcc(BROKER_COPY_EMAIL); 
					$msg  = $this->load->view('mail/forms', $data, TRUE);
					$msg .= $this->load->view('mail/deb_sig',$data,TRUE);

					$this->email->subject($data['transaction_name']);
					$this->email->message($msg); 
					$this->email->set_alt_message('error');
					// var_dump($send_to);
					// echo $msg;
					$this->email->send();
					// exit();
				}

			}
		}
		else
		{
			echo "token not working";
		}
	}
}
