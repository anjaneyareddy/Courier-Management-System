<?php include 'db_connect.php' ?>
<!-- <?php $status = isset($_GET['status']) ? $_GET['status'] : 'all' ?> -->
<div class="col-lg-12">
	
	<div class="row">
		<div class="col-md-12 ">
			<div class="card">
				<div class="card-body">
					<div class="row">
						<div class="col-md-12">
        					<button type="button" class="btn btn-success float-right" style="display: none" id="print"><i class="fa fa-print"></i> Print</button>
						</div>
					</div>	
					
					<table class="table table-bordered" id="report-list">
						<thead>
							<tr>
								<th>#</th>
								<th>Date</th>
								<th>Created By</th>
								<th>Parcel Number</th>
								<th>Complaint</th>
								<!-- <th>Status</th> -->
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
			</div>
			
		</div>
	</div>
</div>
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
	<h3 class="text-center"><b> Complaints Report</b></h3>
</noscript>

<script>
	function load_report(){
		start_load()
		// var date_from = $('#date_from').val()
		// var date_to = $('#date_to').val()
		// var status = $('#status').val()
			$.ajax({
				url:'ajax.php?action=get_complaint_report',
				method:'POST',
				data:'',
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error')
					end_load()
				},
				success:function(resp){
					console.log(resp)
					if(typeof resp === 'object' || Array.isArray(resp) || typeof JSON.parse(resp) === 'object'){
						resp = JSON.parse(resp)
						if(Object.keys(resp).length > 0){
							$('#report-list tbody').html('')
							var i =1;
							Object.keys(resp).map(function(k){
								var tr = $('<tr></tr>')
								tr.append('<td>'+(i++)+'</td>')
								tr.append('<td>'+(resp[k].date_created)+'</td>')
								tr.append('<td>'+(resp[k].user['firstname'])+' '+(resp[k].user['lastname'])+'</td>')
								tr.append('<td>'+(resp[k].parcel_number)+'</td>')
								tr.append('<td>'+(resp[k].message)+'</td>')
								// tr.append('<td>'+(resp[k].status)+'</td>')
								$('#report-list tbody').append(tr)
							})
							$('#print').show()
						}else{
							$('#report-list tbody').html('')
								var tr = $('<tr></tr>')
								tr.append('<th class="text-center" colspan="5">No result.</th>')
								$('#report-list tbody').append(tr)
							$('#print').hide()
						}
					}
				}
				,complete:function(){
					end_load()
				}
			})
	}

$(document).ready(function(){
	load_report()
})
$('#print').click(function(){
		start_load()
		var ns = $('noscript').clone()
		var details = $('.details').clone()
		var content = $('#report-list').clone()
		var date_from = $('#date_from').val()
		var date_to = $('#date_to').val()
		var status = $('#status').val()
		var stat_arr = '<?php echo json_encode($status_arr) ?>';
			stat_arr = JSON.parse(stat_arr);
		details.find('.drange').text(date_from+" to "+date_to )
		if(status>-1)
		details.find('.status-field').text(stat_arr[status])
		ns.append(details)

		ns.append(content)
		var nw = window.open('','','height=700,width=900')
		nw.document.write(ns.html())
		nw.document.close()
		nw.print()
		setTimeout(function(){
			nw.close()
			end_load()
		},750)

	})
</script>