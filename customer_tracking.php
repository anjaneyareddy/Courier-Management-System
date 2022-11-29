<?php include 'header.php' ?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Courier Management System</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item ">
        <a class="nav-link" href="login.php">Admin/Staff <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item active" >
        <a class="nav-link" href="customer_tracking.php">Customer</a>
      </li>
      
  </div>
</nav>


    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Track</h1>
          </div><!-- /.col -->

        </div><!-- /.row -->
            <hr class="border-primary">
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="col-lg-12">
            <div class="card card-outline card-primary">
                <div class="card-body">
                    <div class="d-flex w-100 px-1 py-2 justify-content-center align-items-center">
                        <label for="">Enter Tracking Number</label>
                        <div class="input-group col-sm-5">
                            <input type="search" id="ref_no" class="form-control form-control-sm" placeholder="Type the tracking number here">
                            <div class="input-group-append">
                                <button type="button" id="track-btn" class="btn btn-sm btn-primary btn-gradient-primary">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="timeline" id="parcel_history">
                        
                    </div>
                </div>
            </div>
        </div>
      </div>
    </section>

<div id="clone_timeline-item" class="d-none">
	<div class="iitem">
	    <i class="fas fa-box bg-blue"></i>
	    <div class="timeline-item">
	      <span class="time"><i class="fas fa-clock"></i> <span class="dtime">12:05</span></span>
	      <div class="timeline-body">
	      	asdasd
	      </div>
	    </div>
	  </div>
</div>
<?php include 'footer.php' ?>

<script>
	function track_now(){
		// start_load()
		var tracking_num = $('#ref_no').val()
		if(tracking_num == ''){
			$('#parcel_history').html('')
			// end_load()
		}else{
			$.ajax({
				url:'tracking.php',
				method:'POST',
				data:{ref_no:tracking_num},
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error')
					// end_load()
				},
				success:function(resp){
                    console.log(resp);
					if(typeof resp === 'object' || Array.isArray(resp) || typeof JSON.parse(resp) === 'object'){
						resp = JSON.parse(resp)
						if(Object.keys(resp).length > 0){
							$('#parcel_history').html('')
							Object.keys(resp).map(function(k){
								var tl = $('#clone_timeline-item .iitem').clone()
								tl.find('.dtime').text(resp[k].date_created)
								tl.find('.timeline-body').text(resp[k].status)
								$('#parcel_history').append(tl)
							})
						}
					}else if(resp == 2){
						alert_toast('Unkown Tracking Number.',"error")
					}
				}
				,complete:function(){
					// end_load()
				}
			})
		}
	}
	$('#track-btn').click(function(){
		track_now()
	})
	$('#ref_no').on('search',function(){
		track_now()
	})
</script>