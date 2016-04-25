<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_alter_cmspages extends CI_Migration {

    public function up()
    {
		 $this->db->query("ALTER TABLE `cmspages` CHANGE COLUMN `name` `title`  varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `id`;");
		 $this->db->query("ALTER TABLE `cmspages` ADD COLUMN `slug`  varchar(255) NULL AFTER `title`;");
 }
}
