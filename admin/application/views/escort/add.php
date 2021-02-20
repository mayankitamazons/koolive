<div class="content-body">
    <div class="container">
        <div class="row page-titles">
            <div class="col p-0">
                <h4>Add New Escort</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <form id="form_addEscort">
                        <div class="card-body">
                            <div class="row">
                                <div class="wrap-custom-file">
                                    <input type="file" name="escort_logo" id="add_escort_logo" accept=".gif, .jpg, .png">
                                    <label for="add_escort_logo">
                                        <span>Escort Logo</span>
                                        <i class="fa fa-plus-circle"></i>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <lable for="">Thumbnails</lable>
                                    <div action="/escort/upload_thumbnails" name="escort_thumbnails[]" class="dropzone" id="dropzone_escort" enctype="multipart/form-data">
                                        <!-- <input type="file" name="file" multiple/> -->
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <p>Name:</p>
                                    <input type="text" name="name" class="form-control input-rounded" placeholder="Name" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <p>Agency:</p>
                                    <select name="agency" class="form-control input-rounded js-example-basic-select2">
                                       <?php foreach($agencies as $agency):?>
                                        <option value="<?php echo $agency->id;?>"><?php echo $agency->name;?></option>
                                        <?php endforeach; ?>
                                    </select>
                                 </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <p>Email:</p>
                                    <input type="email" name="email" class="form-control input-rounded" placeholder="Email" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <p>Url:</p>
                                    <input type="text" name="url" class="form-control input-rounded" placeholder="Url">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <p>Age:</p>
                                    <input type="text" name="age" class="form-control input-rounded" placeholder="Age">
                                </div>
                                <div class="form-group col-md-6">
                                    <p>Statistics:</p>
                                    <input type="text" name="statistics" class="form-control input-rounded" placeholder="Statistics">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <p>Height:</p>
                                    <input type="text" name="height" class="form-control input-rounded" placeholder="Height">
                                </div>
                                <div class="form-group col-md-6">
                                    <p>Weight:</p>
                                    <input type="text" name="weight" class="form-control input-rounded" placeholder="Weight">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <p>Hair:</p>
                                    <input type="text" name="hair" class="form-control input-rounded" placeholder="Hair">
                                </div>
                                <div class="form-group col-md-6">
                                    <p>Eyes:</p>
                                    <input type="text" name="eyes" class="form-control input-rounded" placeholder="Eyes">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <p>Nationality:</p>
                                    <input type="text" name="nationality" class="form-control input-rounded" placeholder="Nationality">
                                </div>
                                <div class="form-group col-md-6">
                                    <p>Language:</p>
                                    <input type="text" name="language" class="form-control input-rounded" placeholder="Language">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <p>Incall Loacation:</p>
                                    <input type="text" name="incall_location" class="form-control input-rounded" placeholder="Incall Location">
                                </div>
                                <div class="form-group col-md-6">
                                    <p>Outcall Loacation:</p>
                                    <input type="text" name="outcall_location" class="form-control input-rounded" placeholder="Outcall Location">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <p>Password:</p>
                                    <input type="password" name="password"class="form-control input-rounded" placeholder="Password" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <p>Confirm Password:</p>
                                    <input type="password" name="conpassword" class="form-control input-rounded" placeholder="Confirm" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group checkbox col-md-6">
                                    <p>Masseuse:</p>
                                    <input type="hidden" name="masseuse" value="Yes">
                                    <label>
                                        <input class="js-switch escort_masseuse" type="checkbox" checked="checked">
                                    </label>
                                </div>
                                <div class="form-group checkbox col-md-6">
                                    <p>Active:</p>
                                    <input type="hidden" name="active" value="Yes">
                                    <label>
                                        <input class="js-switch escort_active" type="checkbox" checked="checked">
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <p>Category:</p>
                                    <select name="category[]" class="form-control input-rounded js-example-basic-select2" multiple>
                                       <?php foreach($categories as $cat):?>
                                        <option value="<?php echo $cat->id;?>"><?php echo $cat->category;?></option>
                                        <?php endforeach; ?>
                                    </select>
                                 </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="">Bio</label>
                                    <textarea id="bio_editor"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="">Full Bio</label>
                                    <textarea id="full_bio_editor"></textarea>
                                </div>
                            </div>
                            <div class="input_fields_wrap row">
                                <div class="col-md-12">
                                    <lable>Rate</lable>
                                </div>
                                <div class="col-md-12 fields">
                                    <button class="btn btn-success add_field_button pull-right">Add More Fields</button>
                                    <div class="form-group rate">
                                        <input class="form-control-sm input-rounded col-md-3" type="text" name="duration[]" placeholder="Duration">
                                        <input class="form-control-sm input-rounded col-md-3" type="text" name="rate[]" placeholder="Rate">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary pull-right">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>