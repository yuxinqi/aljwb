<?php

/*
 * lang("plugin/aljwb","aljwb_inc_php_1"): Discuz!lang("plugin/aljwb","aljwb_inc_php_2")
 * lang("plugin/aljwb","aljwb_inc_php_3"): http://www.dzx30.com
 * lang("plugin/aljwb","aljwb_inc_php_4")QQ: 190360183
 */


if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
class plugin_aljwb{
    function common(){
        global $_G;
    }
    function global_usernav_extra1() {
        global $_G;
        if($_G['uid']){
            $connectUser = DB::fetch_first('select * from %t where uid=%d',array('aljwb_user',$_G['uid']));
            if($connectUser){
                return;
            }
            return '<span class="pipe">|</span><a href="plugin.php?id=aljwb"><img src="source/plugin/aljwb/img/weibo_bind.png" class="qq_bind" align="absmiddle" /></a>';
        }
    }
    function global_login_extra() {
        include template('aljwb:wb');
        return $return;
    }
    /**lang("plugin/aljwb","hook_class_php_5")**/
    function global_login_text() {
        global $_G;
        return '&nbsp;<a rel="nofollow" target="_top" href="plugin.php?id=aljwb"><img class="vm" src="source/plugin/aljwb/img/weibo_login.png"></a>';
    }
}
class plugin_aljwb_member extends plugin_aljwb{
    /**lang("plugin/aljwb","hook_class_php_6")**/
    function logging_method(){
        global $_G;
        return '<a rel="nofollow" target="_top" href="plugin.php?id=aljwb"><img class="vm" src="source/plugin/aljwb/img/weibo_login.png"></a>';

    }
    /**lang("plugin/aljwb","hook_class_php_7")**/
    function register_logging_method(){
        return '<a rel="nofollow" target="_top" href="plugin.php?id=aljwb"><img class="vm" src="source/plugin/aljwb/img/weibo_login.png"></a>';
    }
}
class plugin_aljwb_forum extends plugin_aljwb {
    /**lang("plugin/aljwb","hook_class_php_8")**/
    function global_login_text(){
        return '&nbsp;<a rel="nofollow" target="_top" href="plugin.php?id=aljwb"><img class="vm" src="source/plugin/aljwb/img/weibo_login.png"></a>';
    }
}