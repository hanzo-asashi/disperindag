<ol class="breadcrumb bc-3">
    <li>
        <a href="/admin">Beranda</a>
    </li>

    <li class="active">
        <strong>Tambah Pengguna</strong>
    </li>
</ol>

<h2>Tambah Pengguna</h2>
<br />

<form role="form" method="post" class="form-horizontal form-groups-bordered validate" action="">
    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-primary" data-collapsed="0">

                <div class="panel-body">

                    <form role="form" class="form-horizontal form-groups-bordered validate">

                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label">Nama Lengkap</label>

                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="name" value="" data-validate="required" data-message-required="Wajib diisi">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label">Nama Pengguna</label>

                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="username" value="" data-validate="required" data-message-required="Wajib diisi">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label">Kata Sandi</label>

                            <div class="col-sm-5">
                                <input type="password" class="form-control" name="password" value="" data-validate="required" data-message-required="Wajib diisi">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label">Ulangi Kata Sandi</label>

                            <div class="col-sm-5">
                                <input type="password" class="form-control" name="repassword" value="" data-validate="required" data-message-required="Wajib diisi">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-5">
                                <button type="submit" class="btn btn-success">Simpan</button>
                            </div>
                        </div>
                    </form>

                </div>

            </div>

        </div>
    </div>

</form>


<?php $registerscript = "
	<script>
		$('.nav-user').addClass('opened');
		$('.nav-user ul li:nth-child(2)').addClass('active');
	</script>
" ?>