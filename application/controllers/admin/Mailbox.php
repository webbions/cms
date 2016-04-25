<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Mailbox extends MY_Controller {

	protected $layout = 'admin_default';

	function __construct() {
		parent::__construct();
		$this->ensure_group(['admin']);
		$this->load->model(['AdminUserModel']);
		/*Load the private messaging library*/
		$this->load->library('mahana_messaging');
	}

	public function inbox() {
		$data = [];
		$data['page_title'] = 'Inbox';
		$data['page_header'] = 'Mailbox';
		$data['allMembers'] = $this->AdminUserModel->getUserByGroup(MEMBER_GROUP);
		
		/*Get all messages by Admin*/
		$limit = ' LIMIT 0,1';
		$data['TotalThread'] = $this->db->count_all('msg_threads');
		//$allThreadsCount = $this->mahana_messaging->get_all_threads_grouped($this->session->userdata['user_id'],FALSE, 'DESC');
		//echo count($allThreadsCount);exit;
		$allThreads = $this->mahana_messaging->get_all_threads_grouped($this->session->userdata['user_id'],FALSE, 'DESC',$limit);
		$data['allThreads'] = $allThreads['retval'];
		//die;
		//pr($data['allThreads']);exit;
		$this->template->load($this->layout, 'admin/mailbox/inbox', $data);

	}
	public function messagePaginationajax() {
		$data = [];
		
		$limitpage = $this->input->post('limit');
		$startpage = $this->input->post('start');
		$pagestartlimit = ' LIMIT '.$startpage.','.$limitpage;
		$data['allMembers'] = $this->AdminUserModel->getUserByGroup(MEMBER_GROUP);
		/*Get all messages by Admin*/
		$allThreads = $this->mahana_messaging->get_all_threads_grouped($this->session->userdata['user_id'],FALSE, 'DESC',$pagestartlimit);
		$data['allThreads'] = $allThreads['retval'];
		
		$html = '';
		if(count($data['allThreads']) > 0)
		{
			foreach ($data['allThreads'] as $key => $value) { 
				$html .='<tr>';
				$html .='<td><input type="checkbox"></td>';
				$html .='<td class="mailbox-name">';
				$html .='<a data-target="#modal_name_form1" data-toggle="modal" href="javascript:void(0);" onclick=openEditPopup("'.base_url('admin/mailbox/messageDetails/'.$value['thread_id']).'") class="">'.$value['messages'][0]['user_name'].'</a>';
				$html.='</td>';
				$html.='<td class="mailbox-subject"><b>'.$value['messages'][0]['subject'].'</b></td>'; 
				$html.='<td class="mailbox-attachment"></td>';
				$html.='<td class="mailbox-date">'.humanTiming(strtotime($value['messages'][0]['cdate'])).'ago</td>';
				$html.='</tr>'; 
				    
			
			} //foreach
		}
		else
		{
			$html = 'Error';  
		}
		echo $html;
	}

	public function compose() {
		$data = [];

		$this->form_validation->set_rules('to', 'Recipient', 'required');
		$this->form_validation->set_rules('subject', 'Subject', 'required|trim');
		$this->form_validation->set_rules('content', 'Message Content', 'required');
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			/*Validation part*/

			if ($this->form_validation->run() == TRUE) {

				$data = $this->input->post();

				/*Prepare the message details*/
				$body = $data['content'];
				$subject = $data['subject'];
				$from = $this->session->userdata['user_id'];
				$to = $data['to'];

				//$data['msg'] = $this->mahana_messaging->send_new_message($from, [$to], $subject, $body, 4);

				if ($this->mahana_messaging->send_new_message($from, [$to], $subject, $body, 4)) {
					$this->data['errors'] = [];
					$this->data['success'] = true;
					$this->session->set_flashdata('success', 'Message sent successfully');
				} else {
					$this->session->set_flashdata('success', 'There is some issue in sending message.');
					$this->data['success'] = false;
				}
			} else {

				$this->data['errors'] = $this->form_validation->error_array();
				$this->data['success'] = false;
			}
			$this->_render_json();

		}
	}
	public function get_ajax_page_listing() {
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
			         * you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array('id', 'first_name', 'last_name', 'email');

		//$extraColumns = array('name_id', 'category_name');

		// DB table to use
		$sTable = 'users';
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
			'iTotalRecords' => $iTotal-1,
			'iTotalDisplayRecords' => $iFilteredTotal-1,
			'whereStatement' => base64_encode($where_statment),
			'aaData' => array(),
		);

		for ($i = 0; $i < count($aColumns); $i++) {
			$aColumns[$i] = str_replace("`", "", $aColumns[$i]);
		}

		foreach ($rResult->result_array() as $aRow) {
			if($aRow['id'] == 1) { continue;} 
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
			$url = base_url() . 'admin/user/edit/' . $aRow["id"];
			$row[] = '<td>'
			. '<div class="row"><div class="col-sm-9">'
			. '<a class="btn btn-primary btn-flat" href="javascript:void(0);" onclick=openEditPopup("' . $url . '")> <i class="fa fa-pencil"></i>Edit </a> '
			. '<a class="btn btn-danger btn-flat deletebtn deleteLinkButton" data-target="#delete_confirm" data-toggle="modal" value="' . $aRow["id"] . '" id="' . $aRow["id"] . '"> Delete </a>'
			. '<span id="anchor_' . $aRow["id"] . '" style="display:none;">'
			. '<a href="' . base_url() . 'admin/user/delete/' . $aRow["id"] . '" class="btn btn-default">Delete</a>'
			. '</span>'
			//. '<a class="btn btn-primary btn-flat" href="' . base_url() . 'admin/setting/edit/' . $aRow["id"] . '"> <i class="fa fa-pencil"></i>Edit </a> '
			 . '</div>'
				. '</td>';
			$output['aaData'][] = $row;
		}

		echo json_encode($output);
		die;
	}
	public function reply($msg_id = ""){
		$data = [];
		$this->form_validation->set_rules('content1', 'Message Content', 'required');
		
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			
			if ($this->form_validation->run() == TRUE) {
				
				//echo 'msgid is :'.$msg_id;exit;
				$data = $this->input->post();
				//print_r($data);exit;
				/*Prepare the message details*/
				$body = $data['content1'];
				$sender_id = $this->session->userdata['user_id'];
				$msg_id = $data['mdgid'];
				$subject = $data['subject'];
				if($this->mahana_messaging->reply_to_message($msg_id,$sender_id, $subject,$body))
				{
					//var_dump($resp);
					$this->data['errors'] = [];
					$this->data['success'] = true;
					$this->session->set_flashdata('success', 'Message sent successfully');
				}else{
					$this->session->set_flashdata('success', 'There is some issue in sending message.');
					$this->data['success'] = false;
				}
			}
			$this->_render_json();
		}
	}

	public function messageDetails($thread_id = ""){
		$data = [];
		$data['page_title'] = 'Inbox';
		$data['page_header'] = 'Mailbox';
		
		$user_id = $this->session->userdata['user_id'];
		$threadDetails = $this->mahana_messaging->get_full_thread($thread_id,$user_id, true, 'DESC');
		
		$data['threadDetails'] = $threadDetails;
		//echo '<pre>';
		//print_r($data);exit;
		//$this->template->load($this->layout, 'admin/mailbox/modal_message_details_form', $data);
		$this->load->view('/admin/mailbox/elements/modal_message_details_form', $data);
		
	}

}