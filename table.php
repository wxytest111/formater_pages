<?
	$folder_path = $_GET["folder_path"];
	// $folder_path = iconv( 'UTF-8', 'gb2312', $folder_path );
?>
<!DOCTYPE html>
<html lang="en">
<head>
	
	<!-- start: Meta -->
	<meta charset="utf-8">
	<title>格式转换</title>
	<meta name="description" content="Template">
	<meta name="author" content="Xinyu">
	<!-- end: Meta -->
	
	<!-- start: Mobile Specific -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- end: Mobile Specific -->
	
	<!-- start: CSS -->
	
	<!-- bootstrap -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- page css files -->
	<link href="assets/css/font-awesome.min.css" rel="stylesheet">
	<link href="assets/css/jquery-ui-1.10.3.custom.min.css" rel="stylesheet">
	<!-- <link href="http://localhost:8888/bootstrap/perfectum2/assets/css/dataTables.css" rel="stylesheet"> -->
	
	<!-- main style -->
	<link href="assets/css/style.min.css" rel="stylesheet">

	<!--[if lt IE 9 ]>
		<link href="assets/css/style-ie.css" rel="stylesheet">
	<![endif]-->
	
	<!-- end: CSS -->
	

	<!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
		
	  	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<script src="assets/js/respond.min.js"></script>
		
	<![endif]-->

	<!-- start: Favicon -->
	<!-- <link rel="shortcut icon" href="http://localhost:8888/bootstrap/perfectum2/img/favicon.ico"> -->
	<!-- end: Favicon -->
	
		
		
		
</head>

<body>
	
	<!-- start: Header -->
	<div class="navbar">
		<div class="container">
			<button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".sidebar-nav.nav-collapse">
			      <span class="icon-bar"></span>
			      <span class="icon-bar"></span>
			      <span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="index.html"> <img alt="Perfectum Dashboard" src="assets/img/logo20.png" /> <span>格式转换</span></a>
							
			<!-- start: Header Menu -->
			
			<!-- end: Header Menu -->
			
		</div>
	</div>
	<!-- start: Header -->
	
		<div class="container">
		<div class="row">
					<!-- start: Main Menu -->
			<div class="col-sm-2 main-menu-span">
				<div class="sidebar-nav nav-collapse collapse navbar-collapse">
					<ul class="nav nav-tabs nav-stacked main-menu">
						<li><a href="index.html"><i class="fa fa-home icon"></i><span class="hidden-sm"> 首页</span></a></li>
					</ul>
				</div><!--/.well -->
			</div><!--/col-->
			<!-- end: Main Menu -->
			
			<noscript>
				<div class="alert alert-block col-sm-10">
					<h4 class="alert-heading">Warning!</h4>
					<p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a> enabled to use this site.</p>
				</div>
			</noscript>
			
			<div id="content" class="col-sm-10">
			<!-- start: Content -->
			
			<div class="row">		
				<div class="col-lg-12">
					<div class="box">
						<div class="box-header" data-original-title>
							<h2><i class="fa fa-user"></i><span class="break"></span></h2>
							
						</div>
						<div class="box-content">
							<form class="form-horizontal" action="table.php" method="get">
								<div class="form-group">
									<label class="control-label col-sm-2">请输入目录:</label>
									<div class="col-sm-8">
										<input class="form-control" id="folder_path" name="folder_path" value="<? echo $folder_path; ?>"/>
									</div>
									<div class="col-sm-2">
										<button class="btn btn-primary" type='submit'>确认</button>
									</div>
								</div>

							</form>
							<?

								if(is_dir($folder_path)){
									$word_path= $folder_path.'/干部任免审批表.txt';
									$word_file = fopen($word_path,'r');
									$word_data = fread($word_file, filesize($word_path));
									$word_data = mb_convert_encoding($word_data, 'UTF-8', "GBK");
									$every_datas = split('干\ 部\ 任\ 免\ 审\ 批\ 表', $word_data);
									$export_path = $folder_path.'/export_json.txt';
									$export_file = fopen($export_path, 'w');
									
									$export_array = [];
									for($i=1;$i<sizeof($every_datas);$i++){
										$every_datas[$i] = str_replace(array(" ","　","\t","\n","\r"),array("","","","",""),$every_datas[$i]);
										preg_match('/(姓名)(.*)(性别)(.*)(籍贯)(.*)(出生地)(.*)(入党时间)(.*)(参加工作时间)(.*)(健康状况)(.*)(专业技术职务)(.*)(熟悉专业有何专长)(.*)(学历学位)(.*)(全日制教育)(.*)(毕业院校系及专业)(.*)(在职教育)(.*)(毕业院校系及专业)/', $every_datas[$i], $match);
								 		print_r($match[14]);
										$name=$match[2];
										$jiguan = $match[6];
										$chushengdi = $match[8];
										$rudangshijian = $match[10];
										$jiankangzhuangkuang = $match[14];
										$zhuanchang = $match[18];
										$quanrizhijiaoyu = $match[22];
										$quanrizhijiaoyu_yuanxiao = $match[24];
										$zaizhijiaoyu = $match[26];
										$zaizhijiaoyu_yuanxiao = $match[28];
										$export_array[] = json_encode(["name"=>$name, 'jiguan'=>$jiguan,'chushengdi'=>$chushengdi,'rudangshijian'=>$rudangshijian,'jiankangzhuangkuang'=>$jiankangzhuangkuang, 'zhuanchang'=>$zhuanchang,'quanrizhijiaoyu'=>$quanrizhijiaoyu,'quanrizhijiaoyu_yuanxiao'=>$quanrizhijiaoyu_yuanxiao,'zaizhijiaoyu'=>$zaizhijiaoyu,'zaizhijiaoyu_yuanxiao'=>$zaizhijiaoyu_yuanxiao]);
										// echo '<br>'.mb_convert_encoding($name, 'GBK', 'UTF-8');
										// echo '<br>'.mb_convert_encoding($jiguan, 'GBK', 'UTF-8');
										// echo '<br>'.mb_convert_encoding($chushengdi, 'GBK', 'UTF-8');
										// echo '<br>'.mb_convert_encoding($zhuanchang, 'GBK', 'UTF-8');
										// echo '<br>';
									}	
									// echo mb_convert_encoding($word_data,  "GBK", 'UTF-8');
									fwrite($export_file, implode('|',$export_array));
									fclose($export_file);
									$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder_path));
									?>
									<label>该目录下的文件有:</label>
										<table class="table table-striped table-bordered bootstrap-datatable datatable" id="file_name_tables">
											<thead>
												<tr>
													<th>Username</th>
													<th>Status</th>
												</tr>
											</thead>   
											<tbody>
									<?
									while($it->valid()) {

									    if (!$it->isDot()) {
									    	if(preg_match("/.*00000\.htm$/", $it->key())){
									    		// echo 'SubPathName: ' . $it->getSubPathName() . "<br/>";
									        	// echo 'SubPath:     ' . $it->getSubPath() . "<br/>";
									        	// echo 'Key:         ' . $it->key() . "<br/><br/>";
									        	?>
									        	<tr>
													<td><? echo $it->key(); ?></td>
													<td>
														<span class="label label-default">未进行转换</span>
													</td>
														
												</tr>
									        	<?
									    	}
									        
									    }

									    $it->next();
									}
							?>
									</tbody>
								</table>
							<?	
								}else {
							?>
								<div class="alert alert-danger" role="alert">不是一个合法的路径</div>
								
							<?
								}
							?>
							         
						</div>
					</div>
				</div><!--/col-->
			
			</div><!--/row-->

			
			
			
    
					<!-- end: Content -->
			</div><!--/#content.span10-->
				</div><!--/fluid-row-->
				
			<div class="modal fade" id="myModal">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">Modal title</h4>
						</div>
						<div class="modal-body">
							<p>Here settings can be configured...</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary">Save changes</button>
						</div>
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
		
		<div class="clearfix"></div>
		
		<footer>
			<p>
				<span style="text-align:center;display:block;">&copy; 2016 省组织部联合西安工业大学研制。 
			</p>
		</footer>
				
	</div><!--/.fluid-container-->

	<!-- start: JavaScript-->
	<!--[if !IE]>-->

			<script src="assets/js/jquery-2.0.3.min.js"></script>

	<!--<![endif]-->

	<!--[if IE]>
	
		<script src="assets/js/jquery-1.10.2.min.js"></script>
	
	<![endif]-->

	<!--[if !IE]>-->

		<script type="text/javascript">
			window.jQuery || document.write("<script src='assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
		</script>

	<!--<![endif]-->

	<!--[if IE]>
	
		<script type="text/javascript">
	 	window.jQuery || document.write("<script src='assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
		</script>
		
	<![endif]-->
	<script src="assets/js/jquery-migrate-1.2.1.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	
	
	<!-- page scripts -->
	<script src="assets/js/jquery-ui-1.10.3.custom.min.js"></script>
	<script src="assets/js/jquery.dataTables.min.js"></script>
	<script src="assets/js/dataTables.bootstrap.min.js"></script>
	
	<!-- theme scripts -->
	<script src="assets/js/default.min.js"></script>
	<script src="assets/js/core.min.js"></script>
	<script src="assets/js/underscore.js"></script>
	
	<!-- inline scripts related to this page -->
	<script src="assets/js/pages/table.js"></script>
	
		<!-- end: JavaScript-->
	
</body>
</html>
