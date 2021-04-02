<div class="content-body">
    <div class="container">
        <div class="row page-titles">
            <div class="col p-0">
                <h4>Add New Agency</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <form id="form_addAgency">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group">
                                    <div class="wrap-custom-file-logo">
                                        <input type="file" name="agency_logo" id="add_agency_logo" accept=".gif, .jpg, .png">
                                        <label for="add_agency_logo">
                                            <span>Agency Logo</span>
                                            <i class="fa fa-plus-circle"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="">Name:</label>
                                    <input type="text" name="name" class="form-control input-rounded" placeholder="Name" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Email:</label>
                                    <input type="email" name="email" class="form-control input-rounded" placeholder="Email" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="">Mobile:</label>
                                    <input type="text" name="mobile" class="form-control input-rounded" placeholder="Mobile">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Website:</label>
                                    <input type="text" name="website" class="form-control input-rounded" placeholder="Website">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="">Password:</label>
                                    <input type="password" name="password" class="form-control input-rounded pwd" placeholder="Password" requried>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Confirm Password:</label>
                                    <input type="password" class="form-control input-rounded conpwd" placeholder="Confirm" requried>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <div class="wrap-custom-file-banner">
                                        <input type="file" name="agency_banner" id="add_agency_banner" accept=".gif, .jpg, .png">
                                        <label for="add_agency_banner">
                                            <span>Agency Banner</span>
                                            <i class="fa fa-plus-circle"></i>
                                        </label>
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
                                    <textarea class="info_editor"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer form-group col-md-12">   
                            <button type="submit" class="btn btn-primary pull-right">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>