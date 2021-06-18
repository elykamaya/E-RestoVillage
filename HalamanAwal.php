<?php

if (isset($_POST['add_temp'])) {
  $id_menuku = $_POST['id_menuku'];
  
  $d = mysqli_fetch_array($mysqli->query("SELECT * FROM stok WHERE id_menu=$id_menuku"));
  $sisa = $d['sisa'];
  
  if ($sisa == 0) {
    echo "<script> alert('Menu Yang Anda Pesan Sudah habis'); </script>";
  } else {
    $total = $_POST['jumlah'] * $_POST['harga'];
    $tgl = date('Y-m-d');
    $query = $mysqli->query("INSERT INTO temp_transaksi_pemesanan  (id,tgl,id_menu,id_harga,jumlah,total)
      VALUES ('','$tgl','$_POST[id_menuku]','$_POST[id_harga]','$_POST[jumlah]','$total')");
    echo "<script>window.location='pelanggan.php'</script>";
  }
}
if (isset($_POST['update_pesanan'])) {
  $jumlah_menu = count($_POST['id_menu']);
  $kd_transaksi = $_POST['kd_transaksi'];
  $no_meja =  $_POST['no_meja'];
  $atas_nama = $_POST['atas_nama'];
  $jumlah =  $_POST['jumlah'];
  $harga =  $_POST['harga'];
  $id_menu =  $_POST['id_menu'];
  // $total = $_POST['total'];
  $tgl = date('Y-m-d');


  for ($i = 0; $i < $jumlah_menu; $i++) {
    $total[$i] = $jumlah[$i] * $harga[$i];
    //   var_dump($total);
    // die();

  
    $d = mysqli_fetch_array($mysqli->query("SELECT * FROM stok WHERE id_menu=$id_menu[$i]"));
    $sisa = $d['sisa'];
    // die();
    if ($sisa > 0 && $jumlah[$i] <= $sisa) {
      $masukkan = $mysqli->query("UPDATE temp_transaksi_pemesanan set jumlah='$jumlah[$i]',total='$total[$i]' where id_menu=$id_menu[$i] ");
    } else {
      echo "<script> alert('Pesanan Anda melebihi stok yang tersedia'); </script>";
    }
  }
  echo "<script>window.location='pelanggan.php'</script>";
}

if (isset($_POST['add'])) {
  // $total = $_POST['jumlah'] * $_POST['harga'];
  $kd_transaksi = $_POST['kd_transaksi'];
  $no_meja =  $_POST['no_meja'];
  $atas_nama = $_POST['atas_nama'];
  $jumlah =  $_POST['jumlah'];
  $harga =  $_POST['harga'];
  $id_menu =  $_POST['id_menu'];
  $total = $_POST['total'];
  $tgl = date('Y-m-d');

  ///////////////////////////membuat jumlah item yang adippilih
  $jumlah_menu = count($_POST['id_menu']);

  ////////////////////query untuk save ke tabel transaksi_pemesanan
  $query = $mysqli->query("INSERT INTO transaksi_pemesanan  (id,kd_transaksi,tgl,nomer_meja,atas_nama,total)
  VALUES ('','$_POST[kd_transaksi]','$tgl','$_POST[no_meja]','$_POST[atas_nama]','$total')");
  $id_pemesanan = $mysqli->insert_id();
  for ($i = 0; $i < $jumlah_menu; $i++) {
    ///////////////////perulanagan 
    $masukkan = $mysqli->query("INSERT INTO transaksi_pemesanan_detail (id,id_pemesanan,id_menu,porsi)
    VALUES ('','$id_pemesanan', '$id_menu[$i]','$jumlah[$i]')");

    // update stok beberapa menu yg dipilih
    $update_stok = $mysqli->query("UPDATE stok set sisa=sisa-$jumlah[$i] where id_menu=$id_menu[$i]");
  }
  $delete_temp_transaksi_pemesanan = $mysqli->query("DELETE FROM temp_transaksi_pemesanan");
  // echo "<script>window.location='index.php'</script>";
  echo " <center> <div class='alert alert-success'>
            <h3><span id='TotalBayar'>Pesanan Anda Berhasil, Dan Akan segera di antar ke meja anda. Terimakasih</span></h3>
          </div> 
<meta http-equiv='refresh' content='3; url=pelanggan.php' /> ";
}
?>


    <!-- Content Header (Page header) -->
      <strong>
        <h1><CENTER><strong>WEBSITE E-RESTO VILLAGE</strong> </CENTER></h1>
      </strong>


    <?php 
    include 'banner.php';
    ?>

<?php
  include 'dataPesanan.php';
?>








