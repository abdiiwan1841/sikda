<script>
jQuery().ready(function (){ 
	jQuery("#listobatpopup").jqGrid({ 
		url:'c_master_obat/obatxml', 
		emptyrecords: 'Nothing to display',
		datatype: "xml", 
		colNames:['Pilih','KODE OBAT','NAMA OBAT','KD GOL OBAT','KD SAT KECIL','KD SAT BESAR','KD TERAPI OBAT','GENERIK','FRACTION','SINGKATAN','IS DEFAULT','','Action'],
		rownumbers:true,
		width: 1021,
		height: 'auto',
		mtype: 'POST',
		altRows     : true,		
		colModel:[ 
				{name:'kd_obat',index:'kd_obat', width:20,hidden:false,formatter:formatterAction}, 
				{name:'kode_obat_val',index:'kode_obat_val', width:80},				
				{name:'nama_obat',index:'nama_obat', width:90}, 
				{name:'kd_gol_obat',index:'kd_gol_obat', width:80,hidden:true},
				{name:'kd_sat_kecil',index:'kd_sat_kecil', width:80,hidden:true},
				{name:'kd_sat_besar',index:'kd_sat_besar', width:80,hidden:true},
				{name:'kd_ter_obat',index:'kd_ter_obat', width:90},
				{name:'generik',index:'generik', width:60,hidden:true},
				{name:'fraction',index:'fraction', width:73,hidden:true},
				{name:'singkatan',index:'singkatan', width:73,hidden:true},
				{name:'de',index:'de', width:75,hidden:true},
				{name:'default',index:'default', width:75,hidden:true},
				{name:'myid',index:'myid', width:90,align:'center',hidden:true}
			],
			rowNum:5, 
			rowList:[10,20,30], 
			pager: jQuery('#pagerobatpopup'), 
			viewrecords: true, 
			sortorder: "desc",
			beforeRequest:function(){
				//dari=$('#dariobatpopup').val()?$('#dariobatpopup').val():'';
				//sampai=$('#sampaiobatpopup').val()?$('#sampaiobatpopup').val():'';
				nama=$('#namaobatpopup').val()?$('#namaobatpopup').val():'';
				$('#listobatpopup').setGridParam({postData:{/*'dari':'','sampai':'',*/'nama':nama}})
			}
	}).navGrid('#pagerobatpopup',{edit:false,add:false,del:false,search:false});
	
	function formatterAction(cellvalue, options, rowObject) {
		var content = '';
		return '<input type="checkbox" class="chk" name="chk[]" />';
		return content;
	}
	
	$(".chk").live('click', function(){		
		$("#listobatpopup").find('input[type=checkbox]').each(function() {
			$(this).change( function(){
				var colid = $(this).parents('tr:last').attr('id');
				if( $(this).is(':checked')) {
					$("#listobatpopup").jqGrid('setSelection', colid );
					$(this).prop('checked',true);
					var myCellDataId = jQuery('#listobatpopup').jqGrid('getCell', colid, 'myid');
					var myCellDataNamaposyandu = jQuery('#listobatpopup').jqGrid('getCell', colid, 'nama_obat');
					$('#<?=$id_caller?> #nama_obat_hidden').val(myCellDataId);
					$('#<?=$id_caller?> #nama_obat').val(myCellDataNamaposyandu);
					$("#dialogtransaksi_cari_namaobat").dialog("close");
				}
			});	
		});
	});
	
	$('form').resize(function(g) {
		if($("#listobatpopup").getGridParam("records")>0){
		jQuery('#listobatpopup').setGridWidth(($(this).width()));
		}
	});
	
	jQuery.fn.reset = function (){
	  $(this).each (function() { this.reset(); });
	}
	$("#resetobatpopup").live('click', function(event){
		event.preventDefault();
		$('#formobatpopup').reset();
		$('#listobatpopup').trigger("reloadGrid");
	});
	$("#cariobatpopup").live('click', function(event){
		event.preventDefault();
		$('#listobatpopup').trigger("reloadGrid");
	});
})
</script>

<div class="mycontent">
	<form id="formobatpopup">
	<div class="gridtitle">Daftar Obat</div>
		<fieldset style="margin:0 13px 0 13px ">
			<span>
				<label>Nama Obat</label>
				<input type="text" name="nama" class="nama" id="namaobatpopup"/>
			</span>
			<span>
				<input type="submit" class="cari" value="&nbsp;Cari&nbsp;" id="cariobatpopup" />
				<input type="submit" class="reset" value="&nbsp;Reset&nbsp;" id="resetobatpopup" />
			</span>
		</fieldset>
		<table id="listobatpopup"></table>
		<div id="pagerobatpopup"></div>
		</div >
	</form>
</div>