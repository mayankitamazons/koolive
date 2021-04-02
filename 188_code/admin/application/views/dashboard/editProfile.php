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
                    <form id="form_editAgencyProfile">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group">
                                    <div class="wrap-custom-file-logo">
                                        <input type="file" name="agency_logo" id="edit_agency_logo" accept=".gif, .jpg, .png">
                                        <?php if(isset($agency->logo_path)):?>
                                        <label for="edit_agency_logo" style="background-image: url('<?= base_url().$agency->logo_path;?>')">
                                        </label>
                                        <?php else:?>
                                        <label for="edit_agency_logo">
                                            <span>Agency Logo</span>
                                            <i class="fa fa-plus-circle"></i>
                                        </label>
                                        <?php endif;?>
                                    </div>                              
                                </div>
                            </div>
                            <div class="row">
                                <input type="hidden" name="id" value="<?=isset($agency_id)?$agency_id:""?>">
                                <div class="form-group col-md-6">
                                    <label for="">Name:</label>
                                    <input type="text" name="name" class="form-control input-rounded" placeholder="Name" value="<?=$agency->name;?>" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Email:</label>
                                    <input type="email" name="email" class="form-control input-rounded" placeholder="Email" value="<?=$agency->email;?>" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="">Mobile:</label>
                                    <input type="text" name="mobile" class="form-control input-rounded" placeholder="Mobile" value="<?=$agency->mobile;?>" >
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Website:</label>
                                    <input type="text" name="website" class="form-control input-rounded" placeholder="Website" value="<?=$agency->website;?>" >
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="">Password:</label>
                                    <input type="password" name="password" class="form-control input-rounded pwd" placeholder="Password" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Confirm Password:</label>
                                    <input type="password" class="form-control input-rounded conpwd" placeholder="Confirm" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <div class="wrap-custom-file-banner">
                                        <input type="file" name="agency_banner" id="edit_agency_banner" accept=".gif, .jpg, .png">
                                        <?php if($agency->banner_path):?>
                                        <label for="edit_agency_banner" style="background-image: url('<?= base_url().$agency->banner_path;?>')">
                                        </label>
                                        <?php else:?>
                                        <label for="edit_agency_banner" style="background-image: url('<?= base_url().$agency->banner_path;?>')">
                                            <span>Agency Banner</span>
                                            <i class="fa fa-plus-circle"></i>
                                        </label>
                                        <?php endif;?>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="row">
                                <div class="form-group col-md-12">
                                    <lable>Info:</label>
                                    <div class="info_editor">
                                        <div class='agency_info'>
                                    </div>
                                </div>
                            </div> -->
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="">Info</label>
                                    <textarea id="agency_info_editor"><?=$agency->info?></textarea>
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