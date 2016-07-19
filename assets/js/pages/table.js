$(document).ready(function(){
	/* ---------- Datable ---------- */
	$('.datatable').dataTable({
		"sDom": "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-12'i><'col-lg-12 center'p>>",
		"sPaginationType": "bootstrap",
		"iDisplayLength": 100,
		"oLanguage": {
			"sLengthMenu": "每页显示 _MENU_ 条记录",
		      "sZeroRecords": "抱歉， 没有找到",
		      "sInfo": "从 _START_ 到 _END_ /共 _TOTAL_ 条数据",
		      "sInfoEmpty": "没有数据",
		      "sInfoFiltered": "(从 _MAX_ 条数据中检索)",
		      "oPaginate": {
		        "sFirst": "首页",
		        "sPrevious": "前一页",
		        "sNext": "后一页",
		        "sLast": "尾页"
		      },
		      "sSearch": "搜索",
		      "sZeroRecords": "没有检索到数据"
		}
	});

	if($('#file_name_tables tbody tr').length>0){
		_.each($('#file_name_tables tbody tr'),function(ele){
			console.log($(ele).find('td:nth(0)').html());
			$.ajax({
				url:'/formater.php',
				data:{
					path:$(ele).find('td:nth(0)').html()
				}
			}).done(function(res){
				$(ele).find('td:nth(1)').html('<span class="label label-success">完成转化</span>')
			});
		})
	}

});