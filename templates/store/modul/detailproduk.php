 <?php

$det=mysql_query("SELECT * FROM produk WHERE id_produk='$_GET[id]'");

$r = mysql_fetch_array($det);



$kategori=mysql_query("SELECT nama_kategori, kategori_seo FROM kategoriproduk   

				  WHERE id_kategori='$r[id_kategori]'");

$n = mysql_fetch_array($kategori);



$subkategori=mysql_query("SELECT nama_sub, subkategori_seo FROM subkategori   

				  WHERE id_subkategori='$r[id_subkategori]'");

$nsub = mysql_fetch_array($subkategori);



	$harga     = format_rupiah($r[harga]);

	$disc      = ($r[diskon]/100)*$r[harga];

	$hargadisc = number_format(($r[harga]-$disc),0,",",".");

	if ($r[diskon] != 0) {

		$rego = "<h3><s style='color:red;'>Rp. $harga</s> Rp. <strong>$hargadisc</strong></h3>";

	} else {

		$rego = "<h3>Rp. $harga</h3>";	

	}

$ukuran = mysql_query("SELECT * FROM uk_produk WHERE id_produk='$r[id_produk]' ORDER BY id_uk");

$gbrproduk  = mysql_query("SELECT NamaGambar FROM imagesproduk WHERE idProduk = '$r[id_produk]'");

$jumlahgbr  = mysql_num_rows($gbrproduk);

while ($gbr = mysql_fetch_array($gbrproduk)) {

	$a[] =$gbr;

}		



echo '

	<section class="container" style="top:60px;">	

		<section class="holder">

		<div class="breadcrumbs">

			<a title="Home" href="./">Home</a>

			<span><a title="'.$n[nama_kategori].'" href="kategoriproduk-'.$r[id_kategori].'-'.$n[kategori_seo].'.html">'.$n[nama_kategori].'</a></span>

			<span><a title="'.$nsub[nama_sub].'" href="subkategoriproduk-'.$r[id_subkategori].'-'.$nsub[subkategori_seo].'.html">'.$nsub[nama_sub].'</a></span>

			<span>'.$r[nama_produk].'</span>

		</div>

		

		<div class="seven columns">

			

			<div class="bordered">

					<a class="single-image" href="#">

						<img src="foto_produk/'.$a[0][NamaGambar].'" title="'.$r[nama_produk].'" alt="'.$r[nama_produk].'" />

					</a>

			</div><!--/ .bordered-->

			

			';

			for ($i=0;$i<$jumlahgbr;$i++) {

					echo '

			<div class="two columns">

					<div class="bordered">

						<figure class="add-border">

							<a class="single-image picture-icon" rel="holder" href="foto_produk/'.$a[$i][NamaGambar].'"><img src="foto_produk/small_'.$a[$i][NamaGambar].'" title="'.$r[nama_produk].'" alt="'.$r[nama_produk].'" /></a>

						</figure>

					</div><!--/ .bordered-->

			</div>';

				}		

				

			echo '			

		</div>

		

		<div class="nine columns">

			

			<h2 class="title">'.$r[nama_produk].'</h2>

			'.$rego.'

			

			'.$r[deskripsi].'	

			 

			<form enctype="multipart/form-data" method="post" class="cart" action="aksi.php?module=keranjang&act=tambah&id='.$r[id_produk].'">';
                /*
                echo'
				<span class="label qty-label">Qty:</span>

				<br><br>
				
                
				<div class="four columns">

					<select name=jml value='.$r[jumlah].' onChange=\'this.form.submit()\'>';
					 if ($r[id_kategori] == 1) {
					 	echo "<option value=1 >1</option>";
					 }else{
                    if($r[stok] > 0){

					 for ($j=1;$j <= $r[stok];$j++){

						  if($j == $r[jumlah]){

						   echo "<option value=$j selected>$j</option>";

						  }else{

						   echo "<option value=$j >$j</option>";

						  }

					  }			
                    }else{
                        
                            $eee     = $_GET['name'];
                            echo "akjsdbkhasgdhkjsabdvaskhdv".$id;
                            $qty    = mysql_query("SELECT * FROM uk_produk WHERE id='$id' ");
                            $isi    = mysql_fetch_array($qty);
                            
                            for ($j=1;$j <= 15;$j++){
        
        						  if($j == $isi[jumlah]){
        
        						   echo "<option value=$j selected>$j</option>";
        
        						  }else{
        
        						   echo "<option value=$j >$j</option>";
        
        						  }
        
        					 }
					  
                        
                    }
					}
					echo'</select>

				

				</div>';
				*/
                echo'
				<br><br>

				<span class="label qty-label">Ukuran:'.$eee.'</span>

				<br><br>	

				<div class="four columns">

					<select name=uk >';

					while ($uk = mysql_fetch_array($ukuran)) {
					$ukurr  = mysql_query("SELECT * FROM ukuran WHERE id_uk='$uk[id_uk]'");
			        $ukur   = mysql_fetch_array($ukurr);

						   echo '<option value='.$uk[id_uk].' data-id="'.$uk['id'].'" >'.$ukur[ukuran].'</option>';
					
					}

					echo'</select>

				

				</div>';

				if ($r[id_kategori]== 1) {
					echo '<br><br><br><div class="col-md-12">
 						<center><h2>Custom';
 					//echo ' (Biaya Rp. '.format_rupiah($r[harga_tambahan]).')';
 					echo '</h2></center>';
 					echo '<center></h4>Biaya Rp. '.format_rupiah($r[harga_tambahan]).'<h4></center>';
 					echo '
						<p class="input-block">
								<label for="email">No punggung :</label>
								<input type="text" name="no" id="telp" onkeyup="validAngka(this)" />
						  </p>
						  <p class="input-block">
								<label for="email">Nama :</label>
								<input type="text" name="name" id="telp" />
						  </p>
						  </div>';
				}

			  echo '<button class="button blue small" type="submit"> Beli</button>



			  <div class="clear"></div>

			</form>

			

			<p>Share : </p>

			<a href="https://api.addthis.com/oexchange/0.8/forward/facebook/offer?url='.$ridentitas[alamat_website].'/produk-'.$r[id_produk].'-'.$r[produk_seo].'.html" target="_blank"><img src="files/rizky-mobile-facebook.png" border="0" alt="Facebook"/></a>

		

			<a href="https://api.addthis.com/oexchange/0.8/forward/twitter/offer?url='.$ridentitas[alamat_website].'/produk-'.$r[id_produk].'-'.$r[produk_seo].'.html" target="_blank"><img src="files/rizky-mobile-twitter.png" border="0" alt="Twitter"/></a>

		

			<a href="https://api.addthis.com/oexchange/0.8/forward/google_plusone_share/offer?url='.$ridentitas[alamat_website].'/produk-'.$r[id_produk].'-'.$r[produk_seo].'.html" target="_blank"><img src="files/rizky-mobile-google_plus.png" border="0" alt="Google+"/></a>

					

		</div><!--/ .twelve .columns-->

		

		<div class="border-divider"></div>

	

		</section>

	</section><!--/ .main -->

		';

		

?>	
<script type="text/javascript">
    function getQty(that){
        var id = $(that).data('id');
        var vari = $(that).val();
        window.location.href = "detailproduk.php?name=" + vari; 
    }
</script>