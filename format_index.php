<? 
	require_once('simple_html_dom.php');
	set_time_limit(0);
	$toggle = $_GET['toggle'];
	if($toggle=='1'){
		$path = 'D:\\PDA20160719\省级干部\html\陕西省人民政府.htm';
		$json_file_path = 'D:\\PDA20160719\export_json.txt';
		$json_file_path2 = 'D:\\PDA20160719\export_json2.txt';
		$bianzhi_path = 'D:\\PDA20160719\bianzhi.txt';
	}else{
		$path = $_GET['path'];
		$json_file_path = $_GET['json_folder'].DIRECTORY_SEPARATOR.'export_json.txt';
		$json_file_path2 = $_GET['json_folder'].DIRECTORY_SEPARATOR.'export_json2.txt';
		$bianzhi_path = $_GET['json_folder'].DIRECTORY_SEPARATOR.'bianzhi.txt';
	}


	$json_file2 = fopen($json_file_path2, 'r');
	$json_data2 = fread($json_file2, filesize($json_file_path2));
	$every_person_jsons= explode('|', $json_data2);


	$bianzhi_file = fopen($bianzhi_path, 'r');
	$bianzhi_data = fread($bianzhi_file, filesize($bianzhi_path));
	$bianzhi_data = str_replace(array(" ","　","\t","\n","\r"),array("","","","",""), $bianzhi_data);
	$bianzhi_data = mb_convert_encoding($bianzhi_data, 'UTF-8', 'GBK');
	$every_bianzhis = explode('；', $bianzhi_data);
	//print_r($every_bianzhis);

	$path_parts = pathinfo($path);
	$path = iconv('UTF-8','GBK', $path);
	$original_file = fopen($path,'r');
	$original_data = fread($original_file, filesize($path));
	$original_data = mb_convert_encoding($original_data, 'UTF-8', 'GBK');

	$html = new simple_html_dom();
	$html->load($original_data);
	
	$anchors = $html->find('a');
	$need_change = false;
	
	for($i=0;$i<sizeof($anchors);$i++){
		//echo $anchors[$i]->href;
		if(preg_match("/.*000\.htm$/",  $anchors[$i]->href)){
			$need_change = true;
			break;
		}
	}
	
	if($need_change ==false){
		exit;
	}
	
	$template_path = dirname(__FILE__).DIRECTORY_SEPARATOR.'inde_template.html';
	$template = fopen($template_path,'r');
	$template_str=fread($template,filesize($template_path));

	$link_area = $html->find('table')[0]->find('tr')[1];
	//echo '----';
	echo $link_area->find('td')[0]->children(2);
	$link_area->innertext = preg_replace('/<\/td>/','{bianzhi_str}</td>', $link_area->innertext );
	//$link_area->innertext = $link_area->innertext.'{bianzhi_str}';
	//echo $link_area->innertext;
	//echo '----';
	$template_str=str_replace('{link_area}',$link_area->outertext, $template_str); 
	$bianzhi_str = '';
	for($i=0;$i<sizeof($every_bianzhis)-1;$i++){
		$departments = explode('：',$every_bianzhis[$i]);
		
		if(strpos($link_area->innertext,$departments[0])>-1 && $departments[1]!=''){
			$bianzhi_str = ':['.$departments[1].$departments[2].']';
			break;
		}
	}
	$template_str=str_replace('{bianzhi_str}',$bianzhi_str, $template_str); 
	//echo ($link_area->innertext);
	$person_str='';
	for($n=0;$n<sizeof($anchors);$n++){
		//echo $anchors[$n]->href;
		for($i=0;$i<sizeof($every_person_jsons)-1;$i++){
		$hash = (array)json_decode($every_person_jsons[$i]);
		
		if(strpos($anchors[$n]->href,$hash['file_path'])>-1){
			//echo $anchors[$n]->href;
			print_r(mb_strlen($hash['jiguan']));
			$jiguan = '';
			for($j=0;$j<mb_strlen($hash['jiguan']);$j++){
				//echo mb_substr($hash['name'],$j,1);
				$jiguan.=mb_substr($hash['jiguan'],$j,1);
				if($j%2==1){
					$jiguan.='<br>';
				}
			}

			$person_str.='<tr><td><a href="'.$anchors[$n]->href.'">'.$anchors[$n]->plaintext.'</a></td><td>'.$hash['zhiwu'].'</td><td>'.$hash['chushengnianyue'].'</td><td>'.$jiguan.'</td><td class="xueli"><span class="jiaoyuxinxi">'.$hash['quanrizhijiaoyu'].'</span><span class="jiaoyuxinxi">'.$hash['quanrizhijiaoyu_yuanxiao'].'</span></td><td class="xueli"><span class="jiaoyuxinxi">'.$hash['zaizhijiaoyu'].'</span><span class="jiaoyuxinxi">'.$hash['zaizhijiaoyu_yuanxiao'].'</span></td><td>'.$hash['zhuanyejishuzhiwu'].'</td><td>'.$hash['renzhishijian'].'</td></tr>';
			break;
		}
		
	}

	}

	
	
	$template_str=str_replace('{person_str}',$person_str, $template_str);
	
	echo $template_str;
	$template_str = mb_convert_encoding($template_str, "GBK", 'UTF-8');
	//echo  $template_str;
	if($toggle=='1'){
		$dist_path = 'D:'.DIRECTORY_SEPARATOR.'a.htm';
	}else{
		$dist_path = $path;
	}
	//echo $dist_path;
	$dist_file = fopen($dist_path, 'w');
	// $o_data = fread($dist_file, filesize($dist_path));
	// echo $o_data;
	fwrite($dist_file, $template_str);
	fclose($dist_file);


?>	