<?php
class Transactions_model extends CI_Model {


	private $item_id;
	private $transaction_item_id;
	private $our_client;
	private $our_agent;
	private $our_broker;
	private $our_tc;

	public function __construct()
	{
		$this->load->database();
	}

	public function add()
	{
		$data = $this->input->post();
		$data['status'] = 1;
		$data['user_id'] = $_SESSION['user_id'];

		$this->db->insert('transactions', $data);
		return $this->db->insert_id();
	}

	// get item all or by id
	public function get_item_by_id($table, $where = FALSE)
	{
		
		if($where !== FALSE){$this->db->where($where); }
			$q = $this->db->get($table);
		return $q->result_array();
	}

	public function get_transactions($status = NULL){
		// get all transactions
		$this->db->where('status', $status);
		$q = $this->db->get('transactions');
		return $q->result_array();
	}

	public function add_transaction_date()
	{
		$conv_date = date("Y-m-d", strtotime($this->input->post('date')));
		$data['calendar_date'] = $conv_date;
		$data['date'] = $this->input->post('date_type');		
		$data['transaction'] = $_SESSION['transaction_id'];

		$this->db->insert('transaction_dates',$data);
		return $this->db->insert_id();
	}

	// get list of available dates
	public function get_transaction_dates($transaction_id = FALSE)
	{
		$this->db->select('*, transaction_dates.id AS id');
		$this->db->from('transaction_dates');
		$this->db->join('date_types','transaction_dates.date=date_types.id');
		$this->db->where('transaction',$_SESSION['transaction_id']);

		$q = $this->db->get();

		return $q->result_array();
	}


	public function get_transaction_dates2($transaction_id = FALSE)
	{
		$this->db->select('calendar_date, date, id');
		$this->db->from('transaction_dates');
		// $this->db->join('date_types','transaction_dates.date=date_types.id');
		$this->db->where('transaction',$transaction_id);

		$q = $this->db->get();

		return $q->result_array();
	}

	public function add_party()
	{
		// // check if contact exists
		$data = array(
			'first_name' => $this->input->post('first_name'),
			'last_name' => $this->input->post('last_name'),
			'email' => $this->input->post('email')
		);
			$this->db->insert('contacts',$data);
			$id = $this->db->insert_id();
			return $id;

	}

	public function get_transaction_party($party_id)
	{
		// get all the transaction parties
		$this->db->select('contacts.email AS email, transaction_parties.id AS id, contacts.first_name AS first_name, contacts.last_name AS last_name, parties.name AS party, transaction_parties.is_primary AS is_primary, transaction_parties.id AS transaction_party_id, contacts.id AS contact_id');
		$this->db->from('transaction_parties');
		$this->db->where('transaction_parties.id', $party_id);
		$this->db->join('parties','transaction_parties.party=parties.id');
		$this->db->join('contacts', 'contacts.id = transaction_parties.contact_id');
		$this->db->where('transaction_id', $_SESSION['transaction_id']);

		$q = $this->db->get();

		return $q->result_array();

	}
	public function get_transaction_parties($transaction_id)
	{
		// get all the transaction parties
			$this->db->select('transaction_parties.id AS id, contacts.first_name AS first_name, contacts.last_name AS last_name, parties.name AS party, transaction_parties.is_primary AS is_primary, transaction_parties.id AS transaction_party_id, contacts.id AS contact_id');
			$this->db->from('transaction_parties');
			$this->db->join('parties','transaction_parties.party=parties.id');
			$this->db->join('contacts', 'contacts.id = transaction_parties.contact_id');
			$this->db->where('transaction_id', $transaction_id);

			$q = $this->db->get();

		return $q->result_array();
	}

	// get selected reminders
	public function get_selected_template_list()
	{
		$template = $this->input->post('id');
		$this->db->select('*');
		$this->db->from('template_items');
		$this->db->join('items', 'items.id = template_items.item');
		$this->db->where('template_items.template', $template);
		$q = $this->db->get();
		return $q->result_array();
	}


	// PRIVATE METHODS 

	public function add_transaction_party($id)
	{
		// add party to transaction
		$d = array(
			'transaction_id' => $_SESSION['transaction_id'],
			'contact_id' => $id,
			'party' => $this->input->post('party')
			);

		$this->db->insert('transaction_parties', $d);
		
		if(!$this->db->insert_id())
		{
			echo "failed to add contact";
			exit();
		}
		else
		{
			$tp = $this->db->insert_id();  // transaction party
		}

		// add to to transaction items like forms and reminders
		// get transaction items that match the new 
		// // contacts party and add the contact to the transaction_item_parties table
		$items=$this->get_parties_for_transaction_items_by_party($this->input->post('party'));
		// add each transaction item to transaction item parties
		foreach ($items as $item) 
		{
			$this->db->insert('transaction_item_parties', array('transaction_item_id'=>$item['id'], 'transaction_party_id'=>$tp));
		}
	}

public function add_item()
	{
		// add the item to the items table
		$data = array(
			'body' => $this->input->post('body'),
			'heading' => $this->input->post('heading'),
			'days' => $this->input->post('days'),
			'date_type' => $this->input->post('date_type'),
			'queue' => $this->input->post('queue'),
			'item_type' => $this->input->post('item_type'),
			'tc_id' => $this->input->post('tc_id'),
			);
		$q = $this->db->insert('items', $data );
		$new_item_id = $this->db->insert_id();

		// add item parties
		foreach ($this->input->post('parties') as $party) 
		{
			$data3 = array
			(
				'party' => $party,
				'side' => 1,
				'item' => $new_item_id
			);
			$this->db->insert('item_parties', $data3);
		}

		return $new_item_id;
	}	

	public function add_transaction_item($item_id , $audit = 0)
	{
		$this->item_id = $item_id;
		// get default Q
		$this->db->select('queue, item_type');
		$this->db->where('id',$this->item_id);
		$ra= $this->db->get('items');
		$queue = $ra->result_array();

		$d = array(
			'transaction_id' => $_SESSION['transaction_id'],
			'item_id' => $item_id,
			'queue'=> $queue[0]['queue'],
			'type' => $queue[0]['item_type']
			);

		$this->db->insert('transaction_items', $d);
		$this->transaction_item_id = $this->db->insert_id();

		$this->add_transaction_item_parties($audit);
	}

	private function add_transaction_item_parties($audit = 0)   
	{
		if($this->input->post('parties'))
		{
			$item_parties = $this->input->post('parties');
		}else
		// get item parties 
		{
			// get the parties from the existing item
			$this->db->select('party');
			$this->db->where('item', $this->item_id);
			$q = $this->db->get('item_parties');
			$item_parties = $q->result_array();
		}
		// now add the item to the transaction item parties table
		// this is pulling item parties from the form submission
		
		foreach ($item_parties as $party)
		{
			if(is_array($party))
			{
				$new_party = $this->reset_item_party($party['party']);
			}
			else
			{
				$new_party = $this->reset_item_party($party);
			}

			$this->db->where('transaction_id', $this->session->userdata('transaction_id'));
			$this->db->where('party', $new_party);
			$q4 = $this->db->get('transaction_parties'); // get transaction party that matches party type
			// echo "transaction parties";

			if($q4->num_rows() > 0)
			{
				// now add each transaction party and transaction item to transaction item party
				foreach ($q4->result_array() as $transaction_parties) 
				{
					$this->add_tip($this->transaction_item_id, $transaction_parties['id'], $audit);
				}

			}
		}
	}


	// set transaction side
	private function reset_item_party($item_party)
	{
		$new_party = 0;
		// get transadtion sides
		$q = $this->get_item_by_id('transactions', array('id' => $_SESSION['transaction_id']));
		$transaction_side = $q[0]['side'];

		// reset item parites base on side
		if($transaction_side == 1) // buyer
		{
			switch ($item_party) {
				case 9 :
				$new_party = 1;
					break;
				case 10 :
				$new_party = 13;
					break;
				case 11 :
				$new_party = 3;
					break;
				case 12 :
				$new_party = 6;
					break;
				
				default:
					$new_party = $item_party;
					break;
			}
		}
		elseif($transaction_side == 4) // seller
		{
			switch ($item_party) {
				case 9 :
				$new_party = 8;
					break;
				case 10 :
				$new_party = 14;
					break;
				case 11 :
				$new_party = 4;
					break;
				case 12 :
				$new_party = 7;
					break;
				
				default:
					$new_party = $item_party;
					break;
			}
		}
		// reset item sides
		return $new_party;
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

	public function get_transaction_items2($transaction_id, $item_type){

		$this->db->where('type', $item_type);
		$this->db->where('transaction_id', $transaction_id);
		$q = $this->db->get('transaction_items');

		return $q->result_array();
	}

	public function get_transaction_items($type, $transaction_id, $aud) // 2 is form 1 is reminder
	{
		// first get all the items
		$this->db->select('transaction_items.id AS id, items.queue AS queue_id, queues.name AS queue, items.heading AS heading, items.body AS body, notes');
		$this->db->from('transaction_item_parties');
		$this->db->join('transaction_items', 'transaction_item_parties.transaction_item_id = transaction_items.id');
		$this->db->join('items', 'items.id = transaction_items.item_id');
		$this->db->join('queues', 'queues.id = transaction_items.queue');
		$this->db->where('transaction_item_parties.audit', $aud);
		$this->db->where('transaction_id', $transaction_id);
		$this->db->where('type', $type);
		$this->db->group_by('id');
		$this->db->order_by('heading', 'asc' );
		$q = $this->db->get();

		$item_list = $q->result_array();

		$i = 0;
		// loop through transaction items to get the parites and status
		foreach ($item_list as $transaction_item) 
		{

			// check if all complete
			$this->db->where('complete', 0); // check if there are any transaction items parties that are not signed
			$this->db->where('transaction_item_id', $transaction_item['id']);
			$this->db->where('transaction_item_parties.audit', $aud);
			$q = $this->db->get('transaction_item_parties');
			if($q->num_rows() == 0)
			{
				$item_list[$i]['all_signed'] = 1;  // item is complete
			}
			else
			{
				$item_list[$i]['all_signed'] = 0;  // item is incomplete
			}
			// check if buyer complete
			// first get transaction buyers
			$this->db->select('id');
			$this->db->where('party', 1);  // 1 is buyer
			$this->db->where('transaction_id', $transaction_id);
			$qp = $this->db->get('transaction_parties');
			$all_buyer_complete = 1;
			foreach ($qp->result_array() as $party_c) 
			{
				# code...
				$this->db->where('complete', 0); // select incomplete
				$this->db->where('transaction_item_id', $transaction_item['id']);
				$this->db->where('transaction_party_id', $party_c['id']);
				$this->db->where('transaction_item_parties.audit', $aud);
				$qe = $this->db->get('transaction_item_parties');
				if($qe->num_rows() > 0) // if 0 rows then this signer  is complete
				{
					$all_buyer_complete = 0;
				}

			}


			if($all_buyer_complete == 1) // no rows returned for incomplete
			{
				$item_list[$i]['all_buyer_complete'] = 1;
			}
			else
			{
				$item_list[$i]['all_buyer_complete'] = 0;

			}
			// check if seller complete
			$this->db->select('id');
			$this->db->where('party', 2); // 2 is seller
			$qp = $this->db->get('transaction_parties');
			$all_seller_complete = 1;
			foreach ($qp->result_array() as $party_c) 
			{
				# code...
				$this->db->where('complete', 0);
				$this->db->where('transaction_item_id', $transaction_item['id']);
				$this->db->where('transaction_party_id', $party_c['id']);
				$this->db->where('transaction_item_parties.audit', $aud);
				$qd = $this->db->get('transaction_item_parties');
				if($qd->num_rows() > 0)
				{
					$all_seller_complete = 0;
				}

			}

			if($all_seller_complete == 1)
			{
				$item_list[$i]['all_seller_complete'] = 1;
			}
			else
			{
				$item_list[$i]['all_seller_complete'] = 0;

			}

			// get all the parties
			$this->db->select('transaction_item_parties.id AS id, contacts.first_name AS first_name, contacts.last_name AS last_name, parties.name AS party, complete');
			$this->db->from('transaction_item_parties');
			$this->db->join('transaction_parties', 'transaction_parties.id = transaction_item_parties.transaction_party_id');
			$this->db->join('parties', 'parties.id = transaction_parties.party');
			$this->db->join('contacts', 'contacts.id = transaction_parties.contact_id');
			$this->db->where('transaction_item_id',$transaction_item['id']);
			$this->db->where('transaction_item_parties.audit', $aud);
			$q2 = $this->db->get();
			$item_list[$i]['parties'] = $q2->result_array();
			$i++;
		}

		return $item_list;

	}

	public function get_items($heading)
	{

		$this->db->like('heading', $heading);
		$this->db->or_like('body', $heading);
		$q = $this->db->get('items');
		return $q->result_array();

	}

	public function update_transaction_name()
	{
		$this->db->where('id',$_SESSION['transaction_id']);
		$this->db->update('transactions',$this->input->post());
	}

	public function get_contacts_by_email_and_last_name()
	{
		$this->db->where('last_name',$this->input->post('last_name'));
		$this->db->where('email',$this->input->post('email'));
		$this->db->where('first_name', $this->input->post('first_name'));
		$q = $this->db->get('contacts');
		return $q->result_array();
	}

	public function add_contact()
	{
		$data = array(
			'tc_id' => $_SESSION['user_id'],
			'first_name' => $this->input->post('first_name'),
			'last_name' => $this->input->post('last_name'),
			'email' => $this->input->post('email')
			);
		$q = $this->db->insert('contacts', $data);
		return $this->db->insert_id();
	}

	public function update_transaction_contact()
	{
		// update contact
		$transaction_contact_id = $this->input->post('transaction_party_id');
		$data = array(
			'is_primary' => $this->input->post('is_primary'),
			'party' => $this->input->post('party')
			);
		$this->db->where('id',$transaction_contact_id);
		$this->db->update('transaction_parties', $data);

		$contact_id = $this->input->post('contact_id');
		$data2 = array(
			'first_name' => $this->input->post('first_name'),
			'last_name' => $this->input->post('last_name'),
			'email' => $this->input->post('email')
			);

		$this->db->where('id', $contact_id);
		$this->db->update('contacts', $data2);

	}

	public function update_checklist_status()
	{
		// first reset all signers to 0 for item
		$data_reset = array(
			'complete' => 0
			);
		// reset the completes to 0 for all transaction_item_parties
		// get transaction item id
		$this->db->where('transaction_id', $_SESSION['transaction_id']);
		$transaction_items = $this->db->get('transaction_items');
		// for each transactino item id update transaction item parties where item id to 0
		foreach ($transaction_items->result_array() as $key2 => $value) 
		{
			# code...set transactio item parties complete to 0 where transactino_item_id
			$this->db->where('transaction_item_id', $value['id']);
			$this->db->where('audit', 0); // original not audit
			$data_reset = array('complete' => 0);
			$this->db->update('transaction_item_parties', $data_reset);
		}

		foreach ($this->input->post('notes') as $n_id => $note) 
		{
			$this->db->where('id', $n_id);
			$this->db->update('transaction_items', array('notes' =>  $note));
		}

		// check if individuals signed 
		if($this->input->post('transaction_item_parties_signed'))
		{
			foreach ($this->input->post('transaction_item_parties_signed') as $tips) 
			{
				$this->db->where('id', $tips);
				$this->db->update('transaction_item_parties', array('complete' => 1));
			}
		}

		// check if 'all_buyer_signed'  signed 
		if($this->input->post('all_buyer_signed' ))
		{
			foreach ($this->input->post('all_buyer_signed' ) as $tips) 
			{
				// get transaction parites = to buyers id is 1 
				// get all transaction item parties where party = buyer -- above
				// and where transaction item id =
				// set to complete
				$this->db->where('transaction_id' , $_SESSION['transaction_id']);
				$this->db->where('(party=1 OR party=3)', NULL);  // 1 is buyer 3 is buyers agent
				$t_parties = $this->db->get('transaction_parties');
				foreach ($t_parties->result_array() as $t_party) 
				{
					$this->db->where('transaction_party_id', $t_party['id']);
					$this->db->where('transaction_item_id', $tips);
					$this->db->where('audit', 0);
					$this->db->update('transaction_item_parties', array('complete'=>1));
				}
			}
		}


		// check if 'all_seller_signed'  signed 
		if($this->input->post('all_seller_signed' ))
		{
			foreach ($this->input->post('all_seller_signed' ) as $tips) 
			{
				// get transaction parites = to buyers id is 8 
				// get all transaction item parties where party = buyer -- above
				// and where transaction item id =
				// set to complete
				$this->db->where('transaction_id' , $_SESSION['transaction_id']);
				$this->db->where('(party=8 OR party=4)', NULL);  // 1 is buyer 3 is buyers agent
				$t_parties = $this->db->get('transaction_parties');
				foreach ($t_parties->result_array() as $t_party) 
				{
					$this->db->where('transaction_party_id', $t_party['id']);
					$this->db->where('transaction_item_id', $tips);
					$this->db->where('audit', 0);
					$this->db->update('transaction_item_parties', array('complete'=>1));
				}
			}
		}


		// check if 'all_signed'  signed 
		if($this->input->post('all_signed' ))
		{
			foreach ($this->input->post('all_signed' ) as $tips) 
			{
				// get transaction parites = to buyers id is 8 
				// get all transaction item parties where party = buyer -- above
				// and where transaction item id =
				// set to complete
				$this->db->where('transaction_id' , $_SESSION['transaction_id']);
				$t_parties = $this->db->get('transaction_parties');
				foreach ($t_parties->result_array() as $t_party) 
				{
					$this->db->where('transaction_party_id', $t_party['id']);
					$this->db->where('transaction_item_id', $tips);
					$this->db->where('audit', 0);
					$this->db->update('transaction_item_parties', array('complete'=>1));
				}
			}
		}
	}

	public function delete_item($table, $id)
	{
		$this->db->delete($table, array('id' => $id) );
	}

	private function get_parties_for_transaction_items_by_party($party_id)
	{
		$this->db->select('*, transaction_items.id AS id');
		$this->db->where('transaction_id', $_SESSION['transaction_id']);
		$this->db->where('party', $party_id);
		$this->db->join('item_parties', 'transaction_items.item_id=item_parties.item');
		$q = $this->db->get('transaction_items');
		return $q->result_array();
	}

	public function del_contact($id)
	{
		$this->db->delete('transaction_parties', array('id'=>$id));
	}

	public function get_due_transaction_items($item_type)
	{   
		$g = array();
		$i=0;
		$this->db->select('*, transaction_items.id AS id');
		$this->db->where('mailed', 1); // 1 = not mailed
		$this->db->where('type', $item_type);
		$today = array('due_date <=' => now());
		$this->db->join('items', 'items.id = transaction_items.item_id');
		$this->db->where($today);
		$this->db->where('transaction_id', 163); //$_SESSION['transaction_id']	
		$q = $this->db->get('transaction_items');
		$items = $q->result_array();
		// now for each item get the parites
		foreach ($items as $item) 
		{
			$g[$i]= $item;
			// get item parties
			$this->db->where('transaction_item_id', $item['id']);
			$ip = $this->db->get('transaction_item_parties');
			// echo "t party";
			foreach ($ip->result_array() as $party) 
			{
				// echo $party['transaction_party_id'] . "<br /> party ";
				// get transactino party info 
				$this->db->join('transaction_parties', 'transaction_parties.contact_id=contacts.id');
				$this->db->where('transaction_parties.id', $party['transaction_party_id']);
				$c = $this->db->get('contacts');
				$contact = $c->result_array();
				$g[$i]['contact'] = $contact;
			}
			// get transaction parties that match item parties
			$i++;
		}
		// if reminder set mailed to 2 = mailed
		return $g;
	}

	public function get_agents()
	{
		$this->db->join('contact_type', 'contact_type.contact_id=contacts.id');
		$this->db->where('type', 9); //9 is agent
		$q= $this->db->get('contacts');

		return $q->result_array();
	}

	public function apply_templates()
	{
		// get items for each template and add them to the transaction
		foreach ($this->input->post('templates') as $template) 
		{
			// get items for this template
			$this->db->where('template', $template);
			$q = $this->db->get('template_items');
			// now add each item to the transaction
			foreach ($q->result_array() as $item) 
			{
				$this->add_transaction_item($item['item']);
			}

		}
	}

	public function get_transaction_item_party_by_transaction_item_id($tid)
	{
		$this->db->where('transaction_item_id', $tid);
		$q = $this->db->get('transaction_item_parties');
		return $q->result_array();
	}

	public function get_contacts($first_name)
	{
		$this->db->like('first_name', $first_name);
		$q = $this->db->get('contacts');
		return $q->result_array();
	}

	public function get_transaction_contacts($id)
	{
		$this->db->select('*, transaction_parties.id AS id');
		$this->db->from('transaction_parties');
		$this->db->join('contacts', 'transaction_parties.contact_id = contacts.id');
		$this->db->where('transaction_parties.transaction_id', $id);
		$q = $this->db->get();
		return $q->result_array();
	}

	public function get_transaction_item_parties($transaction_item_id = NULL,$transaction_party_id = NULL , $date_type = NULL, $type = NULL, $complete = NULL, $queue = NULL)
	{
		$this->db->select('*, items.queue AS queue, items.heading AS heading, items.body AS body, transaction_item_parties.id AS id');
		$this->db->from('transaction_item_parties');
		$this->db->join('transaction_items', 'transaction_item_parties.transaction_item_id = transaction_items.id');
		$this->db->join('items', 'transaction_items.item_id = items.id');
		if($transaction_party_id != NULL){
			$this->db->where('transaction_item_parties.transaction_party_id', $transaction_party_id);
		}
		if($date_type != NULL){
			$this->db->where('items.date_type' , $date_type );
		}
		if($type != NULL){
			$this->db->where('items.item_type', $type);
		}
		if($queue != NULL){
			$this->db->where('items.queue', $queue);
		}
		if($complete != NULL){
			$this->db->where('transaction_item_parties.complete', $complete);
		}
		if($transaction_item_id != NULL){
			$this->db->where('transaction_item_parties.transaction_item_id' , $transaction_item_id );
		}
		$q= $this->db->get();
		return $q->result_array();
	}

	public function get_transaction_item_parties2($transaction_party_id, $complete){

		$this->db->where('transaction_party_id', $transaction_party_id);
		$this->db->where('complete', $complete);

		$q = $this->db->get('transaction_item_parties');
		return $q->result_array();
	}

	public function get_items_due($transaction_party_id, $complete){

		$tips = $this->get_transaction_item_parties2($transaction_party_id, $complete);

		// now find out which items are due
		foreach ($tips as $tip) {
			# code...get the due date
			$this->db->select('date_type, days');
			$this->db->join('transaction_items', 'transaction_items.item_id = items.id');
			$due_dates = $this->db->get('items');
		}
	}

	public function get_items_not_complete($tid, $party){

		$this->db->from('transaction_item_parties');
		$this->db->join('transaction_parties', 'transaction_parties.id = transaction_item_parties.transaction_party_id');
		$this->db->where('transaction_item_parties.complete', 0 );
		$this->db->where('transaction_item_parties.transaction_item_id', $tid);
		$this->db->where_not_in('transaction_parties.party', $party );
		$q = $this->db->get();
		return $q->result_array();
	}

	private function get_calendar_date($transaction_id, $date_type){

		// get calendar date for transaction to see if it's due

		$this->db->select('calendar_date');
		$this->db->where('transaction_dates.transaction',$transaction_id);
		$this->db->where('transaction_dates.date', $date_type);
		$sql = $this->db->get_compiled_select('transaction_dates');
		$sql1 = '('.$sql.')';

		return $sql1;
	}

	public function get_past_due_reminders($contact_id){

		$this->db->select('items.heading as heading, items.body as body, transaction_item_parties.id as id');
		$this->db->from('transaction_item_parties');
		$this->db->join('transaction_items', 'transaction_item_parties.transaction_item_id = transaction_items.id' );
		$this->db->join('items', 'transaction_items.item_id = items.id ' );
		$this->db->join('transaction_dates', 'transaction_dates.transaction = transaction_items.transaction_id' );
		$this->db->where('transaction_item_parties.complete', 0); // incomplete
		$this->db->where('transaction_item_parties.audit', 0); // not audit
		$this->db->where('items.item_type', 1); // select reminders only
		$this->db->where('transaction_item_parties.transaction_party_id', $contact_id);
		$where = "transaction_dates.date = items.date_type";
		$this->db->where($where);
		$this->db->where('curdate() > date_add(transaction_dates.calendar_date, interval items.days DAY)', NULL
			);
		
		$q=$this->db->get();
		return $q->result_array();
	}

	// public function get_past_due_forms($transaction_id){

	// 	$list = array();

	// 	// get all transaction dates
	// 	$dates = $this->get_transaction_dates2($transaction_id);
	// 	foreach ($dates as $date) {
	// 		# code...
	// 		// get calendar date
	// 		$sql1 = $this->get_calendar_date($transaction_id, $date['date_type']);

	// 		// get the forms
	// 		$this->db->select('transaction_items.id as tid, items.id as id, items.heading as heading, items.body as body ');
			
	// 		$this->db->from('transaction_item_parties');
			
	// 		$this->db->join('transaction_items', 'transaction_items.id = transaction_item_parties.transaction_item_id ');
	// 		$this->db->join('items', 'items.id = transaction_items.item_id');

	// 		$this->db->where('transaction_items.transaction_id', $transaction_id);
	// 		$this->db->where('transaction_item_parties.complete', 0);
	// 		$this->db->where('items.item_type', 2);
	// 		$this->db->where('transaction_item_parties.complete', 0);
	// 		$this->db->where($sql1. ' < date_sub(curdate(), interval days day)', NULL
	// 			);
	// 		// $this->db->group_by('tid');
	// 		$this->db->order_by('id', 'ASC');

	// 		$q = $this->db->get();

	// 		$list[] = $q->result_array();
	// 		echo $date['date_type'];
	// 		var_dump($list);
	// 	}


	public function get_past_due_forms($transaction_id){

		// get trasactin items that match date
		$this->db->select('transaction_items.id as id, items.heading as heading, items.body as body , 
transaction_dates.date as t_date, items.date_type as idt, transaction_dates.calendar_date as c_date, items.days as days');
		$this->db->from('transaction_dates');
		$this->db->join('transactions', 'transaction_dates.transaction = transactions.id');
		$this->db->join('transaction_items', 'transaction_items.transaction_id = transactions.id');
		$this->db->join('items', 'items.id = transaction_items.item_id');
		$this->db->where('transactions.id', $transaction_id);
		$this->db->where('items.item_type', 2);
		$where = "transaction_dates.date = items.date_type";
		$this->db->where($where);
		$this->db->where('curdate() > date_add(transaction_dates.calendar_date, interval items.days DAY)', NULL);
		$q = $this->db->get();
		$items= $q->result_array();
		
		return $items;
	}

	public function get_unsigned_parties($tid){
		// get unsigned parties by transaction item id tid
		$this->db->select('contacts.first_name as first_name, contacts.last_name AS last_name ');
		$this->db->from('transaction_item_parties');
		$this->db->join('transaction_parties', 'transaction_parties.id = transaction_item_parties.transaction_party_id');
		$this->db->join('contacts', 'contacts.id = transaction_parties.contact_id');
		$this->db->join('transaction_items', 'transaction_items.id = transaction_item_parties.transaction_item_id 
');
		$this->db->where('transaction_item_parties.complete', 0); //incomplete only
		$this->db->where('transaction_item_parties.audit', 0);
		$this->db->where('transaction_items.id', $tid);

		$q = $this->db->get();

		return $q->result_array();
	}

	public function set_items_to_complete($data){
		//
		foreach ($data as $tip) {
			# code...

			$ra = array('complete' => 1);
			$this->db->where('id', $tip['id']);
			$this->db->update('transaction_item_parties', $ra);
				
		}
	}

	public function get_party($transaction_id, $party){

		$this->db->select('contacts.first_name as first_name, contacts.last_name as last_name, contacts.email as email');
		$this->db->from('transaction_parties');
		$this->db->join('contacts', 'contacts.id = transaction_parties.contact_id');
		$this->db->where('transaction_parties.transaction_id', $transaction_id);
		$this->db->where('transaction_parties.party', $party);
		$q = $this->db->get();

		return $q->result_array();

	}
}