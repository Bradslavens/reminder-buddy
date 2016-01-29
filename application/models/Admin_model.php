<?php
class Admin_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}

	// public function add_item()
	// {
	// 	$data = array
	// 	(
	// 		'heading' => $this->input->post('heading'),
	// 		'body' => $this->input->post('body'),
	// 		'date_type' => 1,
	// 		'days' => 5,
	// 		'default_queue' => 1,
	// 		'tc_id' => 1,
	// 		'type' => 1
	// 		);

	// 	$q = $this->db->insert('items',$data);

	// 	// now update the json file
	// 	// query all the items
	// 	// update json file

	// 	$result = $this->db->get('items');

	// 	$data = $result->result_array();

	// 	if ( ! write_file('assets/items/reminder_test.json', json_encode($data),'w'))
	// 		{
	// 		        echo 'Unable to write the file';
	// 		}
	// 		else
	// 		{
	// 		        echo 'File written!';
	// 		        $f = file_get_contents('assets/items/reminder_test.json');

	// 		        var_dump(json_decode($f), true);
	// 		}


	// }

	public function get_list($table, $where = FALSE, $order = FALSE)
	{
		// $table = "items";
		// $where = array('item_type' => 1);
		if($where !==FALSE)
		{
			$this->db->where($where);
		}
		$this->db->order_by($order, 'asc');
		$q = $this->db->get($table);
		return $q->result_array();
		// var_dump($q->result_array());
		// exit();
	}

	public function template_get_list($table, $where = FALSE)
	{
		$ids = $this->get_list($table, $where);

		$item_det = array();
		foreach ($ids as $dets) 
		{
			$item_det[] = $this->get_item_by_id('items', $dets['item']);
		}

		return $item_det;
	}
	
	private function insert_item_with_party()
	{
		// insert item 
		$table = "items";
		$data = array('heading'=> $this->input->post('heading'), 'body'=> $this->input->post('body'), 'date_type'=> $this->input->post('date_type'), 'days'=> $this->input->post('days'), 'queue'=> $this->input->post('queue'), 'item_type'=> $this->input->post('item_type'),'tc_id'=> $this->input->post('tc_id'));
		$this->db->insert($table, $data);
		$item = $this->db->insert_id();
		// insert parties
		foreach ($this->input->post('party') as $party) 
		{
			$data_p = array('party' => $party, 'item' => $item, 'side' => 1); // sides is in progress 
			$this->db->insert('item_parties', $data_p);
		}
	}

	private function insert_template()
	{
		$data = array('name'=> $this->input->post('name'), 'user_id' => $_SESSION['user_id'], );
		$this->db->insert('templates', $data);
		$id = $this->db->insert_id();
		foreach ($this->input->post('item') as $item)
		{
			$this->db->insert('template_items', array('template' => $id, 'item' => $item));
		}
	}

	public function insert_list_item($table)
	{
		// if it's a form or reminder with parties.. insert form or reminder insteatd
		if($this->input->post('party'))
		{
			$this->insert_item_with_party();
		}
		elseif($this->input->post('item'))
		{
			$this->insert_template();
		}
		else
		{
			$this->db->insert($table, $this->input->post());
		}

	}

	public function delete_list_item($table)
	{
		if($table == "forms"  || $table == "reminders")
		{
			$table = "items";
		}
		$this->db->delete($table, $this->input->post());
	}

	public function update_list_item($table)
	{
		if($table == "forms"  || $table == "reminders")
		{
			$table = "items";
		}
		
		$this->db->where('id', $this->input->post('id'));
		$this->db->update($table, $this->input->post());
	}

	public function get_item_list()
	{	$this->db->select('*, items.id AS id');
		$this->db->join('item_types', 'item_types.id = items.item_type');
		$q = $this->db->get('items');
		return $q->result_array();
	}

	public function get_item_by_id($table, $id)
	{
		$this->db->where('id', $id);
		$q= $this->db->get($table);
		return $q->result_array();
	} 

	public function insert_item($table, $cols)
	{
		$q = $this->db->insert($table, $cols);
		return $this->db->insert_id();
	}

	public function update_item($table, $cols, $where)
	{
		$this->db->where($where);
		$q = $this->db->update($table, $cols);
		return $this->db->affected_rows();
	}

	public function get_item_dets($item_id){

		$i['item_dets'] = $this->get_item_by_id('items', $item_id); // get item details

			$this->db->where('item', $item_id);
		$q =$this->db->get('item_parties');	// get item parties
		$i['curr_parties'] = $q->result_array();

		return $i;
	}

	public function update_item2(){
		// update item
		$this->db->where('id',$this->input->post('id'));
		$cols = array(
			'heading' => $this->input->post('heading') ,
			'body' =>$this->input->post('body'),
			'date_type' =>$this->input->post('date_type'),
			'days' =>$this->input->post('days'),
			'queue' =>$this->input->post('queue'),
			'tc_id' =>$this->input->post('tc_id'),
			'item_type' =>$this->input->post('item_type')
			);
 		$this->db->update('items', $cols);

 		// update parties
 		// first delete old item parties
 		$this->db->delete('item_parties', array('item' => $this->input->post('id'))); 
 		// now insert new item parties
 		foreach ($this->input->post('party') as $party) {
 			$cols1 = array(
 				'party' => $party,
 				'item' => $this->input->post('id'),
 				'side' => 3
 				);
 			$this->db->insert('item_parties', $cols1);
 			# code...
 		}

	}
}