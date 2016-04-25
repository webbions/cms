<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_user_interests extends CI_Migration {

	public function up() {
		// Drop table 'groups' if it exists
		$this->down();

		$this->db->query("CREATE TABLE `user_interests` (
									  `id` int(11) NOT NULL,
									  `userid` int(11) NOT NULL,
									  `category_id` int(11) NOT NULL
									) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

		$this->db->query("ALTER TABLE `user_interests` ADD PRIMARY KEY (`id`);");
		$this->db->query("ALTER TABLE `user_interests` MODIFY `id` int(5) NOT NULL AUTO_INCREMENT");

	}

	public function down() {
		$this->dbforge->drop_table('user_interests', TRUE);
	}
}
