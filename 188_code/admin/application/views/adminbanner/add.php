<div class="content-body">
    <div class="container">
        <div class="row page-titles">
            <div class="col p-0">
                <h4>Add New Banner</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <form id="form_addBanner">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group">
                                    <div class="banner-image">
                                        <input type="file" name="banner_logo" id="add_banner_logo" accept=".gif, .jpg, .png">
                                        <label for="add_banner_logo">
                                            <span>Add Banner</span>
                                            <i class="fa fa-plus-circle"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="">Title:</label>
                                    <input type="text" name="title" class="form-control input-rounded" placeholder="Title" required>
                                </div>
                                <div class="form-group checkbox col-md-6">
                                    <p>Active:</p>
                                    <input type="hidden" name="active" value="Yes">
                                    <label>
                                        <input class="js-switch banner_active" type="checkbox" checked="checked">
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="">Position:</label>
                                    <select type="text" name="position" class="form-control input-rounded">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="">Text</label>
                                    <textarea class="text_editor"></textarea>
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