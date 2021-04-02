<div class="content-body">
    <div class="container">
        <div class="row page-titles">
            <div class="col p-0">
                <h4>Categories</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <a class="btn btn-rounded btn-outline-danger add_category" href="/category/add">Add Category</a>
                            <table class="table table-striped table-bordered zero-configuration" id="table_category">
                                <thead>
                                    <tr>
                                    	<th>Id</th>
                                        <th>Category</th>
                                        <th>Url</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($categories as $cat): 
                                    ?>
                                        <tr>
                                            <td><?php echo $cat->id;?></td>
                                            <td><?php echo $cat->category;?></td>
                                            <td><a href="<?php echo $cat->url;?>"><?php echo $cat->url;?><a></td>
                                            <td>
                                                <a href="/category/edit?cat_id=<?php echo $cat->id;?>" class="btn btn-rounded btn-sm btn-outline-danger edit_cat" id="edit_<?php echo $cat->id;?>">Edit</a>
                                                <a href="/category/delete?cat_id=<?php echo $cat->id;?>" class="btn btn-rounded btn-sm btn-outline-danger delete_cat" id="delete_<?php echo $cat->id;?>">Delete</button>
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
