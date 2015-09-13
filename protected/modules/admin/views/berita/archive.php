<div class="mail-env">


    <!-- Mail Body -->
    <div class="mail-body">

        <div class="mail-header">
            <!-- title -->
            <h3 class="mail-title">
                Arsip
                <span class="count">(6)</span>
            </h3>

            <!-- search -->
            <form method="get" role="form" class="mail-search">
                <div class="input-group">
                    <input type="text" class="form-control" name="s" placeholder="Cari berita..." />

                    <div class="input-group-addon">
                        <i class="entypo-search"></i>
                    </div>
                </div>
            </form>
        </div>


        <!-- mail table -->
        <table class="table mail-table">
            <!-- mail table header -->
            <thead>
                <tr>
                    <th width="5%">
            <div class="checkbox checkbox-replace">
                <input type="checkbox" />
            </div>
            </th>
            <th colspan="4">

            <div class="mail-select-options"><button class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i>&nbsp; Hapus</button></div>
            <div class="mail-select-options"><button class="btn btn-default btn-sm"><i class="entypo-install"></i>&nbsp; Pindah ke Draf</button></div>

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

                <tr class="alert-empty"><!-- empty -->
                    <td colspan="4" align="center">
                        Folder Arsip Berita Kosong
                    </td>
                </tr>

                <?php for ($i = 0; $i < 10; $i++) { ?>
                    <tr><!-- new email class: unread -->
                        <td>
                            <div class="checkbox checkbox-replace">
                                <input type="checkbox" />
                            </div>
                        </td>
                        <td class="col-name">
                            <a href="/admin/berita/update" class="col-name">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</a>
                        </td>
                        <td class="col-options">
                            Komputer
                        </td>
                        <td class="col-time">13:52</td>
                    </tr>
                <?php } ?>

            </tbody>
        </table>
    </div>

    <!-- Sidebar -->
    <div class="mail-sidebar">

        <!-- compose new email button -->
        <div class="mail-sidebar-row hidden-xs">
            <a href="/admin/berita/create">
                Tambah Baru
            </a>
        </div>

        <!-- menu -->
        <ul class="mail-menu">
            <li>
                <a href="/admin/berita">
                    Semua Berita
                </a>
            </li>

            <li>
                <a href="/admin/berita/draft">
                    <span class="badge badge-gray pull-right">1</span>
                    Draf
                </a>
            </li>

            <li class="active">
                <a href="/admin/berita/archive">
                    Arsip
                </a>
            </li>
        </ul>

    </div>

</div>
<hr>
<script>
    $('.nav-blog').addClass('opened');
    $('.nav-blog ul li:nth-child(1)').addClass('active');

    var content = $('.table tbody tr td .checkbox').length;
    if (content == 0) {
            $('.alert-empty').show();
    }
</script>