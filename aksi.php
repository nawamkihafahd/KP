<?php
session_start();
// error_reporting(0);
include "config/koneksi.php";
include "config/library.php";
include "config/fungsi_rupiah.php";
include "config/fungsi_antiinjeksi.php";
include "config/class_paging.php";

$module=$_GET['module'];
$act=$_GET['act'];

if ($module=='keranjang' AND $act=='tambah'){
	$sid 	= session_id();
	$sql2 	= mysql_query("SELECT stok,dibeli FROM produk WHERE id_produk='$_GET[id]'");
	$r 		= mysql_fetch_array($sql2);
	$stok 	= $r[stok];
	$dibeli = $r[dibeli]+1;
	$no 	= $_POST[no];
	$name 	= $_POST[name];
	$ukuran = $_POST[uk];
	$jumlah = $_POST[jml];
	$sql_uk = mysql_query("SELECT * FROM uk_produk WHERE id_produk='$_GET[id]' AND id_uk='$_POST[uk]'");
	$row_uk = mysql_fetch_array($sql_uk);

	if (!empty($jumlah)) {
		$jml = $jumlah;
	} else { $jml = '1'; }
  
  	// if ($row_uk[stok] == 0){
   //    	echo "stok habis";
  	// }
  	// elseif($row_uk[stok] < $jml){
  	// 	echo "stok sekarang sisa ".$row_uk[stok];
  	// }
  	// else{
		// check if the product is already
		// in cart table for this session
		$sqlprod = mysql_query("SELECT * FROM produk WHERE id_produk='$_GET[id]'");
		$prod = mysql_fetch_array($sqlprod);
		$kategori_id = $prod[id_kategori];
		$ketemu = 0;
		
		    $sql2 = mysql_query("SELECT id_produk FROM orders_temp WHERE id_produk='$_GET[id]' AND id_session='$sid' AND name='$name' AND no='$no' AND id_ukuran='$ukuran'");
		    $ketemu=mysql_num_rows($sql2);
		
		
		if ($ketemu==0){
			// put the product in cart table
			mysql_query("INSERT INTO orders_temp (id_produk, id_ukuran, jumlah, id_session, tgl_order_temp, jam_order_temp, stok_temp, no, name) VALUES ('$_GET[id]', $ukuran, '$jml', '$sid', '$tgl_sekarang', '$jam_sekarang', '$row_uk[stok]', '$no', '$name')");
				
			// update dibeli
			// mysql_query("UPDATE produk SET dibeli=$dibeli  WHERE id_produk='$_GET[id]'"); 
		} else {
			// update product quantity in cart table
			    mysql_query("UPDATE orders_temp 
		        	SET jumlah = jumlah + 1
					WHERE id_session ='$sid' AND id_produk='$_GET[id]' AND name='$_POST[name]' AND no='$_POST[no]' AND id_ukuran='$_POST[uk]'");
		}	
		deleteAbandonedCart();
		// header('location:keranjang-belanja.html');
		echo "<script> window.location = 'keranjang-belanja.html'</script>";
  	// }				
}

elseif ($module=='keranjang' AND $act=='hapus'){
	mysql_query("DELETE FROM orders_temp WHERE id_orders_temp='$_GET[id]'");
	// header('location:keranjang-belanja.html');
	echo "<script> window.location = 'keranjang-belanja.html'</script>";	
}

elseif ($module=='keranjang' AND $act=='update'){
  $id       = $_POST['id'];
  $jml_data = count($id);
  $jumlah   = $_POST['jml']; // quantity
  for ($i=1; $i <= $jml_data; $i++){
	$sql2 = mysql_query("SELECT stok_temp FROM orders_temp WHERE id_orders_temp='".$id[$i]."'");
	while($r=mysql_fetch_array($sql2)){
		if ($jumlah[$i] > $r[stok_temp]){
			echo "<script>window.alert('Jumlah yang dibeli melebihi stok yang ada');
			window.location=('keranjang-belanja.html')</script>";
		}
		elseif($jumlah[$i] == 0){
			echo "<script>window.alert('Anda tidak boleh menginputkan angka 0 atau mengkosongkannya!');
			window.location=('keranjang-belanja.html')</script>";
		}
		else{
		  mysql_query("UPDATE orders_temp SET jumlah = '".$jumlah[$i]."'
										  WHERE id_orders_temp = '".$id[$i]."'");
		 //  header('location:keranjang-belanja.html');
		echo "<script> window.location = 'keranjang-belanja.html'</script>";
		}
	}
  }
}

if ($module=='keranjang' AND $act=='checkout'){
	
	$curid = mysql_fetch_array(mysql_query("SELECT MAX(id_orders) AS latestid FROM orders"));
	
	$newid = $curid[latestid] + 1;
	
	$ran = rand(00,99);
	list($alamat_website) = mysql_fetch_array(mysql_query("SELECT alamat_website FROM identitas WHERE id_identitas='1'"));

	$kode_orders = $bln_sekarang . $tgl_skrg . $newid;
	$sid = $_POST['id_session'];   
	$sqlproduk = mysql_query("SELECT * FROM orders_temp WHERE id_session='$sid'");
	$qq	  = mysql_query("SELECT * FROM orders_temp WHERE id_session='$sid'");
 
  	$nama 		= $_POST['nama'];
  	$u          = $_POST['id_uk'];
  	$telp 		= $_POST['telp'];
  	$email 		= $_POST['email'];
  	$provinsi 	= $_POST['provinsi-tujuan'];
  	$kota 		= $_POST['kota-tujuan'];
  	$alamat 	= $_POST['alamat'];
  	$paket 		= $_POST['paket'];
  	$subtotal 	= $_POST['subtotal']; 
  	$ongkir 	= $_POST['ongkir']; 
  	$total 		= $_POST['total'];
  	$kurir      = $_POST['kurir'];
  	$namaprovinsi= $_POST['namaprovinsi'];
  	$namakota    = $_POST['kotanama'];
  	$namakecamatan = $_POST['namakecamatan'];

  	// put the product in cart table
  	mysql_query("INSERT INTO orders (kode_orders, status_order, tgl_order, jam_order, nama_pemesan, email, alamat, telp, jumlah_dibayar, kota, provinsi, kurir, paket, biaya_ongkir, namakota, namaprovinsi, namakecamatan)
        VALUES ('$kode_orders', 'Baru', '$tgl_sekarang', '$jam_sekarang', '$nama', '$email', '$alamat','$telp', '$total', 
          '$kota', '$provinsi','$kurir', '$paket', '$ongkir', '$namakota', '$namaprovinsi', '$namakecamatan')");
          
  	while ($rpro = mysql_fetch_array ($sqlproduk)) {
      
  		$sql2 	= mysql_query("SELECT stok,dibeli FROM produk WHERE id_produk='$rpro[id_produk]'");
  		$r 		= mysql_fetch_array($sql2);

  		$rqq 	= mysql_fetch_array($qq);

  		$sql 	= mysql_query("SELECT jumlah,id_ukuran FROM orders_temp
        			WHERE id_produk='$rpro[id_produk]' AND id_session='$sid'");
  		$rsql 	= mysql_fetch_array($sql);

  		$sql_uk = mysql_query("SELECT * FROM uk_produk WHERE id_produk='$rpro[id_produk]' AND id_uk='$rsql[id_ukuran]'");
		$row_uk = mysql_fetch_array($sql_uk);
  
  		$ukuran = $rsql['id_ukuran'];
  		$stok 	= $r['stok'];
  		$no 	= $rqq['no'];
  		$name 	= $rqq['name'];
  		$jml 	= $rsql['jumlah'];
  		$dibeli = $r['dibeli'] + $jml;
  		$stok_baru = $stok - $jml;
  		$stok_uk   = $row_uk['stok'] - $jml;

  		//if ($no != null) 
  		//{
  		mysql_query("INSERT INTO orders_detail (id_produk, id_ukuran, jumlah, id_kustomer, kode_orders, no, name)
          	VALUES ('$rpro[id_produk]', '$rpro[id_ukuran]', '$rpro[jumlah]', '$_POST[id_kustomer]', '$kode_orders', '$no', '$name')");	
  		//}
  		/*
  		else{
  		mysql_query("INSERT INTO orders_detail (id_produk, id_ukuran, jumlah, id_kustomer, kode_orders)
          	VALUES ('$rpro[id_produk]', '$rpro[id_ukuran]', '$rpro[jumlah]', '$_POST[id_kustomer]', '$kode_orders')");
  		}
  		*/
        if($r[stock] > 0){
   		mysql_query("UPDATE produk SET stok = '$stok_baru', dibeli = '$dibeli' WHERE id_produk='$rpro[id_produk]'");
        }
   		mysql_query("UPDATE uk_produk SET stok = '$stok_uk' WHERE WHERE id_produk='$rpro[id_produk]' AND id_uk='$rsql[id_ukuran]'");
  	}       
	
	$sqlorder=mysql_query("SELECT * FROM orders_detail, produk, ukuran 
					 WHERE orders_detail.id_produk=produk.id_produk
					 AND orders_detail.id_ukuran=ukuran.id_uk  
					 AND orders_detail.kode_orders='$kode_orders'");

$body_mail = '
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- So that mobile will display zoomed in -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- enable media queries for windows phone 8 -->
  <meta name="format-detection" content="telephone=no"> <!-- disable auto telephone linking in iOS -->
  <title>Single Column</title>
  
  <style type="text/css">
body {
  margin: 0;
  padding: 0;
  -ms-text-size-adjust: 100%;
  -webkit-text-size-adjust: 100%;
}

table {
  border-spacing: 0;
}

table td {
  border-collapse: collapse;
}

.ExternalClass {
  width: 100%;
}

.ExternalClass,
.ExternalClass p,
.ExternalClass span,
.ExternalClass font,
.ExternalClass td,
.ExternalClass div {
  line-height: 100%;
}

.ReadMsgBody {
  width: 100%;
  background-color: #ebebeb;
}

table {
  mso-table-lspace: 0pt;
  mso-table-rspace: 0pt;
}

img {
  -ms-interpolation-mode: bicubic;
}

.yshortcuts a {
  border-bottom: none !important;
}

@media screen and (max-width: 599px) {
  .force-row,
  .container {
    width: 100% !important;
    max-width: 100% !important;
  }
}
@media screen and (max-width: 400px) {
  .container-padding {
    padding-left: 12px !important;
    padding-right: 12px !important;
  }
}
.ios-footer a {
  color: #aaaaaa !important;
  text-decoration: underline;
}
</style>
</head>

<body style="margin:0; padding:0;" bgcolor="#F0F0F0" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<!-- 100% background wrapper (grey background) -->
<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#F0F0F0">
  <tr>
    <td align="center" valign="top" bgcolor="#F0F0F0" style="background-color: #F0F0F0;">

      <br>
      <!-- 600px container (white background) -->
      <table border="0" width="600" cellpadding="0" cellspacing="0" class="container" style="width:600px;max-width:600px">
        <tr>
          <td class="container-padding header" align="left" style="font-family:Helvetica, Arial, sans-serif;font-size:24px;font-weight:bold;padding-bottom:12px;color:#DF4726;padding-left:24px;padding-right:24px">
            &nbsp;
          </td>
        </tr>
        <tr>
          <td class="container-padding content" align="left" style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff">
            <br>

<div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">Pesanan Anda Dari Persela Store</div>
<br>

<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">';

  $body_mail .= "<h2>Berikut Detail Pesanan Anda </h2>";
           
  $body_mail .= "<h3>Kode Order $kode_orders </h3>";
  $body_mail .= "  
          <table border='0' width='400' class='container' style='width:400px;max-width:400px'>
            <thead>
              <tr>
                <th class='container-padding content'>Nama Produk</th>
                
                <th class='container-padding content'>Jumlah</th>
                <th class='container-padding content'>Ukuran</th>
                <th align=rightclass='container-padding content'>Harga Satuan</th>
                <th align=rightclass='container-padding content'>Sub Total</th>
              </tr>
            </thead>
            <tbody>";     
  $nn = 0;
  $totalharga = 0;
  while($s=mysql_fetch_array($sqlorder)){
    // rumus untuk menghitung subtotal dan total    
    if($s['diskon'] != 0){
      $disc   = ($s['diskon']/100)*$s['harga'];
      $harga  = $s['harga']-$disc; 
    }
    else{
      $harga  = $s['harga'];
    }
    if($s[name] || $s[no])
    {
        $harga += $s[harga_tambahan];
    }
    
    $subtotal    = $harga * $s['jumlah'];
    $totalharga     = $totalharga + $subtotal;
    $harga_rp = format_rupiah($s[harga]);
    $harga_tambahan = format_rupiah($s[harga_tambahan]);
    $subtotal_rp = format_rupiah($subtotal);    
    $total_rp    = format_rupiah($totalharga);
    
    $body_mail .='<tr>
          <td class="container-padding content">'.$s['nama_produk'].'<br>';
          if($s['name'])
          {
               $body_mail .='Nama: '.$s['name'].'<br>';
          }
          if($s['no'])
          {
               $body_mail .='No Punggung: '.$s['no'].'<br>';
          }
           $body_mail .='</td>
          <td align=center class="container-padding content">'.$s['jumlah'].'</td>
          <td align=center class="container-padding content">'.$s['ukuran'].'</td>
          <td align=right class="container-padding content">Rp. '.$harga_rp;
          if($s[name] || $s[no])
          {
              $body_mail.='<br>+Rp. '.$harga_tambahan.'<br>(Biaya Custom)';
          }
          $body_mail.='</td>
          <td align=right class="container-padding content">Rp. '.$subtotal_rp.'</td>
        </tr>'        
      ;
    $nn++;
  } 
  $body_mail .= '
            <tr>
              <td colspan=3 align=right>Ongkos Kirim : </td>
              <td colspan=2 align=right><b>Rp.'.format_rupiah($ongkir).'</b></td>
            </tr> 
            <tr>
              <td colspan=3 align=right>Total : </td>
              <td colspan=2 align=right><b>Rp'.format_rupiah($total).'</b></td>
            </tr>  
            </tbody>
          </table>';
		   
	$rekening = mysql_query("SELECT * FROM rekening");
	while ($rrek = mysql_fetch_array($rekening)) {
	$body_mail .= '
            <p> Bank '.$rrek['bank'].'</br>
              Nomer Rekening <b> '.$rrek['no_rekening'].' <b></br>
              atas nama '.$rrek['nama_pemilik'].'</br>
            </p>  
            <br>
		   ';	
	}
	
$body_mail .=' 
          <br> untuk konfirmasi pembayaran anda, silahkan klik link berikut : <br>
          <a href="'.$alamat_website.'/media.php?module=konfirmasipembayaran&kode='.$kode_orders.'" target="_blank"> Konfirmasi </a> 
</div>
          </td>
        </tr>
        <tr>
          <td class="container-padding footer-text" align="left" style="font-family:Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#aaaaaa;padding-left:24px;padding-right:24px">
            <br><br>
            <a href="'.$alamat_website.'" style="color:#aaaaaa" target="_blank">'.$alamat_website.'</a> © 2016 Persela Store.
            <br><br>

            <br>
            <br><br>

          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
';
deleteCart($sid);

date_default_timezone_set('Etc/UTC');

require "mail/PHPMailerAutoload.php";
//Create a new PHPMailer instance
$mail = new PHPMailer;

//Tell PHPMailer to use SMTP
$mail->isSMTP();
$mail->SMTPDebug = 0;
$mail->Debugoutput = 'html';
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->SMTPSecure = 'tls';
$mail->SMTPAuth = true;
$mail->Username = "diehardstorelamongan@gmail.com";
$mail->Password = "diehard12345";
$mail->setFrom("diehardstorelamongan@gmail.com", "Pesanan baru Persela Store");
$mail->addReplyTo("diehardstorelamongan@gmail.com", "Pesanan baru Persela Store");
$mail->addAddress("$email", "$email");
$mail->Subject = "Pesanan Persela Store";
//$mail->msgHTML("$html");
$mail->isHTML(true);
$mail->Body = $body_mail;

//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');
//send the message, check for errors
  if (!$mail->send()) {   
  	echo "sukses";  	
  	echo "Mailer Error: " . $mail->ErrorInfo;
  } 
  else {
    echo "<script>alert('Kami akan mengirmkan detailnya ke email anda.'); window.location='$alamat_website'</script>";
  }		
}


if ($module=='keranjang' AND $act=='konfirmasi'){
    
	list($alamat_website) = mysql_fetch_array(mysql_query("SELECT alamat_website FROM identitas WHERE id_identitas='1'"));
	
	$kode_orders = $_POST[kode_orders];
	$id_kustomer = $_POST[id_kustomer];
	$dari_bank = $_POST[dari_bank];
	$ke_bank = $_POST[ke_bank];
	$pengirim = $_POST[pengirim];
	$jumlah = $_POST[jumlah];
	
	$dupsql = mysql_query("SELECT * from konfirmasi WHERE kode_konfirmasi='$kode_orders' ");
	$dupnum = mysql_num_rows($dupsql);
	
	// put the product in cart table
	mysql_query("INSERT INTO konfirmasi (id_kustomer, kode_konfirmasi, dari_bank, ke_bank, pengirim, tgl, jam, jumlah, status)
				VALUES ('$id_kustomer','$kode_orders','$dari_bank','$ke_bank','$pengirim','$tgl_sekarang','$jam_sekarang','$jumlah','Baru Dibayar')");
				
	mysql_query("UPDATE orders SET status_order = 'Baru Dibayar' WHERE kode_orders ='$kode_orders'");		
			

	$sql2=mysql_query("SELECT * FROM orders_detail, produk 
                     WHERE orders_detail.id_produk=produk.id_produk 
                     AND orders_detail.kode_orders='$kode_orders'");
					 
	$rorder = mysql_fetch_array(mysql_query("SELECT email, nama_pemesan, alamat, jumlah_dibayar, biaya_ongkir, namakota, namaprovinsi, namakecamatan FROM orders
                     WHERE kode_orders='$kode_orders'"));
                     
					 			 
	$emailx = mysql_query("SELECT * FROM email WHERE id_email='1'");
	$remail = mysql_fetch_array($emailx);
	
	$qDataKonf = mysql_query("select * from konfirmasi where kode_konfirmasi = '$kode_orders'");
	$rDataKonf = mysql_fetch_array($qDataKonf);
					 
	$body_mail = "<h2>Detail Konfirmasi </h2>\r\n";
	$body_mail .= "$remail[header]\r\n";
					 
	$body_mail .= "<h3>Kode Order $kode_orders </h3>\r\n";
	$body_mail .= "	 
					<table class=\"table table-striped\">
						<thead>
							<tr>
								<th>Nama Produk</th>
								<th>Ukuran</th>
								<th>Jumlah</th>
								<th>Harga Satuan</th>
								<th>Sub Total</th>
							</tr>
						</thead>
						<tbody>";

			
	$n = 1;
	/*
	echo "Kode: ".$kode_orders."";
	echo "Objek: ".$rorder[nama_pemesan]."";
	$countorder= mysql_num_rows(mysql_query("SELECT email, nama_pemesan, alamat, jumlah_dibayar, biaya_ongkir, namakota, namaprovinsi FROM orders
                     WHERE kode_orders='$kode_orders'"));
    echo "Count: ".$countorder."";
    */
	while($s=mysql_fetch_array($sql2)){
		// rumus untuk menghitung subtotal dan total		
		// $disc        = ($s[diskon]/100)*$s[harga];
		// $hargadisc   = number_format(($s[harga]-$disc),0,",","."); 
		
		$harga = $s[harga];
	    $hargadisc = $s[harga] * (100 - $s[diskon])/100;
	    if($s[name] || $s[no])
	    {
	        $hargadisc += $s[harga_tambahan];
	    }
		$subtotal    = $hargadisc * $s[jumlah];
		$harga_rp    = format_rupiah($s[harga]);
		$total       = $total + $subtotal;
		$subtotal_rp = format_rupiah($subtotal);    
		$total_rp    = format_rupiah($total);
		$harga_tambahan = format_rupiah($s[harga_tambahan]);
		
		$ukuranbarangsql =  mysql_query("SELECT * from ukuran WHERE id_uk = $s[id_ukuran]");
		$ukuranbarang = mysql_fetch_array($ukuranbarangsql);
		
		$stoksql = mysql_query("SELECT * from uk_produk WHERE id_uk = $s[id_ukuran] AND id_produk = $s[id_produk] ");
		$stoknum = mysql_num_rows($stoksql);
		
		
		//echo "<script>alert('".$dupnum."')</script>";
		if($stoknum > 0 && $dupnum == 0)
		{
		    $stokobj = mysql_fetch_array($stoksql);
		    $oldstok = $stokobj[stok];
		    $newstok = $oldstok - $s[jumlah];
		
		    $updatestoksql = mysql_query("UPDATE uk_produk SET stok = '$newstok' WHERE id_uk = $s[id_ukuran] AND id_produk = $s[id_produk] ");
		    
		}
		

		// $subtotalberat = $s[berat] * $s[jumlah]; // total berat per item produk 
		// $totalberat  = $totalberat + $subtotalberat; // grand total berat all produk yang dibeli
		
		$body_mail .="
				<tr>
				    <td>".$s['nama_produk']."<br>";
                    if($s['name'])
                    {
                        $body_mail .="Nama: ".$s['name']."<br>";
                    }
                    if($s['no'])
                    {
                        $body_mail .="No Punggung: ".$s['no']."<br>";
                    }
                    $body_mail .="</td>
                    <td>$ukuranbarang[ukuran]</td>
					<td align=center>$s[jumlah]</td>
					<td align=right>Rp. $harga_rp";
					if($s[name] || $s[no])
					{
					    $body_mail .="<br>+Rp. $harga_tambahan<br>(Biaya Custom)</td>";
					}
					$body_mail .="<td align=right>Rp. $subtotal_rp</td>
				</tr>
			";
		$n++;
	}
	$ongkoskirim = $rorder[biaya_ongkir];
	$grandtotal    = $total + $ongkoskirim; 
	
	$ongkoskirim_rp = format_rupiah($ongkoskirim);
	$ongkoskirim1_rp = format_rupiah($ongkoskirim1); 
	$grandtotal_rp  = format_rupiah($grandtotal);	
	
	$body_mail .= "			
						<tr>
							<td colspan=4 align=right>Total : </td>
							<td align=right><b>Rp. $total_rp</b></td>
						</tr> 
						<tr>
							<td colspan=4 align=right>Ongkos Kirim : </td>
							<td align=right><b>Rp. $ongkoskirim_rp</b></td>
						</tr><tr>
							<td colspan=4 align=right>Grand Total : </td>
							<td align=right><b>Rp. $grandtotal_rp</b></td>
						</tr>					

						</tbody>
					</table>
	
				\r\n";
	
	$body_mail .= "<table class=\"table table-striped\">
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
							<td>Nominal</td>
							<td> : $rDataKonf[jumlah]</td>
						</tr>
						<tr>
							<td>Tanggal Transfer</td>
							<td> : $rDataKonf[tgl]</td>
						</tr>
						<tr>
							<td>Nama Pemesan</td>
							<td> : $rorder[nama_pemesan]</td>
						</tr>
						<tr>
							<td>Alamat Pengiriman</td>
							<td> : $rorder[alamat], $rorder[namakecamatan], $rorder[namakota], $rorder[namaprovinsi]</td>
						</tr>
					</table>\r\n";
	$body_mail .= '<br> untuk melihat status pesanan anda, silahkan klik link berikut : <br>
          <a href="'.$alamat_website.'/media.php?module=tracking&kode='.$kode_orders.'" target="_blank"> Status Pesanan </a> ';
	$body_mail .= "$remail[footer]\r\n";
// 	$headers = "From: $remail[email]\r\n";
// 	$headers .= "Reply-to: $remail[email]\r\n";
// 	$headers .= "MIME-Version: 1.0\r\n";
// 	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
	// $mail_sent = @mail($rorder[email], "Konfirmasi dari Alazazi.co.id", $body_mail, $headers);
	// kirim email ke admin
// 	$mail_sent = @mail($remail[email], "Konfirmasi pembayaran", $body_mail, $headers);
	// $mail_sent = @mail("andhika.tri@gmail.com", "Konfirmasi", $body_mail, $headers);
	
// 	echo $mail_sent ? "<script>alert('Terimakasih, kami akan segera memprosesnya'); window.location = './'</script>" : "Gagal";	
	
    date_default_timezone_set('Etc/UTC');
    
    require "mail/PHPMailerAutoload.php";
    //Create a new PHPMailer instance
    $mail = new PHPMailer;
    
    //Tell PHPMailer to use SMTP
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->Debugoutput = 'html';
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = "diehardstorelamongan@gmail.com";
    $mail->Password = "diehard12345";
    $mail->setFrom($remail[email], "Konfirmasi Pembayaran");
    $mail->addReplyTo($remail[email], "Konfirmasi Pembayaran");
    $mail->addAddress($rorder[email], "Persela Store");
    $mail->Subject = "Konfirmasi Pembayaran di Persela Store";
    //$mail->msgHTML("$html");
    $mail->isHTML(true);
    $mail->Body = $body_mail;
    
    //Attach an image file
    //$mail->addAttachment('images/phpmailer_mini.png');
    //send the message, check for errors
      if (!$mail->send()) {   
      	echo "sukses";  	
      	echo "Mailer Error: " . $mail->ErrorInfo;
      } 
      else {
        echo "<script>alert('Terimakasih, kami akan segera memprosesnya.'); window.location='$alamat_website'</script>";
      }		
  
			
}

if ($module == 'sendemail') {
	$emailx = mysql_query("SELECT * FROM email WHERE id_email='1'");
	$remail = mysql_fetch_array($emailx);
	
	$body_mail = "<h2>Detail Konfirmasi </h2>\r\n";
	$body_mail .= "$remail[header]\r\n";
					 
	$body_mail .= "<h3>Kode Order </h3>\r\n";
	
    date_default_timezone_set('Etc/UTC');
    
    require "mail/PHPMailerAutoload.php";
	//Create a new PHPMailer instance
    $mail = new PHPMailer;
    
    //Tell PHPMailer to use SMTP
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->Debugoutput = 'html';
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = "diehardstorelamongan@gmail.com";
    $mail->Password = "diehard12345";
    $mail->setFrom("diehardstorelamongan@gmail.com", "Pesanan baru Persela Store");
    $mail->addReplyTo("diehardstorelamongan@gmail.com", "Pesanan baru Persela Store");
    $mail->addAddress("andhika.tri@gmail.com", "andhika tri");
    $mail->Subject = "Pesanan Persela Store";
    //$mail->msgHTML("$html");
    $mail->isHTML(true);
    $mail->Body = $body_mail;
    
    //Attach an image file
    //$mail->addAttachment('images/phpmailer_mini.png');
    //send the message, check for errors
      if (!$mail->send()) {   
      	echo "sukses";  	
      	echo "Mailer Error: " . $mail->ErrorInfo;
      } 
      else {
        	echo "sukses vroh";  	
      }	
  
}

if($module == 'aksisorting'){
	$order  = $_POST[order];
	$id  = $_POST[id];
	$where  = $_POST[where];
		
	$p      = new PagingsubKateProduk;
	$batas  = 20;
	$posisi = $p->cariPosisi($batas);
	
	$subproduk = mysql_query("SELECT * FROM produk where $where='$id' ORDER BY $order LIMIT $posisi,$batas");
	while($r = mysql_fetch_array($subproduk)){
			
		$gbrproduk  = mysql_query("SELECT * FROM imagesproduk WHERE idProduk = '$r[id_produk]'");
		$gbr = 	mysql_fetch_array($gbrproduk);
		
		$harga     = format_rupiah($r[harga]);
		$disc      = ($r[diskon]/100)*$r[harga];
		$hargadisc = number_format(($r[harga]-$disc),0,",",".");
		if ($r[diskon] != 0) {
			$rego = "<h5><s style='color:red;'>Rp. $harga</s> Rp. $hargadisc</h5>";
		} else {
			$rego = "<h5>Rp. $harga</h5>";	
		}
		
		echo '
		<div class="four columns">
			<div class="detailimg">
				<div class="bordered">
					<figure class="add-border">
						<a class="single-image" href="'.$r[url].'"><img src="foto_produk/'.$gbr[NamaGambar].'" alt="'.$r[nama_produk].'" title="'.$r[nama_produk].'" /></a>
					</figure>
				</div><!--/ .bordered-->
				
					<h5>'.$r[nama_produk].'</h5>
					'.$rego.'
				
					<a href="produk-'.$r[id_produk].'-'.$r[produk_seo].'.html" class="button default">Detail</a>
					<a href="aksi.php?module=keranjang&act=tambah&id='.$r[id_produk].'" class="button default"> Beli </a>
			</div>
		</div>	
		';
	}
	  $jmldata     = mysql_num_rows(mysql_query("SELECT * FROM produk WHERE id_subkategori ='$id'"));
	  $jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
	  $linkHalaman = $p->navHalaman($_GET[halsubkategoriproduk], $jmlhalaman);
		if ($batas < $jmldata) {
			echo '<div class="wp-pagenavi" style="text-align:center;">
				  '.$linkHalaman.'	
				  </div>';
		}
	echo'
		</section>
		<div class="clear"></div>
	</section>';
}



/*
	Delete all cart entries older than one day
*/
function deleteAbandonedCart(){
	$kemarin = date('Y-m-d', mktime(0,0,0, date('m'), date('d') - 1, date('Y')));
	mysql_query("DELETE FROM orders_temp WHERE tgl_order_temp < '$kemarin'");
}
function deleteCart($sid){
	mysql_query("DELETE FROM orders_temp WHERE id_session = '$sid'");
}
?>