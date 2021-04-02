<div class="content-body">
    <div class="container">
        <div class="row page-titles">
            <div class="col p-0">
                <h4>Agencies</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <a class="btn btn-rounded btn-outline-danger add_agency" href="/dashboard/add">Add Agency</a>
                            <table class="table table-striped table-bordered zero-configuration" id="table_agency">
                                <thead>
                                    <tr>
                                    	<th>Id</th>
                                        <th>Logo</th>
                                        <th>Name</th>
                                        <th>Website</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($agencies as $agency): 
                                        //var_dump($agency);
                                    ?>
                                        <tr>
                                            <td><?php echo $agency->id;?></td>
                                            <td><image src="<?php echo base_url().$agency->logo_path;?>"></td>
                                            <td><a href=""><?php echo $agency->name;?><a></td>
                                            <td><a href="<?php echo $agency->website;?>"><?php echo $agency->website;?><a></td>
                                            <td><?php echo $agency->email;?></td>
                                            <td><?php echo $agency->mobile;?></td>
                                            <td>
                                                <a href="/dashboard/edit?agency_id=<?php echo $agency->id;?>" class="btn btn-rounded btn-sm btn-outline-danger edit_agency" id="edit_<?php echo $agency->id;?>">Edit</a>
                                                <a href="/dashboard/delete?agency_id=<?php echo $agency->id;?>" class="btn btn-rounded btn-sm btn-outline-danger delete_agency" id="delete_<?php echo $agency->id;?>">Delete</a>
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
