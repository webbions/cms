<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

	protected $layout = 'admin_default';

	function __construct()
	{
		parent::__construct();
		$this->ensure_group(['admin']);
		$this->load->library(array('ion_auth','form_validation'));
		$this->load->helper(array('url','language'));
		$this->load->model(['AdminUserModel']);
		$this->load->model(['AdminAuthModel']);
		//$this->load->model(['AdminCategoryModel']);

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');
	}

	public function index(){



		$data = [];
		//$data['page_title'] = 'Users List';
		//$data['page_header'] = 'Users';
		$users = ['Top Level'];

		$dbUsers = $this->AdminUserModel->get_list([], ['id', 'email']);

		$groups = $this->ion_auth->groups()->result_array();

		//$dbUsers = $this->AdminUserModel->getUserByGroup(MEMBER_GROUP);
		$data['users'] = array_merge($users, $dbUsers);

		// $this->data['title'] = "Create User";
		$this->data['page_title'] = 'Users List';
		$this->data['page_header'] = 'Users List';

            $this->data['first_name'] = array(
                'name'  => 'first_name',
                'id'    => 'first_name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('first_name'),
                'class' => 'form-control'
            );
            $this->data['last_name'] = array(
                'name'  => 'last_name',
                'id'    => 'last_name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('last_name'),
                'class' => 'form-control'
            );
            $this->data['email'] = array(
                'name'  => 'email',
                'id'    => 'email',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('email'),
                'class' => 'form-control',
            );
            $this->data['password'] = array(
                'name'  => 'password',
                'id'    => 'password',
                'type'  => 'password',
                'value' => $this->form_validation->set_value('password'),
                'class' => 'form-control'
            );
            $this->data['password_confirm'] = array(
                'name'  => 'password_confirm',
                'id'    => 'password_confirm',
                'type'  => 'password',
                'value' => $this->form_validation->set_value('password_confirm'),
                 'class' => 'form-control'
            );

			$this->data['goupoptions']=$groups;

            $this->data['statusoptions'] = array(
                '1'  => 'Active',
                '0'    => 'Inactive',
            );
			$data = $this->data;
		//pr($data);exit;
		$this->template->load($this->layout, 'admin/user/index', $data);
	}
	public function add(){
		$data = [];
		$this->data['page_title'] = 'Users';
		$this->data['page_header'] = 'Users';
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
            redirect('admin/user/add', 'refresh');
        }

        $tables = $this->config->item('tables','ion_auth');
        $identity_column = $this->config->item('identity','ion_auth');
        $this->data['identity_column'] = $identity_column;

        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required');
        if($identity_column!=='email')
        {
            $this->form_validation->set_rules('identity',$this->lang->line('create_user_validation_identity_label'),'required|is_unique['.$tables['users'].'.'.$identity_column.']');
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
        }
        else
        {
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
        }
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'trim');
        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

        if ($this->form_validation->run() == true)
        {
            $email    = strtolower($this->input->post('email'));
            $identity = ($identity_column==='email') ? $email : $this->input->post('identity');
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'active'    => $this->input->post('status'),
                'phone'      => $this->input->post('phone'),
            );

			$group =  array('id' => $this->input->post('group'));
        }
        if ($this->form_validation->run() == true && $this->ion_auth->register($identity, $password, $email, $additional_data,$group))
        {
            // check to see if we are creating the user
            // redirect them back to the admin page
			$this->data['errors'] = [];
			$this->data['success'] = true;
			$this->session->set_flashdata('success', 'User added Successfully');
			//$this->session->set_flashdata('message', $this->ion_auth->messages());
			//redirect("admin/user", 'refresh');
        }
        else
        {
			$this->data['errors'] = $this->form_validation->error_array();
			$this->data['success'] = false;
		}
    	$this->_render_json();
		/*$data = [];
		$data['page_title'] = 'Add User';
		$data['page_header'] = 'Add user';
		$this->template->load($this->layout, 'admin/user/add', $data);
		//$this->template->load($this->layout, 'admin/auth/create_user', $data);*/
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

	public function edit($id = NULL) {
		$this->data['title'] = "Edit User";
		if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id)))
		{
			redirect('admin/auth', 'refresh');
		}

		$user = $this->ion_auth->user($id)->row();

		$groups=$this->ion_auth->groups()->result_array();
		$currentGroups = $this->ion_auth->get_users_groups($id)->result();

		// display the edit user form
		$this->data['csrf'] = $this->_get_csrf_nonce();

		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// pass the user to the view
		$this->data['user'] = $user;
		$this->data['groups'] = $groups;
		$this->data['currentGroups'] = $currentGroups;

	 $this->data['first_name'] = array(
                'name'  => 'first_name',
                'id'    => 'first_name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('first_name',$user->first_name),
                'class' => 'form-control'
            );
            $this->data['last_name'] = array(
                'name'  => 'last_name',
                'id'    => 'last_name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('last_name',$user->last_name),
                'class' => 'form-control'
            );
            $this->data['email'] = array(
                'name'  => 'email',
                'id'    => 'email',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('email',$user->email),
                'class' => 'form-control',
            );
            $this->data['password'] = array(
                'name'  => 'password',
                'id'    => 'password',
                'type'  => 'password',
                'class' => 'form-control'
            );
            $this->data['password_confirm'] = array(
                'name'  => 'password_confirm',
                'id'    => 'password_confirm',
                'type'  => 'password',
                 'class' => 'form-control'
            );


            $this->data['goupoptions']=$groups;

            $this->data['statusoptions'] = array(
                '1'  => 'Active',
                '0'    => 'Inactive',
            );
			$data = $this->data;
			$data['userEdit'] = $user;

			//$this->_render_json();
		//$data['categoryDetails'] = $this->AdminCategoryModel->get(['id' => $id]);
		//$categories = ['Top Level'];
		//$dbCategories = $this->AdminCategoryModel->get_list([], ['id', 'name']);
		//$data['allCategories'] = array_merge($categories, $dbCategories);

		$this->load->view('admin/user/edit', $data);
	//pr($data);exit;
				// validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'required');
		$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required');
		 $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');

		if (isset($_POST) && !empty($_POST))
		{

			/*// do we have a valid request?
			if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
			{
				show_error($this->lang->line('error_csrf'));
			}

			// update the password if it was posted
			if ($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
			}*/

			if ($this->form_validation->run() === TRUE)
			{

				$data = array(
					'first_name' => $this->input->post('first_name'),
					'last_name'  => $this->input->post('last_name'),
					'email'  => $this->input->post('email'),
					'active'    => $this->input->post('status'),
				);

				// update the password if it was posted
				if ($this->input->post('password'))
				{
					$data['password'] = $this->input->post('password');
				}



				// Only allow updating groups if user is admin
				if ($this->ion_auth->is_admin())
				{
					//Update the groups user belongs to

					$groupData = array('id'=>$this->input->post('group'));

					if (isset($groupData) && !empty($groupData)) {

						$this->ion_auth->remove_from_group('', $id);

						foreach ($groupData as $grp) {

							$this->ion_auth->add_to_group($grp, $id);
						}

					}
				}

			// check to see if we are updating the user
			   if($this->ion_auth->update($user->id, $data))
			    {
			    	// redirect them back to the admin page if admin, or to the base url if non admin
				    $this->session->set_flashdata('message', $this->ion_auth->messages() );
				    if ($this->ion_auth->is_admin())
					{

						$this->data['errors'] = [];
						$this->data['success'] = true;
						$this->session->set_flashdata('success', 'User update Successfully');
						//redirect('admin/auth', 'refresh');
					}
					else
					{
						$this->data['errors'] = [];
						$this->data['success'] = false;
						$this->session->set_flashdata('success', 'Error in update user');
						//redirect('/', 'refresh');
					}

			    }
			    else
			    {
			    	// redirect them back to the admin page if admin, or to the base url if non admin
				    ///$this->session->set_flashdata('message', $this->ion_auth->errors() );
				    if ($this->ion_auth->is_admin())
					{

						$this->data['errors'] = [];
						$this->data['success'] = true;
						$this->session->set_flashdata('success', 'User update Successfully');
					}
					else
					{
						$this->data['errors'] = [];
						$this->data['success'] = false;
						$this->session->set_flashdata('success', 'Error in update user');
					}

			    }
			}else{
				$this->data['errors'] = $this->form_validation->error_array();
				$this->data['success'] = false;
			}
			$this->_render_json();
		}


	}

	function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	function _valid_csrf_nonce()
	{
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
			$this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function delete($id = NULL) {
		$this->AdminUserModel->delete(['id' => $id]);
		$this->session->set_flashdata('success', 'USer deleted Successfully');
		redirect(base_url() . 'admin/user/index');exit;

	}

	public function profile(){
		$data = [];
		$this->template->load($this->layout, 'admin/home/profile', $data);
	}



}
