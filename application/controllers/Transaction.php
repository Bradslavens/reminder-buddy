<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends CI_Controller {

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

		$this->load->model('transactions_model');
		$this->load->model('user_model');
	}


	// home page
	public function index()
	{
		unset($_SESSION['transaction_id']);


		$this->load->view('templates/header');


			
		// load views 
		$this->load->view('transactions/home');

		// transaction list
		$this->load->model('transactions_model');
		// get transaction list FALSE IS FOR USER TRANSACTIONS COMING SOON
		$list = $this->transactions_model->get_item_by_id('transactions', array('status'=> 1));
		$data['transactions'] = $list;
		$this->load->view('transactions/transaction_list', $data);

		// }

		$this->load->view('templates/footer');
	}
}
