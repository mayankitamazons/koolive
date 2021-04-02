<div class="content-body">
	<div class="container">
		<div class="row page-titles">
			<div class="col p-0">
                <h4>View Comments</h4>
            </div>
		</div>
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-striped table-bordered zero-configuration" id="table_comment">
								<thead>
                                    <tr>
                                    	<th>id</th>
                                        <th>User</th>
                                        <th>Merchant</th>
                                        <th>Rating</th>
                                        <th>Comment</th>
                                        <th>Date</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php foreach($comments as $key => $item): ?>
                                		<tr>
                                            <td><?= ($key + 1);?></td>
                                            <td><?= $item->user_name;?></td>
                                            <td><?= $item->merchant_name;?></td>
                                            <td><?=$item->rating?></td>
                                            <td><?=$item->comment?></td>
                                            <td><?=substr($item->Created_on, 0, 10)?></td>
                                           	<td>
                                                <a href="/dashboard/edit?agency_id=<?php echo $item->id;?>" class="btn btn-rounded btn-sm btn-outline-danger edit_agency" id="edit_<?php echo $item->id;?>"><i class="fa fa-edit"></i></a>
                                                <a href="" data-target="#deleteModal" data-toggle="modal" class="btn btn-rounded btn-sm btn-outline-danger delete_agency" id="<?=$item->id;?>"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                </tbody>
							</table>
							<div id="deleteModal" class="modal fade" role="dialog">
  								<div class="modal-dialog">

								    <!-- Modal content-->
								    <div class="modal-content">
								      	<div class="modal-header">
								        	<button type="button" class="close" data-dismiss="modal">&times;</button>
								        	<h4 class="modal-title"></h4>
								      	</div>
								      	<div class="modal-body">
								        	<p>Are you sure to delete?</p>
								      	</div>
								      	<div class="modal-footer">
								      		<button type="button" class="btn btn-default confirm_btn" id="" >Ok</button>
								        	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
								      	</div>
								    </div>

							  	</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>