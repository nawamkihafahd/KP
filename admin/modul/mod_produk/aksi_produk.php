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

include "../../../config/fungsi_thumb.php";

include "../../../config/fungsi_seo.php";



$module=$_GET['module'];

$act=$_GET['act'];



// Hapus produk

if ($module=='produk' AND $act=='hapus'){

	$id		= filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

	$data 	= mysql_query("SELECT * FROM imagesproduk WHERE idProduk='$id'");

	while($rdata	= mysql_fetch_array($data)){

		@unlink("../../../foto_produk/$rdata[NamaGambar]"); 

		mysql_query("DELETE FROM imagesproduk WHERE idImages='$rdata[idImages]'");

	}

	mysql_query("DELETE FROM produk WHERE id_produk='$id'");

	header('location:../../media.php?module='.$module.'&notifdel=sukses');

}

// Hapus ukuran

if ($module=='produk' AND $act=='hapus_ukuran'){

	$id		= filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

	$id_uk		= filter_var($_GET['id_uk'], FILTER_SANITIZE_NUMBER_INT);

	$qdata 	= mysql_query("SELECT * FROM uk_produk WHERE id_produk='$id'");

	while($rqdata	= mysql_fetch_array($qdata)){ 

		mysql_query("DELETE FROM uk_produk WHERE id=$id_uk");

	}

	header('location:../../media.php?module='.$module.'&act=editproduk&id='.$id);

}

// Input produk

elseif ($module=='produk' AND $act=='input'){

	$temp    = $_FILES['fupload']['tmp_name'];

	$type      = $_FILES['fupload']['type'];

	$file      = $_FILES['fupload']['name'];

	$size 	= $_FILES['fupload']['size'];

	$maxsize = 3000000; 
	

	$produk_seo     = seo_title(str_replace("'","''",strip_tags($_POST['nama_produk'])));

	$nama_produk	= str_replace("'","''",strip_tags($_POST['nama_produk']));

	$id_kategori	= filter_var($_POST['kategori'], FILTER_SANITIZE_NUMBER_INT);

	$id_subkategori	= filter_var($_POST['subkategori'], FILTER_SANITIZE_NUMBER_INT);
	$id_subsubkategori	= filter_var($_POST['subsubkategori'], FILTER_SANITIZE_NUMBER_INT);

	$berat			= $_POST['berat'];

	$harga			= filter_var($_POST['harga'], FILTER_SANITIZE_NUMBER_INT);
	
	$harga_tambahan			= filter_var($_POST['harga_tambahan'], FILTER_SANITIZE_NUMBER_INT);

	$diskon			= filter_var($_POST['diskon'], FILTER_SANITIZE_NUMBER_INT);

	$deskripsi		= str_replace("'","''",$_POST['deskripsi']);

	$tgl_masuk		= $tgl_sekarang;

	$status			= str_replace("'","''",strip_tags($_POST['status']));

	

	if(!empty($nama_produk) AND !empty($harga)){

		mysql_query("INSERT INTO produk(id_kategori,id_subkategori,id_subsubkategori,nama_produk,produk_seo,deskripsi,harga,harga_tambahan,berat,tgl_masuk,diskon,status) 

		VALUES('$id_kategori','$id_subkategori','$id_subsubkategori','$nama_produk','$produk_seo','$deskripsi','$harga','$harga_tambahan','$berat','$tgl_masuk','$diskon','$status')");

		$id_produk = mysql_insert_id();		

		$mimetype = array('image/jpeg','image/gif','image/x-png','image/png');

		$path = '../../../foto_produk/';

		

		for($i=0;$i<count($temp);$i++){ // ulang sebanyak temp

			$acak     = rand(1,99);

			$namafile[$i]      = seo_gambar($file[$i]);

			$nama_file_unik[$i] = $acak.$namafile[$i];

			if(!empty($temp[$i])){ // jika tedapat file yg diupload

				if($size[$i] <= $maxsize){ // cek besar ukuran yg diupload

					if(in_array($type[$i],$mimetype,TRUE)){ // cocokkan dengan tipe mime yang telah di tentukan

						if(@move_uploaded_file($temp[$i],$path.$nama_file_unik[$i])){

						

	//identitas file asli

  // if($type != "image/jpeg"){

    // $im_src = imagecreatefrompng($path.$nama_file_unik[$i]);

  // }else{

    $im_src = imagecreatefromjpeg($path.$nama_file_unik[$i]);

  // }

  $src_width = imageSX($im_src);

  $src_height = imageSY($im_src);



  //Simpan dalam versi small 90 pixel

  //Set ukuran gambar hasil perubahan

  $dst_width = 212;

  $dst_height = ($dst_width/$src_width)*$src_height;



  //proses perubahan ukuran

  $im = imagecreatetruecolor($dst_width,$dst_height);

  imagecopyresampled($im, $im_src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);



  //Simpan gambar

  // if($type != "image/jpeg"){

  // imagepng($im,$path . "small_" . $nama_file_unik[$i]);

  // }else{

  imagejpeg($im,$path . "small_" . $nama_file_unik[$i]);

  // }	

							//query insert ke tabel gambar

							mysql_query("INSERT INTO imagesproduk (idProduk,NamaGambar) VALUES('$id_produk','$nama_file_unik[$i]')");

							

						}else{

							echo "<font color=\"red\">File ".$file[$i]." tidak bisa diupload.</font><br />";

						}

					}else{

						echo "<font color=\"red\">Files yang anda upload (<b>".$file[$i]."</b>) tidak sesuai dengan format yg ditentukan, yang diijinkan (.jpg,.gif,.png)</font><br />";

					}

				}else{

					echo "<font color=\"red\">Terdapat file yg lebih besar dari ".($maxsize/1024)." Kb.</font><br />";

				}

			}else{

				continue; // Lanjutkan ketika daa masih kosong.

			}

		}

		$uk = $_POST['ukuran'];
		$stok = $_POST['stok'];

		for($i=0;$i<count($uk);$i++){ // ulang sebanyak ukuran

				//query insert ke tabel gambar

				mysql_query("INSERT INTO uk_produk (id_produk,id_uk,stok) VALUES('$id_produk','$uk[$i]','$stok[$i]')");

		}


		header('location:../../media.php?module='.$module);

	}

	else{

		echo "<script>window.alert('Nama Produk dan Harga tidak boleh kosong');

			window.location=('../../media.php?module=produk&act=tambahproduk')</script>";

	}

}

// Update produk

elseif ($module=='produk' AND $act=='update'){

	$temp		= $_FILES['fupload']['tmp_name'];

	$type     = $_FILES['fupload']['type'];

	$file     = $_FILES['fupload']['name'];

	$size 	= $_FILES['fupload']['size'];

	$maxsize = 5000000; 

  

	$produk_seo		= seo_title(str_replace("'","''",strip_tags($_POST['nama_produk'])));

	$id_kategori	= filter_var($_POST['kategori'], FILTER_SANITIZE_NUMBER_INT);

	$nama_produk	= str_replace("'","''",strip_tags($_POST['nama_produk']));

	$id_subkategori	= filter_var($_POST['subkategori'], FILTER_SANITIZE_NUMBER_INT);
	$id_subsubkategori	= filter_var($_POST['subsubkategori'], FILTER_SANITIZE_NUMBER_INT);

	$berat			= $_POST['berat'];

	$harga			= filter_var($_POST['harga'], FILTER_SANITIZE_NUMBER_INT);
	
	$harga_tambahan	= filter_var($_POST['harga_tambahan'], FILTER_SANITIZE_NUMBER_INT);

	$diskon			= filter_var($_POST['diskon'], FILTER_SANITIZE_NUMBER_INT);

	$deskripsi		= str_replace("'","''",$_POST['deskripsi']);

	$status			= str_replace("'","''",strip_tags($_POST['status']));

	$id_produk		= filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);



	if(!empty($nama_produk) AND !empty($harga)){

		mysql_query("UPDATE produk SET nama_produk 	= '$nama_produk',

                                   produk_seo  		= '$produk_seo', 

                                   id_kategori 		= '$id_kategori',

                                   id_subkategori 	= '$id_subkategori',
                                   id_subsubkategori 	= '$id_subsubkategori',

                                   berat       		= '$berat',

                                   harga       		= '$harga',
                                   
                                   harga_tambahan   = '$harga_tambahan',

                                   diskon      		= '$diskon',

                                   deskripsi   		= '$deskripsi',

                                   status      		= '$status'

                             WHERE id_produk   		= '$id_produk'");

		

		$qGLam = mysql_query("SELECT * FROM imagesproduk WHERE idProduk='$_POST[id]' ORDER BY idProduk ASC");

		$j = 0;

		while($rGLam = mysql_fetch_array($qGLam)){

			$idGbr[$j] 		= $rGLam['idImages'];

			$namaGbr[$j]	= $rGLam['NamaGambar'];

			$j++;

		}

		$mimetype = array('image/jpeg','image/gif','image/png');

		$path = '../../../foto_produk/';

		for($i=0;$i<count($temp);$i++){ // ulang sebanyak temp

			$acak     			= rand(1,99);

			$namafile[$i]      = seo_gambar($file[$i]);

			$nama_file_unik[$i] = $acak.$namafile[$i];

			if(!empty($temp[$i])){ // jika tedapat file yg diupload

				if($size[$i] <= $maxsize){ // cek besar ukuran yg diupload

					if(in_array($type[$i],$mimetype,TRUE)){ // cocokkan dengan tipe mime yang telah di tentukan

						if(@move_uploaded_file($temp[$i],$path.$nama_file_unik[$i])){

							//echo "<font color=\"green\">File ".$file[$i]." sukses diupload.</font><br />";

	//identitas file asli

  // if($type != "image/jpeg"){

    // $im_src = imagecreatefrompng($path.$nama_file_unik[$i]);

  // }else{

    $im_src = imagecreatefromjpeg($path.$nama_file_unik[$i]);

  // }

  $src_width = imageSX($im_src);

  $src_height = imageSY($im_src);



  //Simpan dalam versi small 90 pixel

  //Set ukuran gambar hasil perubahan

  $dst_width = 212;

  $dst_height = ($dst_width/$src_width)*$src_height;



  //proses perubahan ukuran

  $im = imagecreatetruecolor($dst_width,$dst_height);

  imagecopyresampled($im, $im_src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);



  //Simpan gambar

  // if($type != "image/jpeg"){

  // imagepng($im,$path . "small_" . $nama_file_unik[$i]);

  // }else{

  imagejpeg($im,$path . "small_" . $nama_file_unik[$i]);

  // }	

  

							if(!empty($namaGbr[$i])){

								//menghapus file lama

								unlink($path.$namaGbr[$i]);

								unlink($path."small_".$namaGbr[$i]);

 

								//query update tabel gambar

								mysql_query("UPDATE imagesproduk SET NamaGambar='$nama_file_unik[$i]' WHERE idImages='$idGbr[$i]'");

							}

							else{

	

								//query insert ke tabel gambar

								mysql_query("INSERT INTO imagesproduk (idProduk,NamaGambar) VALUES('$id_produk','$nama_file_unik[$i]')");

							}

							

						}else{

							echo "<font color=\"red\">File ".$file[$i]." tidak bisa diupload.</font><br />";

						}

					}else{

						echo "<font color=\"red\">Files yang anda upload (<b>".$file[$i]."</b>) tidak sesuai dengan format yg ditentukan, yang diijinkan (.jpg,.gif,.png,.doc,.excel)</font><br />";

					}

				}else{

					echo "<font color=\"red\">Terdapat file yg lebih besar dari ".($maxsize/1024)." Kb.</font><br />";

				}

			}else{

				continue; // Lanjutkan ketika daa masih kosong.

			}

		}

		$uk = $_POST['ukuran'];
		$stok = $_POST['stok'];

		for($i=0;$i<count($uk);$i++){ // ulang sebanyak ukuran

				//query insert ke tabel gambar
				if($uk[$i] > 0)
		        {
                    $hasduplicatesql = mysql_query("SELECT * FROM uk_produk WHERE id_produk = '$id_produk' AND id_uk = '$uk[$i]'");
                    $duplicate = mysql_num_rows($hasduplicatesql);
                    if($duplicate == 0)
                    {
				        mysql_query("INSERT INTO uk_produk (id_produk,id_uk,stok) VALUES('$id_produk','$uk[$i]','$stok[$i]')");
                    }
                    else
                    {
                        mysql_query("UPDATE uk_produk SET stok = stok + '$stok[$i]' WHERE id_produk = '$id_produk' AND id_uk = '$uk[$i]'");
                    }
		        }

		}

		header('location:../../media.php?module='.$module.'&act=editproduk&id='.$_POST[id].'&notif=sukses');	

	}

	else{

		echo "<script>window.alert('Nama Produk dan Harga tidak boleh kosong');

			window.location=('../../media.php?module=produk&act=editproduk&id=$id_produk')</script>";

	}

	

}

// kategori

	elseif ($module=='produk' AND $act=='subkategori'){
	  $sql2 = mysql_query("SELECT * FROM subkategori WHERE id_main='$_POST[id]'");
			echo "<option value=0>- Pilih Sub Kategori -</option>";
		while($r=mysql_fetch_array($sql2)){
			echo "<option value=$r[id_subkategori]>$r[nama_sub]</option>";
		}
	}

	// sub sub kategori
	elseif ($module=='produk' AND $act=='subsubkategori'){
	  $sql2 = mysql_query("SELECT * FROM subsubkategori WHERE id_submain='$_POST[id]'");
			echo "<option value=0>- Pilih Sub Kategori 2 -</option>";
		while($r=mysql_fetch_array($sql2)){
			echo "<option value=$r[id_subkategori]>$r[nama_sub]</option>";
		}
	}

}

?>