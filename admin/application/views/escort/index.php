<div class="content-body">
    <div class="container">
        <div class="row page-titles">
            <div class="col p-0">
                <h4>Escorts</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <a href="/escort/add" class="btn btn-rounded btn-outline-danger add_escort">Add Escort</a>
                            <table class="table table-striped table-bordered zero-configuration" id="table_escort">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Agency</th>
                                        <th>Email</th>
                                        <th>Masseuse</th>
                                        <th>Url</th>
                                        <th>Age</th>
                                        <th>Nationality</th>
                                        <th>Incall Location</th>
                                        <th>Outcall Location</th>
                                        <th>Hair</th>
                                        <th>Eyes</th>
                                        <th>Height</th>
                                        <th>Weight</th>
                                        <th>Statistics</th>
                                        <th>Language</th>
                                        <th>Active</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        foreach($escorts as $escort): 
                                    ?>
                                        <tr>
                                            <td><?php echo $escort->id;?></td>
                                            <td><image src="<?php echo base_url().$escort->logo_path;?>"></td>
                                            <td><a><?php echo $escort->name;?><a></td>
                                            <td><?php echo $escort->agency_name;?></td>
                                            <td><?php echo $escort->email;?></td>
                                            <td><?php echo $escort->masseuse;?></td>
                                            <td><a href="<?php echo $escort->url;?>"><?php echo $escort->url;?></a></td>
                                            <td><?php echo $escort->age;?></td>
                                            <td><?php echo $escort->nationality;?></td>
                                            <td><?php echo $escort->incall_location;?></td>
                                            <td><?php echo $escort->outcall_location;?></td>
                                            <td><?php echo $escort->hair;?></td>
                                            <td><?php echo $escort->eyes;?></td>
                                            <td><?php echo $escort->height;?></td>
                                            <td><?php echo $escort->weight;?></td>
                                            <td><?php echo $escort->statistics;?></td>
                                            <td><?php echo $escort->language;?></td>
                                            <td><?php echo $escort->active;?></td>
                                            <td>
                                                <a href="/escort/edit?id=<?php echo $escort->id?>" class="btn btn-rounded btn-sm btn-outline-danger edit_escort" id="edit_<?php echo $escort->id;?>"><i class="fa fa-edit"></i></a>
                                                <a href="/escort/delete?id=<?php echo $escort->id?>" class="btn btn-rounded btn-sm btn-outline-danger delete_escort" id="delete_<?php echo $escort->id;?>"><i class="fa fa-trash"></i></a>
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
