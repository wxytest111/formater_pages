<?
	function getinitial($str) 
	{ 
	$asc=ord(substr($str,0,1)); 
	if ($asc<160) //非中文 
	{ 
	if ($asc>=48 && $asc<=57){ 
	return '1'; //数字 
	}elseif ($asc>=65 && $asc<=90){ 
	return chr($asc); // A--Z 
	}elseif ($asc>=97 && $asc<=122){ 
	return chr($asc-32); // a--z 
	}else{ 
	return '~'; //其他 
	} 
	} 
	else //中文 
	{ 
	$asc=$asc*1000+ord(substr($str,1,1)); 
	//获取拼音首字母A--Z 
	if ($asc>=176161 && $asc<176197){ 
	return 'A'; 
	}elseif ($asc>=176197 && $asc<178193){ 
	return 'B'; 
	}elseif ($asc>=178193 && $asc<180238){ 
	return 'C'; 
	}elseif ($asc>=180238 && $asc<182234){ 
	return 'D'; 
	}elseif ($asc>=182234 && $asc<183162){ 
	return 'E'; 
	}elseif ($asc>=183162 && $asc<184193){ 
	return 'F'; 
	}elseif ($asc>=184193 && $asc<185254){ 
	return 'G'; 
	}elseif ($asc>=185254 && $asc<187247){ 
	return 'H'; 
	}elseif ($asc>=187247 && $asc<191166){ 
	return 'J'; 
	}elseif ($asc>=191166 && $asc<192172){ 
	return 'K'; 
	}elseif ($asc>=192172 && $asc<194232){ 
	return 'L'; 
	}elseif ($asc>=194232 && $asc<196195){ 
	return 'M'; 
	}elseif ($asc>=196195 && $asc<197182){ 
	return 'N'; 
	}elseif ($asc>=197182 && $asc<197190){ 
	return 'O'; 
	}elseif ($asc>=197190 && $asc<198218){ 
	return 'P'; 
	}elseif ($asc>=198218 && $asc<200187){ 
	return 'Q'; 
	}elseif ($asc>=200187 && $asc<200246){ 
	return 'R'; 
	}elseif ($asc>=200246 && $asc<203250){ 
	return 'S'; 
	}elseif ($asc>=203250 && $asc<205218){ 
	return 'T'; 
	}elseif ($asc>=205218 && $asc<206244){ 
	return 'W'; 
	}elseif ($asc>=206244 && $asc<209185){ 
	return 'X'; 
	}elseif ($asc>=209185 && $asc<212209){ 
	return 'Y'; 
	}elseif ($asc>=212209){ 
	return 'Z'; 
	}else{ 
	return '~'; 
	} 
	} 
	} 
	$name='张三那';
	echo getinitial('李');

	function cutstr($sourcestr,$cutlength){
		$returnstr = '';
		$i = 0;
		$n = 0;
		$str_length = strlen($sourcestr);
		$mb_str_length = mb_strlen($sourcestr,'utf-8');
		while(($n < $cutlength) && ($i <= $str_length)){
		$temp_str = substr($sourcestr,$i,1);
		$ascnum = ord($temp_str);
		if($ascnum >= 224){
		$returnstr = $returnstr.substr($sourcestr,$i,3);
		$i = $i + 3;
		$n++;
		}
		elseif($ascnum >= 192){
		$returnstr = $returnstr.substr($sourcestr,$i,2);
		$i = $i + 2;
		$n++;
		}
		elseif(($ascnum >= 65) && ($ascnum <= 90)){
		$returnstr = $returnstr.substr($sourcestr,$i,1);
		$i = $i + 1;
		$n++;
		}
		else{
		$returnstr = $returnstr.substr($sourcestr,$i,1);
		$i = $i + 1;
		$n = $n + 0.5;
		}
		}
		if ($mb_str_length > $cutlength){
		$returnstr = $returnstr . "...";
		}
		return $returnstr; 
	}


	require_once('simple_html_dom.php');
	set_time_limit(0);
	$toggle = $_GET['toggle'];
	if($toggle=='1'){
		$json_file_path = '/Users/wangxinyu/works/PDA20160719/export_json.txt';
		$json_file_path2 = '/Users/wangxinyu/works/PDA20160719/export_json2.txt';
	}else{
		//$path = $_GET['path'];
		$json_file_path = $_GET['json_folder'].DIRECTORY_SEPARATOR.'export_json.txt';
		$json_file_path2 = $_GET['json_folder'].DIRECTORY_SEPARATOR.'export_json2.txt';
	}
	
	
	$json_file2 = fopen($json_file_path2, 'r');
	$json_data2 = fread($json_file2, filesize($json_file_path2));
	$every_person_jsons= explode('|', $json_data2);
	$all_person_str = '';
	for($i=0;$i<sizeof($every_person_jsons)-1;$i++){
		//echo $every_person_jsons[$i];
		$hash = (array)json_decode($every_person_jsons[$i]);
		if($hash['name']==''){
			continue;
		}
		$pinyin='';
		for($j=0;$j<mb_strlen($hash['name'])/3;$j++){
			//echo mb_substr($hash['name'],$j,1);
			//$pinyin.=getinitial(mb_convert_encoding(mb_substr($hash['name'],$j,1), 'gbk','utf-8'));
			$pinyin.=getinitial(mb_convert_encoding(cutstr(substr($hash['name'],$j*3),1),'gbk','utf-8'));
		}
		echo $hash['link_path'];
		$all_person_str.='<tr><td><a href="'.$hash['link_path'].'">'.$hash['name'].'</a></td><td>'.$pinyin.'</td><td>'.$hash['renzhidanwei'].'</td><td>'.$hash['zhiwu'].'</td><td>'.$hash['chushengnianyue'].'</td></tr>';
		
		
	}

	$template_path = dirname(__FILE__).DIRECTORY_SEPARATOR.'search_tmpl.html';
	$template = fopen($template_path,'r');
	$template_str=fread($template,filesize($template_path));

	$template_str = str_replace('{all_person_str}',$all_person_str, $template_str);
	$template_str = mb_convert_encoding($template_str, "GBK", 'UTF-8');
	if($toggle=='1'){
		$dist_path = '/Users/wangxinyu/works/PDA20160719'.DIRECTORY_SEPARATOR.'search.html';
	}else{
		$dist_path = $_GET['json_folder'].DIRECTORY_SEPARATOR.'search.html';
	}
	// echo $dist_path;
	$dist_file = fopen($dist_path, 'w');
	// $o_data = fread($dist_file, filesize($dist_path));
	// echo $o_data;
	fwrite($dist_file, $template_str);
	fclose($dist_file);


	$index_template_path = dirname(__FILE__).DIRECTORY_SEPARATOR.'index.html';
	$index_tempate = fopen($index_template_path,'r');
	$index_str=fread($index_tempate,filesize($index_template_path));
	$index_str = mb_convert_encoding($index_str, "GBK", 'UTF-8');
	if($toggle=='1'){
		//$index_dist_path = 'D:'.DIRECTORY_SEPARATOR.mb_convert_encoding('领导干部名册.html','gbk','utf-8');
		$index_dist_path = '/Users/wangxinyu/works/PDA20160719/领导干部名册.html';
	}else{
		$index_dist_path = $_GET['json_folder'].DIRECTORY_SEPARATOR.'领导干部名册.html';
	}
	// echo $dist_path;
	$index_dist_file = fopen($index_dist_path, 'w');
	// $o_data = fread($dist_file, filesize($dist_path));
	// echo $o_data;
	fwrite($index_dist_file, $index_str);
	fclose($index_dist_file);



?>