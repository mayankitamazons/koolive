<div class="content-body">
    <div class="container">
        <div class="row page-titles">
            <div class="col p-0">
                <h4>Edit Banner</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <form id="form_editBanner">
                        <div class="card-body">
                            <?php var_dump($banner);?>
                            <div class="row">
                                <div class="form-group">
                                    <div class="banner-image">
                                        <input type="file" name="banner_logo" id="edit_banner_logo" accept=".gif, .jpg, .png">
                                        <?php if(isset($banner->image_path)):?>
                                        <label for="edit_banner_logo" style="background-image: url('<?=base_url().$banner->image_path;?>')">
                                        </label>
                                        <?php else:?>
                                        <label for="edit_banner_logo">
                                            <span>Add Banner</span>
                                            <i class="fa fa-plus-circle"></i>
                                        </label>
                                        <?php endif;?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <input type="hidden" name="id" value="<?=$banner_id;?>">
                                    <label for="">Title:</label>
                                    <input type="text" name="title" class="form-control input-rounded" placeholder="Title" value="<?=$banner->title;?>" required>
                                </div>
                                <div class="form-group checkbox col-md-6">
                                    <p>Active:</p>
                                    <input type="hidden" name="active" value="<?=$banner->active;?>" >
                                    <label>
                                        <?php if($banner->active=="Yes"):?>
                                        <input class="js-switch banner_active" type="checkbox" checked="checked">
                                        <?php else:?>
                                        <input class="js-switch banner_active" type="checkbox">
                                        <?php endif;?>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="">Position:</label>
                                    <select type="text" name="position" class="form-control input-rounded">
                                        <?php for($i=1; $i<=10; $i++){?>
                                            <?php if($i==$banner->position):?>
                                                <option value="<?= $i;?>" selected><?=$i;?></option>
                                            <?php else:?>
                                                <option value="<?= $i;?>"><?=$i;?></option>
                                            <?php endif;
                                        }?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="">Text</label>
                                    <textarea class="text_editor"> <?=$banner->text;?> </textarea>
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