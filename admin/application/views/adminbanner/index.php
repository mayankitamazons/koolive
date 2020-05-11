<div class="content-body">
    <div class="container">
        <div class="row page-titles">
            <div class="col p-0">
                <h4>Banners</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <a class="btn btn-rounded btn-outline-danger add_banner" href="/adminbanner/add">Add Banner</a>
                            <table class="table table-striped table-bordered zero-configuration" id="table_banner">
                                <thead>
                                    <tr>
                                    	<th>Id</th>
                                        <th>Banner Image</th>
                                        <th>Title</th>
                                        <th>Text</th>
                                        <th>Position</th>
                                        <th>Active</th>
                                        <th>Created Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($banners as $banner): 
                                    ?>
                                        <tr>
                                            <td><?php echo $banner->id;?></td>
                                            <td><image src="<?php echo base_url().$banner->image_path;?>"></td>
                                            <td><?php echo $banner->title;?></td>
                                            <td><?php echo $banner->text;?></td>
                                            <td><?php echo $banner->position;?></td>
                                            <td><?php echo $banner->active;?></td>
                                            <td><?php echo $banner->created_date;?></td>
                                            <td>
                                                <a href="/adminbanner/edit?banner_id=<?php echo $banner->id;?>" class="btn btn-rounded btn-sm btn-outline-danger edit_banner" id="edit_<?php echo $banner->id;?>">Edit</a>
                                                <a href="/adminbanner/delete?banner_id=<?php echo $banner->id;?>" class="btn btn-rounded btn-sm btn-outline-danger delete_banner" id="delete_<?php echo $banner->id;?>">Delete</button>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #/ container -->
</div>
<!-- Modal -->
