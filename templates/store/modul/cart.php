 <style type="text/css">
    .loader{
        width: 214px;
        height: 20px;
        position: absolute;
        margin: 0 auto;
        background-image: url('files/ajax-loader-7.gif') no-repeat;
        left: 40%;
        top: 8%;
        opacity: 0.5;
        display:none;
    }
    .auto-table th {
      width: 200px;
    }
 </style>
<?php
$sid = session_id();
$sql = mysql_query("SELECT * FROM orders_temp ot LEFT JOIN produk p ON ot.id_produk=p.id_produk WHERE ot.id_session = '$sid'");
// $r=mysql_fetch_array($sql);
$ketemu=mysql_num_rows($sql);	

// var_dump($r);
// break;
if($ketemu < 1){
echo "<script>window.alert('Keranjang Belanjanya Kosong');
	window.location=('./')</script>";
}
else { 
echo '
<section class="main container sbr clearfix" style="top:60px;">	
	<section class="holder">	
	<div class="breadcrumbs">
		<a title="Home" href="./">Home</a>
		<span>Keranjang Belanja '.$sid.'</span>
	</div><!--/ .breadcrumbs-->	
	
	<div class="one columns">
		<h4>&nbsp;</h4>
	</div>
	<div class="two columns">
		<h4>&nbsp;</h4>
	</div>
	
	<div class="three columns">
		<h4 class="with-desc" >Produk</h4>
	</div>
	<div class="two columns">
		<h4 class="with-desc" >Ukuran</h4>
	</div>
	<div class="two columns">
		<h4 class="with-desc">Berat</h4>
	</div>
	<div class="two columns">
		<h4 class="with-desc">Jumlah</h4>
	</div>
	<div class="two columns">
		<h4 class="with-desc"> Harga</h4>
	</div>
	<div class="two columns">
		<h4 class="with-desc">Subtotal</h4>
	</div>
	
	<form action="aksi.php?module=keranjang&act=update" method="post">
	';
	
$no=1;
while($r=mysql_fetch_array($sql)){
	$idukuran = $r['id_ukuran'];
	$ukuran = mysql_query("SELECT * FROM ukuran WHERE id_uk= '$idukuran'");
	$u = mysql_fetch_array($ukuran);
	$rid = $r['id_produk'];
	$gbrproduk  = mysql_query("SELECT NamaGambar FROM imagesproduk WHERE idProduk = '$rid'");
	$gbr = 	mysql_fetch_array($gbrproduk);
	$disc        = ($r['diskon']/100)*$r['harga'];
	$hargadisc   = number_format(($r['harga']-$disc),0,",",".");
    $subtotal    = ($r['harga']-$disc);
    if($r[name] || $r[no])
	{
	    $subtotal += $r[harga_tambahan];
	}
	$subtotal    = $subtotal * $r['jumlah'];
	
	$total       = $total + $subtotal;  
	$subtotal_rp = format_rupiah($subtotal);
	$total_rp    = format_rupiah($total);
	$hargatemp   = $r['harga'];
	$hargatambahan = format_rupiah($r[harga_tambahan]);
	$harga       = format_rupiah($hargatemp);
	$beratawal = $r['berat'] * $r['jumlah'];
	$berat   = $berat + $beratawal;
	
	echo '
	<div class="one columns">
		<a class="single-image" href="aksi.php?module=keranjang&act=hapus&id='.$r['id_orders_temp'].'" title="Hapus" >
			<img src="files/remove.png" alt="Hapus" />
		</a>
		<input type=hidden name=id['.$no.'] value='.$r['id_orders_temp'].'>
		<input type=hidden name=id_produk value='.$r['id_produk'].'>
		<input type=hidden name=id_uk value='.$r['id_ukuran'].'>
	</div>
	<div class="two columns">
		<div class="bordered">
			<figure class="add-border">
				<a class="single-image" href="#">
					<img src="foto_produk/small_'.$gbr['NamaGambar'].'" alt="'.$r['nama_produk'].'" />
				</a>
			</figure>
		</div>
	</div>
	
	<div class="three columns">
		<p>'.$r['nama_produk'].'</p>';
	    if($r[name])
	    {
	        echo '<p>Nama: '.$r['name'].'</p>';
	    }
	    if($r[no])
	    {
	        echo '<p>Nomor: '.$r['no'].'</p>';
	    }
	echo '
	</div>
	<div class="two columns">
		<p>'.$u['ukuran'].'</p>
	</div>
	<div class="two columns">
		<p>'.$r['berat'].'  Kg </p>
	</div>
	<div class="two columns">
		<select name=jml['.$no.'] value='.$r['jumlah'].' onChange=\'this.form.submit()\'>';
		if($r['stok_temp']>0){
		 for ($j=1;$j <= $r['stok_temp'];$j++){
			  if($j == $r['jumlah']){
			   echo "<option value=$j selected>$j</option>";
			  }else{
			   echo "<option value=$j >$j</option>";
			  }
		  }
		}else{
		    for ($j=1;$j <= 15;$j++){
			 if($j == $r[jumlah]){
			  echo "<option value=$j selected>$j</option>";
			  }else{
			   echo "<option value=$j >$j</option>";
			  }
		  }
		}
		echo'</select>
	</div>
	<div class="two columns">
		<p>Rp. '.$harga.'</p>';
		if($r[no] || $r[name])
		{
		    echo '
		    <p>+Rp. '.$hargatambahan.'</p>
		    <p>(Biaya Custom)</p>';
		}
    echo'
	</div>
	<div class="two columns">
		<p>Rp. '.$subtotal_rp.'</p>
	</div>
	
	<div class="border-divider"></div>
';
$no++;
}
$beratview = $berat;
$berat   = ceil($berat);


echo '
</form>

	<div class="nine columns">
		<h4 class="with-desc">&nbsp;</h4>
	</div>
	<div class="four columns">
		<h4 class="with-desc"> Total Harga</h4>
	</div>
	<div class="three columns">
		<h5 style="text-align:right;">Rp. '.$total_rp.' ,-</h4>
	</div>
	
	<div class="border-divider"></div>
	
	<div class="one columns">
	&nbsp;</div>

    <form id="datauser" role="form" method="POST" onsubmit="submitme(); return false;" action="ajax.php?module=proses">

	<div class="five columns">
		<p class="input-block">
			<label> Provinsi:</label>
			<input type="hidden" name="namaprovinsi" id="namaprovinsi" value="">
			<select name="provinsi-tujuan" onchange="provinsiTo(this)" required style="line-height: 20px;">
			<option value="">- Pilih Provinsi -</option>
';
	$curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_URL => "http://pro.rajaongkir.com/api/province",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "GET",
	  CURLOPT_HTTPHEADER => array(
	    "key: 5e610f0aa5f9d307a84c9c541e1d1701"
	  ),
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  echo "cURL Error #:" . $err;
	} else {
	  $data = json_decode($response);
	  foreach ($data->rajaongkir->results as $result) {
	  	echo '<option value="'.$result->province_id.'">'.$result->province.'</option>';
	  }
	}
echo '
			</select>
		</p>
		<p class="input-block"> 
			<label> Kab / Kota:</label>
 			<input type="hidden" name="id_session" value="'.$sid.'">
 			<input type="hidden" name="berat" value="'.$berat.'">
 			<input type="hidden" name="subtotal" value="'.$total.'">
 			<input type="hidden" name="kotanama" id="kotanama" value="">
			<select name="kota-tujuan" class="with-desc" style="line-height: 20px;" onchange="kecamatanTo(this)" disabled required>
			<option value="">- Pilih Kota</option></select>
		</p>

		<p class="input-block"> 
			<label> Kecamatan:</label>
			<input type="hidden" name="namakecamatan" id="namakecamatan" value="">
			<select name="kecamatan-tujuan" onchange="namaKecamatan(this)" class="with-desc" style="line-height: 20px;" disabled required>
			<option value="">- Pilih Kecamatan</option></select>
		</p>
		
		<p class="input-block">
			<label>Alamat:</label>
			<textarea name="alamat" cols="30" rows="5" required></textarea>	
		</p>
	</div>
	
	<div class="five columns">
	
		<p class="input-block">
			<label for="name">Nama Penerima:</label>
			<input type="text" name="nama" id="name" required/>
		</p>

		<p class="input-block">
			<label for="email">Telepon:</label>
			<input type="text" name="telp" id="telp" onkeyup="validAngka(this)" required/>
		</p>
		<p class="input-block">
			<label for="email">E-mail:</label>
			<input type="email" name="email" id="email" required/>
		</p>
		
		<p class="input-block">
			<label> Kurir:</label>
			 <select class="form-control" id="kurir" onchange="checkPay(this)" name="kurir" required>
                <option value="">- Pilih Kurir -</option> 
                <option value="pos">POS</option>
                <option value="jnt">J&T</option>
              </select>
		</p>

		<p class="input-block">
			<label> Paket:</label>
			 <select name="paket" onchange="resultPay(this)" required>
                <option value="">- Pilih Paket</option>
              </select>
		</p>			
	</div>

	<div class="five columns">                
	<table>
		<tr>
			<td><h5 class="with-desc"> Total Harga</h5>
			</td>					
			<td><h5 style="text-align:right;">Rp. '.$total_rp.',00</h5>	
			</td>
		</tr>
		<tr>
			<td><h5 class="with-desc"> Total Berat</h5>
			</td>					
			<td><h5 style="text-align:right;"> '.$beratview.' Kg</h5>
			</td>
		</tr>
		<tr>
			<td><h5 class="with-desc">Ongkos Kirim </h5>
			</td>					
			<td><h5 style="text-align:right;" id="show-tr-result">Rp. 0</h5> 
			</td>
		</tr>
		<tr>
			<td><h5 class="with-desc">Total </h5>
			</td>					
			<td><h5 style="text-align:right;" id="show-total">Rp. '.$total_rp.',00</h5>
			</td>
		</tr>
	</table>

		<div id="respon"></div>
	</div>
	<br><br>
	<br><br>
	<br><br>
	
	<p><button class="button default"> Pesan </button></p>
	</form>

	<div class="border-divider"></div>
	</section>
</section>
';
}
?>
<script type="text/javascript">

  var processsend = 0;
  
  Number.prototype.formatMoney = function(c, d, t){
  var n = this, 
      c = isNaN(c = Math.abs(c)) ? 2 : c, 
      d = d == undefined ? "." : d, 
      t = t == undefined ? "," : t, 
      s = n < 0 ? "-" : "", 
      i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
      j = (j = i.length) > 3 ? j % 3 : 0;
     return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
   };

    $('#ke').typeahead({
      ajax: 'ajax.php?module=kota',
      displayField: 'namakota',
      onSelect: function(item) {
        $('#kehid').val(item.value);
      }
    });

    // $("#datauser").submit(function(){
    // });
    
 	function submitme(){
 	    console.log(processsend);
 	    if(processsend == 0)
 	    {
 	        $(".loader").css({"display":"block"});
            $("#datauser").css({"opacity":"0.5"});
 		    var data = $("#datauser").serialize();
 	    	// var url = 'ajax.php?module=ceckout';
 		    var url = 'aksi.php?module=keranjang&act=checkout';
 		    $.post(url,data, function(data){
 		        $(".loader").css({"display":"none"});
                $("#datauser").css({"opacity":"1"});
 		    	alert('Kami akan mengirimkan detailnya ke email anda.');window.location='./';
 		    	
 	    	});
 	    	$(".loader").css({"display":"none"});
            $("#datauser").css({"opacity":"1"});
 	    }
 		processsend = 1;
 	}

	function validAngka(a){
		if(!/^[0-9.]+$/.test(a.value)) {
		a.value = a.value.substring(0,a.value.length-1000);
		}
	}

    function checkPay(that) {

      if ($(that).val!=""){
        $(".loader").css({"display":"block"});
        $("#datauser").css({"opacity":"0.5"});
        $("#show-tr-result").html('<td>Tidak Ada</td>');
        var postData = $("#datauser").serializeArray();
        var formURL = $("#datauser").attr("action");
        $.ajax({
          url: formURL,
          type: "POST",
          data: postData,
          dataType: "json",
          success: function (response) {
            $(".loader").css({"display":"none"});
            $("#datauser").css({"opacity":"1"});
            $("select[name='paket']").html('<option value="">- Pilih Paket - </option>');    
            $.each(response, function( index, value ) {
              $("select[name='paket']").append('<option value="'+value.namapaket+'-'+value.biaya+'-'+value.hari+'">'+value.namapaket+' - '+value.biaya+'</option>');
            });
          },
          error: function () {
            alert("Maaf ada kesalahan kurir");
            $(".loader").css({"display":"none"});
            $("#datauser").css({"opacity":"1"});
          }
        });
        return false;
      }
      else{
        $(".loader").css({"display":"none"});
        $("#datauser").css({"opacity":"1"});
        $("select[name='paket']").html('<option value="">- Pilih Paket - </option>');
      }
    }

    function provinsiTo(that){
      var id_provinsi = $(that).val();
      var provincename = that.options[that.selectedIndex].text;
      var provincediv = document.getElementById("namaprovinsi");
      console.log(provincediv.value);
      provincediv.value = provincename;
      //console.log("changed:");
      //console.log(provincename);

      if (id_provinsi!=""){
          $.ajax({
          url: "ajax.php?module=kota",
          type: "GET",
          data: {id:id_provinsi},
          dataType: "JSON",
          success: function (response) {
            $("select[name='kota-tujuan']").html('<option value="">- Pilih Kota - </option>')
            $.each(response, function( index, value ) {
              $("select[name='kota-tujuan']").append('<option value="'+value.id+'">'+value.tipe+' '+value.namakota+'</option>');
            });
            $("select[name='kota-tujuan']").prop("disabled",false);
            
          },
          error: function () {
            alert("Maaf ada kesalahan");
          }
        });
      } 
      else
      {$("select[name='kota-tujuan']").html('<option value="">- Pilih Kota - </option>')}

    }

    function kecamatanTo(that){
        var id = $(that).val();
        var cityname = that.options[that.selectedIndex].text;
        var citydiv = document.getElementById("kotanama");
        console.log(citydiv.value);
        citydiv.value = cityname;
      if (id!=""){
          $.ajax({
          url: "ajax.php?module=kecamatan",
          type: "GET",
          data: {id:id},
          dataType: "JSON",
          success: function (response) {
            $("select[name='kecamatan-tujuan']").html('<option value="">- Pilih Kecamatan</option>')
            $.each(response, function( index, value ) {
              $("select[name='kecamatan-tujuan']").append('<option value="'+value.id+'">'+value.namakecamatan+'</option>');
            });
            $("select[name='kecamatan-tujuan']").prop("disabled",false);
            
          },
          error: function () {
            alert("Maaf ada kesalahan");
          }
        });
      } 
      else
      {$("select[name='kecamatan-tujuan']").html('<option value="">- Pilih Kecamatan</option>')}
    }

    function resultPay(that) {
      if ($(that).val()!=""){
        $(".loader").css({"display":"block"});
        $("#datauser").css({"opacity":"0.5"});
        var str = $(that).val();
        var res = str.split("-");
        var berat = parseInt($("input[name='berat']").val());
        var biaya = parseInt(res[1])*berat;

        var subtotal = parseInt($("input[name='subtotal']").val());
        var total = biaya + subtotal;
        $("#show-tr-result").html('<td>Rp. '+biaya.formatMoney(2, ',', '.')+'</td><td><input type=hidden name="ongkir" value='+biaya+'></td>');
        $("#show-total").html('<td><b>Rp. '+total.formatMoney(2, ',', '.')+'</b></td><td><input type=hidden name="total" value='+total+'></td>');
        $(".loader").css({"display":"none"});
        $("#datauser").css({"opacity":"1"});
      }
      else{
        $(".loader").css({"display":"none"});
        $("#datauser").css({"opacity":"1"});
        alert("Maaf ada kesalahan");
      }

    }
    function namaKecamatan(that){
        var districtname = that.options[that.selectedIndex].text;
        var districtdiv = document.getElementById("namakecamatan");
        console.log(districtdiv.value);
        districtdiv.value = districtname;
    }
  </script>