<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_clientinfo_table extends CI_Migration {

	public function up() {
		// Drop table 'groups' if it exists
		$this->down();

		$this->db->query("CREATE TABLE IF NOT EXISTS `clients_info` (
									  `id` int(11) NOT NULL,
									  `user_id` int(11) NOT NULL,
									  `company_name` varchar(255) NOT NULL,
									  `company_website` varchar(255) NOT NULL,
									  `position` varchar(255) NOT NULL,
									  `updated` datetime NOT NULL,
									  `created` datetime NOT NULL
									) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;");

		$this->db->query("ALTER TABLE `clients_info` ADD PRIMARY KEY (`id`);");
		$this->db->query("ALTER TABLE `clients_info` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

	}

	public function down() {
		$this->dbforge->drop_table('clients_info', TRUE);
	}
}
