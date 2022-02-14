<script language="javascript">
	function validasi(form){
	  if (form.kode_orders.value == ""){
		alert("Anda belum mengisikan Kode Order");
		form.kode_orders.focus();
		return (false);
	  } 
	  else if (form.ke_bank.value == "0"){
		alert("Anda belum mengisikan bank tujuan");
		form.ke_bank.focus();
		return (false);
	  }
	  else if (form.pengirim.value == ""){
		alert("Anda belum mengisikan Nama Pengirim");
		form.pengirim.focus();
		return (false);
	  }
	  else if (form.jumlah.value == ""){
		alert("Anda belum mengisikan jumlah pembayaran");
		form.jumlah.focus();
		return (false);
	  }
	  else {
		return (true);
	  }
	}
</script>
	<section class="main container sbr clearfix" style="top:30px;">	
		<!-- - - - - - - - - - Breadcrumbs - - - - - - - - - - - - -->	
		<div class="breadcrumbs">

			<a title="Home" href="./">Home</a>
			<span>Konfirmasi Pembayaran</span>

		</div><!--/ .breadcrumbs-->	
		
		<!-- - - - - - - - - end Breadcrumbs - - - - - - - - - - - -->	
		<div class="sixteen columns">
			<section id="contact">
			<h2 class="entry-title">Konfirmasi Pembayaran</h2>

				<form method="post" action="aksi.php?module=keranjang&act=konfirmasi" onSubmit='return validasi(this)' class="comments-form">

					<p class="input-block">
						<label for="name">Kode Order :</label> 
						<input type="text" name="kode_orders" onkeyup="validAngka(this)" required value="<?php echo $_GET["kode"] ?>">
					</p>

					<p class="input-block">
                      <label for="email">Transfer dari (Bank Anda)</label>
                      <input type="text" name="dari_bank" required>
                    </p>

                    <p class="input-block">
                      <label for="url">Ke Rekening</label>			  			 
						<select name="ke_bank" >
					<?php
						 $rek = mysql_query("SELECT * FROM rekening WHERE keterangan = 'Y'");
							 echo "<option value=0>- Pilih Bank -</option>";
						 while ($rrek = mysql_fetch_array($rek)){
							 echo "<option value=$rrek[bank]>$rrek[bank]</option>";
					}
					?>
						</select>
                    </p>
                    <p class="input-block">
                      <label for="url">Nama Pengirim</label>
                      <input type="text" name="pengirim" required>
                    </p>
                    <p class="input-block">
                      <label for="url">Jumlah</label>
                      <input type="text" name="jumlah" onkeyup="validAngka(this)" required>
                    </p>
					<p class="input-block">
						<button class="button default" type="submit" >Konfirmasi Pembayaran</button>
					</p>

				</form><!--/ .comments-form-->	
			</section><!--/ #contact-->
		</div>
	</section><!--/ .main -->

	<script language='javascript'>
	function validAngka(a){
		if(!/^[0-9.]+$/.test(a.value)) {
		a.value = a.value.substring(0,a.value.length-1000);
		}
	}
	</script>