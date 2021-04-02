<div class="content-body">
    <div class="container">
        <div class="row page-titles">
            <div class="col p-0">
                <h4>Edit Blog</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <form id="form_editBlog">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <div class="blog-image">
                                        <!-- <image class="blog_image_preview"></image> -->
                                        <input type="file" name="blog_logo" id="edit_blog_logo" accept=".gif, .jpg, .png">
                                        <?php if(isset($blog->blog_image)):?>
                                        <label for="edit_blog_logo" style="background-image: url('<?=base_url().$blog->blog_image;?>')">
                                        </label>
                                        <?php else:?>
                                        <label for="edit_blog_logo">
                                            <span>Blog Image</span>
                                            <i class="fa fa-plus-circle"></i>
                                        </label>
                                        <?php endif;?>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="hidden" name="id" value="<?php echo $blog_id;?>">
                                    <div class="form-group">
                                        <label for="">Title:</label>
                                        <input type="text" name="title" class="form-control input-rounded" placeholder="Title" value="<?=$blog->title;?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Url:</label>
                                        <input type="text" name="url" class="form-control input-rounded" placeholder="Url" value="<?=$blog->url;?>">
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="row">
                                <div class="form-group col-md-12">
                                    <lable>Info:</label>
                                    <div class="info_editor">
                                        <div class='blog_info'>
                                    </div>
                                </div>
                            </div> -->
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="">Info</label>
                                    <textarea class="info_editor"><?=$blog->content;?></textarea>
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