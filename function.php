<?php
session_start();
//membuat koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "inventory_obat");


//menambah obat baru
if (isset($_POST['addnewobat'])) {
    $namaobat = $_POST['namaobat'];
    $deskripsi = $_POST['deskripsi'];
    $keteranganObt = $_POST['keteranganObt'];
    $tgl_kadarluasa = $_POST['tgl_kadarluasa'];
    $barcode = rand(100000, 999999);

    // $stock = $_POST['stock'];

    // buat nilai DEFAULT untuk tipe obat
    if ($keteranganObt == "USIA") {
        $keteranganObt = "0 - 2 TAHUN";
    }

    $ambilsemuadatastock = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM stock WHERE namaobat='$namaobat'"));
    if ($ambilsemuadatastock > 0) {
        header("location: index.php?added=false");
    } else {
        $addtotable = mysqli_query($conn, "INSERT INTO stock (namaobat, deskripsi, keteranganObt, tgl_kadarluasa, barcode) VALUES('$namaobat', '$deskripsi', '$keteranganObt', '$tgl_kadarluasa', '$barcode')");
        header("location: index.php?added=true");
    }
}


//Menambah obat masuk
if (isset($_POST['obatmasuk'])) {
    $obatnya = $_POST['obatnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstockobat = mysqli_query($conn, "SELECT * FROM stock WHERE idobat='$obatnya'");
    $ambildatanya = mysqli_fetch_array($cekstockobat);

    $cekstockobatmasuk = $cekstockobat = mysqli_query($conn, "SELECT * FROM masuk WHERE idobat='$obatnya'");
    if (mysqli_num_rows($cekstockobatmasuk) > 0) {
        header("location: masuk.php?added=false");
    } else {
        $stocksekarang = $ambildatanya['stock'];
        $tambahkanstockbaranydenganquantity = $stocksekarang + $qty;


        $addtomasuk = mysqli_query($conn, "INSERT INTO masuk (idobat, keterangan, qty) VALUES('$obatnya','$penerima','$qty')");
        $updatestockmasuk = mysqli_query($conn, "UPDATE stock SET stock='$tambahkanstockbaranydenganquantity' WHERE idobat='$obatnya'");
        header("location: masuk.php?added=true");
    }
}


//Menambah obat keluar
if (isset($_POST['addobatkeluar'])) {
    $obatnya = $_POST['obatnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstockobat = mysqli_query($conn, "SELECT * FROM stock WHERE idobat='$obatnya'");
    $cekstockobatkeluar = mysqli_query($conn, "SELECT * FROM keluar WHERE idobat='$obatnya'");
    $ambildatanya = mysqli_fetch_array($cekstockobat);

    if (mysqli_num_rows($cekstockobatkeluar) > 0) {
        header("location: keluar.php?added=false");
    } else {
        $stocksekarang = $ambildatanya['stock'];
        $tambahkanstockbaranydenganquantity = $stocksekarang - $qty;


        $addtokeluar = mysqli_query($conn, "INSERT INTO keluar (idobat, penerima, qty) VALUES('$obatnya','$penerima','$qty')");
        $updatestockmasuk = mysqli_query($conn, "UPDATE stock SET stock='$tambahkanstockbaranydenganquantity' WHERE idobat='$obatnya'");
        header("location: keluar.php?added=true");
        
    }
}


// update info obat
if (isset($_POST['updateobat'])) {
    $idb = $_POST['idb'];
    $namaobat = $_POST['namaobat'];
    $deskripsi = $_POST['deskripsi'];
    $tgl_kadarluasa = $_POST['tgl_kadarluasa'];

    $update = mysqli_query($conn, "UPDATE stock SET namaobat ='$namaobat', deskripsi='$deskripsi', tgl_kadarluasa ='$tgl_kadarluasa' WHERE idobat ='$idb'");
    if ($update) {
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    }
}

//menghapus obat dari stock
if (isset($_POST['hapusobat'])) {
    $idb = $_POST['idb'];

    $hapus = mysqli_query($conn, "delete FROM stock WHERE idobat='$idb'");
    if ($hapus) {
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    }
};


// mengubah data obat masuk
if (isset($_POST['updateobatmasuk'])) {
    $idb = $_POST['idb'];
    $idm = $_POST['idm'];
    $deskripsi = $_POST['keterangan'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn, "SELECT * FROM stock WHERE idobat='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    $qtyskrg = mysqli_query($conn, "SELECT * FROM masuk WHERE idmasuk='$idm'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];


    if ($qty > $qtyskrg) {
        $selisih = $qty - $qtyskrg;
        $kurangin = $stockskrg + $selisih;
        $kurangistocknya = mysqli_query($conn, "UPDATE stock SET stock= '$kurangin' WHERE idobat='$idb'");
        $updatenya = mysqli_query($conn, "UPDATE masuk SET qty='$qty', keterangan='$deskripsi' WHERE idmasuk='$idm'");
        if ($kurangistocknya && $updatenya) {
            header('location:masuk.php');
        } else {
            echo 'Gagal';
            header('location:masuk.php');
        }
    } else {
        $selisih = $qtyskrg - $qty;
        $kurangin = $stockskrg - $selisih;
        $kurangistocknya = mysqli_query($conn, "UPDATE stock SET stock = '$kurangin' WHERE idobat = '$idb'");
        $updatenya = mysqli_query($conn, "UPDATE masuk SET qty = '$qty', keterangan = '$deskripsi' WHERE idmasuk = '$idm'");
        if ($kurangistocknya && $updatenya) {
            header('location:masuk.php');
        } else {
            echo 'Gagal';
            header('location:masuk.php');
        }
    }
}


//menghapus obat masuk
if (isset($_POST['hapusobatmasuk'])) {
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idm = $_POST['idm'];

    $getdatastock = mysqli_query($conn, "SELECT * FROM stock WHERE idobat='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stok = $data['stock'];

    $selisih = $stok - $qty;

    $update = mysqli_query($conn, "UPDATE stock SET stock='$selisih' WHERE idobat='$idb'");
    $hapusdata = mysqli_query($conn, "delete FROM masuk WHERE idmasuk='$idm'");

    if ($update && $hapusdata) {
        header('location:masuk.php');
    } else {
        header('location:masuk.php');
    }
}


//mengubah data obat keluar
if (isset($_POST['updateobatkeluar'])) {
    $idb = $_POST['idb'];
    $idk = $_POST['idk'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn, "SELECT * FROM stock WHERE idobat='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    $qtyskrg = mysqli_query($conn, "SELECT * FROM keluar WHERE idkeluar='$idk'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];

    if ($qty > $qtyskrg) {
        $selisih = $qty - $qtyskrg;
        $kurangin = $stockskrg - $selisih;
        $kurangistocknya = mysqli_query($conn, "UPDATE stock SET stock= '$kurangin' WHERE idobat='$idb'");
        $updatenya = mysqli_query($conn, "UPDATE keluar SET qty='$qty', penerima='$penerima' WHERE idkeluar='$idk'");
        if ($kurangistocknya && $updatenya) {
            header('location:keluar.php');
        } else {
            echo 'Gagal';
            header('location:keluar.php');
        }
    } else {
        $selisih = $qtyskrg - $qty;
        $kurangin = $stockskrg + $selisih;
        $kurangistocknya = mysqli_query($conn, "UPDATE stock SET stock= '$kurangin' WHERE idobat='$idb'");
        $updatenya = mysqli_query($conn, "UPDATE keluar SET qty='$qty', penerima='$penerima' WHERE idkeluar='$idk'");
        if ($kurangistocknya && $updatenya) {
            header('location:keluar.php');
        } else {
            echo 'Gagal';
            header('location:keluar.php');
        }
    }
}


//menghapus obat keluar
if (isset($_POST['hapusobatkeluar'])) {
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idk = $_POST['idk'];

    $getdatastock = mysqli_query($conn, "SELECT * FROM stock WHERE idobat='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stok = $data['stock'];

    $selisih = $stok + $qty;
    $update = mysqli_query($conn, "UPDATE stock SET stock='$selisih' WHERE idobat='$idb'");
    $hapusdata = mysqli_query($conn, "delete FROM keluar WHERE idkeluar='$idk'");

    if ($update && $hapusdata) {
        header('location:keluar.php');
    } else {
        header('location:keluar.php');
    }
}

// Fungsi Retur Barang
if (isset($_POST['returbarang'])) {
    $obatnya = $_POST['obatnya'];
    $keterangan = $_POST['keterangan'];
    $qty = $_POST['qty'];

    $cekstockobat = mysqli_query($conn, "SELECT * FROM stock WHERE idobat='$obatnya'");
    $cekstockkeluar = mysqli_query($conn, "SELECT * FROM keluar WHERE idobat='$obatnya'");
    $cekstockretur = mysqli_query($conn, "SELECT * FROM retur WHERE idobat='$obatnya'");
    $ambildatanya = mysqli_fetch_array($cekstockobat);
    $ambildataqtykeluar = mysqli_fetch_array($cekstockkeluar);
    $ambildataqty = $ambildataqtykeluar['qty'];
    $ambildataqtyretur = mysqli_fetch_assoc($cekstockretur);
    
    
    if (mysqli_num_rows($cekstockretur) > 0) {
        header("location: retur.php?added=false");
    } else {
        $stocksekarang = $ambildatanya['stock'];
        $tambahkanstockbaranydenganquantity = $stocksekarang + $qty;

        $a = $ambildataqtykeluar['qty'];
        $b = $qty;
        $c = $a - $b ;

        $addtoretur = mysqli_query($conn, "INSERT INTO retur (idobat, keterangan, qty) VALUES('$obatnya','$keterangan','$qty')");
        $updatestock = mysqli_query($conn, "UPDATE stock SET stock='$tambahkanstockbaranydenganquantity' WHERE idobat='$obatnya'");
        $updatestockkeluar = mysqli_query($conn, "UPDATE keluar SET qty='$c' WHERE idobat='$obatnya'");
        header("location: retur.php?added=true");
    }
}

// mengubah data obat retur
if (isset($_POST['updateobatretur'])) {
    $idb = $_POST['idb'];
    $idk = $_POST['idk'];
    $idr = $_POST['idr'];
    $deskripsi = $_POST['keterangan'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn, "SELECT * FROM stock WHERE idobat='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    $cekstockkeluar = mysqli_query($conn,"SELECT * FROM keluar WHERE idkeluar='$idk'");
    $stockkeluar = mysqli_fetch_array($cekstockkeluar);
    $stksekarang = $stockkeluar['qty'];

    $qtyskrg = mysqli_query($conn, "SELECT * FROM retur WHERE idretur='$idr'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];



    if ($qty > $qtyskrg) {
        $selisih = $qty - $qtyskrg;
        $kurangin = $stockskrg + $selisih;
        $keluar = $qty + $stksekarang;
        $kurangistocknya = mysqli_query($conn, "UPDATE stock SET stock= '$kurangin' WHERE idobat='$idb'");
        $qtykeluar = mysqli_query($conn, "UPDATE keluar SET qty='$qty', keterangan='$deskripsi' WHERE idkeluar='$idk'");
        $updatenya = mysqli_query($conn, "UPDATE retur SET qty='$qty', keterangan='$deskripsi' WHERE idretur='$idr'");
        if ($kurangistocknya && $updatenya && $qtykeluar) {
            header('location:retur.php');
        } else {
            echo 'Gagal';
            header('location:retur.php');
        }
    } else {
        $selisih = $qtyskrg - $qty;
        $kurangin = $stockskrg - $selisih;
        $keluar = $stksekarang + $qty;
        $kurangistocknya = mysqli_query($conn, "UPDATE stock SET stock = '$kurangin' WHERE idobat = '$idb'");
        $qtykeluar = mysqli_query($conn, "UPDATE keluar SET qty='$qty', keterangan='$deskripsi' WHERE idkeluar='$idk'");
        $updatenya = mysqli_query($conn, "UPDATE retur SET qty = '$qty', keterangan = '$deskripsi' WHERE idretur = '$idr'");
        if ($kurangistocknya && $updatenya && $qtykeluar) {
            header('location:retur.php');
        } else {
            echo 'Gagal';
            header('location:retur.php');
        }
    }
}


//menghapus obat retur
if (isset($_POST['hapusobatretur'])) {
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idr = $_POST['idr'];

    $getdatastock = mysqli_query($conn, "SELECT * FROM stock WHERE idobat='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stok = $data['stock'];

    $selisih = $stok - $qty;

    $update = mysqli_query($conn, "UPDATE stock SET stock='$selisih' WHERE idobat='$idb'");
    $hapusdata = mysqli_query($conn, "delete FROM retur WHERE idretur='$idr'");

    if ($update && $hapusdata) {
        header('location:retur.php');
    } else {
        header('location:retur.php');
    }
}