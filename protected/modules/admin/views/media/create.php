<ol class="breadcrumb bc-3">
    <li>
        <a href="index.php">Beranda</a>
    </li>

    <li class="active">
        <strong>Media</strong>
    </li>
</ol>

<h2>Tambah Media</h2>
<br />

<form action="data/upload-file.php" class="dropzone" id="dropzone_example">
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
	<script>
		$('.nav-media').addClass('opened');
		$('.nav-media ul li:nth-child(2)').addClass('active');
	</script>
" ?>