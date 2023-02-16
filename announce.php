<?php
/**
 * -------   U-232 Codename Trinity   ----------*
 * ---------------------------------------------*
 * --------  @authors U-232 Team  --------------*
 * ---------------------------------------------*
 * -----  @site https://u-232.duckdns.org/  ----*
 * ---------------------------------------------*
 * -----  @copyright 2020 U-232 Team  ----------*
 * ---------------------------------------------*
 * ------------  @version V6  ------------------*
 */

require_once(__DIR__. '/include/ann_config.php');
require_once(INCL_DIR.'ann_functions.php');
require_once(CACHE_DIR.'cache_keys.php');
if (isset($_SERVER['HTTP_COOKIE']) || isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) || isset($_SERVER['HTTP_ACCEPT_CHARSET'])) {
    exit('It takes 46 muscles to frown but only 4 to flip \'em the bird.');
}
if (XBT_TRACKER) {
    err('Please redownload this torrent from the tracker');
}
gzip();
$parts = [];
if (!isset($_GET['torrent_pass']) || !preg_match('/^[0-9a-fA-F]{32}$/i', $_GET['torrent_pass'], $parts)) {
    err('Invalid Passkey');
} else {
    $GLOBALS['torrent_pass'] = $parts[0];
}
foreach ([
             'info_hash',
             'peer_id',
             'event',
             'ip',
             'localip',
         ] as $x) {
    if (isset($_GET["$x"])) {
        $GLOBALS[$x] = '' .$_GET[$x];
    }
}
foreach ([
             'port',
             'downloaded',
             'uploaded',
             'left',
         ] as $x) {
    $GLOBALS[$x] = 0 + $_GET[$x];
}
foreach ([
             'torrent_pass',
             'info_hash',
             'peer_id',
             'port',
             'downloaded',
             'uploaded',
             'left',
         ] as $x) {
    if (!isset($x)) {
        err("Missing key: $x");
    }
}
foreach ([
             'info_hash',
             'peer_id',
         ] as $x) {
    if (strlen($GLOBALS[$x]) != 20) {
        err("Invalid $x (".strlen($GLOBALS[$x]). ' - ' .urlencode($GLOBALS[$x]). ')');
    }
}
unset($x);
$info_hash = $info_hash;
$ip = $_SERVER['REMOTE_ADDR'];
$port = (int)$port;
$downloaded = (int)$downloaded;
$uploaded = (int)$uploaded;
$left = (int)$left;
$rsize = 30;
foreach ([
             'num want',
             'numwant',
             'num_want',
         ] as $k) {
    if (isset($_GET[$k])) {
        $rsize = (int)$_GET[$k];
        break;
    }
}
if ($uploaded < 0) {
    err('invalid uploaded (less than 0)');
}
if ($downloaded < 0) {
    err('invalid downloaded (less than 0)');
}
if ($left < 0) {
    err('invalid left (less than 0)');
}
if (!$port || $port > 0xffff) {
    err('invalid port');
}
if (!isset($event)) {
    $event = '';
}
$seeder = $left === 0 ? 'yes' : 'no';
$user = get_user_from_torrent_pass($torrent_pass);
if (!$user) {
    err('Invalid passkey. Please redownload the torrent from '.$TRINITY20['baseurl']);
}
$userid = (int)$user['id'];
$user['perms'] = (int)$user['perms'];
if ($user['enabled'] == 'no') {
    err('Permission denied, you\'re not enabled');
}
//== Start ip logger - Melvinmeow, Mindless, pdq
if (ANN_IP_LOGGING == 1) {
    $no_log_ip = ($user['perms'] & bt_options::PERMS_NO_IP);
    if ($no_log_ip !== 0) {
        $ip = '127.0.0.1';
        $userid = (int)$user['id'];
    }
    if ($no_log_ip === 0) {
        ($res = ann_sql_query('SELECT * FROM ips WHERE ip = ' .ann_sqlesc($ip). ' AND userid =' .ann_sqlesc($userid))) || ann_sqlerr(__FILE__, __LINE__);
        if ($res->num_rows == 0) {
            ann_sql_query('INSERT LOW_PRIORITY INTO ips (userid, ip, lastannounce, type) VALUES (' .ann_sqlesc($userid). ', ' .ann_sqlesc($ip). ', ' .TIME_NOW.",'announce')") || ann_sqlerr(__FILE__,
                __LINE__);
        } else {
            ann_sql_query('UPDATE LOW_PRIORITY ips SET lastannounce = ' .TIME_NOW. ' WHERE ip = ' .ann_sqlesc($ip). ' AND userid =' .ann_sqlesc($userid)) || ann_sqlerr(__FILE__,
                __LINE__);

        }
        $cache->delete($cache_keys['ip_history'].$userid);
    }
}
// End Ip logger
$realip = $_SERVER['REMOTE_ADDR'];
$torrent = get_torrent_from_hash($info_hash);
if (!$torrent) {
    err('torrent query error - contact site admin');
}
$torrentid = (int)$torrent['id'];
//err("your id is ".$torrentid);
$torrent_modifier = get_slots($torrentid, $userid);
$torrent['freeslot'] = $torrent_modifier['freeslot'];
$torrent['doubleslot'] = $torrent_modifier['doubleslot'];
$happy_multiplier = ($TRINITY20['happy_hour'] ? get_happy($torrentid, $userid) : 0);
$fields = 'seeder, peer_id, ip, port, uploaded, downloaded, userid, last_action, ('.TIME_NOW.' - last_action) AS announcetime, last_action AS ts, '.TIME_NOW.' AS nowts, prev_action AS prevts';
//== Wantseeds - Retro
$limit = '';
if ($torrent['numpeers'] > $rsize) {
    $limit = "ORDER BY RAND() LIMIT $rsize";
}
// if user is a seeder, then only supply leechers.
$wantseeds = '';
if ($seeder == 'yes') {
    $wantseeds = 'AND seeder = "no"';
}
($res = ann_sql_query("SELECT $fields FROM peers WHERE torrent = $torrentid $wantseeds $limit")) || ann_sqlerr(__FILE__, __LINE__);
unset($wantseeds);
//== compact mod
if ($_GET['compact'] != 1) {
    $resp = 'd'.benc_str('interval').'i'.$TRINITY20['announce_interval'].'e'.benc_str('private').'i1e'.benc_str('peers').'l';
} else {
    $resp = 'd'.benc_str('interval').'i'.$TRINITY20['announce_interval'].'e'.benc_str('private').'i1e'.benc_str('min interval').'i'. 300 .'e5:'.'peers';
}
$peer = [];
$peer_num = 0;
while ($row = $res->fetch_assoc()) {
    if ($_GET['compact'] != 1) {
        $row['peer_id'] = str_pad($row['peer_id'], 20);
        if ($row['peer_id'] === $peer_id) {
            $self = $row;
            continue;
        }
        $resp .= 'd' .benc_str('ip').benc_str($row['ip']);
        if (!$_GET['no_peer_id']) {
            $resp .= benc_str('peer id').benc_str($row['peer_id']);
        }
        $resp .= benc_str('port'). 'i' .$row['port']. 'e' . 'e';
    } else {
        $peer_ip = explode('.', $row['ip']);
        $peer_ip = pack('C*', $peer_ip[0], $peer_ip[1], $peer_ip[2], $peer_ip[3]);
        $peer_port = pack('n*', (int)$row['port']);
        $time = (int)((TIME_NOW % 7680) / 60);
        if ($_GET['left'] == 0) {
            $time += 128;
        }
        $time = pack('C', $time);
        $peer[] = $time.$peer_ip.$peer_port;
        $peer_num++;
    }
}
if ($_GET['compact'] != 1) {
    $resp .= 'ee';
} else {
    $o = '';
    for ($i = 0; $i < $peer_num; $i++) {
        $o .= substr($peer[$i], 1, 6);
    }
    $resp .= strlen($o ?? '').':'.$o.'e';
}
$selfwhere = 'torrent=' .ann_sqlesc($torrentid). ' AND ' .hash_where('peer_id', $peer_id);
if (!isset($self)) {
    ($res = ann_sql_query("SELECT $fields FROM peers WHERE $selfwhere")) || ann_sqlerr(__FILE__, __LINE__);
    $row = $res->fetch_assoc();
    if ($row) {
        $userid = (int)$row['userid'];
        $self = $row;
    }
}
//// Up/down stats shit////////////////////////////////////////////////////////////
$useragent = substr($peer_id, 0, 8);
$agentarray = [
    'R34',
    '-AZ21',
    '-AZ22',
    '-AZ24',
    'AZ2500BT',
    'BS',
    'exbc',
    '-TS',
    'Mbrst',
    '-BB',
    '-SZ',
    'XBT',
    'turbo',
    'A301',
    'A310',
    '-UT11',
    '-UT12',
    '-UT13',
    '-UT14',
    '-UT15',
    'FUTB',
    '-BC',
    'LIME',
    'eX',
    '-ML',
    'FRS',
    '-AG',
];
foreach ($agentarray as $bannedclient) {
    if (str_contains ($useragent, $bannedclient)) {
        err('Client is banned. Please use uTorrent 1.6 > or Azureus 2.5 >!');
    }
}
if ($torrent['vip'] == 1 && $user['class'] < UC_VIP) {
    err('VIP Access Required, You must be a VIP In order to view details or download this torrent! You may become a Vip By Donating to our site. Donating ensures we stay online to provide you with more Vip-Only Torrents!');
}
$user_updateset = [];
if (!isset($self)) {
    ($valid_qry = ann_sql_query('SELECT COUNT(*) FROM peers WHERE torrent=' .ann_sqlesc($torrentid). ' AND torrent_pass=' .ann_sqlesc($torrent_pass))) || ann_sqlerr(__FILE__,
        __LINE__);
    $valid = $valid_qry->fetch_row();
    if ($valid[0] >= 3 && $seeder == 'yes') {
        err('Connection limit exceeded!');
    }
    if ($left > 0 && $user['class'] < UC_VIP && $TRINITY20['wait_times'] || $TRINITY20['max_slots']) {
        $ratio = (($user['downloaded'] > 0) ? ($user['uploaded'] / $user['downloaded']) : 1);
        if ($TRINITY20['wait_times']) {
            $gigs = $user['uploaded'] / (1024 * 1024 * 1024);
            $elapsed = floor((TIME_NOW - $torrent['ts']) / 3600);
            if ($ratio < 0.5 || $gigs < 5) {
                $wait = 48;
            } elseif ($ratio < 0.65 || $gigs < 6.5) {
                $wait = 24;
            } elseif ($ratio < 0.8 || $gigs < 8) {
                $wait = 12;
            } elseif ($ratio < 0.95 || $gigs < 9.5) {
                $wait = 6;
            } else {
                $wait = 0;
            }
            if ($elapsed < $wait) {
                err('Not authorized (' .($wait - $elapsed). 'h) - READ THE FAQ!');
            }
        }
        if ($TRINITY20['max_slots']) {
            if ($ratio < 0.95) {
                $max = match (true) {
                    $ratio < 0.5 => 2,
                    $ratio < 0.65 => 3,
                    $ratio < 0.8 => 5,
                    default => 10,
                };
            } else {
                $max = match ($user['class']) {
                    UC_USER => 20,
                    UC_POWER_USER => 30,
                    default => 99,
                };
            }
            if ($max > 0) {
                if (($Slot_Query = $cache->get($cache_keys['max_slots'].$userid)) === false) {
                    ($Slot_Q = sql_query('SELECT COUNT(*) AS num FROM peers WHERE userid=' .sqlesc($userid)." AND seeder='no'")) || ann_sqlerr(__FILE__,
                        __LINE__);
                    $Slot_Query = $Slot_Q->fetch_assoc();
                    $cache->set($cache_keys['max_slots'].$userid, $Slot_Query, $TRINITY20['expires']['max_slots']);
                }
                if ($Slot_Q['num'] >= $max) {
                    err("Access denied (Torrents Limit exceeded - $max) See FAQ!");
                }
            }
        }
    }
} else {
    $upthis = max(0, $uploaded - $self['uploaded']);
    $downthis = max(0, $downloaded - $self['downloaded']);
    //==sitepot
    if (($Pot_query = $cache->get($cache_keys['sitepot'])) === false) {
        $Pot_query_fields_ar_int = [
            'value_s',
            'value_i',
        ];
        $Pot_query_fields = implode(', ', array_merge($Pot_query_fields_ar_int));
        ($Pq = ann_sql_query('SELECT  ' .$Pot_query_fields." FROM avps WHERE arg = 'sitepot'")) || ann_sqlerr(__FILE__, __LINE__);
        $Pot_query = $Pq->fetch_assoc();
        if ($Pot_query !== null) {
            foreach ($Pot_query_fields_ar_int as $i) {
                $Pot_query[$i] = (int)$Pot_query[$i] ?? '';
            }
            $cache->set($cache_keys['sitepot'], $Pot_query, $TRINITY20['expires']['sitepot']);
        }
    }
    if (isset($Pot_query['value_s']) && isset($Pot_query['value_i']) && $Pot_query['value_s'] == 1 && $Pot_query['value_i'] >= 10000) {
        $downthis = 0;
    }
    //== happyhour
    if ($happy_multiplier) {
        $upthis *= $happy_multiplier;
        $downthis = 0;
    }
    //== Karma contribution system by ezero updated by putyn/Mindless
    if (($contribution = $cache->get($cache_keys['freecontribution'])) === false) {
        $contribution_fields_ar_int = [
            'startTime',
            'endTime',
        ];
        $contribution_fields_ar_str = [
            'freeleechEnabled',
            'duploadEnabled',
            'hdownEnabled',
        ];
        $contribution_fields = implode(', ', array_merge($contribution_fields_ar_int, $contribution_fields_ar_str));
        ($fc = ann_sql_query('SELECT ' .$contribution_fields. ' FROM events ORDER BY startTime DESC LIMIT 1')) || ann_sqlerr(__FILE__, __LINE__);
        $contribution = $fc->fetch_assoc();
        foreach ($contribution_fields_ar_int as $i) {
            $contribution[$i] = (int)$contribution[$i];
        }
        foreach ($contribution_fields_ar_str as $i) {
            $contribution[$i] = $contribution[$i];
        }
        $cache->set($cache_keys['freecontribution'], $contribution, $TRINITY20['expires']['contribution']);
    }
    if ($contribution['startTime'] < TIME_NOW && $contribution['endTime'] > TIME_NOW) {
        if ($contribution['freeleechEnabled'] == 1) {
            $downthis = 0;
        }
        if ($contribution['duploadEnabled'] == 1) {
            $upthis *= 2;
            $downthis = 0;
        }
        if ($contribution['hdownEnabled'] == 1) {
            $downthis /= 2;
        }
    }
    if ($upthis > 0 || $downthis > 0) {
        $isfree = $isdouble = $issilver = '';
        include(CACHE_DIR.'free_cache.php');
        if (isset($free)) {
            foreach ($free as $fl) {
                $isfree = ($fl['modifier'] == 1 || $fl['modifier'] == 3) && $fl['expires'] > TIME_NOW;
                $isdouble = ($fl['modifier'] == 2 || $fl['modifier'] == 3) && $fl['expires'] > TIME_NOW;
                $issilver = ($fl['modifier'] == 4) && $fl['expires'] > TIME_NOW;
            }
        }
        //== Silver torrents
        if ($torrent['silver'] != 0 || $issilver) {
            $upthis = $upthis;
            $downthis /= 2;
        }

        $RatioFreeCondition = ($TRINITY20['ratio_free'] ? 'downloaded = downloaded + 0' : "downloaded = downloaded + $downthis");
        $crazyhour_on = ($TRINITY20['crazy_hour'] && crazyhour_announce ());
        //$freecountdown_on = freeleech_announce();
        if ($downthis > 0 && !($crazyhour_on || $isfree || $user['free_switch'] != 0 || $torrent['free'] != 0 || $torrent['vip'] != 0 || ($torrent['freeslot'] != 0))) {
            $user_updateset[] = $RatioFreeCondition;
        }
        if ($upthis > 0) {
            if (!$crazyhour_on) {
                $user_updateset[] = 'uploaded = uploaded + ' .(($torrent['doubleslot'] != 0 || $isdouble) ? ($upthis * 2) : $upthis);
            } else {
                $user_updateset[] = "uploaded = uploaded + ($upthis*3)";
            }
        }
    }
}
//== Snatchlist and Hit and Run begin
if (portblacklisted($port)) {
    err("Port $port is blacklisted.");
} elseif ($TRINITY20['connectable_check']) {
    //== connectable checking - pdq
    $connkey = $cache_keys['conn'].md5($realip.':'.$port);
    if (($connectable = $cache->get($connkey)) === false) {
        $sockres = @fsockopen($realip, $port, $errno, $errstr, 5);
        if (!$sockres) {
            $connectable = 'no';
            $conn_ttl = 15;
        } else {
            $connectable = 'yes';
            $conn_ttl = 900;
            @fclose($sockres);
        }
        $cache->set($connkey, $connectable, $conn_ttl);
    }
}
//==
$a = 0;
($res_snatch = ann_sql_query('SELECT seedtime, uploaded, downloaded, finished, start_date AS start_snatch FROM snatched WHERE torrentid = ' .ann_sqlesc($torrentid). ' AND userid = ' .ann_sqlesc($userid))) || ann_sqlerr(__FILE__,
    __LINE__);
if ($res_snatch->num_rows > 0) {
    $a = $res_snatch->fetch_assoc();
}
if (!$mysqli->affected_rows && $seeder == 'no') {
    ann_sql_query('INSERT LOW_PRIORITY INTO snatched (torrentid, userid, peer_id, ip, port, connectable, uploaded, downloaded, to_go, start_date, last_action, seeder, agent) VALUES (' .ann_sqlesc($torrentid). ', ' .ann_sqlesc($userid). ', ' .ann_sqlesc($peer_id). ', ' .ann_sqlesc($realip). ', ' .ann_sqlesc($port). ', ' .ann_sqlesc($connectable). ', ' .ann_sqlesc($uploaded). ', ' .($TRINITY20['ratio_free'] ? '0' : '' .ann_sqlesc($downloaded)). ', ' .ann_sqlesc($left). ', ' .TIME_NOW. ', ' .TIME_NOW. ', ' .ann_sqlesc($seeder). ', ' .ann_sqlesc($agent). ')') || ann_sqlerr(__FILE__,
        __LINE__);
}
$torrent_updateset = $snatch_updateset = [];
if (isset($self) && (empty($event) || $event == 'stopped')) {
    $seeder = 'no';
    ann_sql_query("DELETE FROM peers WHERE $selfwhere") || ann_sqlerr(__FILE__, __LINE__);
    //=== only run the function if the ratio is below 1
    $a_finishd = $a['finished'] ?? '';
    $a_upload = isset($a['uploaded']) ? (float)$a['uploaded'] : 0;
    $a_downld = isset($a['downloaded']) ? (float)$a['downloaded'] : 0;
    if (($a_upload + $upthis) < ($a_downld + $downthis) && $a_finishd == 'yes') {
        $HnR_time_seeded = ($a['seedtime'] + $self['announcetime']);
        //=== get times per class
        switch (true) {
            case ($user['class'] <= $TRINITY20['firstclass']):
                $days_3 = $TRINITY20['_3day_first'] * 3600; //== 1 days
                $days_14 = $TRINITY20['_14day_first'] * 3600; //== 1 days
                $days_over_14 = $TRINITY20['_14day_over_first'] * 3600; //== 1 day
                break;

            case ($user['class'] < $TRINITY20['secondclass']):
                $days_3 = $TRINITY20['_3day_second'] * 3600; //== 12 hours
                $days_14 = $TRINITY20['_14day_second'] * 3600; //== 12 hours
                $days_over_14 = $TRINITY20['_14day_over_second'] * 3600; //== 12 hours
                break;

            case ($user['class'] >= $TRINITY20['thirdclass']):
                $days_3 = $TRINITY20['_3day_third'] * 3600; //== 12 hours
                $days_14 = $TRINITY20['_14day_third'] * 3600; //== 12 hours
                $days_over_14 = $TRINITY20['_14day_over_third'] * 3600; //== 12 hours
                break;

            default:
                $days_3 = 0; //== 12 hours
                $days_14 = 0; //== 12 hours
                $days_over_14 = 0; //== 12 hours
        }
        switch (true) {
            case (($a['start_snatch'] - $torrent['ts']) < $TRINITY20['torrentage1'] * 86400):
                $minus_ratio = ($days_3 - $HnR_time_seeded);
                break;

            case (($a['start_snatch'] - $torrent['ts']) < $TRINITY20['torrentage2'] * 86400):
                $minus_ratio = ($days_14 - $HnR_time_seeded);
                break;

            case (($a['start_snatch'] - $torrent['ts']) >= $TRINITY20['torrentage3'] * 86400):
                $minus_ratio = ($days_over_14 - $HnR_time_seeded);
                break;

        }
        $hit_and_run = (($TRINITY20['hnr_online'] == 1 && $minus_ratio > 0 && ($a['uploaded'] + $upthis) < ($a['downloaded'] + $downthis)) ? "seeder='no', hit_and_run= '".TIME_NOW."'" : "hit_and_run = '0'");
    } //=== end if not 1:1 ratio
    else {
        $hit_and_run = "hit_and_run = '0'";
    }
    //=== end hit and run
    if ($mysqli->affected_rows) {
        if ($self['seeder'] == 'yes') {
            adjust_torrent_peers($torrentid, -1);
        } else {
            adjust_torrent_peers($torrentid, 0, -1);
        }
        $torrent_updateset[] = ($self['seeder'] == 'yes' ? 'seeders = seeders - 1' : 'leechers = leechers - 1');
        if ($a) {
            $snatch_updateset[] = 'ip = ' .ann_sqlesc($realip). ', port = ' .ann_sqlesc($port). ', connectable = ' .ann_sqlesc($connectable).", uploaded = uploaded + $upthis, ".($TRINITY20['ratio_free'] ? 'downloaded = downloaded + 0' : "downloaded = downloaded + $downthis"). ', to_go = ' .ann_sqlesc($left). ', upspeed = ' .($upthis > 0 ? $upthis / $self['announcetime'] : 0). ', downspeed = ' .($downthis > 0 ? $downthis / $self['announcetime'] : 0). ', ' .($self['seeder'] == 'yes' ? "seedtime = seedtime + {$self['announcetime']}" : "leechtime = leechtime + {$self['announcetime']}"). ', last_action = ' .TIME_NOW. ', seeder = ' .ann_sqlesc($seeder). ', agent = ' .ann_sqlesc($agent).", $hit_and_run";
        }
    }
} elseif (isset($self)) {
    if ($event == 'completed') {
        if ($a) {
            $snatch_updateset[] = 'complete_date = ' .TIME_NOW.", finished = 'yes'";
        }
        $torrent_updateset[] = 'times_completed = times_completed + 1';
        $finished = ', finishedat = ' .TIME_NOW;
        adjust_torrent_peers($torrentid, 0, 0, 1);
    }
    $prev_action = ann_sqlesc($self['ts']);
    ann_sql_query('UPDATE LOW_PRIORITY peers SET connectable = ' .ann_sqlesc($connectable). ', uploaded = ' .ann_sqlesc($uploaded). ', ' .($TRINITY20['ratio_free'] ? 'downloaded = 0' : 'downloaded = ' .ann_sqlesc($downloaded)). ', to_go = ' .ann_sqlesc($left). ', last_action = ' .TIME_NOW.", prev_action = $prev_action, seeder = ".ann_sqlesc($seeder). ', agent = ' .ann_sqlesc($agent)." $finished WHERE $selfwhere") || ann_sqlerr(__FILE__,
        __LINE__);
    if ($mysqli->affected_rows) {
        if ($seeder != $self['seeder']) {
            if ($seeder == 'yes') {
                adjust_torrent_peers($torrentid, 1, -1);
            } else {
                adjust_torrent_peers($torrentid, -1, 1);
            }
            $torrent_updateset[] = ($seeder == 'yes' ? 'seeders = seeders + 1, leechers = leechers - 1' : 'seeders = seeders - 1, leechers = leechers + 1');
        }
        if ($a) {
            $snatch_updateset[] = 'ip = ' .ann_sqlesc($realip). ', port = ' .ann_sqlesc($port). ', connectable = ' .ann_sqlesc($connectable).", uploaded = uploaded + $upthis, ".($TRINITY20['ratio_free'] ? 'downloaded = downloaded + 0' : "downloaded = downloaded + $downthis"). ', to_go = ' .ann_sqlesc($left). ', upspeed = ' .($upthis > 0 ? $upthis / $self['announcetime'] : 0). ', downspeed = ' .($downthis > 0 ? $downthis / $self['announcetime'] : 0). ', ' .($self['seeder'] == 'yes' ? "seedtime = seedtime + {$self['announcetime']}" : "leechtime = leechtime + {$self['announcetime']}"). ', last_action = ' .TIME_NOW. ', seeder = ' .ann_sqlesc($seeder). ', agent = ' .ann_sqlesc($agent). ', timesann = timesann + 1';
        }
    }
} else {
    if ($user['parked'] == 'yes') {
        err('Your account is parked! (Read the FAQ)');
    } elseif (($user['downloadpos'] != 1 || $user['hnrwarn'] == 'yes') && $seeder != 'yes') {
        err('Your downloading privileges have been disabled! (Read the rules)');
    }
    ann_sql_query('INSERT LOW_PRIORITY INTO peers'
        . ' (torrent, userid, peer_id, ip, port, connectable, uploaded, downloaded, '
        . ' to_go, started, last_action, seeder, agent, downloadoffset, uploadoffset, torrent_pass'
        . ') VALUES ('
        .ann_sqlesc($torrentid). ', ' .ann_sqlesc($userid). ', ' .ann_sqlesc($peer_id). ', '
        .ann_sqlesc($realip). ', ' .ann_sqlesc($port). ', ' .ann_sqlesc($connectable). ', '
        .ann_sqlesc($uploaded). ', ' .($TRINITY20['ratio_free'] ? '0' : '' .ann_sqlesc($downloaded)). ', '
        .ann_sqlesc($left). ', ' .TIME_NOW. ', ' .TIME_NOW. ', ' .ann_sqlesc($seeder). ', '
        .ann_sqlesc($agent). ', ' .($TRINITY20['ratio_free'] ? '0' : '' .ann_sqlesc($downloaded)). ', '
        .ann_sqlesc($uploaded). ', ' .ann_sqlesc($torrent_pass). ')'
        . ' ON DUPLICATE KEY UPDATE '
        . ' userid = ' .ann_sqlesc($userid). ', '
        . ' ip = ' .ann_sqlesc($realip). ', '
        . ' port = ' .ann_sqlesc($port). ', '
        . ' connectable = ' .ann_sqlesc($connectable). ', '
        . ' uploaded = ' .ann_sqlesc($uploaded). ', '
        . ' downloaded = ' .($TRINITY20['ratio_free'] ? '0' : '' .ann_sqlesc($downloaded)). ', '
        . ' to_go = ' .ann_sqlesc($left). ', '
        . ' last_action = ' .TIME_NOW. ', '
        . ' seeder = ' .ann_sqlesc($seeder). ', '
        . ' agent = ' .ann_sqlesc($agent)) || ann_sqlerr(__FILE__, __LINE__);
    if ($mysqli->affected_rows) {
        $torrent_updateset[] = ($seeder == 'yes' ? 'seeders = seeders + 1' : 'leechers = leechers + 1');
        if ($seeder == 'yes') {
            adjust_torrent_peers($torrentid, 1);
        } else {
            adjust_torrent_peers($torrentid, 0, 1);
        }
        if ($a) {
            $snatch_updateset[] = 'ip = ' .ann_sqlesc($realip). ', port = ' .ann_sqlesc($port). ', connectable = ' .ann_sqlesc($connectable). ', to_go = ' .ann_sqlesc($left). ', last_action = ' .TIME_NOW. ', seeder = ' .ann_sqlesc($seeder). ', agent = ' .ann_sqlesc($agent).", timesann = timesann + 1, hit_and_run = '0', mark_of_cain = 'no'";
        }
    }
}
if ($seeder == 'yes') {
    if ($torrent['banned'] != 'yes') {
        $torrent_updateset[] = 'visible = \'yes\'';
    }
    $torrent_updateset[] = 'last_action = '.TIME_NOW;
    $cache->update_row($cache_keys['torrent_details'].$torrentid, [
        'visible' => 'yes',
    ], $TRINITY20['expires']['torrent_details']);
    $cache->update_row($cache_keys['last_action'].$torrentid, [
        'lastseed' => TIME_NOW,
    ], 1800);
}
if ((is_countable($torrent_updateset) ? count($torrent_updateset) : 0) > 0) {
    ann_sql_query('UPDATE LOW_PRIORITY torrents SET '.implode(',', $torrent_updateset).' WHERE id = '.ann_sqlesc($torrentid)) || ann_sqlerr(__FILE__,
        __LINE__);
}
if ((is_countable($snatch_updateset) ? count($snatch_updateset) : 0) > 0) {
    ann_sql_query('UPDATE LOW_PRIORITY snatched SET '.implode(',',
            $snatch_updateset).' WHERE torrentid = '.ann_sqlesc($torrentid).' AND userid = '.ann_sqlesc($userid)) || ann_sqlerr(__FILE__, __LINE__);
}
if ((is_countable($user_updateset) ? count($user_updateset) : 0) > 0) {
    ann_sql_query('UPDATE LOW_PRIORITY users SET '.implode(',', $user_updateset).' WHERE id = '.ann_sqlesc($userid)) || ann_sqlerr(__FILE__,
        __LINE__);
    $cache->delete($cache_keys['user_stats'].$userid);
    $cache->delete($cache_keys['user_statss'].$userid);
}
if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && $_SERVER['HTTP_ACCEPT_ENCODING'] == 'gzip') {
    header('Content-Encoding: gzip');
    $resp_raw = benc_resp_raw($resp);
    echo gzencode((string) $resp_raw, 9);
} else {
    benc_resp_raw($resp);
}