$(document).ready(function(){
	/* ---------- Datable ---------- */

	if($('#file_name_tables .name_files').length>0){
		_.each($('#file_name_tables .name_files'),function(ele){
			//console.log($(ele).html());
			$.ajax({
				url:'/formater.php',
				data:{
					path:$(ele).html(),
					json_folder: $('#folder_path').val()
				}
			}).done(function(res){
				$(ele).parent().find('td:nth(1)').html('<span class="label label-success">完成转化</span>')
			});
		})
	}
	

	if($('#file_name_tables .index_files').length>0){
		_.each($('#file_name_tables .index_files'),function(ele){
			//console.log($(ele).html());
			$.ajax({
				url:'/format_index.php',
				data:{
					path:$(ele).html(),
					json_folder: $('#folder_path').val()
				}
			}).done(function(res){
				$(ele).parent().find('td:nth(1)').html('<span class="label label-success">完成转化</span>')
			});
		})
	}
	if($('#file_name_tables .name_files').length>0){
		$.ajax({
			url:'/format_search.php',
			data:{
				json_folder: $('#folder_path').val()
			}
		}).done(function(res){
			alert('格式化完成');
		});
	}

	
});