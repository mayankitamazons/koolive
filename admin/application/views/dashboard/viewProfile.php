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
                    <form id="form_viewAgencyProfile">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group">
                                    <div class="wrap-custom-file-logo">
                                        <img class="profile_agency_logo" src="<?= base_url().$agency->logo_path;?>">
                                    </div>                              
                                </div>
                            </div>
                            <div class="row">
                                <input type="hidden" name="id" value="<?=isset($agency_id)?$agency_id:""?>">
                                <div class="form-group col-md-6">
                                    <label for="">Name:</label>
                                    <input type="text" name="name" class="form-control input-rounded" placeholder="Name" value="<?=$agency->name;?>" disabled>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Email:</label>
                                    <input type="email" name="email" class="form-control input-rounded" placeholder="Email" value="<?=$agency->email;?>" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="">Mobile:</label>
                                    <input type="text" name="mobile" class="form-control input-rounded" placeholder="Mobile" value="<?=$agency->mobile;?>" disabled>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Website:</label>
                                    <input type="text" name="website" class="form-control input-rounded" placeholder="Website" value="<?=$agency->website;?>" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <div class="wrap-custom-file-banner">
                                        <img class="profile_agency_banner" src="<?= base_url().$agency->banner_path;?>">
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
                                    <textarea id="agency_info_editor" disabled><?=$agency->info?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="/dashboard/editProfile?email=<?=$agency->email;?>" class="btn btn-primary pull-right">Edit</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>