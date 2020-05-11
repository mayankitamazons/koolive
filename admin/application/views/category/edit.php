<div class="content-body">
    <div class="container">
        <div class="row page-titles">
            <div class="col p-0">
                <h4>Edit Category</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <form id="form_editCategory">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <input type="hidden" name="id" value="<?php echo $cat_id;?>">
                                    <label for="">Title:</label>
                                    <input type="text" name="category" class="form-control input-rounded" placeholder="Category" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="">Url:</label>
                                    <input type="text" name="url" class="form-control input-rounded" placeholder="Url">
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