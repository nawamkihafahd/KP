<section class="container" style="background-color:#fff">	
<!-- - - - - - - - - - - - - Slider - - - - - - - - - - - - - - - -->	
		<div class="fullwidthbanner-container" style="top:20px;">
			<div class="fullwidthbanner">
				<ul>	
			<?php
				$slider  = mysql_query("SELECT * FROM slider WHERE status='Y' ORDER BY jenis_slider LIMIT 4");
				while($rslider = mysql_fetch_array($slider)){
					echo '
					<li data-transition="fade" data-slotamount="7" data-thumb="foto_slider/large_'.$rslider[gambar].'"> 
						<img src="foto_slider/large_'.$rslider[gambar].'" alt="'.$rslider[judul].'" title="'.$rslider[judul].'">	
					</li>';
				}
			?>	
				</ul>
			</div><!--/ .fullwidthbanner-->	
		</div>
	<!-- - - - - - - - - - - - - end Slider - - - - - - - - - - - - - - -->
	<div class="border-divider"></div>
		
<?php
	$banner  = mysql_query("SELECT * FROM banner");
	while($rbanner = mysql_fetch_array($banner)){
		$b [] = $rbanner;
	}
	
	echo '
		<div class="eleven columns">
			<div class="detailimg">
				<a class="single-image" href="'.$b[0][url].'" target="_blank"><img src="foto_banner/'.$b[0][gambar].'" alt="'.$b[0][judul].'" title="'.$b[0][judul].'" /></a>
			</div><!--/ .detailimg-->
			';
	for ($i=1;$i<3;$i++) {
		echo '
		<div class="one-third column">
			<div class="detailimg">
				<div class="bordered">
						<a class="single-image" href="'.$b[$i][url].'" target="_blank"><img src="foto_banner/'.$b[$i][gambar].'" alt="'.$b[$i][judul].'" title="'.$b[$i][judul].'" /></a>
				</div><!--/ .bordered-->
			</div><!--/ .detailimg-->
		</div><!--/ .one-third-->
		';
	}
	
	echo '		
		</div>
		<div class="five columns">
			
			<div class="box-toggle">
				<div class="toggle-isi">
					
					<div class="detailimg">
						<a class="single-image" href="'.$b[8][url].'" target="_blank"><img src="foto_banner/'.$b[8][gambar].'" alt="'.$b[8][judul].'" title="'.$b[8][judul].'" /></a>
					</div>
				</div>
			</div>
		</div>
		
		<div class="sixteen columns">
			<div class="detailimg">
				<div class="bordered">
				<a class="single-image" href="'.$b[3][url].'" target="_blank"><img src="foto_banner/'.$b[3][gambar].'" alt="'.$b[3][judul].'" title="'.$b[3][judul].'" /></a>
				</div><!--/ .bordered-->
			</div><!--/ .detailimg-->
		</div>
		
		<div class="ten columns">
			<div class="detailimg">
				<div class="bordered">
				<a class="single-image" href="'.$b[4][url].'" target="_blank"><img src="foto_banner/'.$b[4][gambar].'" alt="'.$b[4][judul].'" title="'.$b[4][judul].'" /></a>
				</div><!--/ .bordered-->
			</div><!--/ .detailimg-->
		</div>
		<div class="six columns">
			<div class="detailimg">
				<div class="bordered">
				<a class="single-image" href="'.$b[5][url].'" target="_blank"><img src="foto_banner/'.$b[5][gambar].'" alt="'.$b[5][judul].'" title="'.$b[5][judul].'" /></a>
				</div><!--/ .bordered-->
			</div><!--/ .detailimg-->
		</div>
	';
?>	
		<div class="clear"></div>
</section>