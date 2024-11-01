<?php
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

add_action('admin_menu', 'vote-links_admin_panel');

function vote-links_admin_panel() {

	add_management_page('Votes', 'Votes', 1, __FILE__, vote-links_admin);
	
	};

function vote-links_admin() {

	global $wpdb;
	global $table_name;
	
	if (ISSET($_GET['edit']){
		$row = $wpdb->get_row("SELECT * FROM $wpdb->$table_name WHERE vote_id = $_GET['id']", ARRAY_A);
		if ($row['vote_is_active']) {
			$checker = "checked=\"checked\"";
		} else {
			$checker = "";
		};
		
		echo "<h2>Edit Vote</h2>
		<form action=\"__FILE__\" method=\"get\" target=\"_self\">
		wp_nonce_field('update-options')
		<table>
		<thead>
		<tr><th scope=\"col\" style=\"text-align: center\">Id</th><thscope=\"col\">Title</th><th scope=\"col\">Votes For</th><th scope=\"col\">Votes Against</th><th scope=\"col\">Votes Abstained</th><th scope=\"col\">Active</th></tr>
		</thead>
		<tbody id=\"the-list\">
		<tr id=\"row-$row['vote_id']\" class=\"alternate\"><th scope=\"row\" style=\"text-align: center\">$row['vote_id']</th><td><input type=\"text\" value=\"$row['vote_title']\"/></td><td>$row['votes_for']</td><td>$row['votes_against']</td><td>$row['votes_abstain']</td><td><input name=\"is_active\" type=\"checkbox\" $checker value=\"true\"/></td></tr>
		</tbody>
		<table>
		<input type=\"hidden\" name=\"edit2\" value=\"true\" />
		<input type=\"submit\" id=\"votes-edit-submit\" value=\"Submit &#187;\" class=\"button\" />
		</form>";
		
	} elseif (ISSET($_GET['edit2']){
	
		if ($_GET['is_active'] == "true") {
			$active = 'true';
		} else {
			$active = 'true';
		};
	
		$wpdb->query("UPDATE " . $table_name . " SET vote_title=" . $wpdb->escape($_GET['title']) . ", vote_is_active=" . $wpdb->escape($active) . " WHERE vote_id ='" . $_GET['id'] . "'");
		
		echo "<h2>Votes</h2><p>This is where you can manage your polls of people voting for/against things.</p><p><b>Table Updated</b></p>";
		
		vote-links_admin_table();
		
	} elseif (ISSET($_GET['view']){
		$row = $wpdb->get_row("SELECT * FROM $wpdb->$table_name WHERE vote_id = $_GET['view']", ARRAY_A);
		$row-total = $row['votes_abstain'] + $row['votes_against'] + $row['votes_for'];
		echo "<h2>View Vote</h2>
		<ul><li><b>Votes For:</b> $row['votes_for']
		</li><li><b>Votes Against</b> $row['votes_against']
		</li><li><b>Votes Abstained</b> $row['votes_abstain']
		</li><li><b>Total Votes</b> $row-total
		</li></ul>
		
		[graph id=$_GET['id']]";
		
	} elseif (ISSET($_GET['new']){
		echo "<h2>New Vote</h2>
		<form action=\"__FILE__\" method=\"get\" target=\"_self\">
		<table>
		<thead>
		<tr><th scope=\"col\" style=\"text-align: center\">Id</th><thscope=\"col\">Title</th><th scope=\"col\">Votes For</th><th scope=\"col\">Votes Against</th><th scope=\"col\">Votes Abstained</th><th scope=\"col\">Active</th></tr>
		</thead>
		<tbody id=\"the-list\">
		<tr id=\"row-new\" class=\"alternate\"><th scope=\"row\" style=\"text-align: center\">Auto</th><td><input type=\"text\" name=\"title\"/></td><td>0</td><td>0</td><td>0</td><td><input type=\"checkbox\" name=\"is_active\" checked=\"checked\"/></td></tr>
		</tbody>
		<table>
		<input type=\"hidden\" name=\"new2\" value=\"true\" />
		<input type=\"submit\" id=\"votes-edit-submit\" value=\"Submit &#187;\" class=\"button\" />
		</form>";
		
	} elseif (ISSET($_GET['new2']){	
		
		if ($_GET['is_active'] == "true") {
			$active = 'true';
		} else {
			$active = 'true';
		};
		
		$wpdb->query("UPDATE " . $table_name . " SET vote_title=" . $wpdb->escape($_GET['title']) . ", vote_is_active=" . $wpdb->escape($active) . " WHERE vote_id ='" . $_GET['id'] . "'");
		
		echo "<h2>Votes</h2><p>This is where you can manage your polls of people voting for/against things.</p><p><b>Table Updated</b></p>";
		
		vote-links_admin_table();
		
	} else {
	
		echo "<h2>Votes</h2><p>This is where you can manage your polls of people voting for/against things.</p>";
	
		vote-links_admin_table();
	
	};
	
	};
	
function vote-links_admin_table(){

	$small = 1;
	$big = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->$table_name;");
	
	echo "<table>
	<thead>
	<tr><th scope=\"col\" style=\"text-align: center\">Id</th><thscope=\"col\">Title</th><th scope=\"col\">Votes For</th><th scope=\"col\">Votes Against</th><th scope=\"col\">Votes Abstained</th><th scope=\"col\">Active</th><th scope=\"col\" colspan=\"3\" style=\"text-align: center\">Action</th></tr>
	</thead>
	<tbody id=\"the-list\">";
	while ($small <= $big) {
	
		if ($small %2){
			$class = "alternate";
		} else {
			$class = "";
		};
		$row = $wpdb->get_row("SELECT * FROM $wpdb->$table_name WHERE vote_id = $small", ARRAY_A);
		echo "<tr id=\"row-$row-['vote_id']\" class=\"$class\"><th scope=\"row\" style=\"text-align: center\">$row['vote_id']</th><td>$row['vote_title']</td><td>$row['votes_for']</td><td>$row['votes_against']</td><td>$row['votes_abstain']</td><td>$row['vote_is_active']</td><td><a href=\"?view=$row['vote_id']\">View></a></td><td><a href=\"?edit=$row['vote_id']\">Edit</a></td><td><a href=\"?delete=$row['vote_id']\">Delete</a></td></tr>";
	
		$small ++;
	};
	echo "</tbody>
	<table>";
	};