<?php
class Audit_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}

	public function create_audit_list($transaction_id, $aud_num){
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

	public function compare_audit($tid, $aud, $c_aud){

		// first get the audit list
		$this->db->select('transaction_item_parties.transaction_item_id as ti_id, transaction_item_parties.transaction_party_id as tp_id, transaction_item_parties.id as tip_id, transaction_item_parties.complete as tip_status, transaction_item_parties.audit as audit, transaction_items.id AS ti_id ');
		$this->db->from('transaction_item_parties');
		$this->db->join('transaction_items', 'transaction_items.id = transaction_item_parties.transaction_item_id ');
		$this->db->join('transactions', 'transactions.id = transaction_items.transaction_id ');
		$this->db->where('transactions.id', $tid);
		$this->db->where('transaction_item_parties.audit', $aud); // audit list
		$aud_list = $this->db->get();

		// now compare to the original list 

		foreach ($aud_list->result_array() as $al) {
			# code...
			$this->db->where('transaction_item_id', $al['ti_id']);
			$this->db->where('transaction_party_id', $al['tp_id']);
			$this->db->where('audit', $c_aud);
			$or_list = $this->db->get('transaction_item_parties');

			$orl = $or_list->result_array();
			if(isset($orl[0])){

				if($al['tip_status'] != $orl[0]['complete']){

					// print list of discrepencies
					$this->db->select('items.heading as heading, items.body as body, transaction_item_parties.complete as complete');
					$this->db->join('')//

					}

			}
		}

	}
}