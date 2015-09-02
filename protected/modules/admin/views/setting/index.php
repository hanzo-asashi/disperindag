<?php

    function indonesian_date ($timestamp = '', $date_format = 'l, j F Y') {
        if (trim ($timestamp) == '') {
            $timestamp = time ();
        }
        elseif (!ctype_digit ($timestamp)) {
            $timestamp = strtotime ($timestamp);
        }
        # remove S (st,nd,rd,th) there are no such things in indonesia :p
        $date_format = preg_replace ("/S/", "", $date_format);
        $pattern = array (
            '/Mon[^day]/','/Tue[^sday]/','/Wed[^nesday]/','/Thu[^rsday]/',
            '/Fri[^day]/','/Sat[^urday]/','/Sun[^day]/','/Monday/','/Tuesday/',
            '/Wednesday/','/Thursday/','/Friday/','/Saturday/','/Sunday/',
            '/Jan[^uary]/','/Feb[^ruary]/','/Mar[^ch]/','/Apr[^il]/','/May/',
            '/Jun[^e]/','/Jul[^y]/','/Aug[^ust]/','/Sep[^tember]/','/Oct[^ober]/',
            '/Nov[^ember]/','/Dec[^ember]/','/January/','/February/','/March/',
            '/April/','/June/','/July/','/August/','/September/','/October/',
            '/November/','/December/',
        );
        $replace = array ( 'Sen','Sel','Rab','Kam','Jum','Sab','Min',
            'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu',
            'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des',
            'Januari','Februari','Maret','April','Juni','Juli','Agustus','Sepember',
            'Oktober','November','Desember',
        );
        $date = date ($date_format, $timestamp);
        $date = preg_replace ($pattern, $replace, $date);
        $date = "{$date}";
        return $date;
    }

?>

<ol class="breadcrumb bc-3">
    <li>
        <a href="/admin">Beranda</a>
    </li>

    <li class="active">
        <strong>Pengaturan</strong>
    </li>
</ol>

<h2>Pengaturan</h2>
<br />

<form role="form" method="post" class="form-horizontal form-groups-bordered validate" action="">
    <div class="row">
        <div class="col-md-12">
            
            <div class="panel panel-primary" data-collapsed="0">
                
                <div class="panel-body">
                    
                    <form role="form" class="form-horizontal form-groups-bordered validate">
        
                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label">Nama Situs</label>
                            
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="sitename" value="BPLP Disperindag Sulsel" data-validate="required" data-message-required="Wajib diisi">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="field-2" class="col-sm-3 control-label">Deskripsi Situs</label>
                            
                            <div class="col-sm-5">
                                <textarea class="form-control" id="field-2" data-validate="required" data-message-required="This is custom message for required field."></textarea>
                                <br>
                                <p><i>Jelaskan tentang apa situs ini dengan singkat.</i></p>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="field-3" class="col-sm-3 control-label">Alamat situs (URL)</label>
                            
                            <div class="col-sm-5">
                                <input type="text" class="form-control" id="field-3" value="http://bplpdisperindagsulsel.com" name="url" data-validate="required" data-message-required="Wajib diisi">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="field-4" class="col-sm-3 control-label">Alamat email</label>
                            
                            <div class="col-sm-5">
                                <input type="text" class="form-control" id="field-4" value="service@bplpdisperindagsulsel.com" name="email" data-validate="required" data-message-required="Wajib diisi">
                                <br>
                                <p><i>Alamat ini digunakan untuk tujuan admin, seperti pesan masuk.</i></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="field-4" class="col-sm-3 control-label">Format Tanggal</label>

                            <?php 

                                $mydate=getdate(date("U"));

                            ?>

                            <div class="col-sm-5">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="format_tanggal" id="optionsRadios1" value="1" checked>
                                        <?php echo indonesian_date ("$mydate[weekday], $mydate[mday] $mydate[month] $mydate[year]") ?>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="format_tanggal" id="optionsRadios2" value="2">
                                        <?php echo "$mydate[mday]/$mydate[mon]/$mydate[year]" ?>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="format_tanggal" id="optionsRadios3" value="3">
                                        <?php echo "$mydate[mday]-$mydate[mon]-$mydate[year]" ?>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Tema</label>
                            
                            <div class="col-sm-5">
                                <select class="form-control">
                                    <option>Disperindag</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Kata Kunci Pencarian</label>
                            
                            <div class="col-sm-5">
                                
                                <input type="text" value="Disperindag,Sewa Gedung,Ball Room CCC, CCC, Makassar, Dinas, Sulawesi Selatan," class="form-control tagsinput" />
                                <p><i>Kata kunci akan membantu pengunjung menemukan website ini di Google.</i></p>
                                
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label">Social setting</label>
                            
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">&nbsp;<i class="fa fa-facebook"></i>&nbsp;</span>
                                    <input type="text" class="form-control" name="fb">
                                </div>
                                <br>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-twitter"></i></span>
                                    <input type="text" class="form-control" name="fb">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-5">
                                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                                <button type="reset" class="btn">Kembalikan Perubahan</button>
                            </div>
                        </div>
                    </form>
                    
                </div>
            
            </div>
        
        </div>
    </div>

</form>