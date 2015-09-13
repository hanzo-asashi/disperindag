<ol class="breadcrumb bc-3">
    <li>
        <a href="/admin">Beranda</a>
    </li>

    <li class="active">
        <strong><?php $this->breadcrumbs = array(AdminModule::t("Berita")); ?></strong>
    </li>
    <?php
        if (AdminModule::isAdmin()) {
            $this->layout = '/layouts/column2';
        }
    ?>
</ol>
<div class="mail-env">
    <!-- Mail Body -->
    <div class="mail-body">
        <div class="mail-header">
            <!-- title -->
            <h3 class="mail-title">
                Semua Berita
                <span class="count">(6)</span>
            </h3>
            <!-- search -->
            <form method="get" role="form" class="mail-search">
                <div class="input-group">
                    <input type="text" class="form-control" name="s" placeholder="Cari berita..."/>

                    <div class="input-group-addon">
                        <i class="entypo-search"></i>
                    </div>
                </div>
            </form>
        </div>
        <!-- mail table -->
        <?php
            //            $this->widget('zii.widgets.grid.CGridView', array(
            //                'dataProvider' => $dataProvider,
            //                'columns'      => array(
            //                    array(
            //                        'name'  => 'berita_id',
            //                        'type'  => 'raw',
            //                        'value' => 'CHtml::link(CHtml::encode($data->judul),array("admin/berita/view","id"=>$data->berita_id))',
            //                    ),
            //                    'judul',
            //                    'kategori_id',
            //                    'is_publish',
            //                    'is_draft',
            //                ),
            //            ));
        ?>

        <table class="table mail-table">
            <!-- mail table header -->
            <thead>
            <tr>
                <th width="5%">
                    <div class="checkbox checkbox-replace">
                        <input type="checkbox"/>
                    </div>
                </th>
                <th colspan="4">
                    <div class="mail-select-options">
                        <button class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i>&nbsp; Hapus</button>
                    </div>
                    <div class="mail-select-options">
                        <button class="btn btn-default btn-sm"><i class="entypo-archive"></i>&nbsp; Arsipkan</button>
                    </div>
                    <div class="mail-select-options">
                        <button class="btn btn-default btn-sm"><i class="entypo-install"></i>&nbsp; Pindah ke Draf
                        </button>
                    </div>
                    <div class="mail-pagination" colspan="2">
                        <strong>1-30</strong> <span>dari 79</span>

                        <div class="btn-group">
                            <a href="#" class="btn btn-sm btn-white"><i class="entypo-left-open"></i></a>
                            <a href="#" class="btn btn-sm btn-white"><i class="entypo-right-open"></i></a>
                        </div>
                    </div>
                </th>
            </tr>
            </thead>

            <!-- email list -->
            <tbody>
            <?php foreach ($dataProvider->data as $key =>$value ) { ?>
            <tr class="alert-empty"><!-- empty -->
                <td colspan="4" align="center">
                    Belum Ada Berita
                </td>
            </tr>
                <tr class="unread"><!-- new email class: unread -->
                    <td>
                        <div class="checkbox checkbox-replace">
                            <input type="checkbox"/>
                        </div>
                    </td>

                    <td class="col-name">
                        <a href="<?= !empty($value['url']) ? $value['url'].$value['berita_id'] : ""?>" class="col-name"><?= !empty($value['judul']) ? Helper::ambil_kata($value['judul'],5)."....." : ""?></a>

                    </td>
                    <td class="col-name">
                        <?= !empty($value['isi_berita']) ? Helper::ambil_kata($value['isi_berita'],5) . " ..." : ""?>
                    </td>
                    <td class="col-options">
                        <?= !empty($value['tags_id']) ? $value['tags_id'] : ""?>
                    </td>
                    <td class="col-time"><?= !empty($value['tgl_berita']) ? Helper::timeGo($value['tgl_berita']) : ""?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Sidebar -->
    <div class="mail-sidebar">

        <!-- compose new email button -->
        <div class="mail-sidebar-row hidden-xs">
            <a href="/admin/berita/create" class="btn btn-success btn-icon btn-block">
                Tambah Baru
                <i class="entypo-pencil"></i>
            </a>
        </div>

        <!-- menu -->
        <ul class="mail-menu">
            <li class="active">
                <a href="/admin/berita">
                    Semua Berita
                </a>
            </li>

            <li>
                <a href="/admin/berita/draft">
                    <span class="badge badge-gray pull-right">1</span>
                    Draft
                </a>
            </li>

            <li>
                <a href="/admin/berita/archive">
                    Arsip
                </a>
            </li>
        </ul>
    </div>
</div>
<hr>
<?php
    Yii::app()->clientScript->registerScript('berita', '
        $(".nav-blog").addClass("opened");
        $(".nav-blog ul li:nth-child(1)").addClass("active");

        var content = $(".table tbody tr td .checkbox").length;
        if (content == 0) {
            $(".alert-empty").show();
        };
    ', CClientScript::POS_END);
?>