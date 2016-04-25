<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends MY_Controller {

	protected $layout = 'admin_default';

	function __construct() {
		parent::__construct();
		$this->ensure_group(['admin']);
		$this->load->model(['AdminSettingModel']);
	}

	public function index() {
		$data = [];
		$data['page_title'] = 'Settings List';
		$data['page_header'] = 'Settings';
		$this->template->load($this->layout, 'admin/setting/index', $data);
	}

	public function get_ajax_page_listing() {
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
			         * you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array('id', 'name', 'value', 'created_at');

		//$extraColumns = array('name_id', 'category_name');

		// DB table to use
		$sTable = 'settings';
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
				$row[] = $aRow[$col];
			}
			$url = base_url() . 'admin/setting/edit/' . $aRow["id"];
			$row[] = '<td>'
			. '<div class="row"><div class="col-sm-6">'
			. '<a class="btn btn-primary btn-flat" href="javascript:void(0);" onclick=openEditPopup("' . $url . '")> <i class="fa fa-pencil"></i>Edit </a> '
			//. '<a class="btn btn-primary btn-flat" href="' . base_url() . 'admin/setting/edit/' . $aRow["id"] . '"> <i class="fa fa-pencil"></i>Edit </a> '
			 . '</div>'
				. '</td>';
			$output['aaData'][] = $row;
		}

		echo json_encode($output);
		die;
	}

	public function add() {
		$data = [];

		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules('value', 'Value', 'required|trim');
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			//$this->AdminCmspageModel->get_list([],['id','title','slug']);
			/*Validation part*/

			if ($this->form_validation->run() == TRUE) {

				$data = $this->input->post();
				$data['created_at'] = date('Y-m-d H:i:s');
				unset($data['submit']);
				if ($this->AdminSettingModel->insert($data)) {
					$this->data['errors'] = [];
					$this->data['success'] = true;
					$this->session->set_flashdata('success', 'Settings Add Successfully');
				} else {
					$this->session->set_flashdata('success', 'Setting was not Add successfully');
					$this->data['success'] = false;
				}
			} else {

				$this->data['errors'] = $this->form_validation->error_array();
				$this->data['success'] = false;
			}
			$this->_render_json();

		}

	}

	public function edit($id = NULL) {

		$data['result'] = $this->AdminSettingModel->get(['id' => $id]);

		//$this->template->load($this->layout, 'admin/setting/edit', $data);
		$this->load->view('admin/setting/edit', $data);
		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules('value', 'Value', 'required|trim');

		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			if ($this->form_validation->run() == TRUE) {

				$data = $this->input->post();
				unset($data['submit']);
				$cond = ['id' => $id];

				if ($this->AdminSettingModel->update($cond, $data)) {
					$this->data['errors'] = [];
					$this->data['success'] = true;
					$this->session->set_flashdata('success', 'Setting updated successfully');
				} else {
					$this->session->set_flashdata('success', 'Setting was not updated successfully');
					$this->data['success'] = false;
				}
			} else {
				$this->data['errors'] = $this->form_validation->error_array();
				$this->data['success'] = false;
			}
			$this->_render_json();
		}
	}

}
