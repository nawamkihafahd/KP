<?php
session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
  echo "<link href='style.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{
$aksi="modul/mod_konfirmasi/aksi_konfirmasi.php";
switch($_GET[act]){
  // Tampil Konfirmasi
  default:
    echo '
		<div>
			<ul class="breadcrumb">
				<li>
					<a href="?module=home">Home</a> <span class="divider">/</span>
				</li>
				<li>
					<a href="#">Konfirmasi</a>
				</li>
			</ul>
		</div>
		<div class="row-fluid sortable">
			<div class="box span12">
				<div class="box-header well" data-original-title>
					<h2><i class="icon-picture"></i> Konfirmasi</h2>
					<div class="box-icon">
						<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
						<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
					</div>
				</div>
				<div class="box-content">
				<!--
					<input type=button value=\'Tambah Konfirmasi\' class="btn btn-primary" 
					onclick="window.location.href=\'?module=konfirmasi&act=tambahkonfirmasi\';"><br /><br />
				-->
					<table class="table table-striped table-bordered bootstrap-datatable datatable">
						<thead>
							<tr>
								<th>No</th>
								<th>Tanggal </th>
								<th>Kode Order</th>
								<th>Nama</th>
								<th>Total </th>
								<th>Keterangan </th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
	';
							$tampil = mysql_query("select * from orders o LEFT JOIN konfirmasi k ON 'o.kode_orders' = 'k.kode_konfirmasi' 
								WHERE status_order != 'Baru' ORDER BY id_orders DESC");
							$no = 1;
							while($r=mysql_fetch_array($tampil)){
								$tgl = tgl_indo($r[tgl_order]);
								if ($r[oid] == $r[kid]) {
									$namakustomer = $r[nama];
								} else{
									$namakustomer = 'Guest';
								}
								
								$dibayar = format_rupiah($r[jumlah_dibayar]);
								echo "
								<tr>
									<td>$no</td>
									<td>$tgl</td>
									<td>$r[kode_orders]</td>
									<td>$r[nama_pemesan]</td>
									<td>Rp. $dibayar</td>
									<td>$r[status_order]</td>
									<td>
										<a class=\"btn btn-success\" href=?module=konfirmasi&act=detailkonfirmasi&id=$r[id_orders]&kode=$r[kode_orders]><i class=\"icon-zoom-in icon-white\"></i> Detail</a>
									<!--
										<a class=\"btn btn-info\" href=?module=konfirmasi&act=editkonfirmasi&konfirmasi=$r[konfirmasi]&id=$r[id_orders]><i class=\"icon-edit icon-white\"></i> Edit</a>	<a class=\"btn btn-danger\" href='$aksi?module=konfirmasi&act=hapus&id=$r[id_orders]&namafile=$r[gbr_konfirmasi]'><i class=\"icon-trash icon-white\"></i> Hapus</a>
										-->
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
 
  case "detailkonfirmasi":
    // $edit = mysql_query("SELECT * FROM orders, kustomer WHERE orders.id_kustomer=kustomer.id_kustomer AND id_orders='$_GET[id]'");
    $edit = mysql_query("SELECT * FROM orders WHERE id_orders='$_GET[id]'");
    $jumlah   = mysql_num_rows($edit);
	
	// if ($jumlah < 1) {
		// $edit = mysql_query("SELECT * FROM orders WHERE id_orders='$_GET[id]'");
		// $r    = mysql_fetch_array($edit);		
	// }else {
		$r    = mysql_fetch_array($edit);
	// }
    $tanggal=tgl_indo($r[tgl_order]);
    
	// $qDataKonf = mysql_query("select * from konfirmasi where id_order='$r[id_orders]'");
	$qDataKonf = mysql_query("select * from konfirmasi where kode_konfirmasi = '$_GET[kode]'");
	$rDataKonf = mysql_fetch_array($qDataKonf);
		
    if ($r[status_order]=='Baru'){
        $pilihan_status = array('Baru', 'Pengiriman', 'Selesai');
    }
    elseif ($r[status_order]=='Pengiriman'){
        $pilihan_status = array('Pengiriman', 'Batal', 'Selesai');    
    }
    else{
        $pilihan_status = array('Selesai', 'Pengiriman', 'Batal');    
    }

    $pilihan_order = '';
    foreach ($pilihan_status as $status) {
	   $pilihan_order .= "<option value=$status";
	   if ($status == $r[status_order]) {
		    $pilihan_order .= " selected";
	   }
	   $pilihan_order .= ">$status</option>\r\n";
    }

    echo '
		<div>
			<ul class="breadcrumb">
				<li>
					<a href="?module=home">Home</a> <span class="divider">/</span>
				</li>
				<li>
					<a href="?module=konfirmasi">Konfirmasi</a> <span class="divider">/</span>
				</li>
				<li>
					<a href="#">Detail Order</a>
				</li>
			</ul>
		</div>
		<div class="row-fluid sortable">
			<div class="box span12">
				<div class="box-header well" data-original-title>
					<h2><i class="icon-shopping-cart"></i> Detail Order</h2>
					<div class="box-icon">
						<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
						<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
					</div>
				</div>
				<div class="box-content">
					<form class="form-horizontal" method=POST action='.$aksi.'?module=konfirmasi&act=update>
						<fieldset>						
							<input type=hidden name="kode" value="'.$_GET['kode'].'">
							<table class="table">
								<tr>
									<td>No. Order</td>        
									<td>'.$r[id_orders].'</td>
								</tr>
								<tr>
									<td>Kode Order</td>        
									<td>'.$r[kode_orders].'</td>
								</tr>
								<tr>
									<td>Tgl. & Jam Order</td> 
									<td> '.$tanggal.' & '.$r[jam_order].'</td>
								</tr>
								<tr>
									<td>Status Order</td> 
									<td> '.$r[status_order].'
									</td>
								</tr>
							</table>
						</fieldset>
					</form>
	';
					// tampilkan rincian produk yang di order
					 $sql2=mysql_query("SELECT * FROM orders_detail, produk 
                     WHERE orders_detail.id_produk=produk.id_produk 
                     AND orders_detail.kode_orders='$_GET[kode]'");
                     echo $_GET['kode'];
					 
	echo "			<table class=\"table table-striped\">
						<thead>
							<tr>
								<th>Produk</th>
								<th>Ukuran</th>
								<th>Jumlah</th>
								<th>Harga Satuan</th>
								<th>Sub Total</th>
							</tr>
						</thead>
						<tbody>
	";
						while($s=mysql_fetch_array($sql2)){
							// rumus untuk menghitung subtotal dan total		
							$disc        = ($s[diskon]/100)*$s[harga];
							$hargadisc   = $s[harga]-$disc; 
							if ($s[diskon] == 0){
							  $harga       = $s[harga];
							} else{
							  $harga       = $hargadisc;
							}
							if($s[name] || $s[no])
							{
							    $harga += $s[harga_tambahan];
							}
							$subtotal    = $harga * $s[jumlah];
							$harga_rp    = format_rupiah($s[harga]);
							$harga_tambahan = format_rupiah($s[harga_tambahan]);
							$total       = $total + $subtotal;
							$subtotal_rp = format_rupiah($subtotal);    
							$total_rp    = format_rupiah($total);
							$beratawal = $s['berat'] * $s['jumlah'];
							$berat   = $berat + $beratawal;
							$u           = $s[id_ukuran];
            				$ukrn        = mysql_query("SELECT * FROM ukuran WHERE id_uk='$u'");
					        $uu          = mysql_fetch_array($ukrn);

							echo "
								<tr>
									<td>$s[nama_produk]";
								if($s[name])
								{
								    echo"<br>Nama: ".$s[name];
								}
								if($s[no])
								{
								    echo"<br>Nomor Punggung: ".$s[no];
								}
							echo"</td>
							        <td>$uu[ukuran]</td>
									<td align=center>$s[jumlah]</td>
									<td align=right>Rp. $harga_rp";
							if($s[name] || $s[no])
							{
							    echo"<br>+Rp. ".$s[harga_tambahan]." (Biaya Custom)";
							}
							echo"</td>
									<td align=right>Rp. $subtotal_rp</td>
								</tr>
							";  
						}
						$beratview = $berat;
						$berat = ceil($berat);
						
						$ongkoskirim = $r['biaya_ongkir'];
						$grandtotal    = $total + $ongkoskirim; 
						
						$ongkoskirim_rp = format_rupiah($ongkoskirim);
						$grandtotal_rp  = format_rupiah($grandtotal);
						
	echo "				<tr>
							<td colspan=4 align=right>Total : </td>
							<td align=right><b>Rp. $total_rp</b></td>
						</tr> <tr>
							<td colspan=4 align=right>Berat : </td>
							<td align=right><b> $beratview Kg</b></td>
						</tr> <tr>
							<td colspan=4 align=right>Kurir : </td>
							<td align=right><b> $r[kurir]</b></td>
						</tr> 
						<tr>
							<td colspan=4 align=right>Ongkos Kirim : </td>
							<td align=right><b>Rp. $ongkoskirim_rp</b></td>
						</tr>
						<tr>
							<td colspan=4 align=right>Grand Total : </td>
							<td align=right><b>Rp. $grandtotal_rp</b></td>
						</tr>					
	";
	echo '
						</tbody>
					</table>
	';
		
	echo "			<table class=\"table table-striped\">
						<tr>
							<th colspan=2>Data Pemesan</th>
						</tr>
						<tr>
							<td>Nama Pemesan</td>
							<td> : $r[nama_pemesan]</td>
						</tr>
						<tr>
							<td>Alamat Pengiriman</td>
							<td> : $r[alamat], $r[namakecamatan], $r[namakota], $r[namaprovinsi]</td>
						</tr>
						<tr>
							<td>No. Telpon/HP</td>
							<td> : $r[telp]</td>
						</tr>
						<tr>
							<td>Email</td>
							<td> : $r[email]</td>
						</tr>
					</table>
	";
	$nominal = format_rupiah($rDataKonf['jumlah']);
	echo "			<table class=\"table table-striped\">
						<tr>
							<th colspan=2>Data Konfirmasi</th>
						</tr>
						<tr>
							<td>Nama Pemilik Rekening</td>
							<td> : $rDataKonf[pengirim]</td>
						</tr>
						<tr>
							<td>Transfer Ke Bank</td>
							<td> : $rDataKonf[ke_bank]</td>
						</tr>
						<tr>
							<td>Jumlah yang dibayar</td>
							<td> : Rp. $nominal</td>
						</tr>
						<tr>
							<td>Tanggal Konfirmasi</td>
							<td> : $rDataKonf[tgl]</td>
						</tr>
					</table>
	";
	echo '
				</div>
			</div>
		</div>
	';

    break;  
	}
}
?>