<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_userprofile_tables extends CI_Migration {

	public function up() {
		// Drop table 'groups' if it exists
		$this->down();

		$this->db->query("CREATE TABLE `userprofileaudio` (
									  `id` int(11) NOT NULL,
									  `userid` int(11) NOT NULL,
									  `audio` text NOT NULL,
									  `created_at` datetime NOT NULL
									) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

		$this->db->query("ALTER TABLE `userprofileaudio` ADD PRIMARY KEY (`id`);");
		$this->db->query("ALTER TABLE `userprofileaudio` MODIFY `id` int(5) NOT NULL AUTO_INCREMENT");

		$this->db->query("CREATE TABLE `userprofileimages` (
									  `id` int(11) NOT NULL,
									  `userid` int(11) NOT NULL,
									  `image` text NOT NULL,
									  `created_at` datetime NOT NULL
									) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

		$this->db->query("ALTER TABLE `userprofileimages` ADD PRIMARY KEY (`id`);");
		$this->db->query("ALTER TABLE `userprofileimages` MODIFY `id` int(5) NOT NULL AUTO_INCREMENT");


		$this->db->query("CREATE TABLE `userprofilevideo` (
									  `id` int(11) NOT NULL,
									  `userid` int(11) NOT NULL,
									  `video` text NOT NULL,
									  `created_at` datetime NOT NULL
									) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

		$this->db->query("ALTER TABLE `userprofilevideo` ADD PRIMARY KEY (`id`);");
		$this->db->query("ALTER TABLE `userprofilevideo` MODIFY `id` int(5) NOT NULL AUTO_INCREMENT");

		
	}

	public function down() {
		$this->dbforge->drop_table('userprofileaudio', TRUE);
		$this->dbforge->drop_table('userprofileimages', TRUE);
		$this->dbforge->drop_table('userprofilevideo', TRUE);		
	}
}
