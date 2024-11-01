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

global $wpdb
global $vote_table = $wpdb->prefix . "vote-links";


if (!class_exists("vote-links")){
	
	class vote-links {
		
		function vote() {
			global $wpdb;
			global $vote_table;
			global $extracted;
			
			$curr_votes = $wpdb->get_var("SELECT votes_" . $extracted['opt'] . " FROM " . $wpdb->$vote_table . " WHERE vote_id='" . $extracted['id'] . "'");
			$new_votes = $prev_votes + 1;
			$wpdb->query("UPDATE " . $wpdb->$vote_table . " SET votes_" . $extracted['opt'] . "=" . $wpdb->escape($new_votes) . " WHERE vote_id ='" . $extracted['id'] . "'");
			
		};
		
		
		function activate() {
		
			global $wpdb;
			global $vote_table;
	
			if( $wpdb->get_var("SHOW TABLES LIKE '" . $vote_table . "'") != $vote_table) {
	
				$sql = "CREATE TABLE " . $vote_table . " (
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
	
				$insert = "INSERT INTO " . $vote_table . " (vote_title, votes_for, votes_against, votes_abstain, vote_is_active) " . "VALUES ('" . $wpdb->escape($welcome_title) . "','" . $wpdb->escape($welcome_for) . "','" . $wpdb->escape($welcome_against) . "','" . $wpdb->escape($welcome_abstain) . "','" . $wpdb->escape($welcome_is_active) . "')";

				$welcome_insert = $wpdb->query( $insert );
	
			};
	
		};
		
		function deactivate() {
		
			global $wpdb;
			global $vote_table;
			
			$wpdb->query("UPDATE " . $wpdb->$vote_table . " SET vote_is_active='false'");
		
		};
		
		function links($atts) {

			//[vote id=###]
	
			global $extracted = extract(shortcode_atts(array(
				'id' => 0,
				), $atts));
		
			return "
			<div id=\"vote-$extracted['id']\"> 
			<h4>Vote</h4>
			<ul><li><a href=\"javascript:vote(for, $extracted['id'], ABSPATH)\" rev=\"vote-for\">For</a>
			</li><li><a href=\"javascript:vote(against, $extracted['id'], ABSPATH)\" rev=\"vote-against\">Against</a>
			</li><li><a href=\"javascript:vote(abstain, $extracted['id'], ABSPATH)\" rev=\"vote-abstain\">Abstain</a>
			</li></ul>
			<iframe src=\"\" frameborder=0 height=0 width=0 name=\"daemon-$extracted['id']\" id=\"vote-daemon\"
			</div>
			'";
		
		};
		
	};	

};

if (class_exists("vote-links")) {

	$voteLinks = new vote-links(); 
	
};

if (isset($voteLinks)){

	//Actions
	
	//add_management_page('Votes', 'Votes', 1, ABSPATH . 'wp-content/plugins/vote-links/vote-links-admin.php', vote-links_admin);
	
	//Filters
	
	
	
	//Hooks
	
	register_activation_hook(__FILE__,'activate');
	register_deactivation_hook(__FILE__, 'deactivate');
	
};

?>