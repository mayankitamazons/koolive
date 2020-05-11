<div class="content-body">
    <div class="container">
        <div class="row page-titles">
            <div class="col p-0">
                <h4>Edit Profile</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <form id="form_editEscortProfile">
                        <div class="card-body">
                            <div class="row">
                                <?php if(isset($escort->logo_path)):?>
                                <div class="wrap-custom-file">
                                    <input type="file" name="escort_logo" id="edit_escort_logo" accept=".gif, .jpg, .png">
                                    <label for="edit_escort_logo" style="background-image: url('<?= base_url().$escort->logo_path;?>')" class="file-ok">
                                    </label>
                                </div>
                                <?php else:?>
                                <div class="wrap-custom-file">
                                    <input type="file" name="escort_logo" id="edit_escort_logo" accept=".gif, .jpg, .png">
                                    <label for="edit_escort_logo">
                                        <span>Escort Logo</span>
                                        <i class="fa fa-plus-circle"></i>
                                    </label>
                                </div>
                                <?php endif;?>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <lable>Thumbnails</lable>
                                    <div action="/escort/upload_thumbnails" name="escort_thumbnails[]" class="dropzone" id="dropzone_escort" enctype="multipart/form-data">
                                        <?php foreach($thumbnails as $thumb):?>
                                        <div class="dz-preview dz-processing dz-image-preview dz-success dz-complete dz-preview-<?=array_search($thumb, $thumbnails)?>">
                                            <div class="dz-image">
                                                <img data-dz-thumbnail alt="<?=$thumb->path;?>" src="<?=base_url().$thumb->path;?>">
                                            </div>
                                            <div class="dz-details">
                                                <div class="dz-size">
                                                </div>
                                                <div class="dz-filename">
                                                    <span data-dz-name><?=str_replace("uploads/escort/thumbnails/", "", $thumb->path);?></span>
                                                </div>
                                            </div>
                                            <div class="dz-progress">
                                                <span class="dz-upload" data-dz-uploadprogress></span>
                                            </div>
                                            <div class="dz-error-message">
                                                <span data-dz-errormessage></span>
                                            </div>
                                            <div class="dz-success-mark">  
                                                <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">    
                                                    <title>Check</title>    
                                                    <defs></defs>    
                                                    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">      
                                                        <path d="M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" stroke-opacity="0.198794158" stroke="#747474" fill-opacity="0.816519475" fill="#FFFFFF" sketch:type="MSShapeGroup">
                                                        </path>    
                                                    </g>  
                                                </svg>
                                            </div>
                                            <div class="dz-error-mark">  
                                                <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">    
                                                    <title>Error</title>    
                                                    <defs></defs>    
                                                    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">      
                                                        <g id="Check-+-Oval-2" sketch:type="MSLayerGroup" stroke="#747474" stroke-opacity="0.198794158" fill="#FFFFFF" fill-opacity="0.816519475">        
                                                            <path d="M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" sketch:type="MSShapeGroup">
                                                            </path>      
                                                        </g>    
                                                    </g>  
                                                </svg>
                                            </div>
                                            <a class="dz-remove" href="javascript:undefined;" data-dz-remove>Remove file</a>
                                        </div>
                                        <?php endforeach;?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <input type="hidden" name="id" value="<?php echo $escort_id;?>">
                                    <p>Name:</p>
                                    <input type="text" name="name" class="form-control input-rounded" placeholder="Name" value="<?= $escort->name;?>" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <p>Agency:</p>
                                    <select name="agency" class="form-control input-rounded js-example-basic-select2">
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
                                    <input type="email" name="email" class="form-control input-rounded" placeholder="Email" value="<?=$escort->email;?>" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <p>Url:</p>
                                    <input type="text" name="url" class="form-control input-rounded" placeholder="Url" value="<?=$escort->url;?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <p>Age:</p>
                                    <input type="text" name="age" class="form-control input-rounded" placeholder="Age" value="<?=$escort->age;?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <p>Statistics:</p>
                                    <input type="text" name="statistics" class="form-control input-rounded" placeholder="Statistics" value="<?=$escort->statistics;?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <p>Height:</p>
                                    <input type="text" name="height" class="form-control input-rounded" placeholder="Height" value="<?=$escort->height;?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <p>Weight:</p>
                                    <input type="text" name="weight" class="form-control input-rounded" placeholder="Weight" value="<?=$escort->weight;?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <p>Hair:</p>
                                    <input type="text" name="hair" class="form-control input-rounded" placeholder="Hair" value="<?=$escort->hair;?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <p>Eyes:</p>
                                    <input type="text" name="eyes" class="form-control input-rounded" placeholder="Eyes" value="<?=$escort->eyes;?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <p>Nationality:</p>
                                    <input type="text" name="nationality" class="form-control input-rounded" placeholder="Nationality" value="<?=$escort->nationality;?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <p>Language:</p>
                                    <input type="text" name="language" class="form-control input-rounded" placeholder="Language" value="<?=$escort->language;?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <p>Incall Loacation:</p>
                                    <input type="text" name="incall_location" class="form-control input-rounded" placeholder="Incall Location" value="<?=$escort->incall_location;?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <p>Outcall Loacation:</p>
                                    <input type="text" name="outcall_location" class="form-control input-rounded" placeholder="Outcall Location" value="<?=$escort->outcall_location;?>">
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
                                            <input class="form-control-sm input-rounded col-md-3" type="text" name="duration[]" placeholder="Duration" value="<?=$rate->duration;?>">
                                            <input class="form-control-sm input-rounded col-md-3" type="text" name="rate[]" placeholder="Rate" value="<?=$rate->rate;?>">
                                            <a href="#" class="remove_field">Remove</a>
                                        </div>
                                    <?php endforeach;?>
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