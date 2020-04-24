<?php
	$koneksi =  mysqli_connect("www.db4free.net","suarnata","raykingm","projecta");
	if(mysqli_connect_errno()){
		printf ("Gagal terkoneksi : ".mysqli_connect_error());
		exit();
	}
?>