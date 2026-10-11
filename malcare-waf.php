<?php
// Please validate auto_prepend_file setting before removing this file

if (file_exists('/var/www/limerickstore/wp-content/plugins/blogvault-real-time-backup/protect/prepend/ignitor.php') && !defined("MCDATAPATH")) {
	define("MCDATAPATH", '/var/www/limerickstore/wp-content/mc_data/');
	define("MCCONFKEY", '00d920d48aa04e17d10ffdba7dd6d037');
	include_once('/var/www/limerickstore/wp-content/plugins/blogvault-real-time-backup/protect/prepend/ignitor.php');
}
