<?
	#因为是临时解决方案，所以，就是能用就行了，完全不按照良好写法来
	require_once('simple_html_dom.php');
	$toggle = $_GET['toggle'];
	if($toggle=='1'){
		$path = '/Users/wangxinyu/works/20160714/html/周玉明_BD518363-48B0-DC3F-9B6C-81BE36B7ADA2_F1400M000000000.htm';
		$json_file_path = '/Users/wangxinyu/works/20160714/export_json.txt';
	}else{
		$path = $_GET['path'];
		$json_file_path = $_GET['json_folder'].'/export_json.txt';
	}
	

	$json_file = fopen($json_file_path, 'r');
	$json_data = fread($json_file, filesize($json_file_path));
	$every_person_jsons= explode('|', $json_data);
	
	$path_parts = pathinfo($path);
	$original_file = fopen($path,'r');
	$original_data = fread($original_file, filesize($path));
	
	if(strpos($original_data, 'Xinyu Wang')>-1){
		echo $original_data;
		exit;
	}
	// $data = mb_convert_encoding($original_data, "UTF-8", 'GBK');
	// echo $original_data;

	// echo '----';
	// $enc = mb_detect_encoding($data);
	// $data = mb_convert_encoding($data, "UTF-8", $enc);
	
	// echo $path_parts['dirname'], "\n";
	// echo $path_parts['basename'], "\n";
	// echo $path_parts['extension'], "\n";
	// echo $path_parts['filename'], "\n";
	$template_path = dirname(__FILE__).'/template.html';
	$template = fopen($template_path,'r');
	$template_str=fread($template,filesize($template_path));
	// echo mb_detect_encoding($template_str);
	// $template_str = mb_convert_encoding($template_str, "GBK", 'UTF-8');
	$title = '干 部 任 免 表';
	$template_str=str_replace('{title}',$title, $template_str); 
	$html = new simple_html_dom();
	$html->load($original_data);

	$divs= $html->find('div[align=right]');
	// echo sizeof($divs);

	# 基本信息 -------------------
	$base_table = $html->find('table')[0];
	$base_info = $base_table->find('tr')[2]->find('p')[0]->plaintext;
	$base_infos = explode('，', $base_info);
	$name_str = explode('_', $path_parts['filename'])[0];
	$template_str = str_replace('{name_str}',$name_str, $template_str);
	$template_str = str_replace('{gender_str}',trim($base_infos[0]), $template_str);
	$template_str = str_replace('{mingzu_str}',trim($base_infos[1]), $template_str);
	$niansui = explode('(', $base_infos[2]);
	$template_str = str_replace('{nianyue_str}',preg_replace('/年/','.',preg_replace('/月出生\)/','',$niansui[1])), $template_str);
	$template_str = str_replace('{suishu_str}',trim($niansui[0]), $template_str);
	$jiguan_str = preg_replace('/省|市|县/','.',preg_replace('/人/','',$base_infos[3]));
	$jiguan_arrays = explode('.', $jiguan_str);
	for($i=0;$i<sizeof($every_person_jsons);$i++){
		$hash = (array)json_decode($every_person_jsons[$i]);
		echo mb_convert_encoding($hash['rudangshijian'], 'GBK', 'UTF-8');
		if(strpos($hash['jiguan'], $jiguan_arrays[sizeof($jiguan_arrays)-2])>-1){
			echo(mb_convert_encoding($hash['rudangshijian'],'GBK', 'UTF-8'));
			$template_str = str_replace('{jiguan_str}',$hash['jiguan'], $template_str);
			$template_str = str_replace('{rudang_str}',$hash['rudangshijian'], $template_str);
			$template_str = str_replace('{chushengdi_str}',$hash['chushengdi'], $template_str);
			$template_str = str_replace('{jiankangzhuangkuang_str}',$hash['jiankangzhuangkuang'], $template_str);
			$template_str = str_replace('{zhuanchang_str}',$hash['zhuanchang'], $template_str);
			$template_str = str_replace('{quanrizhijiaoyu_str}',$hash['quanrizhijiaoyu'], $template_str);
			$template_str = str_replace('{quanrizhijiaoyu_yuanxiao_str}',$hash['quanrizhijiaoyu_yuanxiao'], $template_str);
			$template_str = str_replace('{zaizhijiaoyu_str}',$hash['zaizhijiaoyu'], $template_str);
			$template_str = str_replace('{zaizhijiaoyu_yuanxiao_str}',$hash['zaizhijiaoyu_yuanxiao'], $template_str);
			
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
	# 职务信息 -------------------
	$zhiwu_div = $divs[0];
	$zhiwu_table = $zhiwu_div->next_sibling();
	$zhiwus= $zhiwu_table->find('tr');
	$zhiwu_template = '<span class="st_font">{zhiwu}</span>';
	$zhiwu_array = [];
	for($i=1;$i<sizeof($zhiwus);$i++){
		// echo $zhiwus[$i]->find('td')[0];
		$temp_str = $zhiwu_template; 
		$temp_str=str_replace('{zhiwu}',$zhiwus[$i]->find('td')[0]->plaintext, $temp_str); 
		$zhiwu_array[]=$temp_str;
	}
	$zhiwu_str = join('，',$zhiwu_array);
	# --------------------------

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
		                <td colspan="4"
		                    class="home_fifth_td">
		                    <p style="margin:0pt; orphans:0; text-align:justify; widows:0"><span
		                            class="st_font">{danweihezhiwu}</span></p></td>
		                <td style="vertical-align:top"></td>
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
	$template_str = mb_convert_encoding($template_str, "GBK", 'UTF-8');
	echo  $template_str;
	if($toggle=='1'){
		$dist_path = '/Users/wangxinyu/works/20001010/'.$path_parts['basename'];
	}else{
		$dist_path = $path;
	}
	// echo $dist_path;
	$dist_file = fopen($dist_path, 'w');
	// $o_data = fread($dist_file, filesize($dist_path));
	// echo $o_data;
	fwrite($dist_file, $template_str);
	fclose($dist_file);
?>