<?php
// Halaman utama (Home)
if ($_GET[module]=='home'){
	include "modul/home.php";
}
elseif ($_GET[module]=='semuaproduk'){
	include "modul/produk.php";
}
//album
elseif ($_GET[module]=='semuaalbum'){
	include "modul/album.php";
}
elseif ($_GET[module]=='detailalbum'){
	include "modul/detailalbum.php";
}
//Hubungi Kami
elseif ($_GET[module]=='hubungikami'){
	include "modul/hubungi.php";
}
//Detail Produk
elseif ($_GET[module]=='detailproduk'){
	include "modul/detailproduk.php";
}
// Modul profil
elseif ($_GET[module]=='profilkami'){
	include "modul/profil.php";
}
elseif ($_GET[module]=='detailkategori'){
  include "modul/detailkategori.php";
}
elseif ($_GET[module]=='kategori'){
  include "modul/kategori.php";
}
elseif ($_GET[module]=='kategoriproduk'){
  include "modul/kategoriproduk.php";
}
elseif ($_GET[module]=='keranjangbelanja'){
	include "modul/cart.php";
}
elseif ($_GET[module]=='semuaartikel'){
	include "modul/berita.php";
}
elseif ($_GET[module]=='detailartikel'){
	include "modul/detailartikel.php";
}
elseif ($_GET[module]=='halamanstatis'){
	include "modul/halamanstatis.php";
}
elseif ($_GET[module]=='detailsubkategori'){
	include "modul/subkategoriproduk.php";
}
elseif ($_GET[module]=='detailsubsubkategori'){
	include "modul/subsubkategoriproduk.php";
}
elseif ($_GET[module]=='checkout'){
	include "modul/checkout.php";
}
elseif ($_GET[module]=='login'){
	include "modul/login.php";
}
elseif ($_GET[module]=='account'){
    include "modul/account.php";
}
elseif ($_GET[module]=='accountdetail'){
    include "modul/accountdetail.php";
}
elseif ($_GET[module]=='opsipembayaran'){
    include "modul/konfirmasiopsi.php";
}
elseif ($_GET[module]=='kodepembayaran'){
    include "modul/konfirmasikode.php";
}
elseif ($_GET[module]=='konfirmasipembayaran'){
    include "modul/konfirmasipembayaran.php";
}
// Modul hasil pencarian produk 
elseif ($_GET['module']=='hasilcari'){
    include "modul/cari.php";
}
//Modul Form Tracking
elseif ($_GET[module]=='tracking'){
    include "modul/tracking.php";
    if($_GET[status] == 'ordernotfound')
    {
        echo '<script language="javascript">';
        echo 'alert("Pesanan Tidak Ditemukan")';
        echo '</script>';
    }
}

?>