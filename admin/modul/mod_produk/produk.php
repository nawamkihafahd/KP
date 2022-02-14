<?php

session_start();

if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){

echo "<link href='style.css' rel='stylesheet' type='text/css'>

<center>Untuk mengakses modul, Anda harus login <br>";

echo "<a href=../../index.php><b>LOGIN</b></a></center>";

}

else{

$aksi="modul/mod_produk/aksi_produk.php";

switch($_GET[act]){

// Tampil Produk

default:

echo '

	<div>

		<ul class="breadcrumb">

			<li>

				<a href="?module=home">Home</a> <span class="divider">/</span>

			</li>

			<li>

				<a href="#">Produk</a>

			</li>

		</ul>

	</div>

';

		if($_GET[notifdel] == 'sukses'){

			echo '

				<br>

				<div class="alert alert-success">

					<button type="button" class="close" data-dismiss="alert">&times;</button>

					<strong>Warning!</strong> Delete Produk Berhasil

				</div>

			';

		}

echo '

	<div class="row-fluid sortable">

		<div class="box span12">

			<div class="box-header well" data-original-title>

				<h2><i class="icon-th-large"></i> Produk</h2>

				<div class="box-icon">

					<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>

					<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>

				</div>

			</div>

			<div class="box-content">

				<input type=button value=\'Tambah Produk\' class="btn btn-primary" 

				onclick="window.location.href=\'?module=produk&act=tambahproduk\';"><br /><br />

				<table class="table table-striped table-bordered bootstrap-datatable datatable">

					<thead>

						<tr>

							<th>No</th>

							<th>Nama Produk</th>

							<th>Harga</th>

							<th>Tgl. Masuk</th>

							<th>Muncul di Home</th>

							<th>Aksi</th>

						</tr>

					</thead>

					<tbody>

';

						$tampil = mysql_query("SELECT * FROM produk ORDER BY id_produk DESC");
						
				// 		$tampil = mysql_query("SELECT produk.id_produk, produk.nama_produk, produk.harga, produk.tgl_masuk, produk.status, ukuran.ukuran, uk_produk.stok
				// 		FROM uk_produk
				// 		JOIN produk
				// 		ON uk_produk.id_produk=produk.id_produk
				// 		JOIN ukuran
				// 		ON uk_produk.id_uk=ukuran.id_uk ORDER BY id_produk DESC");

						$no = 1;

						while($r=mysql_fetch_array($tampil)){

							$tanggal=tgl_indo($r[tgl_masuk]);

							$harga=format_rupiah($r[harga]);

							echo "

							<tr>

								<td>$no</td>

								<td>$r[nama_produk]</td>

								<td>$harga</td>

								<td>$tanggal</td>

								<td align=center>$r[status]</td>

								<td>

									<a class=\"btn btn-info\" href=?module=produk&act=editproduk&id=$r[id_produk]><i class=\"icon-edit icon-white\"></i> Edit</a> 

									<a class=\"btn btn-danger\" href='$aksi?module=produk&act=hapus&id=$r[id_produk]&namafile=$r[gambar]'><i class=\"icon-trash icon-white\"></i> Hapus</a>

								</td>

							</tr>

							";

							$no++;

						}

echo '

					</tbody>

				</table>

			</div>

		</div>

	</div>

';



break;



case "tambahproduk":

echo '

	<div>

		<ul class="breadcrumb">

			<li>

				<a href="?module=home">Home</a> <span class="divider">/</span>

			</li>

			<li>

				<a href="?module=produk">Produk</a> <span class="divider">/</span>

			</li>

			<li>

				<a href="#">Tambah Produk</a>

			</li>

		</ul>

	</div>

	<div class="row-fluid sortable">

		<div class="box span12">

			<div class="box-header well" data-original-title>

				<h2><i class="icon-plus-sign"></i> Tambah Produk</h2>

				<div class="box-icon">

					<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>

					<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>

				</div>

			</div>

			<div class="box-content">

				<form class="form-horizontal" method=POST action='.$aksi.'?module=produk&act=input enctype=\'multipart/form-data\'>

					<fieldset>

						<div class="control-group">

							<label class="control-label">Nama Produk</label>

							<div class="controls">

								<input type=text name="nama_produk" />

							</div>

						</div>

						<div class="control-group">

							<label class="control-label">Kategori</label>

							<div class="controls">

							<form method=post action='.$aksi.'?module=produk&act=subkategori>

								<select id="kategoriproduk" name="kategori">

									<option value=0 selected>- Pilih Kategori -</option>';

									$tampil=mysql_query("SELECT * FROM kategoriproduk ORDER BY nama_kategori");

									while($r=mysql_fetch_array($tampil)){

										echo "<option value=$r[id_kategori]>$r[nama_kategori]</option>";

									}

echo '

								</select>

							</form>

							</div>

						</div>

						<div class="control-group">
							<label class="control-label">Sub Kategori</label>
							<div class="controls">
								<select id="subkategori" name="subkategori">
									<option value=0 selected>- Pilih Sub Kategori -</option>
								</select>
							</div>
						</div>

						<div class="control-group">
							<label class="control-label">Sub Kategori 2</label>
							<div class="controls">
								<select id="subsubkategori" name="subsubkategori">
									<option value=0 selected>- Pilih Sub Kategori 2 -</option>
								</select>
							</div>
						</div>

						<div class="control-group">

							<label class="control-label">Berat</label>

							<div class="controls">

								<input type=text name="berat" /> Kg

							</div>

						</div>

						<div class="control-group">

							<label class="control-label">Diskon</label>

							<div class="controls">

								<input type=text name="diskon" />%

							</div>

						</div>

						<div class="control-group">

							<label class="control-label">Harga</label>

							<div class="controls">

								<input type=text name="harga" />

							</div>

						</div>
						<div class="control-group">

							<label class="control-label">Harga Tambahan (Untuk Penulisan Nomor Punggung dan Nama)</label>

							<div class="controls">

								<input type=text name="harga_tambahan" />

							</div>

						</div>

						<div class="control-group">

							<label class="control-label">Muncul di Home</label>

							<div class="controls">

								<label class="radio">

									<input type=radio name="status" value="Y">Y

								</label>

								<div style="clear:both"></div>

								<label class="radio">

									<input type=radio name="status" value="N"> N

								</label>

							</div>

						</div>

						<div class="control-group">

							<label class="control-label">Deskripsi</label>

							<div class="controls">

								<textarea id="loko" name=\'deskripsi\' style=\'width: 580px; height: 350px;\'></textarea>

							</div>

						</div>

						<div class="control-group">
			              <label class="control-label">Ukuran</label>
			                <div class="controls">
			                        <table >
			                          <tbody>
			                             <tr id="tambahUkuran" class="tambahuk count-huk0">
			                               <td>
			                                  	<select name="ukuran[]">
			                                    	<option value="2" selected>-- Pilih --</option>';
						                            $ukuran=mysql_query("SELECT * FROM ukuran ORDER BY id_uk");
													while($uk=mysql_fetch_array($ukuran)){
														echo "<option value=$uk[id_uk]>$uk[ukuran]</option>";
													}
													echo '
			                                  	</select>
			                              	</td>
			                              	<td>
												<div class="controls" style="margin-left: 20px;">

													<input type=text name="stok[]" placeholder="Stok"/>

												</div>
			                              	</td>
			                              	<td></td>
			                             </tr>
			                          </tbody>       
			                         </table>
			                      <a class="btn" onclick="AddNewUkuran(this)" data-add="0">Tambah Ukuran/Stok</a>
			                </div>
			              </div>

						<div class="control-group">

							<label class="control-label">Gambar</label>

							<div class="controls">

								<div class="alert alert-info">

											<button type="button" class="close" data-dismiss="alert">&times;</button>

											<strong>Warning!</strong> Tipe gambar harus JPG/JPEG, Ukuran Min 400px X 400px untuk mendapatkan tampilan yang proporsional

										</div>

										<table id="upl">

											<tbody>

											   <tr>

												   <td><input name="fupload[]" type="file" size="60"></td>

											   </tr>

											</tbody>		   

									   </table>

									   <br>

										<input type="button" class="btn" value="Tambah File" id="tambah">

										<input type="button" class="btn" value="Hapus File" id="hapus">

							</div>

						</div>

						<div class="form-actions">

							<input type=submit value=Simpan class="btn btn-primary">

							<input type=button value=Batal onclick=self.history.back() class="btn">

						</div>

					</fieldset>

				</form>	

		</div>

	</div>

';



 break;



case "editproduk":

$edit = mysql_query("SELECT * FROM produk WHERE id_produk='$_GET[id]'");

$r    = mysql_fetch_array($edit);

echo '

	<div>

		<ul class="breadcrumb">

			<li>

				<a href="?module=home">Home</a> <span class="divider">/</span>

			</li>

			<li>

				<a href="?module=produk">Produk</a> <span class="divider">/</span>

			</li>

			<li>

				<a href="#">Edit Produk</a>

			</li>

		</ul>

	</div>

	<div class="row-fluid sortable">

		<div class="box span12">

			<div class="box-header well" data-original-title>

				<h2><i class="icon-edit"></i> Edit Produk</h2>

				<div class="box-icon">

					<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>

					<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>

				</div>

			</div>

			<div class="box-content">

				<form class="form-horizontal" method=POST enctype=\'multipart/form-data\' action='.$aksi.'?module=produk&act=update>

					<fieldset>

						<input type=hidden name=id value='.$r[id_produk].'>

						<div class="control-group">

							<label class="control-label">Nama Produk</label>

							<div class="controls">

								<input type=text name="nama_produk" value="'.$r[nama_produk].'" />

							</div>

						</div>

						<div class="control-group">

							<label class="control-label">Kategori</label>

							<div class="controls">

								<select id="kategoriproduk" name="kategori">

								<form method=post>

';

									$tampil=mysql_query("SELECT * FROM kategoriproduk ORDER BY nama_kategori");

									if ($r[id_kategori]==0){

										echo "<option value=0 selected>- Pilih Kategori -</option>";

									}

									

									while($w=mysql_fetch_array($tampil)){

										if ($r[id_kategori]==$w[id_kategori]){

											echo "<option value=$w[id_kategori] selected>$w[nama_kategori]</option>";

										}

										else{

											echo "<option value=$w[id_kategori]>$w[nama_kategori]</option>";

										}

									}

echo '

								</select>

								</form>

							</div>

						</div>
						
						<div class="control-group">
							<label class="control-label">Sub Kategori</label>
							<div class="controls">
								<select id="subkategori" name="subkategori">

';
									$tampil=mysql_query("SELECT * FROM subkategori WHERE id_main=$r[id_kategori] ORDER BY nama_sub ");
									if ($r[id_subkategori]==0){
										echo "<option value=0 selected>- Pilih Sub Kategori -</option>";
									}

									while($w=mysql_fetch_array($tampil)){
										if ($r[id_subkategori]==$w[id_subkategori]){
											echo "<option value=$w[id_subkategori] selected>$w[nama_sub]</option>";
										}
										else{
											echo "<option value=$w[id_subkategori]>$w[nama_sub]</option>";
										}
									}
echo '
								</select>
							</div>
						</div>
						<div class="control-group">

						<div class="control-group">
							<label class="control-label">Sub Kategori 2</label>
							<div class="controls">
								<select id="subsubkategori" name="subsubkategori">

';
									$tampil=mysql_query("SELECT * FROM subsubkategori WHERE id_submain=$r[id_subkategori] ORDER BY nama_sub ");
									if ($r[id_subkategori]==0){
										echo "<option value=0 selected>- Pilih Sub Kategori 2 -</option>";
									}

									while($w=mysql_fetch_array($tampil)){
										if ($r[id_subsubkategori]==$w[id_subkategori]){
											echo "<option value=$w[id_subkategori] selected>$w[nama_sub]</option>";
										}
										else{
											echo "<option value=$w[id_subkategori]>$w[nama_sub]</option>";
										}
									}
echo '
								</select>
							</div>
						</div>
						<div class="control-group">

							<label class="control-label">Berat</label>
							<div class="controls">
								<input type=text name="berat" value="'.$r[berat].'" /> Kg

							</div>

						</div>

						<div class="control-group">

							<label class="control-label">Diskon</label>

							<div class="controls">

								<input type=text name="diskon" value="'.$r[diskon].'" />

							</div>

						</div>						

						<div class="control-group">

							<label class="control-label">Harga</label>

							<div class="controls">

								<input type=text name="harga" value="'.$r[harga].'" />

							</div>

						</div>
						<div class="control-group">

							<label class="control-label">Harga Tambahan (Untuk Penulisan Nomor Punggung dan Nama)</label>

							<div class="controls">

								<input type=text name="harga_tambahan" value="'.$r[harga_tambahan].'" />

							</div>

						</div>

						<div class="control-group">

							<label class="control-label">Muncul Di Home</label>

							<div class="controls">

';

						if ($r[status]=='Y'){

							echo '

								<label class="radio">

									<input type=radio name="status" value="Y" checked>Y

								</label>

								<div style="clear:both"></div>

								<label class="radio">

									<input type=radio name="status" value="N"> N

								</label>

							';

						}

						else{

							echo '

								<label class="radio">

									<input type=radio name="status" value="Y" > Y  

								</label>

								<div style="clear:both"></div>

								<label class="radio">

									<input type=radio name="status" value="N" checked> N

								</label>

							';

						}

echo '									

							</div>

						</div>

							

						<div class="control-group">

							<label class="control-label">Deskripsi</label>

							<div class="controls">

								<textarea id="loko" name=\'deskripsi\' style=\'width: 600px; height: 350px;\'>'.$r[deskripsi].'</textarea>

							</div>

						</div>

						<div class="control-group">
			              <label class="control-label">Ukuran dan Stok</label>
			                <div class="controls">
			                <div class="row-fluid">
			                    <ul class="thumbnails">';
							   $edituk = mysql_query("SELECT * FROM uk_produk WHERE id_produk='$r[id_produk]'");
							   while($ruk    = mysql_fetch_array($edituk)){
			                   $ukurr  = mysql_query("SELECT * FROM ukuran WHERE id_uk='$ruk[id_uk]'");
			                   $ukur   = mysql_fetch_array($ukurr);
								echo '
			                      <li class="" id="'.$ruk[id].'">
			                          <div class="well gallery-controls" style="padding: 5px;text-align: center;"><p>'.$ukur[ukuran].'</div>
			                          <div class="well gallery-controls" style="padding: 5px;text-align: center;"><p>'.$ruk[stok].'</div>
			                        <div class="well gallery-controls" style="padding: 5px;padding-bottom:0px;margin-top: -15px;text-align: center;">
			                        <p><a href="'.$aksi.'?module=produk&act=hapus_ukuran&id='.$r[id_produk].'&id_uk='.$ruk[id].'" class=" btn"><i class="icon-remove"></i></a>
			                        </p>
			                        </div>
			                      </li>
			                    ';
								}
							echo '
			                    </ul>
			                    <table id="upl">
			                      <tbody>
			                         <tr id="tambahUkuran" class="tambahuk count-huk0">
			                             </tr>
			                      </tbody>       
			                     </table>
			                       <br>
			                      <a class="btn" onclick="AddNewUkuran(this)" data-add="0">Tambah Ukuran/Stok</a>
			                  </div>
			                </div>
			              </div>

						<div class="control-group">

							<label class="control-label">Gambar</label>

							<div class="controls">

								<div class="alert alert-info">

									<button type="button" class="close" data-dismiss="alert">&times;</button>

									<strong>Warning !</strong> Apabila gambar tidak diubah, dikosongkan saja. <br>tipe gambar JPG/JPEG, Ukuran Min 400px X 400px

								</div>

								<div class="row-fluid">

';

	$qG = mysql_query("SELECT NamaGambar FROM imagesproduk WHERE idProduk='$r[id_produk]' ORDER BY idImages ASC");

	while($rG = mysql_fetch_array($qG)){

					echo '

						<div class="span3">

							<div class="fileupload fileupload-new" data-provides="fileupload">

								<div class="fileupload-new thumbnail" style="max-width: 180px;"><img src="../foto_produk/'.$rG[NamaGambar].'" style="height:auto;" /></div>

								<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>

								<div>

									<span class="btn btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" name=\'fupload[]\' /></span>

									<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>

								</div>

							</div>

						</div>

					';

	}

echo '

								<table id="upl">

										<tbody>

										   <tr>

											   <td><input name="fupload[]" type="file" size="60"></td>

										   </tr>

										</tbody>		   

								   </table>

								   <br>

									<input type="button" class="btn" value="Tambah File" id="tambah">

									<input type="button" class="btn" value="Hapus File" id="hapus">

								</div>

							</div>

						</div>

';

echo '	

						<div class="form-actions">

							<input type=submit value=Update class="btn btn-primary">

							<input type=button value=Batal onclick=self.history.back() class="btn">

						</div>

					</fieldset>

				</form>

			</div>

		</div>

	</div>	

';



break;  

}

}

?>

<script type="text/javascript">
	function del_uk(that){
	  var number = parseInt($(that).attr('data-count'));
      $('.count-huk'+number).remove();
    }
    function AddNewUkuran(that){
        //var dom = $(that).closest(".col-md-3").parent(".row");
        var dom = $('#tambahUkuran').parent();
        console.log(dom);
        var count = $("input[name='count_ukuran']");
        var jum = parseInt($(that).attr('data-add')) + 1;
        var prev_count = dom.find(".tambahuk").length;
        
        console.log(jum);

        html = '<tr class="tambahuk count-huk'+jum+'">'+
                '<td>'+
                    '<select name="ukuran[]">'+
                        '<option value="0">-- Pilih --</option>'+
                        <?php 
                            $ukuran=mysql_query("SELECT * FROM ukuran ORDER BY id_uk");
							while($uk=mysql_fetch_array($ukuran)){
                          ?>
                              '<option value="<?php echo $uk[id_uk] ?>"><?php echo $uk[ukuran] ?></option>'+
                          <?php 
                            }
                        ?>
                    '</select>'+
                '</td>'+
                '<td>'+
                	'<div class="controls" style="margin-left: 20px;">'+
						'<input type=text name="stok[]" placeholder="Stok"/>'+
					'</div>'+
                '</td>'+
                '<td><input type="button" class="btn" value="Hapus" data-count="'+jum+'" onclick="del_uk(this)"></td></tr>';
        dom.append(html);
        count.val(dom.find(".tambahuk").length);
        $(that).attr('data-add', jum);

    }
</script>