<?php
/*
	Install Uninstall Upgrade AutoStat System Code
*/
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
//start to put your own code 
$sql = <<<EOF
DROP TABLE IF  EXISTS `pre_aljwb_user`;
EOF;

runquery($sql);
//finish to put your own code
$finish = TRUE;
?>