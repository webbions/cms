<?php defined('BASEPATH') OR exit('No direct script access allowed');
class User extends MY_Controller {
	protected $layout = 'front_master';
	function __construct()
	{
		parent::__construct();
		//$this->ensure_group(['admin']);
		$this->load->library(array('ion_auth','form_validation'));
		$this->load->helper(array('url','language'));
		$this->load->model(['FrontUserModel']);
		$this->load->model(['FrontUserProfileModel']);

		$this->load->model(['ClientModel']);
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
	public function redirect_group($groupId = '') {
		if ( $groupId == '3') {
			redirect( base_url() . "talent/register" );
		}elseif ( $groupId == '2') {
			redirect( base_url() . "client/register" );
		}else{
			redirect( base_url() );
		}
		exit;
	}
	function register_social( $source = ''){
		$this->load->library('HybridAuthLib');
		//pr($this->session->userdata('user_group_sess'));

		if(!$this->session->userdata('user_group_sess')){
			redirect(base_url());exit;
		}
		//Read the session
		$user_group_sess = $this->session->userdata('user_group_sess');

		// Get the user group and source(provider)
		$user_group = $user_group_sess['user_group'];
		$source = $user_group_sess['provider'];

		// check user_group set or not
		if ( $user_group == '') {
			redirect( base_url() );exit;
		}
		// IF source/provider not set then
		if ($source == '') {
			$this->session->set_flashdata('error', "Something went wrong, Please try again.");
			$this->redirect_group( $user_group );
		}

		if ( $source == 'Facebook' || $source == 'LinkedIn') {
			// Send to the view all permitted services as a user profile if authenticated
			$login_data = $this->hybridauthlib->getProviders();

			if ($login_data[$source]['connected'] == 1) {
				$user_profile = [];
				try {
					$user_profile = $login_data[$source]['user_profile'] = $this->hybridauthlib->authenticate($source)->getUserProfile();
				} catch (Exception $e) {
					redirect(base_url());exit;
				}

				//check email exist in database
				$isExist = $this->FrontUserModel->exists( ['email' => $user_profile->email ] );

				// response is null from social site
				if ( count($user_profile) == 0) {
					$this->session->set_flashdata('error', "While fetching user, Please try again.");
					$this->redirect_group( $user_group );
				}

				$email    = strtolower($user_profile->email);
	            $identity = $email;
	            $password = $email;

				if ( !$isExist) {
					//if not exist;
		            $full_name = $user_profile->firstName .' '. $user_profile->lastName;

		            // prepare register details Facebook
	                $additional_data['first_name'] = $user_profile->firstName;
		            $additional_data['last_name']  = $user_profile->lastName;
		            $additional_data['active']     = '1';
		            $additional_data['gender']     = $user_profile->gender;
		            $additional_data['country']    = $user_profile->country;
		            $additional_data['city']     	 = $user_profile->city;
		            $additional_data['birth_date'] = $user_profile->birthYear .'-'.$user_profile->birthMonth.'-'.$user_profile->birthDay;
			        $additional_data['registered_from'] = strtolower($source);
					$group =  array('id' => $user_group);

					//insert user details to users table
					$lastInsertUserId = $this->ion_auth->register($identity, $password, $email, $additional_data,$group);

					//after user registered flush sessions
					@session_start();
					unset($_SESSION['HA::CONFIG'], $_SESSION['HA::STORE'] );
					$this->session->unset_userdata( 'user_group_sess' );

					//call login
					$this->ion_auth->login($identity, $identity );

					$this->session->set_flashdata('message', $this->ion_auth->messages());

					//redirect to profile page
					redirect('/user/profile');exit;

				}elseif ( $this->ion_auth->login($identity, $identity, FALSE, TRUE) ) {
					//if exist then call login
					//if the login is successful
					//redirect them back to the home page
					$this->session->set_flashdata('message', $this->ion_auth->messages());
					redirect('/user/profile');exit;
				}else{
					//if something wrong
					$this->session->set_flashdata('error', "Something wrong, Please try again.");
					$this->redirect_group( $user_group );
				}
				//pr($user_profile );
			}else{
				$this->session->set_flashdata('error', "Something went wrong, Please try again.");
				$this->redirect_group( $user_group );

			}
		}
		exit;
		$this->load->view('hauth/home', $login_data);
	}

	function login()
	{
		$this->data['title'] = "Login";
		//validate form input
		$this->form_validation->set_rules('identity', 'Identity', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		if ($this->form_validation->run() == true)
		{
			// check to see if the user is logging in
			// check for "remember me"
			$remember = (bool) $this->input->post('remember');
			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
			{
				//if the login is successful
				//redirect them back to the home page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect(base_url().'user/profile');
			}
			else
			{
				// if the login was un-successful
				// redirect them back to the login page
				$this->session->set_flashdata('success', $this->ion_auth->messages());
				redirect(base_url().'user/login', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		}
		else
		{
			// the user is not logging in so display the login page
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			/*
			$this->data['identity'] = array('name' => 'identity',
				'id'    => 'identity',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('identity'),
			);
			$this->data['password'] = array('name' => 'password',
				'id'   => 'password',
				'type' => 'password',
			);*/
			$this->template->load($this->layout, 'front/login', $this->data);
		}
	}
	public function register(){
		$data = [];
		$this->data['page_title'] = 'Register';
		$this->data['page_header'] = 'Register';
		$data['categorydata']  = $this->FrontUserModel->getCategory();
		$data['groupsDetails'] = $this->ion_auth->groups()->result_array();
        /*if (!$this->ion_auth->logged_in())
        {
            redirect('user/register', 'refresh');
        }*/
        $tables = $this->config->item('tables','ion_auth');
        $identity_column = $this->config->item('identity','ion_auth');
        $this->data['identity_column'] = $identity_column;
        if ( $this->input->post('regisuteruser') ) {
        	// validate form
	        $this->form_validation->set_rules('group', $this->lang->line('create_user_validation_category_label'), 'required');
	        $this->form_validation->set_rules('ucategory[]', $this->lang->line('create_user_validation_category_label'), 'required|trim');
	        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required');
	        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required');
	        $this->form_validation->set_rules('birthmonth', $this->lang->line('create_user_validation_birthmonth_label'), 'required');
	        $this->form_validation->set_rules('birthyear', $this->lang->line('create_user_validation_birthyear_label'), 'required');
	        $this->form_validation->set_rules('gender', $this->lang->line('create_user_validation_gender_label'), 'required');
	        $this->form_validation->set_rules('country', $this->lang->line('create_user_validation_country_label'), 'required');
	        $this->form_validation->set_rules('city', $this->lang->line('create_user_validation_city_label'), 'required');
	        if($identity_column!=='email')
	        {
	            $this->form_validation->set_rules('identity',$this->lang->line('create_user_validation_identity_label'),'required|is_unique['.$tables['users'].'.'.$identity_column.']', array('is_unique' => 'Email already exist.'));
	            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
	        }
	        else
	        {
	            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[' . $tables['users'] . '.email]', array('is_unique' => 'Email already exist.'));
	        }

	        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[8]|max_length[20]');
			$this->form_validation->set_message('min_length', 'The password must be at least 8 characters in length.');
			$this->form_validation->set_message('max_length', 'The password cannot exceed 20 characters in length.');

	        if ($this->form_validation->run() == true)
	        {
	            $email    = strtolower($this->input->post('email'));
	            $identity = ($identity_column==='email') ? $email : $this->input->post('identity');
	            $password = $this->input->post('password');
	            $full_name = $this->input->post('first_name') .' '. $this->input->post('last_name');
	            $additional_data = array(
	                'first_name' => $this->input->post('first_name'),
	                'last_name'  => $this->input->post('last_name'),
	                'active'     => '0',
	                'gender'      => $this->input->post('gender'),
	                'country'      => $this->input->post('country'),
	                'city'     	 => $this->input->post('city'),
	                'birth_date' => $this->input->post('birthyear') .'-'.$this->input->post('birthmonth').'-01',
	            );
				$group =  array('id' => $this->input->post('group'));
				//insert user details to users table
				$lastInsertUserId = $this->ion_auth->register($identity, $password, $email, $additional_data,$group);
	            if ($lastInsertUserId) {
					//Insert user interest in to user_interest table
					foreach ($this->input->post('ucategory') as $key => $value) {
						$categoryBatchArra[] = [ "category_id" => $value,  "userid" => $lastInsertUserId ];
					}
					$this->FrontUserModel->categoryBatchInsert( $categoryBatchArra );
					//Send verification email
					$this->sendConfirmEmail( $full_name, $email, $password );
					$this->session->set_flashdata('success', 'You registered with '. SITE_NAME.', Please check your email for confirmation link.');
	            }else{
					$this->session->set_flashdata('error', 'While registered your account.');
	            }
				redirect("user/register");exit;
	        }
        }
        // validate form input
		$this->template->load($this->layout, 'front/userregister', $data);
	}
	public function sendConfirmEmail($full_name, $email, $password)	{
		/* Email Start*/
		$confirm_url = base_url().'user/confirm/'.urlencode( md5($email) . '||' . encryptor('e', $password));

		$email_html = '<html><body>';
		$email_html .= 'Welcome '.$full_name;
		$email_html .= '<br><br>You have been successfully registered to '.SITE_NAME.'.<br> Your username/email and password has been created for the login. Please click on below link to verify your account.<br><br>';
		$email_html .= 'Username/Email = '.$email.'<br>';
		$email_html .= 'Password = '.$password;
		$email_html .= '<br><br>';
		$email_html .= 'Activate your account <a href="'.$confirm_url.'">'.$confirm_url.'</a>';
        $email_html .= '<br><br>Regards,<br>'.SITE_NAME;
		$email_html .= '</body></html>';

		$subject = 'Active Your Account - '.SITE_NAME;
		return triggerMail( $email, $subject, $email_html);
		/* Email End*/
	}
    public function client_register(){

		$data = [];
		$this->data['page_title'] = 'Register';
		$this->data['page_header'] = 'Register';
		$data['categorydata']  = $this->FrontUserModel->getCategory();
		$data['groupsDetails'] = $this->ion_auth->groups()->result_array();
        /*if (!$this->ion_auth->logged_in())
        {
            redirect('user/register', 'refresh');
        }*/

        $tables = $this->config->item('tables','ion_auth');
        $identity_column = $this->config->item('identity','ion_auth');
        $this->data['identity_column'] = $identity_column;

        if ( $this->input->post('regisuteruser') ) {
        	// validate form

	        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required');
	        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required');
	        $this->form_validation->set_rules('birthmonth', $this->lang->line('create_user_validation_birthmonth_label'), 'required');
	        $this->form_validation->set_rules('birthyear', $this->lang->line('create_user_validation_birthyear_label'), 'required');
	        $this->form_validation->set_rules('gender', $this->lang->line('create_user_validation_gender_label'), 'required');
	        $this->form_validation->set_rules('country', $this->lang->line('create_user_validation_country_label'), 'required');
	        $this->form_validation->set_rules('city', $this->lang->line('create_user_validation_city_label'), 'required');
	        if($identity_column!=='email')
	        {
	            $this->form_validation->set_rules('identity',$this->lang->line('create_user_validation_identity_label'),'required|is_unique['.$tables['users'].'.'.$identity_column.']');
	            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
	        }
	        else
	        {
	            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
	        }
	        //$this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');

	        //$this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'trim');

                //$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']');//|matches[password_confirm]

                //$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

	        if ($this->form_validation->run() == true)
	        {
	            $email    = strtolower($this->input->post('email'));
	            $identity = ($identity_column==='email') ? $email : $this->input->post('identity');
	            $password = $this->input->post('password');
	            $full_name = $this->input->post('first_name') .' '. $this->input->post('last_name');

	            $additional_data = array(
	                'first_name' => $this->input->post('first_name'),
	                'last_name'  => $this->input->post('last_name'),
	                'active'     => '1',
	                'gender'      => $this->input->post('gender'),
	                'country'      => $this->input->post('country'),
	                'city'     	 => $this->input->post('city'),
	                'birth_date' => $this->input->post('birthyear') .'-'.$this->input->post('birthmonth').'-01',
	            );

                        //$group =  array('id' => $this->input->post('group'));

                        //insert user details to users table
                        $lastInsertUserId = $this->ion_auth->register($identity, $password, $email, $additional_data,CLIENT_GROUP);

	            // check to see if we are creating the user
	            // redirect them back to the admin page
                    $this->data['errors'] = [];
                    $this->data['success'] = true;

                    //Insert user interest in to user_interest table
                    /*foreach ($this->input->post('ucategory') as $key => $value) {
                            $categoryBatchArra[] = [ "category_id" => $value,  "userid" => $lastInsertUserId ];
                    }
                    $this->FrontUserModel->categoryBatchInsert( $categoryBatchArra );
                    */

                    $clientArray = array(
                        'user_id'           => $lastInsertUserId['id'],
                        'company_name'      => $this->input->post('company_name'),
                        'company_name'      => $this->input->post('company_name'),
                        'company_website'   => $this->input->post('company_website'),
                        'position'          => $this->input->post('position'),
                        'updated'           => date('Y-m-d H:i:s'),
                        'created'           => date('Y-m-d H:i:s')
                    );
                    $this->ClientModel->insert( $clientArray );
                    
                    $this->sendConfirmEmail( $full_name, $email, $password );

                    $this->session->set_flashdata('success', 'User added Successfully');
                    redirect("client-register");exit;
	        }else{

                    //echo "error";die;
                    $this->session->set_flashdata('error', validation_errors());
                    redirect("client-register");exit;
                }
        }
            // validate form input
            $this->template->load($this->layout, 'front/client_register', $data);
                //$this->_render_json();
		/*$data = [];
		$data['page_title'] = 'Add User';
		$data['page_header'] = 'Add user';
		$this->template->load($this->layout, 'admin/user/add', $data);
		*/
	}
	public function confirm( $email = '')
    {
		if($email != ''){
			$hash = explode('||', urldecode($email));

			$email = $hash[0];
			$password = encryptor('d', $hash[1] );

			$confirmData = $this->FrontUserModel->get([ "md5(email)"=> $email ]);

			if( !empty($confirmData) ){
				$data['active'] = '1';
				if ( $this->FrontUserModel->update(['md5(email)' => $email], $data) ) {
					if ( $this->ion_auth->login( $confirmData['email'], $password ) ){
						//if the login is successful
						//redirect them back to the home page
						$this->session->set_flashdata('message', 'Your account has been confirmed successfully. '.$this->ion_auth->messages());
						redirect('/user/profile');
					}else{
						// if the login was un-successful
						// redirect them back to the login page
						$this->session->set_flashdata('error', $this->ion_auth->messages());
						redirect('/user/login'); // use redirects instead of loading views for compatibility with MY_Controller libraries
					}exit;
				}else{
					$this->session->set_flashdata('error',"While confirming account!");
				}
				redirect(base_url()."user/login");
			}else{
				$this->session->set_flashdata('error',"Your email id does not Exist!");
				redirect(base_url()."user/login");
			}
		}else{
			$this->session->set_flashdata('error',"Invalid request!");
			redirect(base_url()."user/login");
		}
		exit;
	}
	// log the user out
	function logout()
	{
		$this->data['title'] = "Logout";
		// log the user out
		$logout = $this->ion_auth->logout();
		// redirect them to the login page
		$this->session->set_flashdata('message', $this->ion_auth->messages());
		redirect('user/login', 'refresh');
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
	public function addphotos($id = NULL) {
		$this->data['title'] = "Edit User";
		if (!$this->ion_auth->logged_in())
		{
			redirect('user/login', 'refresh');
		}
		$userid  = $this->session->userdata();
		$userid = $userid['user_id'];
		if(!is_dir('assets/userimages/'.$userid)){
			mkdir('assets/userimages/'.$userid, 0777);
		}
		$this->form_validation->set_rules('userimages', 'User Images', 'required|trim');
		if ($this->form_validation->run() == TRUE) {
				$config['upload_path'] = "assets/userimages/".$userid;
				$config['allowed_types'] = '*';
				//$data = $this->input->post();
				if((!empty($_FILES['userimages'])) && ($_FILES['userimages']['error'] == 0)){
					$myfile = $_FILES['userimages']['name'];
					$ext = explode("/",$_FILES['userimages']['type']);
					$ext = $ext[1];
					$new_filename = 'uploaded_file_'.time().'.'.$ext;
					$config['file_name'] = $new_filename;
					$this->load->library('upload', $config);
					$this->load->initialize($config);
					if ( ! $this->upload->do_upload('userimages')){
						$error = array('error' => $this->upload->display_errors());
						//print_r($error);exit;
					}else{
						$data['upiimage'] = $new_filename;
					}
				}
				$data['upiuid'] = $userid;
				$data['upicreateddate'] = date('Y-m-d H:i:s');
				//pr($data);exit;
				//unset($data['submit']);
				if ($this->FrontUserProfileModel->insert($data)) {
					$this->data['errors'] = [];
					$this->data['success'] = true;
					$this->session->set_flashdata('success', 'Upload Successfully');
					redirect(base_url() . 'user/profile');exit;
				} else {
					$this->session->set_flashdata('success', 'Upload not successfully');
					redirect(base_url() . 'user/addphotos');exit;
				}
			}
		$data['user'] = $this->ion_auth->user($id)->row();
		$this->template->load($this->layout, 'front/user/addeditpohots_view', $data);
	}
	public function addvideo($id = NULL) {
		$this->data['title'] = "Add Video";
		$this->ensure_group(['members']);

		if (!$this->ion_auth->logged_in())
		{
			redirect('user/login', 'refresh');
		}
		$userid  = $this->session->userdata();
		$userid = $userid['user_id'];

		$this->form_validation->set_rules('video_name', 'Video Name', 'required|trim');
		$this->form_validation->set_rules('video_code', 'Embed code', 'required|trim');
		if ($this->form_validation->run() == TRUE) {
			$video_code = $this->input->post('video_code');
			$video_code = str_replace("560", "200", $video_code);
			$video_code = str_replace("315", "200", $video_code);

			$data['userid'] = $userid;
			$data['video_name'] = $this->input->post('video_name');
			$data['video'] = $video_code;
			$data['created_at'] = date('Y-m-d H:i:s');

			if ($this->FrontUserProfileModel->addvideo($data)) {
				$this->data['errors'] = [];
				$this->data['success'] = true;

				$this->data['video_data'] = "<div class='col-sm-3'>".$data['video']."<p>".$data['video_name']."</p></div>";
				$this->session->set_flashdata('success', 'Video added successfully');
			} else {
				$this->session->set_flashdata('success', 'Operation failed.');
				$this->data['success'] = false;
			}
		}else {

			$this->data['errors'] = $this->form_validation->error_array();
			$this->data['success'] = false;
		}
		$this->_render_json();

	}
	public function addaudio($id = NULL) {
		$this->data['title'] = "Add Audio";
		$this->ensure_group(['members']);

		if (!$this->ion_auth->logged_in())
		{
			redirect('user/login', 'refresh');
		}
		$userid  = $this->session->userdata();
		$userid = $userid['user_id'];

		$this->form_validation->set_rules('audio_name', 'Audio Name', 'required|trim');
		$this->form_validation->set_rules('audio_code', 'Embed code', 'required|trim');
		if ($this->form_validation->run() == TRUE) {

			$data['userid'] = $userid;
			$data['audio_name'] = $this->input->post('audio_name');
			$data['audio'] = $this->input->post('audio_code');
			$data['created_at'] = date('Y-m-d H:i:s');

			if ($this->FrontUserProfileModel->addaudio($data)) {
				$this->data['errors'] = [];
				$this->data['success'] = true;

				$this->data['audio_data'] = "<div class='col-sm-3'>".$data['audio']."<p>".$data['audio_name']."</p></div>";
				$this->session->set_flashdata('success', 'Audio added successfully');
			} else {
				$this->session->set_flashdata('success', 'Operation failed.');
				$this->data['success'] = false;
			}
		}else {

			$this->data['errors'] = $this->form_validation->error_array();
			$this->data['success'] = false;
		}
		$this->_render_json();

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
		//$this->ensure_group(['members']);
		$data = [];
		$this->data['title'] = "Profile";
		$currentlogindata = $this->session->userdata();
		$cuid = $currentlogindata['user_id'];
		$data['userdata'] = $this->ion_auth->user($cuid)->row();
		//$data['userimages'] = $this->FrontUserProfileModel->getimages($cuid);
		$data['uservideos'] = $this->FrontUserProfileModel->getvideos($cuid);
		//$data['useraudios'] = $this->FrontUserProfileModel->getaudios($cuid);
		$data['portfolio_image_count'] = $this->FrontUserProfileModel->count(['userid'=>$cuid]);
		$session_data = array('username'=>$data['userdata']->first_name.' '.$data['userdata']->last_name);
		$data['profileData'] = $this->FrontUserModel->getprofile($cuid);
		$data['talentcategory']  = $this->FrontUserModel->gettalentcategory($cuid);
		$data['categorydata']  = $this->FrontUserModel->getCategory();
		$this->session->set_userdata($session_data);
		$this->template->load($this->layout, 'front/user/profile', $data);	
	}

	public function protfolioImages(){
		if (!$this->input->is_ajax_request()) {
		   exit('No direct script access allowed');
		}
		$currentlogindata = $this->session->userdata();
		$cuid = $currentlogindata['user_id'];
		$userimages = $this->FrontUserProfileModel->getimages($cuid);
		$response = "";
		if(count($userimages) > 0){
			foreach ($userimages as $key => $value) {
				$response .= "<div class='col-sm-3'> <img src=".base_url()."assets/userimages/".$cuid."/".$value['image']." class='img-responsive'> </div>";
			}
			$result['response'] = $response;
		}else{
			$response = "<div class='col-sm-3'>No Images</div>";
			$result['response'] = $response;

		}
		echo json_encode($result);die;
	}
	/**
     * Fetch user's embed audios
     * @author Mrudul Shah
     * @param null
     * @return array
     */
	public function protfolioAudios(){
		if (!$this->input->is_ajax_request()) {
		   exit('No direct script access allowed');
		}

		$currentlogindata = $this->session->userdata();
		$cuid = $currentlogindata['user_id'];
		$useraudios = $this->FrontUserProfileModel->getaudios($cuid);
		$response = "";
		if(count($useraudios) > 0){
			foreach ($useraudios as $key => $value) {
				$response .= "<div class='col-sm-3'>".$value['audio']."<p>".$value['audio_name']."</p></div>";
			}
			$result['response'] = $response;
		}else{
			$response = "<div class='col-sm-3'>No Audio Available</div>";
			$result['response'] = $response;

		}
		echo json_encode($result);die;


	}

	/**
     * Fetch user's embed videos
     * @author Mrudul Shah
     * @param null
     * @return array
     */

	public function protfolioVideos(){
		if (!$this->input->is_ajax_request()) {
		   exit('No direct script access allowed');
		}

		$currentlogindata = $this->session->userdata();
		$cuid = $currentlogindata['user_id'];
		$uservideos = $this->FrontUserProfileModel->getvideos($cuid);
		$response = "";
		if(count($uservideos) > 0){
			foreach ($uservideos as $key => $value) {
				$response .= "<div class='col-sm-3'>".$value['video']."<p>".$value['video_name']."</p></div>";
			}
			$result['response'] = $response;
		}else{
			$response = "<div class='col-sm-3'>No Video Available</div>";
			$result['response'] = $response;

		}
		echo json_encode($result);die;


	}

	/**
     * Upload Multiple Images via ajax.
     * @author Mrudul Shah
     * @param array
     * @return array
     */

	public function ajaxFileUpload(){
		if (!$this->input->is_ajax_request()) {
		   exit('No direct script access allowed');
		}

		$userid  = $this->session->userdata();
		$userid = $userid['user_id'];

		if(!is_dir('assets/userimages/'.$userid)){
			mkdir('assets/userimages/'.$userid, 0777);
		}

		$path = "assets/userimages/".$userid;
        $this->load->library('upload');

        // Define file rules
        $this->upload->initialize(array(
            "upload_path"       =>  $path,
            "allowed_types"     =>  "gif|jpg|png",
            //"max_size"          =>  '100',
            "max_width"         =>  '1024',
            "max_height"        =>  '768'
        ));

		if($this->upload->do_multi_upload("images")){
            $data['upload_data'] = $this->upload->get_multi_upload_data();
            //echo '<p class = "bg-success">' . count($data['upload_data']) . 'File(s) successfully uploaded.</p>';
			$response = "";
			$result = [];
			$result['success']  = true;
			$result['message'] =  "
							<div class='alert alert-dismissable alert-success'>
						        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>x</button>
						        <strong>Success !</strong> ".count($data['upload_data']) . "File(s) successfully uploaded
						    </div>
						    <script>
							    $(function(){
							        $(\".alert-dismiss \").fadeTo(2000, 3000).slideUp(500, function(){
							            $(\".alert-dismiss \").alert('close');
							        });
							    });
							</script>";

			foreach ($data['upload_data'] as $key => $val) {

				$insertData['userid'] 		= $userid;
				$insertData['image'] 		= $val['file_name'];
				$insertData['created_at'] 	= date('Y-m-d H:i:s');

				$this->FrontUserProfileModel->insert($insertData);

				$response .= "<div class='col-sm-3'> <img src=".base_url()."assets/userimages/".$userid."/".$val['file_name']." class='img-responsive'> </div>";
			}

			$result['response'] = $response;

			echo json_encode($result);die;

        } else {

        	$response = "";
			$result = [];
			$result['success']  = false;
            // Output the errors
            $this->session->set_flashdata('success', 'Image uploading failed.');
			$errors = array('error' => $this->upload->display_errors('<p class = "bg-danger">', '</p>'));
			$result['message'] =  "
							<div class='alert alert-dismissable alert-danger  shake animated'>
						        <button type='button' class='close' data-dismiss='alert' aria-hidden='true>x</button>
						        <strong>Error !</strong> <?=$errors?>
						    </div>
						    <script>
							    $(function(){
							        $(\".alert-dismiss \").fadeTo(2000, 3000).slideUp(500, function(){
							            $(\".alert-dismiss \").alert('close');
							        });
							    });
							</script>";

			echo json_encode($result);die;

        }
	}
	public function profileupdate(){
		$usession = $this->session->userdata();
		$loginuserid = $usession['user_id'];
		
		$this->form_validation->set_rules('ucategory[]', $this->lang->line('create_user_validation_category_label'), 'required|trim');
		$this->form_validation->set_rules('education', $this->lang->line('create_user_validation_education_label'), 'required|trim');
		$this->form_validation->set_rules('availedtotravel', $this->lang->line('create_user_validation_availedtotravel_label'), 'required|trim');
		$this->form_validation->set_rules('current', $this->lang->line('create_user_validation_current_label'), 'required|trim');		
		$this->form_validation->set_rules('aboutme', $this->lang->line('create_user_validation_aboutme_label'), 'required|trim');
		//Insert user interest in to user_interest table
		if ($this->form_validation->run() == TRUE) {
			$selectedCatIdArr = array();		
			foreach ($this->input->post('ucategory') as $key => $value) {
					 array_push($selectedCatIdArr,$value);
					//$categoryBatchArra[] = [ "category_id" => $value,  "userid" => $loginuserid];
					if(count($this->FrontUserModel->gettalentcatforcheck($loginuserid,$value)) == 0)
					{
						$categoryBatchArra[] = [ "categoryID" => $value,  "userID" => $loginuserid ];
					}
			}
			
			//Add talent category 
			if(isset($categoryBatchArra)){
				$this->FrontUserModel->categoryBatchInsert($categoryBatchArra);
			}
			
			//Delete talent category 
			$telectCateData = $this->FrontUserModel->gettalentcategory($loginuserid);
			$telectCatId  = array();
			foreach($telectCateData as $tCatRow)
			{ 
				//array_push($telectCatId,$tCatRow['categoryID']);
				if(!in_array($tCatRow['categoryID'],$selectedCatIdArr))
				{
					$this->FrontUserModel->deleteCatFromTalent($tCatRow['userInterestID']);
				}
			}
			$telectCateData = $this->FrontUserModel->gettalentcategory($loginuserid);
			foreach($telectCateData as $tCatRow)
			{ 
				array_push($telectCatId,$tCatRow['categoryID']);
				
			}
			
			
			$userProfile["user_id"] = $loginuserid;
			$userProfile["education"] = $this->input->post('education');
			$userProfile["availedtotravel"] = $this->input->post('availedtotravel');
			$userProfile["current"] = $this->input->post('current');
			$userProfile["aboutme"] = $this->input->post('aboutme');
			//['id' => $id]
			if($this->FrontUserModel->getprofile($loginuserid))
			{
				$this->FrontUserModel->profileUpdate( $userProfile );
			}else{
				$this->FrontUserModel->profileInsert( $userProfile );
			}
			
			$catData = $this->FrontUserModel->getCategory();
			$talentCatData = '';
			//pr($telectCatId);exit;
			foreach($catData as $CatRow)
			{
				if(in_array($CatRow['id'],$telectCatId))
				{
					$talentCatData .= $CatRow['name'].','; 
				}
			}
			$profileData = $this->FrontUserModel->getprofile($loginuserid);
			$curruser = $this->session->userdata();
			$profile_html = '';
			$profile_html .= '<h3>'.$curruser['username'].'</h3>';
			$profile_html .= '<h4>'.trim($talentCatData,',').'</h4>';
			$profile_html .= '<h4 class="mar-top">Current:<span>'.$profileData[0]['current'].'</span></h4>';
			$profile_html .= '<h4>Availed to Travel:<span>'. $profileData[0]['current'].'</span></h4>';
			$profile_html .= '<h4>Education:<span>'.$profileData[0]['education'].'</span></h4>';
			$profile_html .= '<h4 class="mar-top">About Me</h4>';
			$profile_html .= '<p>'. $profileData[0]['aboutme'].'</p>';
			
			$this->data['responsehtml'] = $profile_html;
			$this->data['errors'] = [];
			$this->data['success'] = true;
			$this->session->set_flashdata('success', 'Profile update Successfully');
		}else{
			$this->data['errors'] = $this->form_validation->error_array();
			$this->data['success'] = false;
		}
		$this->_render_json();
		
	}
	
	public function profileupdate2(){
		$usession = $this->session->userdata();
		$loginuserid = $usession['user_id'];
		
		$this->form_validation->set_rules('experience_title', $this->lang->line('create_user_validation_education_label'), 'required|trim');
		$this->form_validation->set_rules('experience_company', $this->lang->line('create_user_validation_availedtotravel_label'), 'required|trim');
		$this->form_validation->set_rules('experience_location', $this->lang->line('create_user_validation_current_label'), 'required|trim');		
		$this->form_validation->set_rules('experience_desc', $this->lang->line('create_user_validation_aboutme_label'), 'required|trim');
		//Insert user interest in to user_interest table
		if ($this->form_validation->run() == TRUE) {
			$selectedCatIdArr = array();		
			
			$userProfile["user_id"] = $loginuserid;
			$userProfile["experience_title"] = $this->input->post('experience_title');
			$userProfile["experience_company"] = $this->input->post('experience_company');
			$userProfile["experience_location"] = $this->input->post('experience_location');
			$userProfile["experience_desc"] = $this->input->post('experience_desc');
			//['id' => $id]
			if($this->FrontUserModel->getprofile($loginuserid))
			{
				$this->FrontUserModel->profileUpdate( $userProfile );
			}else{
				$this->FrontUserModel->profileInsert( $userProfile );
			}
			
			$profileData = $this->FrontUserModel->getprofile($loginuserid);
			$profile_html = '';
			$profile_html .= '<h3>'.$profileData[0]['experience_title'].'</h3>';
			$profile_html .= '<h4>'.$profileData[0]['experience_company'].'</h4>';
			$profile_html .= '<h5>'.$profileData[0]['experience_location'].'<i class="fa fa-map-marker"></i></h4>';
			$profile_html .= '<p>'.$profileData[0]['experience_desc'].'</p>';
			
			$this->data['responsehtml'] = $profile_html;
			$this->data['errors'] = [];
			$this->data['success'] = true;
			$this->session->set_flashdata('success', 'Profile update Successfully');
		}else{
			$this->data['errors'] = $this->form_validation->error_array();
			$this->data['success'] = false;
		}
		$this->_render_json();
		
	}
	

}
