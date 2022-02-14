<script language="javascript">
	function validasi(form){
	  if (form.kode_order.value == ""){
		alert("Anda belum mengisikan Kode Order");
		form.kode_konfirmasi.focus();
		return (false);
	  }
	}
</script>
	<section class="main container sbr clearfix" style="top:30px;background-color:#fff">	
		<!-- - - - - - - - - - Breadcrumbs - - - - - - - - - - - - -->	
		<div class="breadcrumbs">

			<a title="Home" href="./">Home</a>
			<span>Status Pemesanan</span>

		</div><!--/ .breadcrumbs-->	
		
		<!-- - - - - - - - - end Breadcrumbs - - - - - - - - - - - -->	
		<div class="sixteen columns">
			<section id="contact">
			<h5 class="entry-title">Status Pemesanan</h5>

				<form method="post" action="tracking" onSubmit='return validasi(this)' class="comments-form">

					<p class="input-block">
						<label for="name">Kode Order :</label> 
						<input type="text" name="kode_order" onkeyup="validAngka(this)" required value="<?php echo $_GET["kode"] ?>">
					</p>

					
					<p class="input-block">
						<button class="button default" type="submit" >Lihat Status Pemesanan</button>
					</p>

				</form><!--/ .comments-form-->	
			</section><!--/ #contact-->
		</div>
		<?php
		    if($_POST[kode_order])
		    {
		        $sql = mysql_query("SELECT * FROM orders WHERE kode_orders='$_POST[kode_order]'");
		        $ketemu = mysql_num_rows($sql);
	            if($ketemu == 0)
	            {
	                echo '<script language="javascript">';
                    echo 'alert("Pesanan Tidak Ditemukan")';
                    echo '</script>';
	            }
	            else
	            {
	                $sqlcheckkonf = mysql_query("SELECT * FROM konfirmasi WHERE kode_konfirmasi='$_POST[kode_order]'");
	                $ketemu = mysql_num_rows($sqlcheckkonf);
	                if($ketemu == 0)
	                {
	                    echo '<script language="javascript">';
                        echo 'alert("Harap Konfirmasi Pesanan Anda Terlebih Dahulu")';
                        echo '</script>';
	                }
	                else
	                {
	                    $dataorder = mysql_fetch_array($sql);
	                    $sql2 = mysql_query("SELECT * FROM orders_detail WHERE kode_orders='$_POST[kode_order]'");
	                    $i = 0;
	                    $berattotal = 0;
	                
	                    //informasi umum order
	                    echo'
	                    <div class="sixteen columns">
	                        <br>
	                        <br>
	                    </div>
	                    <div class="sixteen columns">
	                        <h4>Detail Pesanan:</h4>
	                    </div>
	                    <div class="five columns">
	                        <h5>No. Order </h5>
	                    </div>
	                    <div class="eleven columns">
	                        <h5>'.': '.$dataorder[id_orders].'</h5>
	                    </div>
	                    <br>
	                    <div class="five columns">
	                        <h5>Kode Order </h5>
	                    </div>
	                    <div class="eleven columns">
	                        <h5>'.': '.$dataorder[kode_orders].'</h5>
	                    </div>
	                    <div class="five columns">
	                        <h5>Tanggal, Jam Order </h5>
	                    </div>
	                    <div class="eleven columns">
	                        <h5>'.': '.$dataorder[tgl_order].', '.$dataorder[jam_order].'</h5>
	                    </div>
	                    <div class="five columns">
	                        <h5>Status Order </h5>
	                    </div>
	                    <div class="eleven columns">
	                        <h5>'.': '.$dataorder[status_order].'</h5>
	                    </div>
	                    <div class="sixteen columns">
	                        <br>
	                        <br>
	                    </div>
	                    ';
	                    //detil order
	                    echo'
	                    <div class="four columns">
	                        <h4><b>Nama Produk</b></h4>
	                    </div>
	                    <div class="two columns">
	                        <h4><b>Ukuran</b></h4>
	                    </div>
	                    <div class="two columns">
	                        <h4><b>Jumlah</b></h4>
	                    </div>
	                    <div class="four columns">
	                        <h4><b>Harga Satuan</b></h4>
	                    </div>
	                    <div class="four columns">
	                        <h4><b>Subtotal</b></h4>
	                    </div>
	                    ';
	                    while($detail = mysql_fetch_array($sql2))
	                    {
	                        $i++;
	                        $sql3 = mysql_query("SELECT * FROM produk WHERE id_produk='$detail[id_produk]'");
	                        $sql4 = mysql_query("SELECT * FROM ukuran WHERE id_uk='$detail[id_ukuran]'");
	                        $produk = mysql_fetch_array($sql3);
	                        $ukuran = mysql_fetch_array($sql4);
	                        $berattotal += $detail[jumlah] * $produk[berat];
	                        $harga = $produk[harga];
	                        $hargadisc = $produk[harga] * (100 - $produk[diskon])/100;
	                        if($detail[name] || $detail[no])
	                        {
	                            $hargadisc += $produk[harga_tambahan];
	                        }
	                        /*
	                        if($produk[promo] == 'Y'){
		                        $harga       = $produk[harga_promo];
	            	        }
	    	                else{
		                        $harga       = $produk[harga];
		                    }
		                    */
		                    $subtotal = $hargadisc * $detail[jumlah];
	                        echo '
	                        <div class="four columns">
	                            <h5>'.$produk[nama_produk].'</h5>
	                        </div>
	                        <div class="two columns">
	                            <h5>'.$ukuran[ukuran].'</h5>
	                        </div>
	                        <div class="two columns">
	                            <h5>'.$detail[jumlah].'</h5>
	                        </div>
	                        <div class="four columns">
	                            <h5>Rp. '.format_rupiah($produk[harga]).'</h5>
	                        </div>
	                        <div class="four columns">
	                            <h5>Rp. '.format_rupiah($subtotal).'</h5>
	                        </div>
	                    
	                        ';
	                        if($detail[name] || $detail[no])
	                        {
	                            if($detail[name])
	                            {
	                                echo '
	                                <div class="eight columns">
	                                    <h6>Nama : '.$detail[name].'</h6>
	                                </div>
	                                <div class="eight columns">
	                                    <h6>+Rp. '.$produk[harga_tambahan].' (Biaya Custom)</h6>
	                                </div>
	                                ';
	                            }
	                            if($detail[no])
	                            {
	                                if($detail[name])
	                                {
	                                    echo '
	                                    <div class="sixteen columns">
	                                        <h6>Nomor Punggung : '.$detail[no].'    </h6>
	                                    </div>
	                                    ';
	                                }
	                                else
	                                {
	                                    echo '
	                                    <div class="eight columns">
	                                        <h6>Nomor Punggung : '.$detail[no].'    </h6>
	                                    </div>
	                                    <div class="eight columns">
	                                        <h6>+Rp. '.$produk[harga_tambahan].'         (Biaya Custom)</h6>
	                                    </div>
	                                    ';
	                                }
	                            }
	                        }
	                    }
	                    $rawcost = $dataorder[jumlah_dibayar] - $dataorder[biaya_ongkir];
	                    echo'
	                    <div class="twelve columns">
	                        <h5>Total:</h5>
	                    </div>
	                    <div class="four columns">
	                        <h5><b>Rp. '.format_rupiah($rawcost).'</b></h5>
	                    </div>
	                    <div class="twelve columns">
	                        <h5>Berat:</h5>
	                    </div>
	                    <div class="four columns">
	                        <h5><b>'.$berattotal.' Kg</b></h5>
	                    </div>
	                    <div class="twelve columns">
	                        <h5>Kurir:</h5>
	                    </div>
	                    <div class="four columns">
	                        <h5><b>'.$dataorder[kurir].'</b></h5>
	                    </div>
	                    <div class="twelve columns">
	                        <h5>Biaya Ongkir:</h5>
	                    </div>
	                    <div class="four columns">
	                        <h5><b>Rp. '.format_rupiah($dataorder[biaya_ongkir]).'</b></h5>
	                    </div>
	                    <div class="twelve columns">
	                        <h5>Grand Total:</h5>
	                    </div>
	                    <div class="four columns">
	                        <h5><b>Rp. '.format_rupiah($dataorder[jumlah_dibayar]).'</b></h5>
	                    </div>
	                    ';
	                    //data pemesan
	                    echo'
	                        <div class="sixteen columns">
	                            <br>
	                            <br>
	                        </div>
	                        <div class="sixteen columns">
	                            <h4>Detail Pemesan:</h4>
	                        </div>
	                        <div class="six columns">
	                            <h5>Nama Pemesan </h5>
	                        </div>
	                        <div class="ten columns">
	                            <h5>'.': '.$dataorder[nama_pemesan].'</h5>
	                        </div>
	                        <div class="six columns">
	                            <h5>Alamat Pengiriman </h5>
	                        </div>
	                        <div class="ten columns">
	                            <h5>'.': '.$dataorder[alamat].', '.$dataorder[namakecamatan].', '.$dataorder[namakota].', '.$dataorder[namaprovinsi].'</h5>
	                        </div>
	                        <div class="six columns">
	                            <h5>No. Telepon/HP </h5>
	                        </div>
	                        <div class="ten columns">
	                            <h5>'.': '.$dataorder[telp].'</h5>
	                        </div>
	                        <div class="six columns">
	                            <h5>Email </h5>
	                        </div>
	                        <div class="ten columns">
	                            <h5>'.': '.$dataorder[email].'</h5>
	                        </div>
	                    ';
	                    //data konfirmasi
	                    $sqlkonf = mysql_query("SELECT * from konfirmasi WHERE kode_konfirmasi = '$dataorder[kode_orders]'");
	                    $datakonf = mysql_fetch_array($sqlkonf);
	                    echo'
	                    <div class="sixteen columns">
	                        <br>
	                        <br>
	                    </div>
	                    <div class="sixteen columns">
	                        <h4>Detail Konfirmasi:</h4>
	                    </div>
	                    <div class="six columns">
	                        <h5>Nama Pemilik Rekening </h5>
	                    </div>
	                    <div class="ten columns">
	                        <h5>'.': '.$datakonf[pengirim].'</h5>
	                    </div>
	                    <div class="six columns">
	                        <h5>Transfer Ke Bank </h5>
	                    </div>
	                    <div class="ten columns">
	                        <h5>'.': '.$datakonf[dari_bank].'</h5>
	                    </div>
	                    <div class="six columns">
	                        <h5>Jumlah Yang Dibayar </h5>
	                    </div>
	                    <div class="ten columns">
	                        <h5>'.': Rp. '.format_rupiah($datakonf[jumlah]).'</h5>
	                    </div>
	                    <div class="six columns">
	                        <h5>Tanggal Konfirmasi </h5>
	                    </div>
	                    <div class="ten columns">
	                        <h5>'.': '.$datakonf[tgl].'</h5>
	                    </div>
	                    ';
	                }
	            }
		    }
		?>
	</section><!--/ .main -->

	<script language='javascript'>
	function validAngka(a){
		if(!/^[0-9.]+$/.test(a.value)) {
		a.value = a.value.substring(0,a.value.length-1000);
		}
	}
	</script>