<?php
require 'function.php';
require 'cek.php';
$stock = 'stock';
$ambilsemuadatastock = mysqli_query($conn, "select * from stock");


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.php">Klinik Telcomedical</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>

    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav" id="nav">
                        <a class="nav-link <?php if ($stock == "stock") echo "active"; ?>" name="stock" href="index.php">
                            <div class="sb-nav-link-icon" id="stock"><i class="fas fa-home-alt"></i></div>
                            Dashboard
                        </a>
                        <a class="nav-link " href="masuk.php" name="masuk">
                            <div class="sb-nav-link-icon "><i class="fas fa-house-person-return"></i></div>
                            Obat Masuk
                        </a>
                        <a class="nav-link " href="keluar.php" name="keluar">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Obat Keluar
                        </a>
                        <a class="nav-link" href="logout.php" data-toggle="modal" data-target="#log">
                            Logout
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    Aditya Wibowo
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Dashboard</h1>

                    <div class="card">
                        <div class="card-header">
                            <!-- Button to Open the Modal -->
                            <div class="row">

                                <div class="col-sm-11">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                        Tambah Obat
                                    </button>
                                </div>
                                <div class="col-sm-1">

                                    <a class="d-inline-flex p-2 position-absolute " onclick="window.print()" style="text-align:right;"><i class='fas fa-print mb-3' style="font-size:24px;color:red;"></i></a>
                                </div>
                            </div>

                        </div>
                        <div class="card-header">

                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Obat</th>
                                        <th>Deskripsi</th>
                                        <th>Tipe Obat</th>
                                        <th>Tanggal Kadaluasa</th>
                                        <th>Stock</th>
                                        <th>barcode</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $ambilsemuadatastock = mysqli_query($conn, "select * from stock");
                                    $i = 1;
                                    $tgl_indo = date('d F, Y');
                                    // $alert = ;
                                    while ($data = mysqli_fetch_array($ambilsemuadatastock)) {
                                        $namaobat = $data['namaobat'];
                                        $deskripsi = $data['deskripsi'];
                                        $keteranganObt = $data['keteranganObt'];
                                        $tgl_kadarluasa = $data['tgl_kadarluasa'];
                                        $stock = $data['stock'];
                                        $barcode = $data['barcode'];
                                        $idb = $data['idobat'];


                                    ?>



                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td><?= $namaobat; ?></td>
                                            <td><?= $deskripsi; ?></td>
                                            <td><?= $keteranganObt; ?></td>
                                            <td><?= date('d-m-Y', strtotime($data["tgl_kadarluasa"])) ?></td>
                                            <td><?= $stock; ?></td>
                                            <td>
                                                <img src="barcode.php?codetype=Code39&size=20&text=<?= $barcode; ?>&print=true" alt="barcode" />
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit<?= $data['idobat']; ?>">
                                                    Edit
                                                </button>
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?= $data['idobat']; ?>">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Edit modal -->
                                        <div class="modal fade" id="edit<?= $idb; ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Edit </h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <form method="post">
                                                        <div class="modal-body">
                                                            <input type="text" name="namaobat" value="<?= $namaobat; ?>" class="form-control">
                                                            <br>
                                                            <input type="text" name="deskripsi" value="<?= $deskripsi; ?>" class="form-control">
                                                            <br>
                                                            <input type="text" name="keteranganObt" value="<?= $keteranganObt; ?>" class="form-control">
                                                            <br>
                                                            <input type="date" name="tgl_kadarluasa" value="<?= $tgl_kadarluasa; ?>" class="form-control" placeholder="tanggal kadarluasa">
                                                            <br>
                                                            <input type="hidden" name="idb" value="<?= $idb; ?>">
                                                            <button type="submit" class="btn btn-primary" name="updateobat">Submit</button>
                                                            <input type="hidden" class="btn btn-danger" data-toggle="modal" data-target="#alert">
                                                            Delete
                                                            </input>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delete modal -->
                                        <div class="modal fade" id="delete<?= $idb; ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Hapus?</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <form method="post">
                                                        <div class="modal-body">
                                                            Apakah Anda Yakin Ingin Menghapus <?= $namaobat; ?>?
                                                            <input type="hidden" name="idb" value="<?= $idb; ?>">
                                                            <br>
                                                            <br>
                                                            <button type="submit" class="btn btn-danger" name="hapusobat">Submit</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>

                                    <?php
                                    };

                                    ?>

                                    <!-- Edit modal -->
                                    <div class="modal fade" id="edit<?= $idb; ?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Edit </h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>

                                                <!-- Modal body -->
                                                <form method="post">
                                                    <div class="modal-body">
                                                        <input type="text" name="namaobat" value="<?= $namaobat; ?>" class="form-control">
                                                        <br>
                                                        <input type="text" name="deskripsi" value="<?= $deskripsi; ?>" class="form-control">
                                                        <br>
                                                        <input type="hidden" name="idb" value="<?= $idb; ?>">
                                                        <button type="submit" class="btn btn-primary" name="updateobat">Submit</button>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete modal -->
                                    <div class="modal fade" id="delete<?= $idb; ?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Hapus?</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>

                                                <!-- Modal body -->
                                                <form method="post">
                                                    <div class="modal-body">
                                                        Apakah Anda Yakin Ingin Menghapus <?= $namaobat; ?>?
                                                        <input type="hidden" name="idb" value="<?= $idb; ?>">
                                                        <br>
                                                        <br>
                                                        <button type="submit" class="btn btn-danger" name="hapusobat">Submit</button>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2023</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>
<!-- The Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Obat</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <form method="post">
                <div class="modal-body">
                    <input type="text" name="namaobat" placeholder="Nama obat" class="form-control" required>
                    <br>
                    <input type="text" name="deskripsi" placeholder="Deskripsi obat" class="form-control" required>
                    <br>
                    <select name="keteranganObt" class="custom-select" placeholder="USIA"> required >usia
                        <option selected>USIA</option>
                        <option value="0 - 2 TAHUN">0 - 2 TAHUN</option>
                        <option value="2 - 5 TAHUN">2 - 5 TAHUN</option>
                    </select>
                    <br>
                    <br>
                    <input type="text" name="tgl_kadarluasa" class="form-control" onfocus="(this.type='date')" onblur="(this.type='text')" id="date"" placeholder=" Tanggal Kadarluasa" required>
                    <br>
                    <button type="submit" class="btn btn-primary" name="addnewobat">Submit</button>
                </div>
            </form>

        </div>
    </div>
</div>


<!-- <script>
    $(document).ready(function() {
        $('select').on('change', function() {
            // alert(this.value);
            let val = this.value
            $.get("data.php", function(data, status) {
                let obat = JSON.parse(data);
                $.each(obat, function(index, value) {
                    console.log(val === value.namaobat);
                    console.log($('#stock'));
                    if (val === value.namaobat) {
                        $("#add-stock").val(value.stock)
                        $("#desc").val(value.deskripsi)
                    }
                });
                console.log(obat);
            })
            // $.ajax({
            //     type: 'GET',
            //     url: 'data.php',
            //     dataType: "json",
            //     success: function(data) {
            //         console.log(data.status);
            //     },
            //     error: 
            // })
        });
    });
</script> -->


<!-- query with -->

<!-- The Modal -->
<div class="modal fade" id="log">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda Ingin Keluar ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="document.location='logout.php'">Keluar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
            </div>
        </div>
    </div>
</div>


<!-- modal alart -->
<div class="modal fade" id="alert">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda Ingin Keluar ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="document.location='logout.php'">Keluar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
            </div>
        </div>
    </div>
</div>

</html>