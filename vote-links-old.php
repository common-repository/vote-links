<?php
/*
Plugin Name: Vote-Links
Plugin URI: http://dunvegan.ath.cx/blogs/sam/
Description: Adds Icons after voting links and also has database and graph functionality.
Version: 1.0
Author: Samuel Elliott
Author URI: http://dunvegan.ath.cx/~sam/
*/
/*
  Copyright 2008  Samuel Elliott  (email : ashe613@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require(ABSPATH . 'wp-content/plugins/vote-links/vote-links-admin.php');
require(ABSPATH . 'wp-content/plugins/vote-links/vote-links-shortcodes.php');
require(ABSPATH . 'wp-content/plugins/vote-links/vote-links-admin.php');

function vote-links_new_table() {

	global $wpdb;
	global $table_name;
	
	if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
	
	$sql = "CREATE TABLE " . $table_name . " (
	  vote_id mediumint(9) UNIQUE NOT NULL AUTO_INCREMENT,
	  vote_title varchar(50) NOT NULL,
	  votes_for mediumint(9) NOT NULL,
	  votes_against mediumint(9) NOT NULL,
	  votes_abstain mediumint(9) NOT NULL,
	  vote_is_active enum('true', 'false') NOT NULL,
	  UNIQUE KEY vote_id (id)
	);";
	
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	
	$welcome_title = 'Test Vote';
	$welcome_for = 33;
	$welcome_against = 21;
	$welcome_abstain = 6;
	$welcome_is_active = 'false';
	
	$insert = "INSERT INTO " . $table_name .
		" (vote_title, votes_for, votes_against, votes_abstain, vote_is_active) " .
		"VALUES ('" . $wpdb->escape($welcome_title) . "','" . $wpdb->escape($welcome_for) . "','" . $wpdb->escape($welcome_against) . "','" . $wpdb->escape($welcome_abstain) . "','" . $wpdb->escape($welcome_is_active) . "')";

	$welcome_insert = $wpdb->query( $insert );
	
	};
	
	};
	
register_activation_hook(__FILE__,'vote-links_new_table');

function vote-links_for() {

	global $wpdb;
	global $id;
	global $table_name;
	
	$prev_votes = $wpdb->get_var("SELECT votes_for FROM $wpdb->$table_name WHERE vote_id='" . $id . "'");
	$new_votes = $prev_votes + 1;
	$wpdb->query("UPDATE " . $table_name . " SET votes_for=" . $wpdb->escape($new_votes) . " WHERE vote_id ='" . $id . "'");
	
	};
	
function vote-links_against() {

	global $wpdb;
	global $id;
	global $table_name;
	
	$prev_votes = $wpdb->get_var("SELECT votes_against FROM $wpdb->$table_name WHERE vote_id='" . $id . "'");
	$new_votes = $prev_votes + 1;
	$wpdb->query("UPDATE " . $table_name . " SET votes_against=" . $wpdb->escape($new_votes) . " WHERE vote_id ='" . $id . "'");
	
	};
	
function vote-links_abstain() {

	global $wpdb;
	global $id;
	global $table_name;
	
	$prev_votes = $wpdb->get_var("SELECT votes_abstain FROM $wpdb->$table_name WHERE vote_id='" . $id . "'");
	$new_votes = $prev_votes + 1;
	$wpdb->query("UPDATE " . $table_name . " SET votes_abstain=" . $wpdb->escape($new_votes) . " WHERE vote_id ='" . $id . "'");
	
	};
?>