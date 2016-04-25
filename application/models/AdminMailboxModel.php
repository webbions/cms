<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class AdminMailboxModel extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->pk = "id";
		$this->tablename = "private_messages";
	}

}