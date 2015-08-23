<ol class="breadcrumb bc-3">
    <li>
        <a href="/admin">Beranda</a>
    </li>

    <li class="active">
        <strong>Berita</strong>
    </li>
</ol>

<h2>Tambah Berita</h2>
<br />

<form role="form" method="post" class="form-horizontal form-groups-bordered validate" action="">
    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-primary" data-collapsed="0">

                <div class="panel-body">

                    <form role="form" class="form-horizontal form-groups-bordered">

                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label">Nama Situs</label>

                            <div class="col-sm-5">
                                <input type="text" class="form-control" id="field-1">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="field-2" class="col-sm-3 control-label">Deskripsi Situs</label>

                            <div class="col-sm-5">
                                <textarea class="form-control" id="field-2"></textarea>
                                <br>
                                <p><i>Jelaskan tentang apa situs ini dengan singkat.</i></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="field-3" class="col-sm-3 control-label">Alamat situs (URL)</label>

                            <div class="col-sm-5">
                                <input type="text" class="form-control" id="field-3">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="field-4" class="col-sm-3 control-label">Alamat email</label>

                            <div class="col-sm-5">
                                <input type="text" class="form-control" id="field-4">
                                <br>
                                <p><i>Alamat ini digunakan untuk tujuan admin, seperti pesan masuk.</i></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label">File Field</label>

                            <div class="col-sm-5">
                                <input type="file" class="form-control" id="field-file" placeholder="Placeholder">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="field-ta" class="col-sm-3 control-label">Textarea</label>

                            <div class="col-sm-5">
                                <textarea class="form-control" id="field-ta" placeholder="Textarea"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="field-ta" class="col-sm-3 control-label">Autogrow</label>

                            <div class="col-sm-5">
                                <textarea class="form-control autogrow" id="field-ta" placeholder="I will grow as you type new lines."></textarea>
                            </div>
                        </div>

                        <div class="form-group has-error">
                            <label for="field-4" class="col-sm-3 control-label">Error field</label>

                            <div class="col-sm-5">
                                <input type="text" class="form-control" id="field-4" placeholder="Placeholder">
                            </div>
                        </div>

                        <div class="form-group has-warning">
                            <label for="field-5" class="col-sm-3 control-label">Warning field</label>

                            <div class="col-sm-5">
                                <input type="text" class="form-control" id="field-5" placeholder="Placeholder">
                            </div>
                        </div>

                        <div class="form-group has-success">
                            <label for="field-6" class="col-sm-3 control-label">Success field</label>

                            <div class="col-sm-5">
                                <input type="text" class="form-control" id="field-6" placeholder="Placeholder">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Select List</label>

                            <div class="col-sm-5">
                                <select class="form-control">
                                    <option>Option 1</option>
                                    <option>Option 2</option>
                                    <option>Option 3</option>
                                    <option>Option 4</option>
                                    <option>Option 5</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-5">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox">Checkbox 1
                                    </label>
                                </div>

                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox">Checkbox 2
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-5">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>Radio Input 1
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">Radio Input 2
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-5">
                                <button type="submit" class="btn btn-default">Sign in</button>
                            </div>
                        </div>
                    </form>

                </div>

            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-md-6">

            <div class="panel panel-primary" data-collapsed="0">

                <div class="panel-heading">
                    <div class="panel-title">
                        Members
                    </div>

                    <div class="panel-options">
                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                        <a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
                    </div>
                </div>

                <div class="panel-body">


                    <div class="form-group">
                        <label class="col-sm-5 control-label">Anyone can register</label>

                        <div class="col-sm-5">

                            <div class="checkbox checkbox-replace">
                                <input type="checkbox" id="chk-1" checked>
                            </div>

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-5 control-label">Default user role</label>

                        <div class="col-sm-5">

                            <select class="form-control">
                                <option>Subscriber</option>
                                <option>Author</option>
                                <option>Editor</option>
                                <option>Administrator</option>
                            </select>

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-5 control-label">New users</label>

                        <div class="col-sm-5">

                            <select class="form-control">
                                <option>Will have to activate their account (via email)</option>
                                <option>Account is automatically activated</option>
                            </select>

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-5" class="col-sm-5 control-label">Maximum login attempts</label>

                        <div class="col-sm-5">

                            <input type="text" name="max_attempts" id="field-5" class="form-control" data-validate="required,number" value="5" />

                        </div>
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="panel panel-primary" data-collapsed="0">

                <div class="panel-heading">
                    <div class="panel-title">
                        Date and Time
                    </div>

                    <div class="panel-options">
                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                        <a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
                    </div>
                </div>

                <div class="panel-body">

                    <div class="form-group">
                        <label for="field-3" class="col-sm-5 control-label">Date format</label>

                        <div class="col-sm-5">

                            <div class="radio radio-replace">
                                <input type="radio" id="rd-1" name="radio1" checked>
                                <label>March 27, 2014</label>
                            </div>

                            <div class="radio radio-replace">
                                <input type="radio" id="rd-2" name="radio1">
                                <label>03/27/2014</label>
                            </div>

                            <div class="radio radio-replace">
                                <input type="radio" id="rd-3" name="radio1">
                                <label>2014/03/27</label>
                            </div>

                            <div class="radio radio-replace">
                                <input type="radio" id="rd-4" name="radio1">
                                <label>
                                    Custom format: 
                                    <input type="text" class="form-control input-sm form-inline" value="d-m-Y" style="width: 70px; display: inline-block;" />
                                    <p class="description">Read more about <a href="http://php.net/date" target="_blank">date format</a></p>
                                </label>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <div class="form-group default-padding">
        <button type="submit" class="btn btn-success">Save Changes</button>
        <button type="reset" class="btn">Reset Previous</button>
    </div>
</form>