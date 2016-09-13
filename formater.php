<?
	#因为是临时解决方案，所以，就是能用就行了，完全不按照良好写法来
	require_once('simple_html_dom.php');
	set_time_limit(0);
	$toggle = $_GET['toggle'];
	if($toggle=='1'){
		//$path = 'D:\\PDA20160719\全体干部\html\刘丹冰_730C755A-42BF-E108-183A-F58359752B12_F1300O000000000.htm';
		$path = '/Users/wangxinyu/works/PDA20160719/省级干部/html/娄勤俭_1F6C5B94-4188-AE86-63E0-19899B1300B7_F10000000000000.htm';
		$json_file_path = '/Users/wangxinyu/works/PDA20160719/export_json.txt';
		$json_file_path2 = '/Users/wangxinyu/works/PDA20160719/export_json2.txt';
	}else{
		$path = $_GET['path'];
		$json_file_path = $_GET['json_folder'].DIRECTORY_SEPARATOR.'export_json.txt';
		$json_file_path2 = $_GET['json_folder'].DIRECTORY_SEPARATOR.'export_json2.txt';
	}
	
	
	$json_file = fopen($json_file_path, 'r');
	$json_data = fread($json_file, filesize($json_file_path));
	$every_person_jsons= explode('|', $json_data);
	$raw_path = $path;

	$path_parts = pathinfo($path);
	//$path = iconv('UTF-8','GBK', $path);
	echo $path;

	$link_path = '';
	/*
	if(strpos($path, mb_convert_encoding('全体干部','gbk','utf-8'))>-1){
		$link_path = substr($path, strpos($path, mb_convert_encoding('全体干部','gbk','utf-8')));
	}else if(strpos($path, mb_convert_encoding('省级干部','gbk','utf-8'))>-1){
		$link_path = substr($path, strpos($path, mb_convert_encoding('省级干部','gbk','utf-8')));
	}else if(strpos($path, mb_convert_encoding('部机关干部','gbk','utf-8'))>-1) {
		$link_path = substr($path, strpos($path, mb_convert_encoding('部机关干部','gbk','utf-8')));
	}
	*/

	if(strpos($path, '全体干部')>-1){
		$link_path = substr($path, strpos($path, '全体干部'));
	}else if(strpos($path, '省级干部')>-1){
		$link_path = substr($path, strpos($path, '省级干部'));
	}else if(strpos($path, '部机关干部')>-1) {
		$link_path = substr($path, strpos($path, '部机关干部'));
	}

	$original_file = fopen($path,'r');
	$original_data = fread($original_file, filesize($path));
	//echo $original_data;
	if(strpos($original_data, 'Xinyu Wang')>-1){
		echo $original_data;
		exit;
	}
	$data = mb_convert_encoding($original_data, "UTF-8", 'GBK');
	//echo $data;

	// echo '----';
	// $enc = mb_detect_encoding($data);
	// $data = mb_convert_encoding($data, "UTF-8", $enc);
	
	// echo $path_parts['dirname'], "\n";
	// echo $path_parts['basename'], "\n";
	// echo $path_parts['extension'], "\n";
	// echo $path_parts['filename'], "\n";
	$template_path = dirname(__FILE__).DIRECTORY_SEPARATOR.'template.html';
	$template = fopen($template_path,'r');
	$template_str=fread($template,filesize($template_path));
	// echo mb_detect_encoding($template_str);
	// $template_str = mb_convert_encoding($template_str, "GBK", 'UTF-8');
	$title = '干 部 任 免 表';
	$template_str=str_replace('{title}',$title, $template_str); 
	$html = new simple_html_dom();
	$html->load($original_data);

	$divs= $html->find('div[align=right]');

	# 基本信息 -------------------
	$base_table = $html->find('table')[0];
	$base_info = $base_table->find('tr')[2]->find('p')[0]->plaintext;
	$base_infos = explode('，', $base_info);
	$name_str = str_replace(array(" ","　","\t","\n","\r"),array("","","","",""), explode('_',explode(DIRECTORY_SEPARATOR,$raw_path)[sizeof(explode(DIRECTORY_SEPARATOR,$raw_path))-1])[0]);
	//echo mb_detect_encoding($name_str);
	echo '分隔符----';
	$template_str = str_replace('{name_str}',$name_str, $template_str);
	$template_str = str_replace('{gender_str}',trim($base_infos[0]), $template_str);
	$template_str = str_replace('{mingzu_str}',trim($base_infos[1]), $template_str);
	$niansui = explode('(', $base_infos[2]);
	$nianyue_str = preg_replace('/年/','.',preg_replace('/月出生\)/','',$niansui[1]));
	$template_str = str_replace('{nianyue_str}',$nianyue_str, $template_str);
	$template_str = str_replace('{suishu_str}',trim($niansui[0]), $template_str);
	$jiguan_str = preg_replace('/省|市|县/','.',preg_replace('/人/','',$base_infos[3]));
	$jiguan_arrays = explode('.', $jiguan_str);
	echo $jiguan_arrays[sizeof($jiguan_arrays)-2]; 

	#职务信息 ---------------------------
	$zhiwu_div = $divs[0];
	$zhiwu_table = $zhiwu_div->next_sibling();
	$zhiwus = $zhiwu_table->find('tr');
	$renzhidanwei = '';
	$zhiwu='';
	$renzhishijian='';
	if(sizeof($zhiwus)>1){
		$tds= $zhiwus[1]->find('td');
		$renzhidanwei = $tds[0]->plaintext;
		$zhiwu = $tds[1]->plaintext;
		$renzhishijian = $tds[3]->plaintext;
	};
	echo '---';
	echo $zhiwu;
	echo '----';
	echo $renzhidanwei;
	echo '---';
	echo 'asfdasfasdf';
	for($i=0;$i<sizeof($every_person_jsons);$i++){
		$hash = (array)json_decode($every_person_jsons[$i]);
		//echo $hash['jiguan'];
		//echo (strpos($hash['jiguan'], $jiguan_arrays[sizeof($jiguan_arrays)-2])>-1);
		if(($name_str==$hash['name'])&&(strpos($hash['jiguan'], $jiguan_arrays[sizeof($jiguan_arrays)-2])>-1)){
			echo($hash['rudangshijian']);
			echo($hash['xianrenzhiwu']);
			//echo '---------------------------我勒个去';
			//print_r(explode('',$hash['jiguan']));
			$template_str = str_replace('{jiguan_str}',$hash['jiguan'], $template_str);
			$template_str = str_replace('{rudang_str}',$hash['rudangshijian'], $template_str);
			$template_str = str_replace('{chushengdi_str}',$hash['chushengdi'], $template_str);
			$template_str = str_replace('{jiankangzhuangkuang_str}',$hash['jiankangzhuangkuang'], $template_str);
			$template_str = str_replace('{zhuanchang_str}',$hash['zhuanchang'], $template_str);
			$template_str = str_replace('{quanrizhijiaoyu_str}',$hash['quanrizhijiaoyu'], $template_str);
			$template_str = str_replace('{quanrizhijiaoyu_yuanxiao_str}',$hash['quanrizhijiaoyu_yuanxiao'], $template_str);
			$template_str = str_replace('{zaizhijiaoyu_str}',$hash['zaizhijiaoyu'], $template_str);
			$template_str = str_replace('{zaizhijiaoyu_yuanxiao_str}',$hash['zaizhijiaoyu_yuanxiao'], $template_str);
			$template_str = str_replace('{xianrenzhiwu_str}',$hash['xianrenzhiwu'], $template_str);
			$hash['zhiwu']=$zhiwu;
			$hash['renzhidanwei']=$renzhidanwei;
			$hash['renzhishijian']=$renzhishijian;
			$hash['file_path']=$path_parts['basename'];
			$hash['chushengnianyue']=$nianyue_str;
			//$hash['link_path']=mb_convert_encoding($link_path, 'utf-8', 'gbk');
			$hash['link_path']=$link_path;
			$hash['bbbb']='省级领导\html\呵呵呵呵__sadfasfd.html';
			$json_file2 = fopen($json_file_path2,'a');
			fwrite($json_file2, json_encode($hash).'|');
			fclose($json_file2);
			break;
		}
	}
	
	$template_str = str_replace('{chushengdi_str}','', $template_str);
	if(sizeof(explode('中共党员', $base_infos[4]))>1){
		$canjiagongzuo_str = $base_infos[5];
		$rudang_str = $base_infos[4];
	}else{
		$canjiagongzuo_str = $base_infos[4];
		$rudang_str = '';
	}
	// echo mb_detect_encoding($base_infos[4]);
	$template_str = str_replace('{rudang_str}',preg_replace('/年/','.',preg_replace('/月参加组织\)/','',explode('(',$rudang_str)[1])), $template_str);
	$template_str = str_replace('{canjiagongzuo_str}',preg_replace('/年/','.',preg_replace('/月参加工作/','',explode('。',$canjiagongzuo_str)[0])), $template_str);
	$img_path= $base_table->find('tr')[2]->find('img')[0]->src;
	$template_str = str_replace('{img_path}',$img_path, $template_str);
	# --------------------------

	# 专业技术信息-------------------
	$zhuanye_div = $divs[6];
	$zhuanye_table = $zhuanye_div->next_sibling();
	$zhuanyes = $zhuanye_table->find('tr');
	$zhuanye_template = '<p class="jianli_text">
                        	<span class="st_font">{zhuanye}</span>
                    	</p>';
	$zhuanyejishu_str = '';
	if($zhuanyes[1]){
		$zhuanyejishu_str = $zhuanyes[1]->find('td')[1]->plaintext; 
	}
	$template_str = str_replace('{zhuanyejishu_str}',$zhuanyejishu_str, $template_str);
	# -------------------------

	# 简历信息 -------------------
	$resume_div = $divs[1];
	$resume_table = $resume_div->next_sibling();
	$resumes = $resume_table->find('tr');
	$resume_template = '<p class="jianli_text">
                        	<span class="st_font">{resume}</span>
                    	</p>';
	$resume_str = '';
	for($i=1;$i<sizeof($resumes);$i++){
		$temp_str = $resume_template; 
		$temp_str=str_replace('{resume}',$resumes[$i]->plaintext, $temp_str); 
		$resume_str.=$temp_str;
	}
	$zhiwu_str = explode('-', $resumes[sizeof($resumes)-1]->plaintext)[1];
	if(sizeof($resumes)<8){
		for($i=1;$i<8-sizeof($resumes);$i++){
			$temp_str = $resume_template; 
			$temp_str=str_replace('{resume}','', $temp_str); 
			$resume_str.=$temp_str;
		}
	}
	#----------------------------


	# 奖惩情况 -------------------
	$jiangcheng_div = $divs[5];
	$jiangcheng_table = $jiangcheng_div->next_sibling();
	$jiangchengs = $jiangcheng_table->find('tr');
	$jiangcheng_template = '<p class="jianli_text">
                        	<span class="st_font">{jiangcheng}</span>
                    	</p>';
	$jiangcheng_str = '';
	for($i=1;$i<sizeof($jiangchengs);$i++){
		$temp_str = $jiangcheng_template; 
		$temp_str=str_replace('{jiangcheng}',$jiangchengs[$i]->plaintext, $temp_str); 
		$jiangcheng_str.=$temp_str;
	}
	if(sizeof($jiangchengs)<8){
		for($i=1;$i<8-sizeof($jiangchengs);$i++){
			$temp_str = $jiangcheng_template; 
			$temp_str=str_replace('{jiangcheng}','', $temp_str); 
			$jiangcheng_str.=$temp_str;
		}
	}
	# --------------------------


	# 家庭信息 -------------------
	$home_template = '<tr style="height:26.45pt">
		                <td colspan="2"
		                    class="home_first_td">
		                    <p style="margin:0pt; orphans:0; text-align:center; widows:0"><span
		                            class="st_font">{chengwei}</span></p></td>
		                <td colspan="3"
		                    class="home_second_td">
		                    <p style="margin:0pt; orphans:0; text-align:center; widows:0"><span
		                            class="st_font">{xingming}</span></p></td>
		                <td colspan="2"
		                    class="home_third_td">
		                    <p style="margin:0pt; orphans:0; text-align:center; widows:0"><span
		                            class="st_font">{nianling}</span></p></td>
		                <td colspan="3"
		                    class="home_forth_td">
		                    <p style="margin:0pt; orphans:0; text-align:center; widows:0"><span
		                            class="st_font">{zhengzhimianmao}</span></p></td>
		                <td colspan="5"
		                    class="home_fifth_td">
		                    <p style="margin:0pt; orphans:0; text-align:justify; widows:0"><span
		                            class="st_font">{danweihezhiwu}</span></p></td>
		                
		            </tr>';
	$home_str = '';
	$next = $divs[8];
	for($i=0;$i<7;$i++){
		if($next){
			$next= $next->next_sibling();
			$strings = explode('，',$next->plaintext);
			$nlings = explode('.',$strings[2]);
			$chengwei = $strings[0];
			$xingming = $strings[1];
			if(sizeof($nlings)==2){
				$nianling = 2016-$nlings[0];
			}else{
				$nianling = '';
			}
			$zhengzhimianmao = $strings[4];
			$danweihezhiwu = $strings[5];
		}else{
			$chengwei = '';
			$xingming = '';
			$nianling = '';
			$zhengzhimianmao = '';
			$danweihezhiwu = '';
		}
		$temp_str = $home_template; 
		$temp_str=str_replace('{chengwei}',$chengwei, $temp_str); 
		$temp_str=str_replace('{xingming}',$xingming, $temp_str); 
		$temp_str=str_replace('{nianling}',$nianling, $temp_str); 
		$temp_str=str_replace('{zhengzhimianmao}',$zhengzhimianmao, $temp_str); 
		$temp_str=str_replace('{danweihezhiwu}',$danweihezhiwu, $temp_str); 
		$home_str.=$temp_str;
	}
	# -------------------------


	$template_str = str_replace('{home_str}',$home_str, $template_str);
	$template_str = str_replace('{resume_str}',$resume_str, $template_str);
	$template_str = str_replace('{zhiwu_str}',$zhiwu_str, $template_str);
	$template_str = str_replace('{jiangcheng_str}',$jiangcheng_str, $template_str);
	# 最后这里还是转成gbk了，因为那个网页上的charset默认是gbk
	$template_str = mb_convert_encoding($template_str, "GBK", 'UTF-8');
	echo  $template_str;
	if($toggle=='1'){
		$dist_path = '/Users/wangxinyu/works/PDA20160719'.DIRECTORY_SEPARATOR.$path_parts['basename'];
	}else{
		$dist_path = $path;
	}
	$dist_path = $path;
	// echo $dist_path;
	$dist_file = fopen($dist_path, 'w');
	// $o_data = fread($dist_file, filesize($dist_path));
	// echo $o_data;
	fwrite($dist_file, $template_str);
	fclose($dist_file);
?>