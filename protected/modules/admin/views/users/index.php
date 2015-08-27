<ol class="breadcrumb bc-3">
    <li>
        <a href="index.php">Beranda</a>
    </li>

    <li class="active">
        <strong>Pengguna</strong>
    </li>
</ol>

<h2>Pengguna</h2>
<br />

<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="panel-title">Daftar Pengguna</div>

        <div class="panel-options">
            <a href="user-new.php" class="bg"><i class="entypo-plus"></i>Tambah Baru</a>
        </div>
    </div>

    <table class="table table-bordered table-responsive">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Lengkap</th>
                <th>Nama Pengguna</th>
                <th>Kata Sandi</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>1</td>
                <td>Gifa Eriyanto</td>
                <td>Abu Sulaim</td>
                <td>***********</td>
                <td><a href="#" data-toggle="modal" data-target="#modal-hapus" class="bg"><i class="fa fa-trash-o"></i> &nbsp;Hapus</a></td>
            </tr>

            <tr>
                <td>2</td>
                <td>Gifa Eriyanto</td>
                <td>Abu Sulaim</td>
                <td>***********</td>
                <td><a href="#" data-toggle="modal" data-target="#modal-hapus" class="bg"><i class="fa fa-trash-o"></i> &nbsp;Hapus</a></td>
            </tr>
        </tbody>
    </table>
</div>

<?php $registerscript = "
	<!-- Modal 1 (Basic)-->
	<div class='modal fade' id='modal-hapus'>
		<div class='modal-dialog'>
			<div class='modal-content'>

				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
					<h4 class='modal-title'>Hapus Pengguna</h4>
				</div>
				
				<div class='modal-body'>
					Apakah Anda yakin menghapus pengguna ini?
				</div>
				
				<div class='modal-footer no-margin'>
					<button type='button' class='btn btn-default' data-dismiss='modal'>Kembali</button>
					<button type='button' class='btn btn-danger'>Hapus</button>
				</div>

			</div>
		</div>
	</div>

	<script>
		$('.nav-user').addClass('opened');
		$('.nav-user ul li:nth-child(1)').addClass('active');
	</script>
" ?>