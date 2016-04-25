<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Slider extends MY_Controller {

	protected $layout = 'admin_default';

	function __construct() {
		parent::__construct();
		$this->ensure_group(['admin']);
		$this->load->model(['AdminCmspageModel']);

	}

	public function index() {
		$data = [];
		$data['page_title'] = 'CMS Pages';
		$data['page_header'] = 'CMS Pages';
		$this->template->load($this->layout, 'admin/page/index', $data);
	}

	public function get_ajax_page_listing() {
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
			         * you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array('id', 'title', 'status', 'created_at');

		//$extraColumns = array('name_id', 'category_name');

		// DB table to use
		$sTable = 'cmspages';
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
						$status = $status . '<span class="label btn-success">Active</span>';
					} else {
						$status = $status . '<span class="label btn-danger">Inactive</span>';
					}
					$row[] = $status;
				} else {

					$row[] = $aRow[$col];
				}
			}
			$url = base_url() . 'admin/page/edit/' . $aRow["id"];

			$row[] = '<td>'
			. '<a class="btn btn-primary btn-flat" href="' . $url . '"> <i class="fa fa-pencil"></i>Edit </a> '
			. '<a class="btn btn-danger btn-flat deletebtn deleteLinkButton" data-target="#delete_confirm" data-toggle="modal" value="' . $aRow["id"] . '" id="' . $aRow["id"] . '"> Delete </a>'
			. '<span id="anchor_' . $aRow["id"] . '" style="display:none;">'
			. '<a href="' . base_url() . 'admin/page/delete/' . $aRow["id"] . '" class="btn btn-default">Delete</a>'
				. '</span>'
				. '</td>';

			$output['aaData'][] = $row;
		}

		echo json_encode($output);
		die;
	}

	public function add() {
		$data = [];
		$data['page_title'] = 'Add Page';
		$data['page_header'] = 'Add Page';

		$data['allPageDetail'] = $this->AdminCmspageModel->gets();

		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			/*Validation part*/
			$this->form_validation->set_rules('title', 'Page Title', 'required|trim');
			$this->form_validation->set_rules('content', 'Page Content', 'required|trim');

			if ($this->form_validation->run() == TRUE) {

				$insertData['title'] = $this->input->post('title');
				$insertData['slug'] = $this->input->post('slug');
				$insertData['content'] = $this->input->post('content');
				$insertData['status'] = $this->input->post('status');
				$insertData['created_at'] = date('Y-m-d h:i:s');

				$q = $this->AdminCmspageModel->count(['slug' => $this->input->post('slug')]);
				if ($q > 0) {
					$this->session->set_flashdata('errors', 'Duplicate Page Is Not Valid.');
				} else {

					$this->AdminCmspageModel->insert($insertData);
					$this->session->set_flashdata('success', 'Page added successfully.');
					redirect(base_url() . 'admin/page/index');exit;
				}
			} else {

				$this->session->set_flashdata('errors', validation_errors());
				redirect(base_url() . 'admin/page/add');exit;

			}
		}

		$this->template->load($this->layout, 'admin/page/add', $data);
	}

	public function edit($id = NULL) {
		$data['page_title'] = 'CMS Pages';
		$data['page_header'] = 'CMS Pages';

		$data['result'] = $this->AdminCmspageModel->get(['id' => $id]);
		$this->template->load($this->layout, 'admin/page/edit', $data);

//	if ($this->input->server('REQUEST_METHOD') == 'POST') {
		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$this->form_validation->set_rules('title', 'Page Title', 'required|trim');
			$this->form_validation->set_rules('content', 'Page Content', 'required|trim');

			if ($this->form_validation->run() == TRUE) {

				$data = array(
					'title' => $this->input->post('title'),
					'slug' => $this->input->post('slug'),
					'content' => $this->input->post('content'),
					'status' => $this->input->post('status'),
					'updated_at' => date('Y-m-d h:i:s'),
				);
				$this->AdminCmspageModel->update(['id' => $this->input->post('id')], $data);
				$this->session->set_flashdata('success', 'Page Update successfully.');
				redirect(base_url() . 'admin/page/index');exit;

			} else {

				$this->session->set_flashdata('errors', validation_errors());
				redirect(base_url() . 'admin/page/edit');exit;

			}

		}

	}

	public function delete($id = NULL) {
		$data['DeleteCmspage'] = $this->AdminCmspageModel->get(['id' => $id]);

		$this->AdminCmspageModel->delete(['id' => $id]);
		$this->session->set_flashdata('success', 'Page deleted Successfully');
		redirect(base_url() . 'admin/page/index');exit;

	}
}
