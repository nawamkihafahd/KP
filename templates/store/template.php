<!DOCTYPE html>
<!--[if IE 7]>					<html class="ie7 no-js" lang="en">     <![endif]-->
<!--[if lte IE 8]>              <html class="ie8 no-js" lang="en">     <![endif]-->
<!--[if IE 9]>					<html class="ie9 no-js" lang="en">     <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="not-ie no-js" lang="en">  <!--<![endif]-->
<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
	<link href='http://fonts.googleapis.com/css?family=Over+the+Rainbow|Open+Sans:300,400,400italic,600,700|Arimo|Oswald|Lato|Ubuntu' rel='stylesheet' type='text/css'>
	
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	
	<title><?php include "dina_titel.php"; ?></title>
	<meta content="<?php include "dina_meta1.php"; ?>" name="description">
	<meta content="<?php include "dina_meta2.php"; ?>" name="keywords">
	<meta name="robots" content="INDEX,FOLLOW" />
<?php
echo '<link href="files/'.$ridentitas[favicon].'" rel="icon" />';
	
if($_GET['module'] == 'detailproduk'){
	$qOpenGraph = mysql_query("SELECT produk_seo,nama_produk FROM produk WHERE id_produk='$_GET[id]'");
	$rOpenGraph = mysql_fetch_array($qOpenGraph);
	$qgbr = mysql_query("SELECT NamaGambar FROM imagesproduk WHERE idProduk='$_GET[id]'");
	$rgbr = mysql_fetch_array($qgbr);
	
	echo '
		<meta property="og:url" content="'.$ridentitas[alamat_website].'/produk-'.$_GET[id].'-'.$rOpenGraph[produk_seo].'.html" /> 
		<meta property="og:title" content="'.$rOpenGraph[nama_produk].'" />
		<meta property="og:description" content="'; include "dina_meta1.php"; echo'" /> 
		<meta property="og:image" content="'.$ridentitas[alamat_website].'/foto_produk/'.$rgbr[NamaGambar].'" /> 		
	';
}
elseif($_GET['module'] == 'detailartikel'){
	$qOpenGraph = mysql_query("SELECT * FROM artikel WHERE id_artikel='$_GET[id]'");
	$rOpenGraph = mysql_fetch_array($qOpenGraph);
	
	echo '
		<meta property="og:url" content="'.$ridentitas[alamat_website].'/artikel-'.$rOpenGraph[id_artikel].'-'.$rOpenGraph[artikel_seo].'.html" /> 
		<meta property="og:title" content="'.$rOpenGraph[nama_artikel].'" />
		<meta property="og:description" content="'; include "dina_meta1.php"; echo'" /> 
		<meta property="og:image" content="'.$ridentitas[alamat_website].'/foto_berita/'.$rOpenGraph[gambar].'" /> 		
	';
}
else{
	echo '
		<meta property="og:url" content="'.$ridentitas[alamat_website].'" /> 
		<meta property="og:title" content="';include "dina_titel.php"; echo'" />
		<meta property="og:description" content="'; include "dina_meta1.php"; echo'" /> 	
	';	
}
	$cta = mysql_query("SELECT * FROM cta");
	$rcta = mysql_fetch_array($cta);
?>

	<link rel="stylesheet" href="<?php echo "$f[folder]/"; ?>css/skeleton.css" media="screen" />
	<link rel="stylesheet" href="<?php echo "$f[folder]/"; ?>css/style.css" media="screen" />
	<link rel="stylesheet" href="<?php echo "$f[folder]/"; ?>css/mediaelementplayer.css" media="screen" />
	<link rel="stylesheet" href="<?php echo "$f[folder]/"; ?>fancybox/jquery.fancybox.css" media="screen" />
	
	<!-- REVOLUTION BANNER CSS SETTINGS -->
	<link rel="stylesheet" href="<?php echo "$f[folder]/"; ?>rs-plugin/css/settings.css" media="screen" />	
	
	<!-- HTML5 SHIV + DETECT TOUCH EVENTS -->
	<script type="text/javascript" src="<?php echo "$f[folder]/"; ?>js/modernizr.custom.js"></script>
	<!-- GET JQUERY FROM THE GOOGLE APIS -->
	
	<link rel="stylesheet" href="<?php echo "$f[folder]/"; ?>css/footer.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
	<script>
		var pin = '<?php echo "$rcta[pin]"; ?>';
		var mail = '<?php echo "$rcta[email]"; ?>';
		var telp = '<?php echo "$rcta[telp_indosat]"; ?>';
	</script>
	<script src="<?php echo "$f[folder]/"; ?>js/aksamedia-cta.js"></script>
</head>
<body class="color-1 h-style-1 text-1">
	<!-- - - - - - - - - - - - - - Header - - - - - - - - - - - - - - - - -->	
	<div id="atasheader">
		<div class="container">
			<div class="event-holder">
	<?php
	if (empty($_SESSION['namalengkap'])){ 
		echo '<span><a href="login" style="color:#121639;">Login / Register</a></span>';
	}
	else {
		echo '<span><a href="aksa-admin/logout.php" style="color:#121639;">Logout</a></span>';
	}
	echo '
		<ul class="social-icons clearfix" style="float:right;">';
			$qsosial = mysql_query("SELECT * FROM sosial WHERE aktif = 'Y'");
			while ($rsosial = mysql_fetch_array($qsosial)){
				echo ' <li class="'.$rsosial[nama].'"><a href="'.$rsosial[link].'" target="_blank">'.$rsosial[nama].'<span></span></a></li>';
			}
			echo '
		</ul>
	';
	?>
			</div>
		</div>
	</div>
	<header id="header">
		
		<div class="container">
	
			<?php
			 	$sid = session_id();
				$sqljum = mysql_query("SELECT count(jumlah) as jum FROM orders_temp 
			                WHERE id_session='$sid'"); 
				$jum = mysql_fetch_array($sqljum);
			echo'
			<div style="text-align:center;margin:0 auto">
			<a href="./" id="logo">
				<img src="files/'.$ridentitas[logo].'" style="max-height:70px;" alt="'.$ridentitas[meta_deskripsi].'">
			</a>
			</div>
			
			<div class="event-holder">
			<span class="event-text" id="batas">
				<aside id="sidebar">
					<div class="four columns widget widget_search">
						<form action="hasil-pencarian.html" method="post" id="searchform">
							
							<fieldset>
								<input type="text" placeholder="Cari disini..." name="search"/>
								<button type="submit" title="Search">Search</button>
							
							</fieldset>
						</form>
					</div><!--/ .widget-->
					
				</aside>
			</span>
			
			<span id="keranjangbesar">
			<a href="keranjang-belanja.html" class="button sky medium" title="keranjang belanja"><img src="files/cart2-black.png"> ('.$jum[jum].')</a>	
			</span>
			</div>
			
			<div >
				<span class="keranjang">
					<a href="keranjang-belanja.html" class="button sky medium" title="keranjang belanja" style="margin-top:20px"><img src="files/cart2-black.png"> ('.$jum[jum].')</a>	
				</span>
			</div>	
			<div class="clear"></div>
			';
			?>
			<!-- - - - - - - - - - - - end Navigation - - - - - - - - - - - - - -->	
			
		</div><!--/ .container-->
		
			<!-- - - - - - - - - - - - - Navigation - - - - - - - - - - - - - - -->	
	
			<nav id="navigation" class="navigation clearfix">
				<ul class="container clearfix">
		<?php
			$mainmenu = mysql_query("SELECT * FROM kategoriproduk ORDER BY id_kategori");
			while($main = mysql_fetch_array($mainmenu)){
				
				if ($_GET[module] === 'home' && $rmenu[nama_menu] === 'Home'){ $class="class='current-menu-item'";}
				elseif ($_GET[module] === 'profilkami' AND $rmenu[link] === 'profil-kami.html'){ $class="class='current-menu-item'";}
				elseif ($_GET[module] === 'semuaproduk' || $_GET[module] === 'kategoriproduk' || $_GET[module] === 'detailproduk' AND $rmenu[link] === 'semua-produk.html'){ $class="class='current-menu-item'";}
				elseif ($_GET[module] === 'semuaalbum' || $_GET[module] === 'detailalbum' AND $rmenu[link] === 'semua-album.html'){ $class="class='current-menu-item'";}
				elseif ($_GET[module] === 'semuaartikel' || $_GET[module] === 'detailartikel' || $_GET[module] === 'kategori' AND $rmenu[link] === 'semua-artikel.html'){ $class="class='current-menu-item'";}
				elseif ($_GET[module] === 'hubungikami' AND $rmenu[link] === 'hubungi-kami.html'){ $class="class='current-menu-item'";}
				else { $class="";}

				echo '<li '.$class.'><a href="kategoriproduk-'.$main[id_kategori].'-'.$main[kategori_seo].'.html"><label style="text-shadow: 1px 2px 5px #121c3f;">'.$main[nama_kategori].'</label></a>';
					$submenu = mysql_query("SELECT * FROM subkategori WHERE id_main='$main[id_kategori]'");
					$jumlahsub = mysql_num_rows ($submenu);
					if ($jumlahsub!=0) {
						echo '<ul>';
						while ($sm = mysql_fetch_array($submenu)){ 
							echo'<li><a href="subkategoriproduk-'.$sm[id_subkategori].'-'.$sm[subkategori_seo].'.html">'.$sm[nama_sub].'</a>';
							$subsubmenu = mysql_query("SELECT * FROM subsubkategori WHERE id_submain='$sm[id_subkategori]'");
							$jumlahsub2 = mysql_num_rows ($subsubmenu);
							if ($jumlahsub2!=0) {
							echo'<ul style="padding:4px;margin-top:1px;">';
								while ($sub = mysql_fetch_array($subsubmenu)){ 
							echo'<li><a href="sub-subkategoriproduk-'.$sub[id_subkategori].'-'.$sub[subkategori_seo].'.html">'.$sub[nama_sub].'</a>
								</li>';
								}
							echo'</ul>';
							}	
							echo'</li>';
						}
						echo '</ul>';
					}
				echo '</li>';
			}
				echo '<li><a href="konfirmasi"><label style="text-shadow: 1px 2px 5px #121c3f;">KONFIRMASI</label></a></li>';
			echo '';
			    echo '<li><a href="tracking"><label style="text-shadow: 1px 2px 5px #121c3f;">STATUS PESANAN</label></a></li>';
			echo '';
		?>
				</ul>
			</nav>
	</header><!--/ #header-->	
<?php
	$banner  = mysql_query("SELECT * FROM banner");
	while($rbanner = mysql_fetch_array($banner)){
		$b [] = $rbanner;
	}
	echo '
	<div class="containerleft">
	<a href="'.$b[6][url].'" target="_blank"><img src="foto_banner/'.$b[6][gambar].'" alt="'.$b[6][judul].'" title="'.$b[6][judul].'" width="195"/></a>
	</div>
	
	<div class="containerright">
	<a href="'.$b[7][url].'" target="_blank"><img src="foto_banner/'.$b[7][gambar].'" alt="'.$b[7][judul].'" title="'.$b[7][judul].'" width="195"/></a>
	</div>
	
	';
		?>
	<!-- - - - - - - - - - - - - - end Header - - - - - - - - - - - - - - - - -->	
	<?php include "konten.php";?>
	
	<!-- - - - - - - - - - - - - - - Footer - - - - - - - - - - - - - - - - -->	
	
	<footer id="footer">
		
	<?php
	$qfooter = mysql_query("SELECT * FROM modul WHERE id_modul = '43'");
	$rfooter = mysql_fetch_array($qfooter);
		echo '
		<div class="container clearfix">
			
			<div class="four columns">
				<h3 class="widget-title">Alamat Kantor</h3>
				<div class="widget widget_contacts">
					<div class="vcard">
						<span class="contact street-address">'.$rfooter[alamat].'</span>
						<!-- <span class="contact tel">'.$rfooter[kontak].'</span>
						<span class="contact email">'.$rfooter[meta_deskripsi].'</span>	-->					
					</div><!--/ .vcard-->
					
				</div><!--/ .widget-->
			</div><!--/ .four-->
			<div class="four columns">
				
				<div class="widget widget_nav_menu">
					<h3 class="widget-title">Jam Buka</h3>
					   
						'.$rfooter[jam].'	

				</div>
			</div>
			<div class="four columns">

				<div class="widget widget_nav_menu">
				<h3 class="widget-title">Bantuan</h3>
					<ul>';
			$qmainmenu = mysql_query("SELECT * FROM halamanstatis ORDER BY id_halaman ASC LIMIT 3");
			while ($rmenu = mysql_fetch_array($qmainmenu)){
				$seo = seo_title($rmenu[judul]);
				echo ' <li><a href="statis-'.$rmenu[id_halaman].'-'.$seo.'.html">'.$rmenu[judul].'</a></li>';
			}
				echo '
					</ul>
			   </div><!--/ .widget-->
			</div><!--/ .four-->
			
			<div class="four columns">
				<div class="widget widget_nav_menu">
					<h3 class="widget-title">Pemesanan</h3>
						<ul>';
			$qmainmenu = mysql_query("SELECT * FROM halamanstatis ORDER BY id_halaman DESC LIMIT 4");
			while ($rmenu = mysql_fetch_array($qmainmenu)){
				$seo = seo_title($rmenu[judul]);
				echo ' <li><a href="statis-'.$rmenu[id_halaman].'-'.$seo.'.html">'.$rmenu[judul].'</a></li>';
			}
				echo '
					</ul>
				</div>
			</div>
			
			<div class="five columns">
				<div class="copyright">&nbsp;</div>
			</div><!--/ .container-->
			<div class="twelve columns">
				<div class="copyright">Copyright 2015 <a href="http://aksamedia.co.id" target="_blank" style="color:aliceblue;">Jasa Pembuatan Website</a></div>
			</div><!--/ .container-->
			
		</div>
		';
	?>
	</footer><!--/ #footer-->
	
	<!-- - - - - - - - - - - - - - end Footer - - - - - - - - - - - - - - - -->			

<!--[if lt IE 9]>
	<script src="js/selectivizr-and-extra-selectors.min.js"></script>
<![endif]-->
<script src="<?php echo "$f[folder]/"; ?>js/respond.min.js"></script>

 <!-- JQUERY KENBURN SLIDER  -->	
<script src="<?php echo "$f[folder]/"; ?>rs-plugin/js/jquery.themepunch.plugins.min.js"></script>			
<script src="<?php echo "$f[folder]/"; ?>rs-plugin/js/jquery.themepunch.revolution.min.js"></script>	
<script src="<?php echo "$f[folder]/"; ?>js/jquery.easing.1.3.js"></script>
<script src="<?php echo "$f[folder]/"; ?>js/jquery.cycle.all.min.js"></script>
<script src="<?php echo "$f[folder]/"; ?>js/mediaelement-and-player.min.js"></script>
<script src="<?php echo "$f[folder]/"; ?>fancybox/jquery.fancybox.pack.js"></script>
<script src="<?php echo "$f[folder]/"; ?>js/custom.js"></script>
</body>
</html>