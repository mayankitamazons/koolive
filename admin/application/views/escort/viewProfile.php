<div class="content-body">
    <div class="container">
        <div class="row page-titles">
            <div class="col p-0">
                <h4>My Profile</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <form id="form_viewEscortProfile">
                        <div class="card-body">
                            <div class="row">
                                <div class="wrap-custom-file">
                                    <img class="profile_escort_logo" src="<?= base_url().$escort->logo_path;?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <lable>Thumbnails</lable>
                                    <div>
                                        <?php foreach($thumbnails as $thumb):?>
                                        <img class="profile_escort_thumb" src="<?=base_url().$thumb->path;?>">
                                        <?php endforeach;?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <input type="hidden" name="id" value="<?php echo $escort_id;?>">
                                    <p>Name:</p>
                                    <input type="text" name="name" class="form-control input-rounded" placeholder="Name" value="<?= $escort->name;?>" disabled>
                                </div>
                                <div class="form-group col-md-6">
                                    <p>Agency:</p>
                                    <select name="agency" class="form-control input-rounded js-example-basic-select2" disabled>
                                       <?php foreach($agencies as $agency):
                                        if($escort->agency_id == $agency->id):?>
                                           <option value="<?php echo $agency->id;?>" selected><?php echo $agency->name;?></option>
                                        <?php else: ?>
                                            <option value="<?php echo $agency->id;?>"><?php echo $agency->name;?></option>
                                        <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                 </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <p>Email:</p>
                                    <input type="email" name="email" class="form-control input-rounded" placeholder="Email" value="<?=$escort->email;?>" disabled>
                                </div>
                                <div class="form-group col-md-6">
                                    <p>Url:</p>
                                    <input type="text" name="url" class="form-control input-rounded" placeholder="Url" value="<?=$escort->url;?>" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <p>Age:</p>
                                    <input type="text" name="age" class="form-control input-rounded" placeholder="Age" value="<?=$escort->age;?>" disabled>
                                </div>
                                <div class="form-group col-md-6">
                                    <p>Statistics:</p>
                                    <input type="text" name="statistics" class="form-control input-rounded" placeholder="Statistics" value="<?=$escort->statistics;?>" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <p>Height:</p>
                                    <input type="text" name="height" class="form-control input-rounded" placeholder="Height" value="<?=$escort->height;?>" disabled>
                                </div>
                                <div class="form-group col-md-6">
                                    <p>Weight:</p>
                                    <input type="text" name="weight" class="form-control input-rounded" placeholder="Weight" value="<?=$escort->weight;?>" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <p>Hair:</p>
                                    <input type="text" name="hair" class="form-control input-rounded" placeholder="Hair" value="<?=$escort->hair;?>" disabled>
                                </div>
                                <div class="form-group col-md-6">
                                    <p>Eyes:</p>
                                    <input type="text" name="eyes" class="form-control input-rounded" placeholder="Eyes" value="<?=$escort->eyes;?>" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <p>Nationality:</p>
                                    <input type="text" name="nationality" class="form-control input-rounded" placeholder="Nationality" value="<?=$escort->nationality;?>" disabled>
                                </div>
                                <div class="form-group col-md-6">
                                    <p>Language:</p>
                                    <input type="text" name="language" class="form-control input-rounded" placeholder="Language" value="<?=$escort->language;?>" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <p>Incall Loacation:</p>
                                    <input type="text" name="incall_location" class="form-control input-rounded" placeholder="Incall Location" value="<?=$escort->incall_location;?>" disabled>
                                </div>
                                <div class="form-group col-md-6">
                                    <p>Outcall Loacation:</p>
                                    <input type="text" name="outcall_location" class="form-control input-rounded" placeholder="Outcall Location" value="<?=$escort->outcall_location;?>" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group checkbox col-md-6">
                                    <p>Masseuse:</p>
                                    <input type="hidden" name="masseuse" value="<?=$escort->masseuse;?>">
                                    <label>
                                        <?php if($escort->masseuse=="Yes"):?>
                                            <input class="js-switch escort_masseuse" type="checkbox" checked="checked">
                                        <?php else:?>
                                            <input class="js-switch escort_masseuse" type="checkbox">
                                        <?php endif;?>
                                    </label>
                                </div>
                                <div class="form-group checkbox col-md-6">
                                    <p>Active:</p>
                                    <input type="hidden" name="active" value="<?=$escort->active;?>">
                                    <label>
                                        <?php if($escort->active=="Yes"):?>
                                            <input class="js-switch escort_active" type="checkbox" checked="checked">
                                        <?php else:?>
                                            <input class="js-switch escort_active" type="checkbox">
                                        <?php endif;?>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <?php $cateArray = json_decode($escort->category);
                                    ?>
                                    <p>Category:</p>
                                    <select name="category[]" class="form-control input-rounded js-example-basic-select2" multiple> -->
                                    <?php foreach($categories as $cat):
                                        //var_dump($cat);
                                        if(array_search($cat->id, $cateArray)!==false):?>
                                            <option value="<?php echo $cat->id;?>" selected><?php echo $cat->category;?></option>
                                        <?php else:?>
                                            <option value="<?php echo $cat->id;?>"><?php echo $cat->category;?></option>
                                        <?php endif;?>
                                    <?php endforeach; ?>
                                    </select>
                                 </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="">Bio</label>
                                    <textarea id="bio_editor"><?=$escort->bio;?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="">Full Bio</label>
                                    <textarea id="full_bio_editor"><?=$escort->full_bio;?></textarea>
                                </div>
                            </div>
                            <div class="input_fields_wrap row">
                                <div class="col-md-12">
                                    <lable>Rate</lable>
                                </div>
                                <div class="col-md-12 fields">
                                    <button class="btn btn-success add_field_button pull-right">Add More Fields</button>
                                    <?php foreach ($rates as $rate):?>
                                        <div class="form-group rate">
                                            <input class="form-control-sm input-rounded col-md-3" type="text" name="duration[]" placeholder="Duration" value="<?=$rate->duration;?>" disabled>
                                            <input class="form-control-sm input-rounded col-md-3" type="text" name="rate[]" placeholder="Rate" value="<?=$rate->rate;?>" disabled>
                                        </div>
                                    <?php endforeach;?>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="/escort/editProfile?email=<?=$escort->email;?>" class="btn btn-primary pull-right">Edit</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>