<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller 
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		// check login
		if($_SESSION['is_logged_in'] !== TRUE)
		{
			redirect('welcome/index');
		}
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('admin_model');
	}

	public function index($page = "home")
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		// add a list item
		if($this->uri->segment(4) == "add_item")
		{
			$this->form_validation->set_rules('first_name', 'First Name', 'alpha');
			$this->form_validation->set_rules('last_name', 'Last Name', 'alpha');
			$this->form_validation->set_rules('email', 'Email', 'valid_email');
			$this->form_validation->set_rules('date', 'Date', 'alpha');

			if ($this->form_validation->run() !== FALSE)
			{
				$this->admin_model->insert_list_item($page);
			}
		}

		// delete an item
		if($this->uri->segment(4) == "del_item")
		{
			$this->admin_model->delete_list_item($page);
		}

		// update an item
		if($this->uri->segment(4) == "update_item")
		{
			$this->admin_model->update_list_item($page);
		}

		// handle page loads and lists
		$data['title'] = ucfirst($page);
		$this->load->view('templates/header');

		// home page 
		if($page == "home")
		{
			$this->load->view('admin/nav_home', $data);
		}
		else
		{
			$this->load->view('admin/nav', $data);
			$this->load->view('admin/list_top', $data);
		}
			// enter individual pages 
			// contacts all
		if($page == "contacts")
		{
			if($this->uri->segment(4) == "edit")
			{
				$data['contact'] = $this->admin_model->get_item_by_id('contacts',$this->uri->segment(5));
				$this->load->view('admin/contact_form', $data);
			}
			else
			{
				$this->load->view('admin/contact_form');
			}
			// if($this->uri->segment(4) == "del")
			// {
			// 	$this->admin_model->delete_item();
			// }
			$data['contacts'] = $this->admin_model->get_list('contacts');
			$this->load->view('admin/contacts', $data);
		}

			// date types
		if($page == "date_types")
		{
			$this->load->view('admin/date_type_form', $data);
			$data['date_types'] = $this->admin_model->get_list('date_types');
			$this->load->view('admin/date_types', $data);
		}

			// forms
		if($page == "forms")
		{
			//get date types
			$data['date_types'] = $this->admin_model->get_list("date_types");
			// //get queues
			// $data['queues'] = $this->admin_model->get_list("queues");
			//get item types
			$data['item_types'] = $this->admin_model->get_list("item_types");
			//get parties
			$data['parties'] = $this->admin_model->get_list("parties");
			//get sides
			$data['sides'] = $this->admin_model->get_list("sides");

			$this->load->view('admin/forms_form', $data);
			$data['forms'] = $this->admin_model->get_list('items', array('item_type' => 2), 'heading'); // two is forms
			$this->load->view('admin/forms', $data);
		}

			// reminders
		if($page == "reminders")
		{
			//get date types
			$data['date_types'] = $this->admin_model->get_list("date_types");
			// //get queues
			// $data['queues'] = $this->admin_model->get_list("queues");
			//get item types
			$data['item_types'] = $this->admin_model->get_list("item_types");
			//get parties
			$data['parties'] = $this->admin_model->get_list("parties");
			//get sides
			$data['sides'] = $this->admin_model->get_list("sides");

			// load views
			$this->load->view('admin/reminder_form', $data);
			$data['reminders'] = $this->admin_model->get_list('items', array('item_type' => 1), 'heading'); // one is reminders
			$this->load->view('admin/reminders', $data);
		}
			// item types
		if($page == "item_types")
		{
			$this->load->view('admin/item_types_form', $data);
			$data['item_types'] = $this->admin_model->get_list('item_types');
			$this->load->view('admin/item_types', $data);
		}
		    // parties
		if($page == "parties")
		{
			$this->load->view('admin/parties_form', $data);
			$data['parties'] = $this->admin_model->get_list('parties');
			$this->load->view('admin/parties', $data);
		}
			// queues
		if($page == "queues")
		{
			$this->load->view('admin/queues_form', $data);
			$data['queues'] = $this->admin_model->get_list('queues');
			$this->load->view('admin/queues', $data);
		}
		 	// sides
		if($page == "sides")
		{
			$this->load->view('admin/sides_form', $data);
			$data['sides'] = $this->admin_model->get_list('sides');
			$this->load->view('admin/sides', $data);
		}
			// templates
		if($page == "templates")
		{
			$data['items'] = $this->admin_model->get_item_list('items');
			$this->load->view('admin/templates_form', $data);
			$data['templates'] = $this->admin_model->get_list('templates');
			$this->load->view('admin/templates', $data);
		}
			// templates
		if($page == "edit_template")
		{
			if($this->uri->segment(4) == "add_template_item")
			{
				// add item
				$this->admin_model->insert_item('template_items', array('template' => $this->uri->segment(5), 'item' => $this->input->post('item')));
				// update template name
				$this->admin_model->update_item('templates', array('name' => $this->input->post('name')), array('id' => $this->uri->segment(5)));
				
				// load existing template items
				// get template NAME for form
				$data['template'] = $this->admin_model->get_item_by_id('templates', $this->uri->segment(5));
				// get all items for form
				$data['items'] = $this->admin_model->get_item_list('items');
				// get template items
				$data['current_items'] = $this->admin_model->template_get_list('template_items', array('template' => $this->uri->segment(5) ));
				$this->load->view('admin/edit_template', $data);
			}
			else
			{
				// load existing template items
				// get template NAME for form
				$data['template'] = $this->admin_model->get_item_by_id('templates', $this->uri->segment(4));
				// get all items for form
				$data['items'] = $this->admin_model->get_item_list('items');
				// get template items
				$data['current_items'] = $this->admin_model->template_get_list('template_items', array('template' => $this->uri->segment(4) ));
				$this->load->view('admin/edit_template', $data);

			}
		}
			// template_types
		if($page == "template_types")
		{
			$this->load->view('admin/template_types_form', $data);
			$data['template_types'] = $this->admin_model->get_list('template_types');
			$this->load->view('admin/template_types', $data);
		}
			// transaction status
		if($page == "transaction_status")
		{
			$this->load->view('admin/transaction_status_form', $data);
			$data['transaction_status'] = $this->admin_model->get_list('transaction_status');
			$this->load->view('admin/transaction_status', $data);
		}

		if($page !== "home")
		{
			$this->load->view('admin/list_bottom', $data);
		}
		$this->load->view('templates/footer', $data);
	}

	public function edit_item($item = NULL){
		
		if($this->input->post()){
			// update item
			$this->admin_model->update_item2();

			// get item info
			$data['item_dets'] =	$this->admin_model->get_item_dets($this->input->post('id'));
			$data['date_types'] = $this->admin_model->get_list('date_types');
			$data['parties'] = $this->admin_model->get_list('parties');
		}

		if($item !== NULL){
			// get item info
		$data['item_dets'] =	$this->admin_model->get_item_dets($item);
		$data['date_types'] = $this->admin_model->get_list('date_types');
		$data['parties'] = $this->admin_model->get_list('parties');

		}

		$this->load->view('templates/header');
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->load->view('admin/edit_item', $data);

		$this->load->view('templates/header');

	}
}
