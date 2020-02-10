<p style="white-space: pre-line"><?php
require_once("FormulaParser.php");
use FormulaParser\FormulaParser;
$lang=0;
$mapping=['database'=>'database','afbs'=>'afbs','vk'=>'vk','formula'=>'formula','conditions'=>'conditions',"equal"=>'equal','noequal'=>'noequal','nl'=>'nl'];
$vars=[];
$input="";
$deb=false;
$dexec=false;
$inter="web";
$tokens=array();
$configs=array();
$sepids=array(array());
$endids=array();
function is_while($str){
	switch($str){
		case "пока":
		case "while":
		case "әлегә":
		case "элегэ":
			return true;
		default: return false;
	}
}
function is_for($str){
	switch($str){
		case "for":
		case "repeat":
		case "повторить":
		case "ҡабатларға":
		case "кабатларга":
			return true;
		default: return false;
	}
}
function is_if($str){
	switch($str){
		case "?":
		case "if":
		case "если":
		case "әгәр":
		case "эгэр":
			return true;
		default: return false;
	}
}
function is_else($str){
	switch($str){
		case "??":
		case "else":
		case "иначе":
		case "әле":
		case "эле":
			return true;
		default: return false;
	}
}
function is_elif($str){
	switch($str){
		case "???":
		case "elif":
		case "или_если":
		case "йәки_әгәр":
		case "йэки_эгэр":
			return true;
		default: return false;
	}
}
function is_then($str){
	$str=substr($str,3);
	switch($str){
		case "then":
		case ":":
		case "тогда":
		case "шул_саҡта":
		case "шул_сакта":
			return true;
		default: return false;
	}
}
function is_end($str){
	switch($str){
		case "end":
		case ".":
		case "конец":
		case "все":
		case "бөттө":
		case "ботто":
			return true;
		default: return false;
	}
}
function is_exit($str){
	switch($str){
		case "q":
		case "exit":
		case "выход":
		case "сығыу":
		case "сыгыу":
			return true;
		default: return false;
	}
}
function is_afbsfunc($str){
    switch($str){
        case "print":
        case "input":
        case "stop":
            return true;
        default: return false;
    }
}
function setlang($str){
    switch($str){
        case "en":
        case "english":
            $GLOBALS['lang']=0;break;
        case "ru":
        case "russian":
            $GLOBALS['lang']=1;break;
        case "bash":
        case "bashkort":
            $GLOBALS['lang']=2;break;
        default:
            $GLOBALS['lang']=0;break;
    }
}
function array_substr($arr,$start,$end){
    $res=array();
    for($i=$start;$i<$end;$i++){
        array_push($res,$arr[$i]);
    }
    return $res;
}
function is_alpha($str){
    if(ctype_alpha($str))return true;
    $str=mb_strtolower($str,"UTF-8");
    $warr=array("а","б","в","г","д","е","ё","ж","з","и","й","к","л","м","н","о","п","р","с","т","у","ф","х","ц","ч","ш","щ","ъ","ы","ь","э","ю","я","ғ","ҙ","ҡ","ң","ө","ҫ","ү","һ","ә","?",".",":",'$','_');
    if(in_array($str,$warr))return true;
    return false;
}
function is_alnum($str){
    if(ctype_alnum($str))return true;
    $str=mb_strtolower($str,"UTF-8");
    $warr=array("а","б","в","г","д","е","ё","ж","з","и","й","к","л","м","н","о","п","р","с","т","у","ф","х","ц","ч","ш","щ","ъ","ы","ь","э","ю","я","ғ","ҙ","ҡ","ң","ө","ҫ","ү","һ","ә","?",".",":",'$','_',"0","1","2","3","4","5","6","7","8","9");
    if(in_array($str,$warr))return true;
    return false;
}
function is_operator($str){
    switch($str){
        case "+":
        case "-":
        case "*":
        case "/":
        case "^":
        case "=":
        //case "<":
        //case ">":
        //case ".":
        //case ",":
            return true;
        default: return false;
    }
}
function is_sep_operator($str){
    switch($str){
        //case "<":
        //case ">":
        case "=":
            return true;
        default: return false;
    }
}
function is_number($str){
    switch($str){
        case "0":
        case "1":
        case "2":
        case "3":
        case "4":
        case "5":
        case "6":
        case "7":
        case "8":
        case "9":
            return true;
        default: return false;
    }
}
function is_whitespace($str){
    switch($str){
        case "\n":
        case "\r":
        case "\t":
            return true;
        default: return false;
    }
}
function is_brackets($str){
    switch($str){
        case "(":
        case ")":
        case "{":
        case "}":
        case "[":
        case "]":
            return true;
        default: return false;
    }
}
function setvar(array $obj){
	$needle=$obj[0];
	$val=$obj[1][0];
	if($needle[0][0]=='$'){
		$GLOBALS[substr($needle[0],1)]=$val;
	}
}
function funcs(string ...$arr){
	$ns=$arr[0];
	$arr=array_substr($arr,1,count($arr));
	if(isset($GLOBALS['mapping'][$ns])){
		$ns=$GLOBALS['mapping'][$ns];
	}
	if(is_afbsfunc($ns)){
		array_unshift($arr,$ns);
		$ns="afbs";
	}
	switch($ns){
		case "afbs":
			switch($arr[0]){
				case "print":
					$out=array_substr($arr,1,count($arr));
					echo ">";
					foreach($out as $ot)echo($ot);
					echo (PHP_EOL);
					return true;
					break;
				case "input":
					return $GLOBALS['input'];
					break;
				case "stop":
					exit;
					break;
			}
			break;
		case "conditions":
			switch($arr[0]){
				case "is_web":
					return $GLOBALS['inter']==="web";
					break;
				case "is_vk":
					return $GLOBALS['inter']==="vk";
					break;
				default: return false;
			}
			break;
		case "equal":
			return strval($arr[0])===strval($arr[1]);
			break;
		case "noequal":
			return strval($arr[0])!==strval($arr[1]);
			break;
		case "nl":
			return "\n";
			break;	

		default: return $ns;
	}
}
function token_type($token){
	return substr($token,0,3);
}
function token_val($token){
	return substr($token,3);
}
function tokenizer($prog){
	$comment=false;
	$commentt=false;
	$nl=true;
	$bo=false;
	$boc=0;
	$so=false;
	$sot="";
	$co=false;
	$coc=0;
	$no=false;
	$vo=false;
	$mo=false;
	$ts="";
	$configs=array();
	$sepids=array(array());
	$endids=array();
	$sepiter=0;
	$tokens=array();
	$prog = preg_split('//u', $prog, -1, PREG_SPLIT_NO_EMPTY);
	for($i=0;$i<count($prog);$i++){
	    $char=$prog[$i];
	    if($comment){
	        if(/*$commentt===false&&*/is_whitespace($char)){
	            $comment=false;
	        }
	        else continue;
	    }
	    if(is_whitespace($char)&&!$so){
	        $nl=true;
	        array_push($tokens,"`o`end");
	        $sepiter++;
	        array_push($endids,count($tokens)-1);
	        $sepids[$sepiter]=array();
	        while(is_whitespace($char)){$i++;$char=$prog[$i];}//убираем виндовс нл
	    }
	    if(!$so&&!$no&&!$comment&&!$vo)while($char==" "){$i++;$char=$prog[$i];}
	    if(!$comment&&!$so&&!$vo){
	        if($char=="#"){
	            $comment=true;
	            $commentt=false;
	            continue;
	        }
	        
	    }
	    if($so){
	        if($char!=$sot)$ts=$ts.$char;
	        else{
	            array_push($tokens,"`s`".str_replace("\"", "\\\"", $ts));
	            $so=false;
	        }
	        continue;
	    }
	    if(($char=="\""||$char=="'")&&!$so&&!$vo&&!$co&&!$mo){
	        $so=true;
	        $sot=$char;
	        $ts="";
	        continue;
	    }
	    if(!$vo&&!$no&&!$mo&&is_number($char)){
	        $ts=$char;
	        $no=true;
	        continue;
	    }
	    if($nl){
	        if($char=="!"){
	            $co=true;
	            $coc=1;
	            $nl=false;
	            $ts="";
	            continue;
	        }
	    }
	    if($no){
	        if(is_number($prog[$i+1])){
	            $ts=$ts.$char;
	            continue;
	        }
	        else{
	            $no=false;
	            $tsn=strval(intval($ts.$char));
	            array_push($tokens,"`n`".$tsn);
	        }
	    }
	    if($co){
	        if($coc==1){
	            $ts=$ts.$char;
	            if($prog[$i+1]==" "){
	                $coc=2;
	                array_push($tokens,"`c`".$ts);
	                $ts="";
	            }
	        }
	        elseif($coc==2){
	            $ts=$ts.$char;
	            if($prog[$i+1]==" "||is_whitespace($prog[$i+1])){
	                $coc=0;
	                $co=false;
	                array_push($configs,$ts);
	                if($tokens[count($tokens)-1]=="`c`lang")setlang($ts);
	                $ts="";
	            }
	        }
	        else $co=false;
	        continue;
	    }
	    if($vo){
	        if(is_alnum($prog[$i+1]))$ts=$ts.$char;
	        else{
	            $vo=false;
	            /*if(is_afbsfunc($ts.$char)){
	                //array_push($tokens,"`v`afbs","`o`.");
	                array_push($tokens,"`v`afbs");
	            }*/
	            array_push($tokens,"`v`".$ts.$char);
	        }
	        continue;
	    }
	    if(!$vo&&is_alpha($char)&&!$so&&!$no&&!$mo){
	        $vo=true;
	        $ts=$char;
	        if(!is_alnum($prog[$i+1])){//для одиночных символов
	        	$vo=false;
	            array_push($tokens,"`v`".$ts);
	        }
	        continue;
	    }
	    if(!$so&&!$co&&!$comment&&!$no&&!$mo&&$char==";"){
	        $nl=true;
	        array_push($tokens,"`o`end");
	        $sepiter++;
	        array_push($endids,count($tokens)-1);
	        $sepids[$sepiter]=array();
	    }
	    if(!$so&&!$vo&&!$mo&&is_operator($char)){
	        if(is_sep_operator($char)){
	            array_push($tokens,"`S`".$char);
	            if(!$bo)array_push($sepids[$sepiter],count($tokens)-1);
	        }
	        else array_push($tokens,"`o`".$char);
	    }
	    if($mo)$ts.=$char;
	    if(!$so&&!$vo&&is_brackets($char)){
	    	if(!$mo&&$char=="["){
	    		$mo=true;
	    		$ts="";
	    		continue;
	    	}
	    	elseif($mo&&$char=="]"){
	    		$mo=false;
	        	array_push($tokens,"`m`".substr($ts,0,strlen($ts)-1));
	    		$ts="";
	    		continue;
	    	}
	    	elseif(!$mo){
	        	array_push($tokens,"`b`".$char);
	       		if($char=="("){
	       		    if($bo)$boc++;
	       		    else{
	       		        $boc=1;
	       		        $bo=true;
	       		    }
	       		}
	       		elseif($char==")"){
	       		    $boc--;
	       		    if($boc==0)$bo=false;
	       		}
	    	}
	    }
	}	
	$GLOBALS['tokens']=$tokens;
	$GLOBALS['configs']=$configs;
	$GLOBALS['sepids']=$sepids;
	$GLOBALS['endids']=$endids;
}
function parser($tokens){
	global $configs, $sepids, $endids;
	$lines=array();
	$lastid=-1;
	for($i=0;$i<count($endids);$i++){
	    array_push($lines,array_substr($tokens,$lastid+1,$endids[$i]));
	    $lastid=$endids[$i];
	}
	$confiter=0;
	$phpcode="";
	foreach ($lines as $key => $value) {
	    if(empty($sepids[$key])){//нет разделителей
			$tokenType=token_type($value[0]);
			$tokenVal=token_val($value[0]);
			if($tokenType=="`c`"){
				if(isset($GLOBALS['mapping'][$tokenVal])){if($GLOBALS['dexec'])echo("\n\n[namespace {$tokenVal} was changed]\n\n");}
				else{
					$GLOBALS['mapping'][$tokenVal]=$configs[$confiter];
				}
				$confiter++;
			}
			if($tokenType=="`v`"){
				if(is_if($tokenVal)){
					$phpcode.="if(";
					$arr=array_filter($value,"is_then");
					if(!empty($arr)){
						$bb=false;
						$arrif=array_substr($value,1,array_keys($arr)[0]);
						$phpcode.="funcs(\"".token_val($arrif[0])."\"";
						$args="";
						foreach ($arrif as $k => $val) {
							if($k==0)continue;
							$tT=token_type($val);
							$tV=token_val($val);
							if($bb){
								if($tT!="`b`")$args.="\"{$tV}\"";
								else if($GLOBALS['dexec'])echo("\n\n[error]\n\n");
								$bb=false;
							}
							elseif($tT=="`b`"){
								if($tV=="("){
									$bb=true;
									$args.=",funcs(";
								}
								elseif ($tV==")") {
									$args.=")";
								}
							}
							else {
								if($tT=='`m`'){
									$parser = new FormulaParser($tV, 2);
									$result = $parser->getResult(); // [0 => 'done', 1 => 16.38]
									if($result[0]=="done"){
										$tV=$result[1];
									}
									else if($GLOBALS['dexec'])echo("\n\n[math syntax error]\n\n");
								}
								($tV[0]=='$')?$args.=",{$tV}":$args.=",\"{$tV}\"";
							}
						}
						$phpcode.=$args.")){".PHP_EOL;

					}
					else echo("\n\nempty\n\n");//синтакс чето там
					//$op="if";
				}
				elseif(is_else($tokenVal)){
					$phpcode.="else{".PHP_EOL;

					//$op="else";
				}
				elseif(is_elif($tokenVal)){
					$phpcode.="elseif(";
					$arr=array_filter($value,"is_then");
					if(!empty($arr)){
						$bb=false;
						$arrif=array_substr($value,1,array_keys($arr)[0]);
						$phpcode.="funcs(\"".token_val($arrif[0])."\"";
						$args="";
						foreach ($arrif as $k => $val) {
							if($k==0)continue;
							$tT=token_type($val);
							$tV=token_val($val);
							if($bb){
								if($tT!="`b`")$args.="\"{$tV}\"";
								else if($GLOBALS['dexec'])echo("\n\n[error]\n\n");
								$bb=false;
							}
							elseif($tT=="`b`"){
								if($tV=="("){
									$bb=true;
									$args.=",funcs(";
								}
								elseif ($tV==")") {
									$args.=")";
								}
							}
							else {
								if($tT=='`m`'){
									$parser = new FormulaParser($tV, 2);
									$result = $parser->getResult(); // [0 => 'done', 1 => 16.38]
									if($result[0]=="done"){
										$tV=$result[1];
									}
									else if($GLOBALS['dexec'])echo("\n\n[math syntax error]\n\n");
								}
								($tV[0]=='$')?$args.=",{$tV}":$args.=",\"{$tV}\"";
							}
						}
						$phpcode.=$args.")){".PHP_EOL;

					}
					//$op="elseif";
				}
				elseif(is_while($tokenVal)){
					$phpcode.="while(";
					//$op="while";
				}
				elseif(is_for($tokenVal)){
					$phpcode.="for($i=0;$i=";
					//$op="for";
				}
				elseif(is_end($tokenVal)){
					$phpcode.="}".PHP_EOL;
					//$op="for";
				}
				else{
					$phpcode.="funcs(\"".token_val($value[0])."\"";
					$args="";
					foreach ($value as $k => $val) {
						if($k==0)continue;
						$tT=token_type($val);
						$tV=token_val($val);
						if($tV[0]=='$')$tV='$_afbsvar_'.substr($tV,1);
						if($bb){
							if($tT!="`b`")$args.="\"{$tV}\"";
							else if($GLOBALS['dexec'])echo("\n\n[bracket error]\n\n");
							$bb=false;
						}
						elseif($tT=="`b`"){
							if($tV=="("){
								$bb=true;
								$args.=",funcs(";
							}
							elseif ($tV==")") {
								$args.=")";
							}
						}
						else {
							if($tT=='`m`'){
								$parser = new FormulaParser($tV, 2);
								$result = $parser->getResult(); // [0 => 'done', 1 => 16.38]
								if($result[0]=="done"){
									$tV=$result[1];
								}
								else if($GLOBALS['dexec'])echo("\n\n[math syntax error]\n\n");
							}
							($tV[0]=='$')?$args.=",{$tV}":$args.=",\"{$tV}\"";
						}
					}
					$phpcode.=$args.");".PHP_EOL;
				}
			}
			
			
	    }
	    else{//есть разделители
	        /*$seplog="";
	        foreach ($sepids[$key] as $k => $val) $seplog.=substr($tokens[$val],3);*/
			$before=true;
			$ki=false;
			$bb=false;
			$fo=false;
	        foreach ($value as $k => $val) {
				$tT=substr($val,0,3);
				$tV=substr($val,3);
				if($k==0){
					if($tT=="`v`"&&$tV[0]=='$'){
						$phpcode.="setvar([0=>[".'\'$_afbsvar_'.substr($tV,1)."'";
					}
					continue;
					
				}
				if($before===true){
					if($k==$sepids[$key][0]||$tT=="`S`"){
						$before=false;
						$phpcode.="],1=>[";
						$ki=true;
						continue;
					}
					elseif($tT=='`m`'){
						$parser = new FormulaParser($tV, 2);
						$result = $parser->getResult(); // [0 => 'done', 1 => 16.38]
						if($result[0]=="done"){
							$tV=$result[1];
						}
						else if($GLOBALS['dexec'])echo("\n\n[math syntax error]\n\n");
					}
					else if($tV[0]=='$')$tV='$_afbsvar_'.substr($tV,1);
					($tV[0]=='$')?$phpcode.=",{$tV}":$phpcode.=",\"{$tV}\"";
				}
				else{
					if($ki){;
						$ki=false;
						if($tT=="`v`"&&$tV[0]=='$'){
							$phpcode.='"$_afbsvar_'.substr($tV,1)."\"";
						}
						elseif($tT=='`m`'){
							$parser = new FormulaParser($tV, 2);
							$result = $parser->getResult(); // [0 => 'done', 1 => 16.38]
							if($result[0]=="done"){
								$tV=$result[1];
							}
							elseif($GLOBALS['dexec']) echo("\n\n[math syntax error]\n\n");
							$phpcode.="{$tV}";
							break;
						}
						else {
							$phpcode.="funcs(\"{$tV}\"";
							$fo=true;
						}
						continue;
					}
					if($bb){
						if($tT!="`b`")$phpcode.="\"{$tV}\"";
						elseif($GLOBALS['dexec']) echo("\n\n[bracket error]\n\n");
						$bb=false;
						continue;
					}
					elseif($tT=="`b`"){
						if($tV=="("){
							$bb=true;
							$phpcode.=",funcs(";
							continue;
						}
						elseif ($tV==")") {
							$phpcode.=")";
							continue;
						}
					}
					if($tT=='`m`'){
						$parser = new FormulaParser($tV, 2);
						$result = $parser->getResult(); // [0 => 'done', 1 => 16.38]
						if($result[0]=="done"){
							$tV=$result[1];
						}
						elseif($GLOBALS['dexec']) echo("\n\n[math syntax error]\n\n");
					}
					($tV[0]=='$')?$phpcode.=",{$tV}":$phpcode.=",\"{$tV}\"";
				}
			}
			($fo)?$phpcode.=")]]);".PHP_EOL:$phpcode.="]]);".PHP_EOL;
	    }
	}
	if(!$GLOBALS['dexec']||($GLOBALS['dexec']&&$GLOBALS['deb']))echo "php".PHP_EOL.'$'."mapping=".var_export($GLOBALS['mapping'],true).';'.PHP_EOL.'$_afbsvar_hellomess="мэсс";'.PHP_EOL.$phpcode;
	if($GLOBALS['dexec'])file_put_contents("code.php", '<'.'?'."php".PHP_EOL.'$'."mapping=".var_export($GLOBALS['mapping'],true).';'.PHP_EOL.'$_afbsvar_hellomess="мэсс";'.PHP_EOL.$phpcode.'?'.'>');
	if($GLOBALS['deb'])echo("\n\n");
	if($GLOBALS['deb'])var_export($lines);
	if($GLOBALS['deb'])echo("\nnamespaces:\n");
	if($GLOBALS['deb'])foreach($GLOBALS['mapping'] as $mk=>$mv)echo("{$mk} = {$mv}\n");
}
/*function parser($tokens,$main=false){
	
}*/
$prog = <<< CODE
print "Привет мир!"
CODE;
//$prog=mb_convert_encoding($prog,"UTF-8");
//parser
if($_POST['type']=="file"){
	$prog=$_POST['text'];
	$dexec=true;
}
elseif($_POST['type']=="fileget"){
	$prog=$_POST['text'];
}
elseif($_POST['type']=="text"){
	$prog=$_POST['text'];
	$deb=true;
	$dexec=true;
}
else {}
if($_POST['inp'])$input=$_POST['inp'];
$prog.="\n";
if($deb)echo("Код:\n".$prog."\n\n");

tokenizer($prog);
if($deb)echo("Язык: ".$lang."\n\n");
parser($tokens);
if($GLOBALS['deb'])echo("---Exec php code---\n");
if($GLOBALS['dexec'])require("code.php");
if($GLOBALS['deb'])echo("---End exec php code---\n");
//var_export($mapping);
/*for($i=0;$i<count($tokens);$i++){
    $type=$tokens[$i][1];
    $val=substr($tokens[$i],3);

}*/
/*echo("\n\nТокены:\n");
$answ="";
foreach($tokens as $token)$answ.=$token."<br>";
echo($answ."\n\n");*/

?></p>

