<script>
$(document).ready(function(){
		$('#formasalpasienmasterasalpasienedit').ajaxForm({
			beforeSend: function() {
				achtungShowLoader();	
			},
			uploadProgress: function(event, position, total, percentComplete) {
			},
			complete: function(xhr) {
				achtungHideLoader();
				if(xhr.responseText!=='OK'){
					$.achtung({message: xhr.responseText, timeout:5});
				}else{
					$.achtung({message: 'Proses Ubah Data Berhasil', timeout:5});
					$("#t14","#tabs").empty();
					$("#t14","#tabs").load('c_master_asal_pasien'+'?_=' + (new Date()).getTime());
				}
			}
		});
})
</script>
<script>
	$('#backlistmasterasalpasien').click(function(){
		$("#t14","#tabs").empty();
		$("#t14","#tabs").load('c_master_asal_pasien'+'?_=' + (new Date()).getTime());
	})
</script>
<div class="mycontent">
<div class="formtitle">Edit Master Asal Pasien</div>
<div class="backbutton"><span class="kembali" id="backlistmasterasalpasien">kembali ke list</span></div>
</br>

<span id='errormsg'></span>
<form name="frApps" id="formasalpasienmasterasalpasienedit" method="post" action="<?=site_url('c_master_asal_pasien/editprocess')?>" enctype="multipart/form-data">
	<fieldset>
		<span>
		<label>Kode Asal</label>
		<input type="text" name="kode_asal" id="kode_asal" value="<?=$data->KD_ASAL?>" />
		<input type="hidden" name="id" id="kode_asal" value="<?=$data->KD_ASAL?>" />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>Asal Pasien</label>
		<input type="text" name="asal_pasien" id="asal_pasien" value="<?=$data->ASAL_PASIEN?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<input type="submit" name="bt1" value="Proses Data"/>
		</span>
	</fieldset>
</form>
</div >