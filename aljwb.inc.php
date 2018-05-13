<?php

/*
 * 作者: Discuz!亮剑工作室
 * 技术支持: http://www.dzx30.com/
 * 客服QQ: 190360183
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

$config =$_G['cache']['plugin']['aljwb'];
if (empty($_GET['code'])) {
    header('Location: ' .aljCreateOauthUrlForCode(1));
} else {
    $accessTokenArray = json_decode(aljGetAccessToken($_GET['code']),true);//access_token,expires_in,refresh_token
    if ($accessTokenArray['uid']) {
        $openid = $accessTokenArray['uid'];
        $referer = !empty($_GET['referer']) ? strip_tags($_GET['referer']) : $_SERVER['HTTP_REFERER'];
        $referer = $referer ? $referer : 'forum.php';
        $connectUser = DB::fetch_first('select * from %t where openid=%s',array('aljwb_user',$openid));
        if($connectUser){
            $user = getuserbyuid($connectUser['uid']);
            require_once libfile('function/member');
            setloginstatus($user, 2592000);
            showmessage(lang("plugin/aljwb","aljwb_inc_php_5"),$referer);
        }else{
            if($_G['uid']){
                DB::insert('aljwb_user',array(
                    'uid' => $_G['uid'],
                    'username' => $_G['username'],
                    'openid' => $openid,
                    'dateline' => TIMESTAMP,
                    'refresh_token' => $accessTokenArray['refresh_token'],
                    'access_token' => $accessTokenArray['access_token'],
                    'expires_in' => $accessTokenArray['expires_in'],
                    'dateline' => TIMESTAMP,
                ));
                showmessage(lang("plugin/aljwb","aljwb_inc_php_6"),$referer);
            }else{
                showmessage(lang("plugin/aljwb","aljwb_inc_php_7"),'member.php?mod=register&referer='.$referer);
            }
        }
    }
}

function aljCreateOauthUrlForCode($redirectUrl) {
    global $_G,$config;
    $urlObj["client_id"] = $config['appid'];
    $urlObj["redirect_uri"] = urlencode($config['url']."plugin.php?id=aljwb");
    $urlObj["response_type"] = "code";
    $urlObj["scope"] = "get_user_info";
    $bizString = aljFormatBizQueryParaMap($urlObj, false);

    return "https://api.weibo.com/oauth2/authorize?".$bizString;
}
function aljFormatBizQueryParaMap($paraMap, $urlencode)
{
    $buff = "";
    ksort($paraMap);
    foreach ($paraMap as $k => $v) {
        if ($urlencode) {
            $v = urlencode($v);
        }
        $buff .= $k . "=" . $v . "&";
    }
    if (strlen($buff) > 0) {
        $reqPar = substr($buff, 0, strlen($buff) - 1);
    }
    return $reqPar;
}
function aljGetAccessToken($code){
    global $_G,$config;
    $url = 'https://api.weibo.com/oauth2/access_token?';
    $urlObj["grant_type"] = 'authorization_code';
    $urlObj["client_id"] = $config['appid'];
    $urlObj["client_secret"] = $config['appkey'];
    $urlObj["code"] = $code;
    $urlObj["redirect_uri"] = urlencode($config['url']."plugin.php?id=aljwb");
    $bizString = aljFormatBizQueryParaMap($urlObj, false);
    $url = $url.$bizString;
    return aljPostData($url,$urlObj,'','');
}
function aljPostData($url, $para, $input_charset = '', $follow=0) {
    //debug($url);
    //global $_G;
    if (trim($input_charset) != '') {
        $url = $url."_input_charset=".$input_charset;
    }
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_HEADER, 0 );
    curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);
    //curl_setopt ($ch,CURLOPT_REFERER,$_G['siteurl']);
    if($para){
        curl_setopt($curl,CURLOPT_POST,true);
        curl_setopt($curl,CURLOPT_POSTFIELDS,$para);
    }
    if($follow) {
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 3);
    }
    $responseText = curl_exec($curl);
    $headers = curl_getinfo($curl);
    curl_close($curl);
    if($para&& $headers && $headers['url'] && $follow) {
        header('Location: '.$headers['url']);
    }
    return $responseText;
}
