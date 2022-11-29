<?php
include 'db_connect.php';
$parcel_id =$_GET['id'];
$qry = $conn->query("SELECT * FROM complaints where id = ".$_GET['id'])->fetch_assoc();
foreach($qry as $k => $v){
	$$k = $v;
}
$qry2 = $conn->query("SELECT * FROM users where id = ".$created_by)->fetch_assoc();
foreach($qry2 as $k => $v){
	$$k = $v;
}		
			$qry1 = $conn->query("SELECT * FROM parcels where reference_number = '".$qry['parcel_number']."'")->fetch_array();
			foreach($qry1 as $k => $v){
				$$k = $v;
			}
			if($to_branch_id > 0 || $from_branch_id > 0){
				$to_branch_id = $to_branch_id  > 0 ? $to_branch_id  : '-1';
				$from_branch_id = $from_branch_id  > 0 ? $from_branch_id  : '-1';
			$branch = array();
			$branches = $conn->query("SELECT *,concat(street,', ',city,', ',state,', ',zip_code,', ',country) as address FROM branches where id in ($to_branch_id,$from_branch_id)");
				while($row = $branches->fetch_assoc()):
					$branch[$row['id']] = $row['address'];
				endwhile;
			}
		

?>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row">
			<div class="col-md-12">
				<div class="callout callout-info">
					<dl>
						<dt>Tracking Number:</dt>
						<dd> <h4><b><?php echo $reference_number ?></b></h4></dd>
					</dl>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-4">
				<div class="callout callout-info">
					<b class="border-bottom border-primary">Complaint Information</b>
					<dl>
						<dt>Name:</dt>
						<dd><?php echo ucwords($firstname.' '.$middlename.' '.$lastname); ?></dd>
						<dt>Address:</dt>
						<dd><?php echo ucwords($address) ?></dd>
						<dt>Contact:</dt>
						<dd><?php echo ucwords($contact) ?></dd>
					</dl>
				</div>
				<div class="callout callout-info">
					<b class="border-bottom border-primary">Replay</b>
					<dl>
						<div class="card card-outline card-primary">
							<div class="card-body">
								<form action="" id="replay-complaint">
									<input type="hidden" id="parcel_id" name="parcel_id" value="<?php echo isset($parcel_id) ? $parcel_id : '' ?>">
									<div id="msg" class=""></div>
										
									<div class="form-group">
										<label for="" class="control-label">Parcel Number</label>
										<input type="text" name="parcel_number" readonly id="parcel_number" class="form-control form-control-sm" value="<?php echo isset($reference_number) ? $reference_number : '' ?>" required>
									</div>
									<div class="form-group">
										<label for="" class="control-label">Message</label>
										<input type="text" name="comment" id="comment" class="form-control form-control-sm" value="<?php echo isset($comment) ? $comment : '' ?>" required>
									</div>
									<div class="form-group" id="fbi-field">
										<label for="" class="control-label">Status</label>
										<select name="status" id="status" class="form-control select2" required="">
											<option value="1">Open</option>
											<option value="2">Close</option>
											<option value="3">Reopen</option>
											<option value="4">Pending</option>
										</select>
									</div>		
									
								</form>
							</div>
							<div class="card-footer border-top border-info">
								<div class="d-flex w-100 justify-content-center align-items-center">
									<button class="btn btn-flat  bg-gradient-primary mx-2" form="replay-complaint">Save</button>
									<a class="btn btn-flat bg-gradient-secondary mx-2" href="./index.php?page=complaints">Cancel</a>
								</div>
							</div>
						</div>
					</dl>
				</div>
			</div>
			<div class="col-md-8">
				<div class="callout  callout-info" >
					<div class="timeline" id="complaints_history" >
						<div id="clone_timeline-item" class="d-none">
							<div class="iitem">
								<i class="fas bg-light "><img src="./assets/uploads/1668742020_html.png"  width="20px" height="20px" /></i>
								<div class="timeline-item">
								<span class="time"><i class="fas fa-clock"></i> <span class="dtime"><?php echo date("Y-m-d H:m:s" ,strtotime($created_by));?></span></span>
								<div class="timeline-body">
									<?php echo $message;?>
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

<div class="modal-footer display p-0 m-0">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>

<div id="clone_timeline-item" class="d-none">
	<div class="iitem">
		<i class="fas  avatar "></i>
		<div class="timeline-item">
		<span class="time"><i class="fas fa-clock"></i> <span class="dtime">nnO GEHGJHSD12:05</span></span>
		<div class="timeline-body">
			asdasd
			SDFNDFSKJ SDKJFHSKDHF SDJFSDF
		</div>
		</div>
	</div>
</div>
<style>
	#uni_modal .modal-footer{
		display: none
	}
	#uni_modal .modal-footer.display{
		display: flex
	}
</style>
<noscript>
	<style>
		table.table{
			width:100%;
			border-collapse: collapse;
		}
		table.table tr,table.table th, table.table td{
			border:1px solid;
		}
		.text-cnter{
			text-align: center;
		}
	</style>
	<h3 class="text-center"><b>Student Result</b></h3>
</noscript>
<script>
	var parcel_number =$('#parcel_number').val();
	console.log(parcel_number);
	$('#replay-complaint').submit(function(e){
		e.preventDefault()
		var parcel_id =$('#parcel_id').val();
		
		$.ajax({
			url:'ajax.php?action=save_comment',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
			
                if(resp == 1){
                    alert_toast('Data successfully saved',"success");
                    setTimeout(function(){
						$('#comment').val('');
						comments(parcel_number);
                    // location.href = 'index.php?page=view_complaints&id='+parcel_id;
                    },2000)
                }
                if(resp == 2){
                    alert_toast('Please check parcel number',"error");
                }
			}
		})
	});

	comments(parcel_number);


	function comments(ref_no){
		// start_load()
		console.log(ref_no);
		if(ref_no == ''){
			$('#complaints_history').html('')
			end_load()
		}else{
			$.ajax({
				url:'ajax.php?action=get_complaint_history',
				method:'POST',
				data:{ref_no:ref_no},
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error')
					end_load()
				},
				success:function(resp){
					if(typeof resp === 'object' || Array.isArray(resp) || typeof JSON.parse(resp) === 'object'){
						resp = JSON.parse(resp)
						if(Object.keys(resp).length > 0){
							$('#complaints_history').html('')
							Object.keys(resp).map(function(k){
								var avatar ='<span class="brand-image img-circle elevation-2 d-flex justify-content-center align-items-center  text-white font-weight-300" style="background-color:'+random_rgba()+'" >'+resp[k].avatar+'</span>';
								var tl = $('#clone_timeline-item .iitem').clone()
								tl.find('.dtime').text(resp[k].created_at)
								tl.find('.timeline-body').text(resp[k].comment)
								tl.find('.avatar').html(avatar)

								$('#complaints_history').append(tl)
							})
							end_load()
						}

					}else if(resp == 2){
						alert_toast('Unkown Parcel Number.',"error")

					}
				},
				complete:function(){
					end_load()
				}
			})
		}
	}

	function random_rgba() {
		var o = Math.round, r = Math.random, s = 255;
		return 'rgba(' + o(r()*s) + ',' + o(r()*s) + ',' + o(r()*s) + ',' + r().toFixed(1) + ')';
	}

// var color = random_rgba();

	var tl = $('#clone_timeline-item .iitem').clone();
	tl.find('.dtime').text("Test 1");
	tl.find('.timeline-body').text("teste sajdhkadhkas");
	$('#complaints_history').append(tl);
	// $('#update_status').click(function(){
	// 	uni_modal("Update Status of: <?php echo $reference_number ?>","manage_parcel_status.php?id=<?php echo $id ?>&cs=<?php echo $status ?>","")
	// })
</script>