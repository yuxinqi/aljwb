<?php
/*
	Install Uninstall Upgrade AutoStat System Code
*/
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

//start to put your own code 
$sql = <<<EOF
CREATE TABLE IF NOT EXISTS `pre_aljwb_user` (
  `uid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `openid` varchar(255) NOT NULL,
  `refresh_token` varchar(255) NOT NULL,
  `access_token` varchar(255) NOT NULL,
  `expires_in` int(10) NOT NULL,
  `dateline` int(10) NOT NULL,
  PRIMARY KEY (`uid`)
);
EOF;
runquery($sql);
//finish to put your own code
$finish = TRUE;
?>