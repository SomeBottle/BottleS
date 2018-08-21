<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
require_once './l/api/api.php';
date_default_timezone_set("Asia/Shanghai");
$ntime = date('Y-m-d H:i:s', time());
header('Content-type:text/json;charset=utf-8');
if (!is_dir('./d/')) {
    mkdir('./d/');
}
function tranTime($time) {
    $rtime = date("m-d H:i", $time);
    $htime = date("H:i", $time);
    $time = time() - $time;
    if ($time < 60) {
        $str = '刚刚';
    } elseif ($time < 60 * 60) {
        $min = floor($time / 60);
        $str = $min . '分钟前';
    } elseif ($time < 60 * 60 * 24) {
        $h = floor($time / (60 * 60));
        $str = $h . '小时前 ' . $htime;
    } elseif ($time < 60 * 60 * 24 * 3) {
        $d = floor($time / (60 * 60 * 24));
        if ($d == 1) $str = '昨天 ' . $rtime;
        else $str = '前天 ' . $rtime;
    } else {
        $str = $rtime;
    }
    return $str;
}
function watchurl($c){
	$main=explode('&nbsp;',$c);
	$end='';
	foreach($main as $val){
		if(!empty($val)){
			if(stripos($val,'http://')!==false||stripos($val,'https://')!==false){
				$val='<a href=\''.$val.'\' target=\'_blank\'>'.$val.'</a>';
			}
			$end=$end.$val.'&nbsp;';
		}
	}
	return $end;
}
$onepage = 8;
$tp = $_POST['t'];
$con = $_POST['c'];
$start = $_POST['st'];
$post = $_POST['pt'];
$r['result'] = 'ok';
$search = $_POST['search'];
if ($tp == 'rqlog') {
    if (checklogin()) {
        $a = file_get_contents('./c/page.php');
        $a = preg_replace("/\t|\[usr\]/", getnowusr(), $a);
        $r['r'] = $a;
    } else {
        $r['result'] = 'notok';
    }
} else if ($tp == 'getc') {
    if (checklogin()) {
        $f = './d/' . getnowusr() . '.php';
        if (!file_exists($f)) {
            file_put_contents($f, '<?php $js=\'{"num":"0"}\';?>');
        }
		require $f;
        $m = $js;
        $mj = json_decode($m, true);
        krsort($mj);
        if (intval($mj['num']) <= 0) {
            $r['r'] = '<h2>啥子都没有~OAO</h2><script>document.getElementById(\'ma\').style.display=\'none\';</script>';
        } else {
            $str = '';
            $nowt = '';
            $total = intval($mj['num']);
            foreach ($mj as $k => $v) {
                if ($k !== 'num' && intval($k) >= $total - $onepage) {
                    $nowt = tranTime(strtotime($mj[$k]['time']));
					$ncon=htmlspecialchars_decode($mj[$k]['content']);
					$ncon=watchurl($ncon);
			        $ncon=str_replace(array("\r\n", "\r", "\n"),'<br>',$ncon); 
                    $str = $str . '<div class=\'o\'><p class=\'w\'>' . $ncon . '</p><p class=\'s\'>' . $nowt . '</p><a class=\'x\' href=\'javascript:void(0);\' onclick=\'d(' . $k . ')\'>×</a></div>';
                }
            }
            $r['r'] = $str;
            $r['n'] = $onepage;
        }
    } else {
        $r['result'] = 'notok';
    }
} else if ($tp == 'rqpage') {
	if (checklogin()) {
    $start = intval($start);
    $end = $start + $onepage;
    $f = './d/' . getnowusr() . '.php';
	require $f;
    $m = $js;
    $mj = json_decode($m, true);
    krsort($mj);
    $str = '';
    $nowt = '';
    $total = intval($mj['num']);
    foreach ($mj as $k => $v) {
        if ($k !== 'num' && intval($k) >= $total - $end && intval($k) < $total - $start) {
            $nowt = tranTime(strtotime($mj[$k]['time']));
			$ncon=htmlspecialchars_decode($mj[$k]['content']);
			$ncon=watchurl($ncon);
			$ncon=str_replace(array("\r\n", "\r", "\n"),'<br>',$ncon); 
            $str = $str . '<div class=\'o\'><p class=\'w\'>' . $ncon . '</p><p class=\'s\'>' . $nowt . '</p><a class=\'x\'  href=\'javascript:void(0);\' onclick=\'d(' . $k . ')\'>×</a></div>';
        }
    }
    if (empty($str)) {
        $str = '<h2 style="color:#AAA;">没有更Door♂~了</h2>';
        $r['rs'] = 'nomore';
    }
    $r['r'] = $str;
    $r['n'] = $end;
	} else {
        $r['result'] = 'notok';
    }
} else if ($tp == 'submit') {
	if (checklogin()) {
    if (!empty($con) && strlen($con) > 5 && strlen($con) <= 30000) {
        $f = './d/' . getnowusr() . '.php';
		require $f;
        $m = $js;
        $mj = json_decode($m, true);
        $num = intval($mj['num']);
		$con=str_ireplace(' ',"&nbsp;",$con);
		$origincon=$con;
		$con = str_ireplace('"',"&quot;",$con);
		$con = str_ireplace("'","&#039;",$con);
        $mj[$num]['content'] = htmlspecialchars($con);
        $mj[$num]['time'] = $ntime;
        $mj['num'] = $num + 1;
        file_put_contents($f, '<?php $js=\''.json_encode($mj, true).'\';?>');
        $r['r'] = '<div class=\'o\'><p class=\'w\'>' . watchurl(nl2br($origincon)) . '</p><p class=\'s\'>刚刚</p></div>';
    } else {
        $r['result'] = 'notok';
        $r['m'] = '不支持发射空文本，文字限制5~30000';
    }
	} else {
        $r['result'] = 'notok';
    }
} else if ($tp == 'rqdel') {
	if (checklogin()) {
    $f = './d/' . getnowusr() . '.php';
	require $f;
    $m = $js;
    $mj = json_decode($m, true);
    $num = intval($mj['num']);
    if (array_key_exists($post, $mj)) {
        array_splice($mj, ($post + 1), 1);
        $mj['num'] = $num - 1;
        file_put_contents($f, '<?php $js=\''.json_encode($mj, true).'\';?>');
        $r['result'] = 'ok';
    } else {
        $r['result'] = 'notok';
    }
	} else {
        $r['result'] = 'notok';
    }
} else if ($tp == 'search') {
    $f = './d/' . getnowusr() . '.php';
	require $f;
    $m = $js;
    $mj = json_decode($m, true);
    krsort($mj);
    $str = '';
    $nowt = '';
    $total = intval($mj['num']);
    foreach ($mj as $k => $v) {
        if ($k !== 'num') {
            if (stripos($mj[$k]['content'], $search) !== false || stripos($mj[$k]['time'], $search) !== false) {
                $nowt = tranTime(strtotime($mj[$k]['time']));
                $mj[$k]['content'] = str_ireplace($search, '<span style=\'color:blue;\'>' . $search . '</span>', $mj[$k]['content']);
				$mj[$k]['content']=str_replace(array("\r\n", "\r", "\n"),'<br>',$mj[$k]['content']);
                $str = $str . '<div class=\'o\'><p class=\'w\'>' . htmlspecialchars_decode($mj[$k]['content']) . '</p><p class=\'s\'>' . $nowt . '</p><a class=\'x\'  href=\'javascript:void(0);\' onclick=\'d(' . $k . ')\'>×</a></div>';
            }
        }
    }
    $r['r'] = $str;
} else {
    $r['result'] = 'notok';
}
echo json_encode($r, true);
?>