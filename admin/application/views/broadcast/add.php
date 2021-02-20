<div class="content-body">
    <div class="container">
        <div class="row page-titles">
            <div class="col p-0">
                <h4>Add New Broadcast</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <form id="form_addBroadcast">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Title:</label>
                                        <input type="text" name="title" class="form-control input-rounded" placeholder="Title" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="">Agency:</label>
                                    <select type="text" name="agency_id" class="form-control input-rounded">
                                        <?php foreach($agencies as $agency):?>
                                        <option value="<?=$agency->id;?>"><?=$agency->name;?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Escort:</label>
                                    <select type="text" name="escort_id" class="form-control input-rounded">
                                        <?php foreach($escorts as $escort):?>
                                        <option value="<?=$escort->id;?>"><?=$escort->name;?></option>
                                        <?php endforeach;?>
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