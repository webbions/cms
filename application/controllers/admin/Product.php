<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends MY_Controller {

	protected $layout = 'admin_default';

	function __construct() {
		parent::__construct();
		$this->ensure_group(['admin']);
		$this->load->model(['InvitationsModel']);
		$this->load->helper('url');
	}

	public function index() {
		$data = [];
		$data['page_title'] = 'Pre-Registrations';
		$data['page_header'] = 'Pre-Registrations';
		$this->template->load($this->layout, 'admin/pregistrations/index', $data);
	}

	public function get_ajax_page_listing(){
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
			         * you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array('id', 'first_name', 'last_name','company_name','email','status', 'created_at');

		//$extraColumns = array('name_id', 'category_name');

		// DB table to use
		$sTable = 'invitations';
		//
		$iDisplayStart = $this->input->post('iDisplayStart', true);
		$iDisplayLength = $this->input->post('iDisplayLength', true);
		$iSortCol_0 = $this->input->post('iSortCol_0', true);
		$iSortingCols = $this->input->post('iSortingCols', true);
		$sSearch = $this->input->post('sSearch', true);
		$sEcho = $this->input->post('sEcho', true);

		// Paging
		if (isset($iDisplayStart) && $iDisplayLength != '-1') {
			$this->db->limit($this->db->escape_str($iDisplayLength), $this->db->escape_str($iDisplayStart));
		}

		// Ordering
		if (isset($iSortCol_0)) {
			for ($i = 0; $i < intval($iSortingCols); $i++) {
				$iSortCol = $this->input->post('iSortCol_' . $i, true);
				$bSortable = $this->input->post('bSortable_' . intval($iSortCol), true);
				$sSortDir = $this->input->post('sSortDir_' . $i, true);

				if ($bSortable == 'true') {
					$this->db->order_by($aColumns[intval($this->db->escape_str($iSortCol))], $this->db->escape_str($sSortDir));
				}
			}
		}

		/*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
		*/

		for ($i = 0; $i < count($aColumns); $i++) {
			$aColumns[$i] = "`" . $aColumns[$i] . "`";
		}

		// Searching
		// i need to define table types, date time cant be like
		$like = array('1');

		for ($i = 0; $i < count($aColumns); $i++) {
			$iSearchCol = $this->input->post('sSearch_' . $i, true);
			$bSearchable = $this->input->post('bSearchable_' . $i, true);
			if (isset($bSearchable) && $bSearchable == 'true') {

				if (!empty($iSearchCol)) {
					if (in_array($i, $like)) {
						$this->db->like($aColumns[$i], $this->db->escape_like_str($iSearchCol));
					} else {
						$this->db->where($aColumns[$i], $this->db->escape_like_str($iSearchCol));
					}
				}
			}
		}

		if (isset($sSearch) && !empty($sSearch)) {
			for ($i = 0; $i < count($aColumns); $i++) {
				$bSearchable = $this->input->post('bSearchable_' . $i, true);

				// Individual column filtering
				if (isset($bSearchable) && $bSearchable == 'true') {
					$this->db->or_like($aColumns[$i], $this->db->escape_like_str($sSearch));
				}
			}
		}
		// Select Data
		if (isset($extraColumns) && !empty($extraColumns)) {
			$this->db->select('SQL_CALC_FOUND_ROWS ' . str_replace(' , ', ' ', implode(', ', $extraColumns)) . ', ' . str_replace(' , ', ' ', implode(', ', $aColumns)), false);
		} else {
			$this->db->select('SQL_CALC_FOUND_ROWS ' . str_replace(' , ', ' ', implode(', ', $aColumns)), false);
		}
		$rResult = $this->db->get($sTable);
		$where_statment = null;
		$last_query = $this->db->last_query();
		preg_match('/' . preg_quote('WHERE') . '(.*?)' . preg_quote('LIMIT') . '/is', $last_query, $match);
		if (isset($match[1])) {
			$where_statment = $match[1];
		}

		/*
          // Clear any existing output (optional)
          ob_clean();
          echo $this->db->last_query();
          // Stop PHP from doing anything else (optional)
          exit();
		*/

		// Data set length after filtering
		$this->db->select('FOUND_ROWS() AS found_rows');
		$iFilteredTotal = $this->db->get()->row()->found_rows;

		// Total data set length
		$iTotal = $this->db->count_all($sTable);

		// Output
		$output = array(
			'sEcho' => intval($sEcho),
			'iTotalRecords' => $iTotal,
			'iTotalDisplayRecords' => $iFilteredTotal,
			'whereStatement' => base64_encode($where_statment),
			'aaData' => array(),
		);

		for ($i = 0; $i < count($aColumns); $i++) {
			$aColumns[$i] = str_replace("`", "", $aColumns[$i]);
		}

		foreach ($rResult->result_array() as $aRow) {
			$row = array();

			foreach ($aColumns as $col) {
				if ($col == 'created_at') {
					$created_at = "";
					//$created_at = humanTiming(strtotime($aRow[$col]));
					$created_at = date("F j, Y, g:i a", strtotime($aRow[$col]));
					$row[] = $created_at;
				} else if ($col == 'status') {
					$status = "";
					if ($aRow[$col] == '1') {
						$status = $status . '<span class="label btn-success">Accepted</span>';
					} else {
						$status = $status . '<span class="label btn-warning">Invite Sent</span>';
					}
					$row[] = $status;
				} else {

					$row[] = $aRow[$col];
				}
			}
			$url = base_url() . 'admin/category/edit/' . $aRow["id"];
			$row[] = '<td>'
			. '<div class="row"><div class="col-sm-9">'
			. '<a class="btn btn-danger btn-flat deletebtn deleteLinkButton" data-target="#delete_confirm" data-toggle="modal" value="' . $aRow["id"] . '" id="' . $aRow["id"] . '"> Delete </a>'
			. '<span id="anchor_' . $aRow["id"] . '" style="display:none;">'
			. '<a href="' . base_url() . 'admin/pregistration/delete/' . $aRow["id"] . '" class="btn btn-default">Delete</a>'
			. '</span>'
			. '</div>'
			. '</td>';
			$output['aaData'][] = $row;
		}

		echo json_encode($output);
		die;	
	}

	public function emailtest(){
		$emailData['first_name'] = "Mrudul Shah"; 
		$body = $this->load->view('/emails/invite_template',$emailData,TRUE);

		/*Send email*/
		$this->load->library('email');

		//pr($this->email);die;

		$this->email->from('mrudul@webbions.com', 'Talentslist');
		$this->email->to("mrudul.ce@gmail.com");
		$this->email->subject('Talentslist Invitation');
		$this->email->message($body);
		$result = $this->email->send();

		var_dump($result); echo '<br />';
		echo $this->email->print_debugger();die;
	}

	public function add(){
		$data = [];

		$this->form_validation->set_rules('first_name', 'First Name', 'required|trim');
		$this->form_validation->set_rules('last_name', 'Parent Category', 'required|trim');
		$this->form_validation->set_rules('company_name', 'Company Name', 'required|trim');
		$this->form_validation->set_rules('email', 'Email', 'is_unique[invitations.email]required|trim');
		
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			/*Validation part*/
			if ($this->form_validation->run() == TRUE) {

				$data = $this->input->post();
				$data['created_at'] = date('Y-m-d H:i:s');
				$data['status'] = 0;
				unset($data['submit']);

				if ($this->InvitationsModel->insert($data)) {

					$email = $this->input->post('email');
					$emailData = []; 
					$emailData['name'] = $data['first_name']." ".$data['last_name']; 
					$emailData['id'] = base64_encode($email); 

					$body = $this->load->view('/emails/new_invitation',$emailData,TRUE);

					/*Send email*/
					$this->load->library('email');

					$this->email->from('donotreply@talentslist.com', 'Talentslist');
					$this->email->to($email);
					$this->email->subject('Talentslist Invitation');
					$this->email->message($body);
					$result = $this->email->send();

					/*$result = $this->email
						->from('webbionstest@gmail.com', 'Talentslist')
						->to($email)
						->subject('Talentslist Invitation')
						->message($body)
						->send();*/

					/*var_dump($result); echo '<br />';
   					echo $this->email->print_debugger();die;*/
   					if(!$result){
   						$this->session->set_flashdata('error', 'Email Send Failed');
						$this->data['success'] = false;
   					}else{

						$this->data['errors'] = [];
						$this->data['success'] = true;
						$this->session->set_flashdata('success', 'Invitation send successfully');
   					}
				} else {
					$this->session->set_flashdata('success', 'Invitation was not sent successfully');
					$this->data['success'] = false;
				}
			} else {

				$this->data['errors'] = $this->form_validation->error_array();
				$this->data['success'] = false;
			}
			$this->_render_json();

		}

	}

	public function delete($id = NULL) {
		$this->InvitationsModel->delete(['id' => $id]);
		$this->session->set_flashdata('success', 'Invitation deleted Successfully');
		redirect(base_url() . 'admin/pregistration/index');exit;

	}

}
