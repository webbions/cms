<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {
		protected $layout = 'front_master';
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
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct() {
		parent::__construct();
		//$this->ensure_group(['admin']);
		$this->load->model(['AdminCategoryModel']);
		$this->load->library('HybridAuthLib');
	}


	public function index()
	{
		// For Social Login
		if( isset( $_SESSION['HA::STORE'] ) ){
			// if any social session register
			$user_group_sess = $this->session->userdata('user_group_sess');
			redirect( base_url() . "user/register_social/" . $user_group_sess['provider']);exit;
		}
		$data = [];
		$data['page_title'] = 'Home';
		$data['page_header'] = 'Home';
		$categories = ['Top Level'];
		$data['categories']  = $this->AdminCategoryModel->all();
		//$data['categories'] = array_merge($categories, $dbCategories);
		$this->template->load($this->layout, 'front/home/index',$data);
		//$this->load->view('');
	}
}
