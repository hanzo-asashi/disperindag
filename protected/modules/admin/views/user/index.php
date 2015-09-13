<ol class="breadcrumb bc-3">
    <li>
        <a href="/admin">Beranda</a>
    </li>

    <li class="active">
        <strong><?= AdminModule::t("Pengguna");?></strong>
    </li>
    <?php
    if(AdminModule::isAdmin()) {
        $this->layout='/layouts/column2';
    }
    ?>
</ol>

<h2><?= AdminModule::t("Pengguna"); ?></h2>
<br />

<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="panel-title"><?= AdminModule::t("Daftar Pengguna"); ?></div>

        <div class="panel-options">
            <a href="/admin/user/registration" class="bg"><i class="entypo-user-add"></i><?= AdminModule::t('Tambah User');?></a>
            <a href="/admin/user/admin" class="bg"><i class="entypo-users"></i><?= AdminModule::t('Pengaturan Pengguna');?></a>
            <a href="/admin/profilefield/admin" class="bg"><i class="entypo-user"></i><?= AdminModule::t('Atur Profile User');?></a>
        </div>
    </div>

    <?php
//	    $this->widget('zii.widgets.grid.CGridView', array(
//        'dataProvider'=>$dataProvider,
//        'columns'=>array(
//            array(
//                'name' => 'username',
//                'type'=>'raw',
//                'value' => 'CHtml::link(CHtml::encode($data->username),array("user/view","id"=>$data->id))',
//            ),
//            'create_at',
//            'lastvisit_at',
//        ),
    //));
    ?>
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
                <td><a href="/admin/users?hapus" data-toggle="modal" data-target="#modal-hapus" class="bg"><i class="fa fa-trash-o"></i> &nbsp;Hapus</a></td>
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