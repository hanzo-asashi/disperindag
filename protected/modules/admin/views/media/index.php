<ol class="breadcrumb bc-3">
    <li>
        <a href="index.php">Beranda</a>
    </li>

    <li class="active">
        <strong>Media</strong>
    </li>
</ol>

<h2>Semua Media</h2>
<br />

<div class="gallery-env">

    <div class="row">

        <div class="col-sm-12">

            <hr>

            <div class="image-categories">
                <span>Filter Images:</span>
                <a href="#" class="active" data-filter="all">Show All</a> /
                <a href="#" data-filter="1d">Taken today</a> /
                <a href="#" data-filter="3d">Taken three days ago</a> /
                <a href="#" data-filter="1w">Taken a week ago</a>
            </div>
        </div>

    </div>

    <div class="row">

        <?php for ($i = 0; $i < 18; $i++) { ?>

            <div class="col-sm-2 col-xs-4" data-tag="1d">

                <article class="image-thumb">

                    <a href="#" class="image">
                        <img src="assets/images/album-image.jpg" />
                    </a>

                    <div class="image-options">
                        <a href="javascript:;" onclick="jQuery('#modal-hapus').modal('show');" class="delete"><i class="entypo-cancel"></i></a>
                    </div>

                </article>

            </div>

        <?php } ?>

        <center>
            <ul class="pagination">
                <li><a href="#"><i class="entypo-left-open-mini"></i></a></li>
                <li><a href="#">1</a></li>
                <li class="active"><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li class="disabled"><a href="#">4</a></li>
                <li><a href="#">5</a></li>
                <li><a href="#">6</a></li>
                <li><a href="#"><i class="entypo-right-open-mini"></i></a></li>
            </ul>
        </center>

    </div>

</div>



<hr />

<h3>
    Tambah Gambar
    <br />
    <small>Silahkan unggah lebih banyak gambar disini.</small>
</h3>

<br />

<form action="data/upload-file.php" class="dropzone dz-min" id="dropzone_example">
    <div class="fallback">
        <input name="file" type="file" multiple />
    </div>
</form>

<div id="dze_info" class="hidden">

    <br />
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">Informasi gambar</div>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width="40%">Nama gambar</th>
                    <th width="15%">Ukuran</th>
                    <th width="15%">Tipe</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?php $registerscript = "
	<!-- Modal 1 (Basic)-->
	<div class='modal fade' id='modal-hapus'>
		<div class='modal-dialog'>
			<div class='modal-content'>

				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
					<h4 class='modal-title'>Hapus Gambar</h4>
				</div>
				
				<div class='modal-body'>
					Apakah Anda yakin menghapus gambar ini? Gambar ini sedang terpakai di:
					<br>
					<br>
					<a class='a' href=''>http://localhost/disperindag-design/admin/media.php#</a>
					<br>
					<a class='a' href=''>http://localhost/disperindag-design/admin/media.php#</a>
				</div>
				
				<div class='modal-footer no-margin'>
					<button type='button' class='btn btn-default' data-dismiss='modal'>Kembali</button>
					<button type='button' class='btn btn-danger'>Hapus</button>
				</div>

			</div>
		</div>
	</div>

	<script>
		$('.nav-media').addClass('opened');
		$('.nav-media ul li:nth-child(1)').addClass('active');
	</script>
" ?>