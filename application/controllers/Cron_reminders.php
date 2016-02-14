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
		if($token == '677'){
			// get transactions
			$ts = $this->transactions_model->get_transactions(1);

			// now get the contacts
			foreach ($ts as $t) {
				$data = array();
				# code...
				$cs = $this->transactions_model->get_transaction_contacts($t['id']);

				// now get transaction items parties  that mathc contact
				foreach ($cs as $c) {
					# code...

					$data['items'] = $this->transactions_model->get_past_due_reminders($c['id']); // 
					
					if($data['items']){

						$data['first_name'] = $c['first_name'];

						$this->load->library('email');
						$this->email->set_mailtype("html");

						$this->email->from(PROCESSING_EMAIL, 'Brad Slavens');
						$this->email->to(BROKER_EMAIL); 
						$this->email->bcc(BROKER_COPY_EMAIL); 
						// $msgp  = $this->load->view('mail/reminders', $data, TRUE);
						// $msgp .= $this->load->view('mail/deb_sig',$data,TRUE);

						$msg  = $this->load->view('mail/reminders_plain', $data, TRUE);
						$msg .= $this->load->view('mail/deb_sig_plain',$data,TRUE);

						$this->email->subject($t['name']);
						$this->email->message($msg); 
						$this->email->set_alt_message('error');
						// echo $msg;
						$this->email->send();

						echo $this->email->print_debugger();

						// send debug info
						if(!$this->email->print_debugger()){

							$this->email->from(TC_EMAIL, 'TC Slavens');
							$this->email->to(BROKER_COPY_EMAIL); 

							$this->email->subject('email errors');
							$this->email->set_alt_message($msg);
							$this->email->message($this->email->print_debugger()); 
							// echo $msg;
							$this->email->send();
						}

						// mark form mail send complete
						$this->transactions_model->set_items_to_complete($data['items']);
// 

					}

				}
			}
		}
		else
		{
			echo "cronjob not working";
		}
	}
}
?>
