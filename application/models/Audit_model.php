<?php
class Audit_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}

	private $new_audit_number;
	private $item_id;

	public function create_audit_list($transaction_id, $aud_num = 0){
		// get transaction item parties
		$this->db->select('transaction_item_parties.transaction_item_id AS item_id, transaction_item_parties.transaction_party_id AS party_id, 
	transaction_item_parties.complete AS complete , transaction_item_parties.audit AS audit ');
		$this->db->join('transaction_parties', 'transaction_parties.transaction_id = transactions.id');
		$this->db->join('transaction_item_parties', 'transaction_item_parties.transaction_party_id = transaction_parties.id');
		$this->db->where('transactions.id', $transaction_id);
		$this->db->where('transaction_item_parties.audit', 0);

		$cur_items = $this->db->get('transactions');
		// duplicate transaction item parties
		foreach ($cur_items->result_array() as $cur_item) {
			# code...
			$cis = array(
				'transaction_item_id' => $cur_item['item_id'],
				'transaction_party_id' => $cur_item['party_id'],
				'complete' => 0,
				'audit' => $aud_num
				);
			$this->db->insert('transaction_item_parties', $cis);
		}

		// make audit the number of the audit
	}

	public function get_transactions($status){

		$this->db->where('status', $status);
		$q =$this->db->get('transactions');
		return $q->result_array();
	}

	public function compare_audit($tid){ 
		$discrepencies['tips'] = array();
		$discrepencies['forms'] = array();
		// get current audit number 
		// first get the audit list compare audit to original
		$this->db->select('transaction_item_parties.transaction_item_id as ti_id, transaction_item_parties.transaction_party_id as tp_id, transaction_item_parties.id as tip_id, transaction_item_parties.complete as complete, transaction_item_parties.audit as audit, transaction_items.id AS ti_id ');
		$this->db->from('transaction_item_parties');
		$this->db->join('transaction_items', 'transaction_items.id = transaction_item_parties.transaction_item_id ');
		$this->db->join('transactions', 'transactions.id = transaction_items.transaction_id ');
		$this->db->where('transactions.id', $tid);
		$this->db->where('transaction_item_parties.audit', $this->session->userdata('aud_number')); // audit list

		$q1 = $this->db->get();

		$aud_list = $q1->result_array();

		// now compare to the original list 

		foreach ($aud_list as $al) {

			# code...
			// get matching original to compare
			$this->db->where('transaction_item_id', $al['ti_id']);
			$this->db->where('transaction_party_id', $al['tp_id']);
			$this->db->where('audit', 0); // original aud list 
			$or_list = $this->db->get('transaction_item_parties');

			$orl = $or_list->result_array();


			if(isset($orl[0])){
				if($al['complete'] != $orl[0]['complete']){
					// // print list of discrepencies
					$this->db->select('transaction_item_parties.id as tip_id, transaction_item_parties.audit as audit, items.heading as heading, 
items.body as body, contacts.first_name as first_name, contacts.last_name as last_name
');
					$this->db->from('transaction_item_parties');
					$this->db->join('transaction_items', 'transaction_item_parties.transaction_item_id = transaction_items.id');
					$this->db->join('items', 'items.id = transaction_items.item_id');
					$this->db->join('transaction_parties', 'transaction_item_parties.transaction_party_id= transaction_parties.id');
					$this->db->join('contacts', 'transaction_parties.contact_id=contacts.id
');
					$this->db->where('transaction_item_parties.id', $al['tip_id']);
					$this->db->where('items.item_type', 2); // forms only

					$q2=$this->db->get();
					$discrepencies['tips'][] = $q2->result_array();
				} // end if compare

			}else{
				$discrepencies['forms'][] = $al;
			}
		}

		// now compare original to audit.. all we need are the missing forms.
		// get tranaction items where aud = 0
					$this->db->select('transaction_items.id as tid, transaction_item_parties.audit as audit, items.heading as heading, items.body as body');
					$this->db->from('transaction_item_parties');
					$this->db->join('transaction_items', 'transaction_item_parties.transaction_item_id = transaction_items.id');
					$this->db->join('items', 'items.id = transaction_items.item_id');
					$this->db->where('audit', 0); // original
					$this->db->where('transaction_items.transaction_id', $tid); // original
					$this->db->where('items.item_type', 2); // forms only

					$q=$this->db->get();
					$original_list = $q->result_array();
					// echo "orig list";
					// var_dump($q->result_array());

		// compare to aud if empty, add form to list
						foreach ($original_list as $original_item) {
							# code...
						$this->db->select('transaction_items.id as tid');
						$this->db->from('transaction_item_parties');
						$this->db->join('transaction_items', 'transaction_item_parties.transaction_item_id = transaction_items.id');
						$this->db->where('audit', $this->session->userdata('aud_number')); // aud // change to audit number variable
						$this->db->where('transaction_items.id', $original_item['tid']); // original tid

						$q3=$this->db->get();
						$audit_list = $q3->result_array();
						// echo "audit list ";
						// var_dump($q3->result_array());
						if(empty($audit_list)){
							$discrepencies['forms'][] = $original_item;
						}

					}

		return $discrepencies;

	}

	private function get_current_audit_number($transaction_id){
		//
		$this->db->select('aud_number');
		$this->db->from('transactions');
		$this->db->where('transactions.id', $transaction_id); 

		$q=$this->db->get();

	 	if ($q->num_rows() > 0){
	    	$row = $q->row(); 
	    	return $row->aud_number;
	 	} 

	}

	public function get_new_audit($transaction_id){
		// get current audit
		$old_audit_number = $this->get_current_audit_number($transaction_id);

    	// update audit number
    	$this->new_audit_number = $old_audit_number +1;

		$this->db->where('id', $transaction_id);
		$this->db->update('transactions', array('aud_number' => $this->new_audit_number)); 

    	return $this->new_audit_number;
	}

	public function delete_aud_item($transaction_item_id, $aud_number){
		// get tips for item where audit
		$this->db->where('transaction_item_id', $transaction_item_id);
		$this->db->where('audit', $aud_number);
		$q =	$this->db->get('transaction_item_parties');

		// delete tips
		foreach ($q->result_array() as $tip) {
			$this->db->where('id',$tip['id']);
			$this->db->delete('transaction_item_parties');
		}
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

		$this->add_transaction_item_parties($this->new_audit_number);
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
			$this->db->where('audit', $this->session->userdata('aud_number'));
			$data_reset = array('complete' => 0);
			$this->db->update('transaction_item_parties', $data_reset);
		}

		// foreach ($this->input->post('notes') as $n_id => $note) 
		// {
		// 	$this->db->where('id', $n_id);
		// 	$this->db->update('transaction_items', array('notes' =>  $note));
		// }

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
					$this->db->where('audit', $this->session->userdata('aud_number'));
					$this->db->update('transaction_item_parties', array('complete'=>1));
				}
			}
		}

		// check if 'all_seller_signed'  signed 
		if($this->input->post('all_seller_signed' ))
		{
			foreach ($this->input->post('all_seller_signed' ) as $tips) 
			{
				// get transaction parites = to buyers id is 1 
				// get all transaction item parties where party = buyer -- above
				// and where transaction item id =
				// set to complete
				$this->db->where('transaction_id' , $_SESSION['transaction_id']);
				$this->db->where('(party=4 OR party=8)', NULL);  // 4 sellers 8 sellers agent
				$t_parties = $this->db->get('transaction_parties');
				foreach ($t_parties->result_array() as $t_party) 
				{
					$this->db->where('transaction_party_id', $t_party['id']);
					$this->db->where('transaction_item_id', $tips);
					$this->db->where('audit', $this->session->userdata('aud_number'));
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
					$this->db->where('audit',  $this->session->userdata('aud_number'));
					$this->db->update('transaction_item_parties', array('complete'=>1));
				}
			}
		}


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
					$this->add_tip($this->transaction_item_id, $transaction_parties['id'], $this->new_audit_number);
				}

			}
		}
	}

	public function get_transaction_items($type, $transaction_id, $aud) // 2 is form 1 is reminder
	{
		// first get all the items
		$this->db->select('transaction_items.id AS id, items.queue AS queue_id, queues.name AS queue, items.heading AS heading, items.body AS body, notes');
		$this->db->join('items', 'items.id = transaction_items.item_id');
		$this->db->join('queues', 'queues.id = transaction_items.queue');
		$this->db->where('transaction_id', $transaction_id);
		$this->db->where('type', $type);
		$this->db->from('transaction_items');
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

}