<div class="content-body">
    <div class="container">
        <div class="row page-titles">
            <div class="col p-0">
                <h4>Broadcasts</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <a class="btn btn-rounded btn-outline-danger add_broadcast" href="/broadcast/add">Add Broadcast</a>
                            <table class="table table-striped table-bordered zero-configuration" id="table_broadcast">
                                <thead>
                                    <tr>
                                    	<th>Id</th>
                                        <th>Title</th>
                                        <th>Agency</th>
                                        <th>Escort</th>
                                        <th>Created Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($broadcasts as $broadcast): 
                                    ?>
                                        <tr>
                                            <td><?php echo $broadcast->id;?></td>
                                            <td><?php echo $broadcast->title;?></td>
                                            <td><?php echo $broadcast->agency;?></td>
                                            <td><?php echo $broadcast->escort;?></td>
                                            <td><?php echo $broadcast->created_date;?></td>
                                            <td>
                                                <a href="/broadcast/edit?broadcast_id=<?php echo $broadcast->id;?>" class="btn btn-rounded btn-sm btn-outline-danger edit_broadcast" id="edit_<?php echo $broadcast->id;?>">Edit</a>
                                                <a href="/broadcast/delete?broadcast_id=<?php echo $broadcast->id;?>" class="btn btn-rounded btn-sm btn-outline-danger delete_broadcast" id="delete_<?php echo $broadcast->id;?>">Delete</button>
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
