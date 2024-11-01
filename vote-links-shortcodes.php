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
function vote-links_shortcode($atts) {

	//[vote opt="(for|against|abstain)" id=###]
	
	$extracted = extract(shortcode_atts(array(
		'opt' => 'abstain',
		'id' => 0,
		), $atts));
		
	add_action('wp_head', 'vote-links_wp_head');
		
	return '<a href="#" rev="vote-' . $extracted['opt'] . '">' . $extracted['opt'] . '</a>';
	};	
	
add_shortcode('vote', 'vote-links_shortcode');	
	
function vote-links_graphing_shortcode($atts) {
	
	//[graph id=###]
	
	$extracted = extract(shortcode_atts(array(
		'id' => 0,
		), $atts));
	
	global $wpdb;
	global $table_name;
	
	$graph_output = $wpdb->get_row("SELECT * FROM $wpdb->$table_name WHERE vote_id='" . $extracted['id'] . "'", ARRAY_A);

	$img_print = "http://chart.apis.google.com/chart?cht=p&chd=t:" . $graph_output['votes_for'] . "," . $graph_output['votes_against'] . "," . $graph_output['votes_abstain'] . "&chs=640x320chl=For|Against|Abstain&chtt=" . $graph_output['vote_title'];
	
	add_action('wp_head', 'vote-links_wp_head');
	
	return '<img src="' . $img_print . '" alt="' . $graph_output['vote_title'] . '<br /> For: ' . $graph_output['votes_for'] . '<br /> Against: ' . $graph_output['votes_against'] . '<br /> Abstain: ' . $graph_output['votes_abstain'] . '" />';
	
	};
	
add_shortcode('graph', 'vote-links_graphing_shortcode');

function vote-links_wp_head() {

	echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/vote-links/vote-links.css" />';
	};
	
	?>
