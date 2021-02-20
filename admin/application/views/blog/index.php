<div class="content-body">
    <div class="container">
        <div class="row page-titles">
            <div class="col p-0">
                <h4>Blogs</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <a class="btn btn-rounded btn-outline-danger add_blog" href="/blog/add">Add Blog</a>
                            <table class="table table-striped table-bordered zero-configuration" id="table_blog">
                                <thead>
                                    <tr>
                                    	<th>Id</th>
                                        <th>Blog Image</th>
                                        <th>Title</th>
                                        <th>Url</th>
                                        <th>Created Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($blogs as $blog): 
                                    ?>
                                        <tr>
                                            <td><?php echo $blog->id;?></td>
                                            <td><image src="<?php echo base_url().$blog->blog_image;?>"></td>
                                            <td><a href=""><?php echo $blog->title;?><a></td>
                                            <td><a href="<?php echo $blog->url;?>"><?php echo $blog->url;?><a></td>
                                            <td><?php echo $blog->created_date;?></td>
                                            <td>
                                                <a href="/blog/edit?blog_id=<?php echo $blog->id;?>" class="btn btn-rounded btn-sm btn-outline-danger edit_blog" id="edit_<?php echo $blog->id;?>">Edit</a>
                                                <a href="/blog/delete?blog_id=<?php echo $blog->id;?>" class="btn btn-rounded btn-sm btn-outline-danger delete_blog" id="delete_<?php echo $blog->id;?>">Delete</button>
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
