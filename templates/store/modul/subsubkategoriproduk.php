<?php
  // nama sub 2
  $rsubkate = mysql_query("SELECT id_subkategori, id_submain, nama_sub from subsubkategori WHERE id_subkategori='$_GET[id]'");
  $nsubkate = mysql_fetch_array($rsubkate);	
  $nmkas = strtoupper ($nsubkate[nama_sub]);
// Tampilkan nama subkategori
  $rq = mysql_query("SELECT * from subkategori where id_subkategori='$nsubkate[id_submain]'");
  $n = mysql_fetch_array($rq);	
  $nmk = strtoupper ($n[nama_sub]);
  // nama sub 1
  $rkate = mysql_query("SELECT id_kategori, nama_kategori, kategori_seo from kategoriproduk WHERE id_kategori='$n[id_main]'");
  $nkate = mysql_fetch_array($rkate);	
  $nmka = strtoupper ($nkate[nama_kategori]);

echo'

<section class="container" style="top:60px;">	
	<section class="portfolio-items pl-col-3 holder">
		<div class="breadcrumbs">
			<a title="Home" href="./">Home</a>
			<span><a title="'.$nmka.'" href="kategoriproduk-'.$nkate[id_kategori].'-'.$nkate[kategori_seo].'.html">Kategori '.$nmka.'</a></span>
			<span><a title="'.$nmk.'" href="subkategoriproduk-'.$nmk[id_subkategori].'-'.$nmk[subkategori_seo].'.html">SubKategori '.$nmk.'</a></span>
			<span>Sub 2 Kategori '.$nmkas.'</span>
		</div>
		<aside id="sidebar" class="three columns">';

		$sub = mysql_query("SELECT * from subkategori where id_main='$_GET[id]'");
		$jumlahsub = mysql_num_rows($sub);

		echo " &nbsp;";
		echo '
		</aside>
		<!--
		<div class="three column" style="text-align:right;">
			<select id="sort" name="sorting"> Urut berdasarkan 
				<option value="id_produk DESC" selected>Default</option>
				<option value="nama_produk ASC">Nama (Ascending)</option>
				<option value="nama_produk DESC">Nama (Descending)</option>
				<option value="harga ASC">Harga (dari terendah)</option>
				<option value="harga DESC">Harga (dari tertinggi)</option>	
			</select>
			<br>
		</div><br><br>
		-->
	<section id="content" class="thirteen columns">
	<div id="baru"></div>
	<div id="lama">
';

	$k = 1;
	$p      = new PagingsubKateProdukLagi;
	$batas  = 20;
	$posisi = $p->cariPosisi($batas);
	$subproduk = mysql_query("SELECT * FROM produk where id_subsubkategori='$nsubkate[id_subkategori]' ORDER BY id_produk DESC LIMIT $posisi,$batas");

	while($r = mysql_fetch_array($subproduk)){

		$gbrproduk  = mysql_query("SELECT * FROM imagesproduk WHERE idProduk = '$r[id_produk]'");
		$gbr = 	mysql_fetch_array($gbrproduk);
		$harga     = format_rupiah($r[harga]);
		$disc      = ($r[diskon]/100)*$r[harga];
		$hargadisc = number_format(($r[harga]-$disc),0,",",".");

		if ($r[diskon] != 0) {
			$rego = "<h4 class='title'><s style='color:red;'>Rp. $harga</s> Rp. $hargadisc</h4>";
		} else {
			$rego = "<h4 class='title'>Rp. $harga</h4>";	
		}		

		echo '
		<div class="four columns">
			<div class="detailimg">
				<div class="bordered">
					<figure class="add-border">
						<a class="single-image" href="produk-'.$r[id_produk].'-'.$r[produk_seo].'.html">
						<img src="foto_produk/'.$gbr[NamaGambar].'" alt="'.$r[nama_produk].'" title="'.$r[nama_produk].'" style="object-fit:cover;width:218px;height:218px;" /></a>
					</figure>
				</div>
				<h2 class="title">'.$r[nama_produk].'</h2>
					'.$rego.'
					<a href="produk-'.$r[id_produk].'-'.$r[produk_seo].'.html" class="button blue small">Detail</a>
					<!-- <a href="aksi.php?module=keranjang&act=tambah&id='.$r[id_produk].'" class="button red small"> Beli </a> -->
			</div>
		</div>	
		';

	}

	  $jmldata     = mysql_num_rows(mysql_query("SELECT * FROM produk WHERE id_subsubkategori ='$id'"));
	  $jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
	  $linkHalaman = $p->navHalaman($_GET[halsubsubkategoriproduk], $jmlhalaman);
	if ($batas < $jmldata) {
		echo '<div class="wp-pagenavi" style="text-align:center;">
			  '.$linkHalaman.'	
			  </div>';
	}

	echo'
		</section>
		<div class="clear"></div>
	</section>
	</div>
</section>';

?>