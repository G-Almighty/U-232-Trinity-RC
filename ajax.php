<?php
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'bittorrent.php');
require_once INCL_DIR.'user_functions.php';

dbconn(true);
loggedinorreturn();
if (($unread_m = $cache->get($cache_keys['inbox_new'].$CURUSER['id'])) === false) {
    ($res = sql_query('SELECT count(id) FROM messages WHERE receiver='.sqlesc($CURUSER['id']).' && unread="yes" AND location = "1"')) || sqlerr(__FILE__,
        __LINE__);
    $arr = $res->fetch_row();
    $unread_m = (int)$arr[0];
    $cache->set($cache_keys['inbox_new'].$CURUSER['id'], $unread_m, $TRINITY20['expires']['unread']);
}
($result = sql_query("SELECT COUNT(id) FROM messages WHERE receiver = ".sqlesc($CURUSER['id'])."  AND unread = 'yes' AND location = '1'")) || sqlerr(__FILE__,
    __LINE__);
$htmlout = '';
if (isset($unread_m) && !empty($unread_m)) {
    $htmlout .= "<span class='badge warning'>".$unread_m."</span>";
}
echo $htmlout;
?>
