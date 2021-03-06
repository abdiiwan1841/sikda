<script>
$(document).ready(function(){
		$('#form1ds_promkesedit').ajaxForm({
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
					$("#t810","#tabs").empty();
					$("#t810","#tabs").load('t_ds_promkes'+'?_=' + (new Date()).getTime());
				}
			}
		});
})

$("#desa_kel_id_combo_ds_promkes").remoteChained("#kec_id_combo_ds_promkes", "<?=site_url('t_masters/getKelurahanByKecamatanId3')?>");
$("#puskesmas_id_combo_ds_promkes").remoteChained("#kec_id_combo_ds_promkes", "<?=site_url('t_masters/getPuskesmasByKecamatanId2')?>");

$(':text,:radio,:checkbox,select,textarea').bind("keydown", function(e) {
   var n = $(":text,:radio,:checkbox,select,textarea").length;
   if (e.which == 13) 
   {
		e.preventDefault();
		var nextIndex = $(':text,:radio,:checkbox,select,textarea').index(this) + 1;
		var thisIndex = $(':text,:radio,:checkbox,select,textarea').index(this);
		$(':text,:radio,:checkbox,select,textarea')[nextIndex].focus();
   }
});
</script>
<script>
	$('#backlistds_promkes').click(function(){
		$("#t810","#tabs").empty();
		$("#t810","#tabs").load('t_ds_promkes'+'?_=' + (new Date()).getTime());
	})
	$("#tahun_ds_promkes").mask("9999");
</script>
<style>
input[type=text] {width: 55px!important;}
label{width:295px!important;}
</style>
<div class="mycontent">
<div class="formtitle">Edit Data Gigi</div>
<div class="backbutton"><span class="kembali" id="backlistds_promkes">kembali ke list</span></div>
</br>

<span id='errormsg'></span>
<form name="frApps" id="form1ds_promkesedit" method="post" action="<?=site_url('t_ds_promkes/editprocess')?>">	
	<fieldset>
		<span>
		<label>Propinsi</label>
		<input type="text" style="width:195px!important" name="" id="textid" value="<?=$data->PROVINSI?>" disabled />
		<input type="hidden" name="ID" value="<?=$data->ID?>" />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>Kabupaten</label>
		<input type="text" style="width:195px!important" name="" id="textid" value="<?=$data->KABUPATEN?>" disabled />
		</span>
	</fieldset>
	<?=getComboKecamatanByKab($data->KD_KECAMATAN,'KD_KECAMATAN','kec_id_combo_ds_promkes','required','')?>
	<?=getComboKelurahanByKec($data->KD_KELURAHAN,'KD_KELURAHAN','desa_kel_id_combo_ds_promkes','required','')?>
	<?=getComboPuskesmasByKec($data->KD_PUSKESMAS,'KD_PUSKESMAS','puskesmas_id_combo_ds_promkes','required','')?>
	<fieldset>
		<span>
		<label>Bulan*</label>
		<select name="BULAN" required>
			<option value="">- pilih bulan -</option>
			<option value="01" <?=$data->BULAN=='01'?'selected':'';?> >Januari</option>
			<option value="02" <?=$data->BULAN=='02'?'selected':'';?> >Februari</option>
			<option value="03" <?=$data->BULAN=='03'?'selected':'';?> >Maret</option>
			<option value="04" <?=$data->BULAN=='04'?'selected':'';?> >April</option>
			<option value="05" <?=$data->BULAN=='05'?'selected':'';?> >Mei</option>
			<option value="06" <?=$data->BULAN=='06'?'selected':'';?> >Juni</option>
			<option value="07" <?=$data->BULAN=='07'?'selected':'';?> >Juli</option>
			<option value="08" <?=$data->BULAN=='08'?'selected':'';?> >Agustus</option>
			<option value="09" <?=$data->BULAN=='09'?'selected':'';?> >September</option>
			<option value="10" <?=$data->BULAN=='10'?'selected':'';?> >Oktober</option>
			<option value="11" <?=$data->BULAN=='11'?'selected':'';?> >November</option>
			<option value="12" <?=$data->BULAN=='12'?'selected':'';?> >Desember</option>
		</select>
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>Tahun*</label>
		<input type="text" name="TAHUN" id="tahun_ds_promkes" value="<?=$data->TAHUN?>" required />
		</span>
	</fieldset>
	</br>
	</br>
	<fieldset>
		<span>
		<label>POSYANDU YANG AKTIF</label>
		<input type="text" name="JML_P_AKTIF" value="<?=$data->JML_P_AKTIF?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>POSYANDU PRATAMA</label>
		<input type="text" name="JML_P_PRATAMA" value="<?=$data->JML_P_PRATAMA?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>POSYANDU MADYA</label>
		<input type="text" name="JML_P_MADYA" value="<?=$data->JML_P_MADYA?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>POSYANDU PURNAMA</label>
		<input type="text" name="JML_P_PURNAMA" value="<?=$data->JML_P_PURNAMA?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>POSYANDU MANDIRI</label>
		<input type="text" name="JML_P_MANDIRI" value="<?=$data->JML_P_MANDIRI?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>POSYANDU LANSIA</label>
		<input type="text" name="JML_P_LANSIA" value="<?=$data->JML_P_LANSIA?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>LAPORAN KEGIATAN POSYANDU LANSIA</label>
		<input type="text" name="JML_LKP_LANSIA" value="<?=$data->JML_LKP_LANSIA?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>KADER AKTIF</label>
		<input type="text" name="JML_KADER_AKTIF" value="<?=$data->JML_KADER_AKTIF?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>PONDOK PESANTREN YANG DIBINA</label>
		<input type="text" name="JML_PP_DIBINA" value="<?=$data->JML_PP_DIBINA?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>FREKUENSI PEMBINAAN PONDOK PESANTREN</label>
		<input type="text" name="JML_FPP_PESANTREN" value="<?=$data->JML_FPP_PESANTREN?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>SOSIALISASI KEGIATAN SAKA BAKTI HUSADA</label>
		<input type="text" name="JML_SKSB_HUSADA" value="<?=$data->JML_SKSB_HUSADA?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>PEMBINAAN SAKA BAKTI HUSADA</label>
		<input type="text" name="JML_PSB_HUSADA" value="<?=$data->JML_PSB_HUSADA?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>PENYULUHAN DB</label>
		<input type="text" name="JML_PENYULUHAN_DB" value="<?=$data->JML_PENYULUHAN_DB?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>PENYULUHAN KESLING</label>
		<input type="text" name="JML_PENYULUHAN_KESLING" value="<?=$data->JML_PENYULUHAN_KESLING?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>PENYULUHAN KIA</label>
		<input type="text" name="JML_PENYULUHAN_KIA" value="<?=$data->JML_PENYULUHAN_KIA?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>PENYULUHAN TBC</label>
		<input type="text" name="JML_PENYULUHAN_TBC" value="<?=$data->JML_PENYULUHAN_TBC?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>PENYULUHAN NAPZA</label>
		<input type="text" name="JML_PENYULUHAN_NAPZA" value="<?=$data->JML_PENYULUHAN_NAPZA?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>PENYULUHAN PTM</label>
		<input type="text" name="JML_PENYULUHAN_PTM" value="<?=$data->JML_PENYULUHAN_PTM?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>PENYULUHAN MALARIA</label>
		<input type="text" name="JML_PENYULUHAN_MALARIA" value="<?=$data->JML_PENYULUHAN_MALARIA?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>PENYULUHAN DIARE</label>
		<input type="text" name="JML_PENYULUHAN_DIARE" value="<?=$data->JML_PENYULUHAN_DIARE?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>PENYULUHAN GIZI</label>
		<input type="text" name="JML_PENYULUHAN_GIZI" value="<?=$data->JML_PENYULUHAN_GIZI?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>PENYULUHAN PHBS</label>
		<input type="text" name="JML_PENYULUHAN_PHBS" value="<?=$data->JML_PENYULUHAN_PHBS?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>PEMBERDAYAAN DALAM PSN</label>
		<input type="text" name="JML_PD_PSN" value="<?=$data->JML_PD_PSN?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>JUMLAH RUMAH BEBAS JENTIK</label>
		<input type="text" name="JML_RMH_BEBAS_JENTIK" value="<?=$data->JML_RMH_BEBAS_JENTIK?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>JUMLAH RUMAH DIPERIKSA</label>
		<input type="text" name="JML_RMH_DIPERIKSA" value="<?=$data->JML_RMH_DIPERIKSA?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>JUMLAH TTU BEBAS JENTIK</label>
		<input type="text" name="JML_TTU_BEBAS_JENTIK" value="<?=$data->JML_TTU_BEBAS_JENTIK?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>JUMLAH TTU DIPERIKSA</label>
		<input type="text" name="JML_TTU_DIPERIKSA" value="<?=$data->JML_TTU_DIPERIKSA?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>TOGA</label>
		<input type="text" name="JML_TOGA" value="<?=$data->JML_TOGA?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>PEMBERDAYAAN TOMAGA</label>
		<input type="text" name="JML_P_TOMAGA" value="<?=$data->JML_P_TOMAGA?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>PEMBINAAN UKK</label>
		<input type="text" name="JML_P_UKK" value="<?=$data->JML_P_UKK?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>PEMBINAAN DESA SIAGA</label>
		<input type="text" name="JML_PD_SIAGA" value="<?=$data->JML_PD_SIAGA?>"  />
		</span>
	</fieldset>
	</br>
	<div class="subformtitle1">RUMAH TANGGA</div>
	<fieldset>
		<span>
		<label>RUMAH TANGGA PRATAMA</label>
		<input type="text" name="JML_RT_PRATAMA" value="<?=$data->JML_RT_PRATAMA?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>RUMAH TANGGA MADYA</label>
		<input type="text" name="JML_RT_MADYA" value="<?=$data->JML_RT_MADYA?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<label>RUMAH TANGGA UTAMA</label>
		<input type="text" name="JML_RT_UTAMA" value="<?=$data->JML_RT_UTAMA?>"  />
		</span>
	</fieldset><fieldset>
		<span>
		<label>RUMAH TANGGA PARIPURNA</label>
		<input type="text" name="JML_RT_PARIPURNA" value="<?=$data->JML_RT_PARIPURNA?>"  />
		</span>
	</fieldset>
	<fieldset>
		<span>
		<input type="submit" name="bt1" value="Proses Data"/>
		</span>
	</fieldset>
</form>
</div >