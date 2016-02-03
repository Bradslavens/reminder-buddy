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
}