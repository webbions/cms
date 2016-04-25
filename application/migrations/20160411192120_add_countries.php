<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_countries extends CI_Migration {

	public function up() {
		// Drop table 'groups' if it exists
		$this->down();

		$this->db->query("CREATE TABLE `countries` (
									  `id` int(5) NOT NULL,
									  `countryCode` char(2) NOT NULL DEFAULT '',
									  `countryName` varchar(45) NOT NULL DEFAULT '',
									  `currencyCode` char(3) DEFAULT NULL
									) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

		$this->db->query("ALTER TABLE `countries` ADD PRIMARY KEY (`id`);");
		$this->db->query("ALTER TABLE `countries` MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;");

	}

	public function down() {
		$this->dbforge->drop_table('settings', TRUE);
	}
}
