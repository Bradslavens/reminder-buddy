<?php
class Cron_reminders extends CI_Controller {


	public function __construct()
	{
		parent::__construct();
		$this->load->model('transactions_model');
		$this->load->helper('date');
	}

	public function send ($token = 1)
	{
		if($token === 677){

			// transactions status = active '1'
			$transactions = $this->transactions_model->get_item_by_id('transactions', array('status' => 1));

			foreach ($transactions as $transaction) 
			{// for each transaction get transaction contacts join contacts
			   $t_contacts = $this->transactions_model->get_transaction_contacts($transaction['id']);
			   // set transaction info for subject
			   $data['transaction_name'] = $transaction['name'];

			   foreach ($t_contacts as $t_contact) 
			   {
			   		$data['first_name'] = $t_contact['first_name'];
			   		$data['email'] = $t_contact['email'];
				   // get transaction dates
				   $transaction_dates = $this->transactions_model->get_item_by_id('transaction_dates', array('transaction' => $transaction['id']));
				   
				   foreach ($transaction_dates as $transaction_date) 
				   {
				   		// for each transaction contact get transaction item party 
				   			// this is the list of reminders that need to be sent 
				   		// get where transaction date type = item date type
				   		$item_list = $this->transactions_model->get_transaction_item_parties($t_contact['id'], $transaction_date['date'], 1 /* reminder */, 0 /* incomplete */); // where date type = date
				   		$data['items'] = array();
				   		foreach ($item_list as $item) 
				   		{
				   			$cd = strtotime($transaction_date['calendar_date']); // transaction date type convert to unix
				   			$days = 60*60*24*$item['days']; // convert transaction days to unix
					   		// compare due date to todays date, if due add to item list 
					   		if( $cd + $days <= now())
							{
								$data['items'][] = $item;						
							}
					   		else
					   		{
					   			echo "<br /> not yet ";
					   		}
				   		}
				   }
				}

					// mail debbie instructions

					$this->load->library('email');
					$this->email->set_mailtype("html");

					$this->email->from(TC_EMAIL, 'TC Slavens');
					$this->email->to(TC_EMAIL); 
					$this->email->bcc(BROKER_COPY_EMAIL); 
					$msg  = $this->load->view('mail/reminders', $data, TRUE);
					$msg .= $this->load->view('mail/deb_sig',$data,TRUE);

					$this->email->subject($data['transaction_name']);
					$this->email->message($msg); 
					$this->email->set_alt_message('error');
					echo $msg;
					// $this->email->send();
			}
				
		}
	}
}
?>
