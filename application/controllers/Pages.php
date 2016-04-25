<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends MY_Controller {
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
		$this->load->model(['AdminCmspageModel']);
	}

	public function index()
	{
		
		$data = [];
		$segment = $this->uri->segment(1, 0);

		$pagecms = array('slug'=>$segment);
		$data['cmspage']  = $this->AdminCmspageModel->get($pagecms, ['id', 'title','content']);
		$data['page_title'] = 'TalentList - '.$data['cmspage']['title'];
		$data['page_header'] = $data['cmspage']['title'];

		//print_r($data);exit;
		//$data['categories'] = array_merge($categories, $dbCategories);
		$this->template->load($this->layout, 'front/pages/index',$data);
		//$this->load->view('');
	}
}
