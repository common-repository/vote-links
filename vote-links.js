function vote(opt, id, ABSPATH) {

	var elemid = "vote-daemon-" + id;
	
	var gets = "?vote=true&opt=" + opt + "&id=" + id;

	var daemon = document.getElementById(elemid);
	daemon.src = ABSPATH + "wp-content/plugins/vote-links/vote-daemon.php" + gets;
		
};