<?php
include 'database.php';

main();

function get($key){
	foreach($_GET as $k => $v)
		if($k == $key)
			return $v;
	
	return post($key);
}

function post($key){
	foreach($_POST as $k => $v)
		if($k == $key)
			return $v;
	
	return "";
}

function main(){
	$text = get('t');
	$out = get('o');
	
	if($text === ''){
		echo("Bad input");
		return false;
	}

	if(get('k') == 'e'){
		$memory = get('m');
		$oText = $text;
		$save = "false";
		$bin = "false";
		
		if($m === '')
			$memory = 1024;
		
		if(get('b') === '1'){
			$text = base64_decode($text);
			$bin = "true";
		}
		
		$encoded = _go($text, $memory);
		
		if(get('v') === '1'){
			$id = __store($encoded[1], $encoded[2], 0);
			$save = "true";
		}else
			$id = array(-1, _compact($encoded[2]));
		
		$output = base64_encode($encoded[0]);
		
		if($out === 'JSON'){
			echo('{"PRAF":{"input":{"kind":"encode","memory":' . $memory . ',"text":"' . $oText . '","save":' . $save . ',"binary":' . $bin . ',"output":"JSON"},"output":{"sorting":"' . $id[1] . '","filling":"' . $encoded[1] . '","id":' . $id[0] . ',"outputText":"' . $output . '"}}}');			
		}elseif($out === 'XML'){
			echo('<?xml version="1.0"?><PRAF><input><kind>encode</kind><memory>' . $memory . '</memory><text>' . $oText . '</text><save>' . $save .'</save><binary>' . $bin .'</binary><output>XML</output></input><output><sorting>' . $id[1] . '</sorting><filling>' . $encoded[1] . '</filling><id>' . $id[0] . '</id><outputText>' . $output . '</outputText></output></PRAF>');						
		}else{
			echo($output . "<br />" . $encoded[1] . "<br />" . $id[1] . "<br />" . $id[0]);
		}
	}elseif(get('k') == 'd'){
		$id = get('i');
		$save = "false";
		$bin = "false";
		
		if($id === '' || !is_numeric($id) || $id <= 0)
			$id = '0';

		if($id === '0')
			$temp = array(get('p'), get('s'));
		
		$memory = get('m');
		
		if($memory === '')
			$memory = 1024;
		
		if($id > 0){
			$temp = __get($id);
			$save = "true";
		}

		if($temp[0] === '' || $temp[1] === ''){
			echo("Bad input");
			return false;
		}

		$plain = _undo(base64_decode($text), $temp[0], $temp[1]);
		$encoded = _go($plain, $memory);
		
		if($id > 0)
			$id = __store($encoded[1], $encoded[2], $id);
		else
			$id = array(-1, _compact($encoded[2]));

		if(get('b') === '1'){
			$plain = base64_encode($plain);
			$bin = "true";
		}

		$output = base64_encode($encoded[0]);

		if($out === 'JSON'){
			echo('{"PRAF":{"input":{"kind":"decode","memory":' . $memory . ',"id":' . $id[0] . ',"text":"' . $text . '","sorting":"' . $temp[1] . '","filling":"' . $temp[0] . '","save":' . $save . ',"binary":' . $bin . ',"output":"JSON"},"output":{"sorting":"' . $id[1] . '","filling":"' . $encoded[1] . '","id":' . $id[0] . ',"outputText":"' . $output . '"}}}');
		}elseif($out === 'XML'){
			echo('<?xml version="1.0"?><PRAF><input><kind>decode</kind><memory>' . $memory . '</memory><id>' . $id[0] . '</id><text>' . $text . '</text><sorting>' . $temp[1] . '</sorting><filling>' . $temp[0] . '</filling><save>' . $save .'</save><binary>' . $bin . '<binary><output>XML</output></input><output><sorting>' . $id[1] . '</sorting><filling>' . $encoded[1] . '</filling><id>' . $id[0] . '</id><outputText>' . $output . '</outputText></output></PRAF>');
		}else{
			echo($plain . "<br />" . $output . "<br />" . $encoded[1] . "<br />" . $id[1] . "<br />" . $id[0]);
		}
	}else{
		echo("Bad input");
		return false;
	}
}

function rnd(){return rand(0, 15);}
function rnb(){return rand(0, 1);}
function getCode($t, $i){
	$gtemp = dechex(ord(substr($t, $i, 1)));
	
	if(strlen($gtemp) === 2)
		return $gtemp;
	elseif(strlen($gtemp) === 1)
		return "0" . $gtemp;
	
	return "00";
}

function getByte($t, $i){return substr($t, $i, 1);}
function a2b($t, $i){return decbin(ord(substr($t, $i, 1)));}

function srndc($x, $y){
	if($x == 7){
		if(hexdec($y) == 15){
			return false;
		}
	}elseif($x < 2){
		return false;
	}
	
	return true;
}

function getPiv($m){
	$p = array();
	$px = 0;
	$max = $m / 8;

	for($pi = 0;$pi < $max;$pi++){
		do{
			$ptemp = "";
			
			for($pj = 0;$pj < 8;$pj++)
				$ptemp .= rnb();
			
			$ptemh = bin2hex($ptemp);

		}while(!srndc(getByte($ptemh, 0), getByte($ptemh, 1)));
		
		for($pj = 0;$pj < 8; $pj++)
			$p[$px++] = substr($ptemp, $pj, 1);
	}
	
	return $p;
}

function fulfill($c, $p, $x, $y, $v){
	$c[$p] = $v[$x];
	
	if($p == 0)
		$c[1] = $v[hexdec($y)];
	else
		$c[0] = $v[hexdec($y)];
	
	return $c;
}

function reSort($txt){
	$len = strlen($txt);
	$sort = array();
	$final = "";
	$reco = 0;
	$index = 0;
	 
	for($ri = 0;$ri < $len;$ri++)
		$sort[$ri] = -1;
	 
	for($ri = 0;$ri < $len;$ri++){
		$pos = -1;

		while(true){
			$pos = rand(0, $len);
			
			if($sort[$pos] == -1)
				break;
		}

		$sort[$pos] = $index++;
	}
	
	for($i = 0;$i < $len;$i++)
		$final .= $txt[$sort[$i]];
	
	return array($final, $sort);
}

function unSort($ur, $pattern){
	$pl = strlen($pattern);
	$ul = strlen($ur);
	$p0 = hexdec($pattern[0]);
	$len = hexdec(substr($pattern, 1, $p0));
	$pF = array();
	$ci = 0;
	$ret = "";
	
	for($ui = (1 + $p0);$ui < $pl;$ui += $len)
		$pF[] = hexdec(substr($pattern, (1 + ($len * ++$ci)), $len));
	
	$pFl = count($pF);
	
	for($ui = 0;$ui < $ul;$ui++){
		$uj = -1;
	
		for($uj = 0;$uj < $pFl;$uj++)
			if($pF[$uj] == $ui)
				break;

		$ret .= $ur[$uj];
	}
	
	return $ret;
}

function _go($text, $memory){
	$v = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F');
	$pos = 0;
  	$offset = 0;
  	$temp = array();
  	$pivot = 0;
  	$gl = strlen($text);
  	$gr = "";
	$gt = "";
	$gs = "";
  	$piv = array();
  	$pivi = 0;
  	$limit = $memory - 1;
	
	if($memory == 0 || $memory % 8 !== 0)
		return false;
	
	$piv = getPiv($memory);
	$srt = reSort($text);
	
	$txt = $srt[0];
	$gs = $srt[1];
	
	while(true){
		$char = array("", "");
		
		if($pos >= $gl)
			break;
		
		if($pivi % $memory == $limit){
			$temPiv = getPiv($memory);
			
			for($tip = 0;$tip < $memory;$tip++)
				$piv[] = $temPiv[$tip];
		}

		$sd = getCode($txt, $pos);
		$sx = rnd();
		$sy = null;
		
		if($sx < 8){
			if($sx % 2 === 0){
				$sy = hexdec(rnd());
			}else{
				$sy = getByte($sd, $offset);
				
				$offset++;
				
				if($offset === 2){
					$offset = 0;
					$pos++;
				}
			}

			$char = fulfill($char, $piv[$pivi++], $sx, $sy, $v);
		}else{
			$sy = getByte($sd, $offset);
			
			$offset++;
			
			if($offset === 2){
				$offset = 0;
				$pos++;
			}
			
			$char = fulfill($char, $piv[$pivi++], $sx, $sy, $v);
		}
		
		$temp[] = join('', $char);
	}
	
	$gll = count($temp);
	
	for($gi = 0;$gi < $gll;$gi++)
		$gr .= chr(hexdec($temp[$gi]));
	
	for($gi = 0;$gi < $pivi;$gi++)
		$gt .= $piv[$gi];

	return array($gr, $gt, $gs);
}

function _undo($txt, $bpadding, $pattern){
	$tl = strlen($txt);
	$ur = "";
	$temp = array();

	for($ui = 0;$ui < $tl;$ui++){
		$put = true;
		$sd = getCode($txt, $ui);
		$sy = null;
		$sx = hexdec(getByte($sd, $bpadding[$ui]));
		
		if($sx < 8)
			if($sx % 2 == 0)
				$put = false;

		if($put){
			if($bpadding[$ui] == "0"){
				$sy = getByte($sd, 1);
			}else{
				$sy = getByte($sd, 0);
			}
			
			$temp[] = $sy;
		}
	}
	
	$ull = count($temp);
	
	for($ui = 0;$ui < $ull; $ui += 2){
		$ut = "" + $temp[$ui];
		$ut .= $temp[$ui + 1];
		$ur .= chr(hexdec($ut));
	}
	
	return unSort($ur, $pattern);
}

function _compact($gs){
	$gsf = "";
	$gsMax = count($gs);
	$gsMaxH = dechex($gsMax);
	$gsLen = strlen($gsMaxH);
	$gsLenH = dechex($gsLen);
	$gsLenM = strlen($gsLenH);
	$gsLenMH = dechex($gsLenM);
	$gsf = $gsLenMH . $gsLenH;
		
	for($si = 0;$si < $gsMax; $si++){
		$st = dechex($gs[$si]);
			
		while(strlen($st) < $gsLen)
			$st = "0" . $st;
			
		$gsf .= $st;
	}
	
	return $gsf;
}
?>
