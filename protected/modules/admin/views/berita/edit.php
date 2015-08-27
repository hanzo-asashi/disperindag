<div class="mail-env">

    <!-- Mail Body -->
    <div class="mail-body">

        <div class="mail-header">
            <!-- title -->
            <h3 class="col-md-5 no-margin">
                Sunting Berita
            </h3>

            <!-- links -->
            <div class="col-md-7">

                <div class="pull-right">

                    <a href="#" class="btn btn-default btn-icon">
                        Simpan di Draft
                        <i class="entypo-tag"></i>
                    </a>

                </div>

            </div>
        </div>


        <div class="mail-compose">

            <form method="post" role="form">

                <div class="form-group">
                    <label for="to">Judul Berita:</label>
                    <input type="text" class="form-control post-title" id="to" tabindex="1" value="Lorem ipsum dolor sit amet." />
                </div>


                <div class="compose-message-editor">
                    <textarea name="post_content" id="editor">
						Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit veritatis omnis vitae velit, ratione recusandae molestias voluptatem incidunt non quos, quae harum praesentium dolorem ipsa numquam libero. Illum quis itaque dolor aliquam officia quod amet quibusdam qui est dignissimos tenetur molestias excepturi, veritatis doloremque aliquid, obcaecati perspiciatis eligendi minus temporibus nihil vero. Dolor nisi, dolore rem itaque inventore officia voluptatem numquam nobis molestias molestiae qui rerum, libero unde commodi obcaecati. Neque tenetur itaque ipsa enim praesentium commodi vel sunt delectus adipisci. Deserunt pariatur eveniet sint dolore atque autem dignissimos, totam?
                    </textarea>
                </div>

                <br>
                <br>

                <div class="row">
                    <label class="col-sm-12 control-label">Kata Kunci Pencarian</label>

                    <div class="col-sm-12">

                        <input type="text" value="Disperindag,Sewa Gedung,Ball Room CCC, CCC, Makassar, Dinas, Sulawesi Selatan," class="form-control tagsinput" />
                        <p><i>Kata kunci akan membantu pengunjung menemukan berita ini di Google.</i></p>

                    </div>
                </div>

            </form>

        </div>
    </div>

    <!-- Sidebar -->
    <div class="mail-sidebar">

        <form action="">

            <!-- compose new email button -->
            <div class="mail-sidebar-row hidden-xs">
                <button class="btn btn-success btn-icon btn-block">
                    Publikasikan
                    <i class="entypo-megaphone"></i>
                </button>
            </div>

            <!-- menu -->
            <ul class="mail-menu">
                <li class="custom">
                    <div class="panel panel-primary" data-collapsed="0">
                        <div class="panel-heading">
                            <div class="panel-title">
                                Gambar Utama
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 100%; height: auto;" data-trigger="fileinput">
                                    <img src="<?php echo yii::app()->request->baseUrl ?>/static/assets/img/gallery/post-1.jpg" alt="...">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 100%; max-height: auto"></div>
                                <div>
                                    <span class="btn btn-white btn-file">
                                        <span class="fileinput-new">Pilih Gambar</span>
                                        <span class="fileinput-exists">Ganti</span>
                                        <input type="file" name="..." accept="image/*">
                                    </span>

                                    <span class="btn">
                                        <a href="#" class="" data-dismiss="fileinput">Hapus</a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="custom">
                    <div class="panel panel-primary" data-collapsed="0">
                        <div class="panel-heading">
                            <div class="panel-title">
                                Kategori
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="checkbox checkbox-replace">
                                <input type="checkbox" id="chk-1" checked>
                                <label>Checkbox 1</label>
                            </div>

                            <div class="checkbox checkbox-replace">
                                <input type="checkbox" id="chk-2">
                                <label>Checkbox 2</label>
                            </div>

                            <div class="checkbox checkbox-replace">
                                <input type="checkbox" id="chk-3">
                                <label>Checkbox 3</label>
                            </div>

                            <hr>

                            <a href="javascript:;" onclick="jQuery('#modal-kategori').modal('show');"><i class="entypo-plus"></i>Kategori Baru</a>
                        </div>
                    </div>
                </li>
            </ul>

        </form>

    </div>

</div>
<hr>


<?php $registerscript = "
	<!-- Modal 1 (Basic)-->
	<div class='modal fade' id='modal-kategori'>
		<div class='modal-dialog'>
			<div class='modal-content'>
				<form action=''>
					<div class='modal-header'>
						<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
						<h4 class='modal-title'>Tambah Kategori</h4>
					</div>
					
					<div class='modal-body'>
						<input type='text' class='form-control' placeholder='Tulis Kategori Baru disini'>
					</div>
					
					<div class='modal-footer no-margin'>
						<button type='button' class='btn btn-default' data-dismiss='modal'>Kembali</button>
						<button type='button' class='btn btn-success'>Tambah</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script src='<?php echo yii::app()->request->baseUrl ?>/static/assets/libs/ckeditor-full/ckeditor.js'></script>
	<script src='<?php echo yii::app()->request->baseUrl ?>/static/assets/libs/js/fileinput.js'></script>

	<script>
		CKEDITOR.replace( 'editor' );
	</script>
	<script>
		$('.nav-blog').addClass('opened');
		$('.nav-blog ul li:nth-child(2)').addClass('active');
	</script>
" ?>