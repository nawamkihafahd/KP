<?php
session_start();
if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
  echo "<link href='style.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{
include "../../../config/koneksi.php";
include "../../../config/library.php";

$module=$_GET['module'];
$act=$_GET['act'];

// Hapus stok
if ($module=='chart' AND $act=='hapus'){
	
		 mysql_query("DELETE FROM stok WHERE id_stok='$_GET[id]'");
	 
		header('location:../../media.php?module='.$module);
  
}

// Update
	elseif ($module=='chart' AND $act=='update'){
		
		$dibeli = $_POST['dibeli'];
		$stok_skrg = $_POST['stok_skrg'];
		$id_produk = $_POST['id_produk'];
		$jml = count($_POST['id_produk']);
		
     	mysql_query("UPDATE orders SET status_order = '$_POST[status]'
                               WHERE kode_orders  = '$_POST[kode]'");

		/*if 	($_POST[status] == 'Konfirmasi') {
			mysql_query("UPDATE produk SET stok     = '$_POST[stok]',
									 WHERE id_produk = '$_POST[id]'");
					
			for($i=0;$i<$jml;$i++){ // ulang sebanyak id_produk
				mysql_query("UPDATE produk SET stok   = '$stok_skrg[$i]', dibeli = '$dibeli[$i]' WHERE id_produk = '$id_produk[$i]'");
			}
		}*/
	  header('location:../../media.php?module='.$module);
	  
	}

}
?>
