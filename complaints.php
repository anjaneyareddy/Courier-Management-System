<?php include'db_connect.php';
include 'admin_class.php';
include 'htmltags.php';
$htmltags =new Htmltags();
$action =new Action();
?>
<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<div class="card-tools">
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary " href="./index.php?page=new_complaint"><i class="fa fa-plus"></i> Add New</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Reference Number</th>
						<th>Complaint</th>
						<th>Created By</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$where = "";
					// if(isset($_GET['s'])){
					// 	$where = " where status = {$_GET['s']} ";
					// }
					// if($_SESSION['login_type'] != 1 ){
					// 	if(empty($where))
					// 		$where = " where ";
					// 	else
					// 		$where .= " and ";
					// 	$where .= " (from_branch_id = {$_SESSION['login_branch_id']} or to_branch_id = {$_SESSION['login_branch_id']}) ";
					// }
					$qry = $conn->query("SELECT * from complaints $where order by  unix_timestamp(created_date) desc ");
					
					if (!empty($qry) && $qry->num_rows > 0) {
					while($row= $qry->fetch_assoc()){
					?>
					<tr>
						<td class="text-center"><?php echo $i++ ?></td>
						<td><b><?php echo ($row['parcel_number']) ?></b></td>
						<td><b><?php echo ucwords($row['message']) ?></b></td>
						<td><b><?php echo !empty($action->get_userdetails($row['created_by']))?$action->get_userdetails($row['created_by'])['user_name']:"Undefined" ;?></b></td>
						<td class="text-center">
							<?php 
							switch ($row['status']) {
								case '1':
									$htmltags->badge_pills('Opened','success');
									break;
								case '2':
									$htmltags->badge_pills('Closed','danger');
									break;
								case '3':
									$htmltags->badge_pills('Reopen','warning');
									break;
								case '4':
									$htmltags->badge_pills('Pending','info');
									break;
								
								default:
									$htmltags->badge_pills('Removed' ,'danger');									
									break;
							}

							?>
						</td> 
						<td class="text-center">
		                    <div class="btn-group">
		                    	<button type="button" class="btn btn-info btn-flat view_complaint" data-id="<?php echo $row['id'] ?>">
		                          <i class="fas fa-eye"></i>
		                        </button>
		                        <a href="index.php?page=edit_complaint&id=<?php echo $row['id'] ?>" class="btn btn-primary btn-flat ">
		                          <i class="fas fa-edit"></i>
		                        </a>
		                        <button type="button" class="btn btn-danger btn-flat delete_complaint" data-id="<?php echo $row['id'] ?>">
		                          <i class="fas fa-trash"></i>
		                        </button>
	                      </div>
						</td>
					</tr>	
				<?php } 
					}else{
						echo "<tr colspan='5'>No Result</tr>";
					}
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<style>
	table td{
		vertical-align: middle !important;
	}
</style>
<script>
	$(document).ready(function(){
		$('#list').dataTable()
		$('.view_complaint').click(function(){
			uni_modal("Complaint Details","view_complaint.php?id="+$(this).attr('data-id'),"large")
		})
	$('.delete_complaint').click(function(){
	_conf("Are you sure to delete this complaint?","delete_complaint",[$(this).attr('data-id')])
	})
	})
	function delete_complaint($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_complaint',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>