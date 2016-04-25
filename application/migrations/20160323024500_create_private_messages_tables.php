<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_private_messages_tables extends CI_Migration {

    public function up() {

        // drop table if exist
        $this->down();
        $this->db->query("SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO'");

        $this->db->query("
                CREATE TABLE `msg_messages` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `thread_id` int(11) NOT NULL,
                  `body` text NOT NULL,
                  `priority` int(2) NOT NULL DEFAULT '0',
                  `sender_id` int(11) NOT NULL,
                  `cdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");

        $this->db->query("
                CREATE TABLE `msg_participants` (
                  `user_id` int(11) NOT NULL,
                  `thread_id` int(11) NOT NULL,
                  `cdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY (`user_id`,`thread_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        $this->db->query("
                CREATE TABLE `msg_status` (
                  `message_id` int(11) NOT NULL,
                  `user_id` int(11) NOT NULL,
                  `status` int(2) NOT NULL,
                  PRIMARY KEY (`message_id`,`user_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        $this->db->query("
                CREATE TABLE `msg_threads` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `subject` text,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");

    }

    public function down() {

        // drop table
        $this->db->query('DROP TABLE IF EXISTS msg_messages');
        $this->db->query('DROP TABLE IF EXISTS msg_participants');
        $this->db->query('DROP TABLE IF EXISTS msg_status');
        $this->db->query('DROP TABLE IF EXISTS msg_threads');
    }

}