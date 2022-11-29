<?php if(!isset($conn)){ include 'db_connect.php'; } ?>
<style>
  textarea{
    resize: none;
  }
</style>
<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-body">
			<form action="" id="<?php echo  isset($action)?$action:"manage-complaint" ;?>" >
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div id="msg" class=""></div>
        <div class="row">
          <div class="col-md-6">

              <div class="form-group">
                <label for="" class="control-label">Parcel Number</label>
                <input type="text" name="parcel_number" id="" class="form-control form-control-sm" value="<?php echo isset($parcel_number) ? $parcel_number : '' ?>" required>
              </div>
              <div class="form-group">
                <label for="" class="control-label">Message</label>
                <input type="text" name="message" id="" class="form-control form-control-sm" value="<?php echo isset($message) ? $message : '' ?>" required>
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
          </div>
          
        </div>
        
      </form>
  	</div>
  	<div class="card-footer border-top border-info">
  		<div class="d-flex w-100 justify-content-center align-items-center">
  			<button class="btn btn-flat  bg-gradient-primary mx-2" form="<?php echo isset($action)?$action:"manage-complaint" ;?>" >Save</button>
  			<a class="btn btn-flat bg-gradient-secondary mx-2" href="./index.php?page=complaints">Cancel</a>
  		</div>
  	</div>
	</div>
</div>

<script>
  
	$('#manage-complaint').submit(function(e){
		e.preventDefault()
		
		$.ajax({
			url:'ajax.php?action=save_complaint',
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
                    location.href = 'index.php?page=complaints';
                    },2000)
                }
                if(resp == 2){
                    alert_toast('Please check parcel number',"error");
                    
                }
			}
		})
	});
  
	debugger;$('#update-complaint').submit(function(e){
		e.preventDefault()
		
		$.ajax({
			url:'ajax.php?action=update_complaint',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
			
                if(resp == 1){
                    alert_toast('Data successfully updated',"success");
                    setTimeout(function(){
                    location.href = 'index.php?page=complaints';
                    },2000)
                }
                if(resp == 2){
                    alert_toast('Please check parcel number',"error");
                    
                }
			}
		})
	});
//   function displayImgCover(input,_this) {
//       if (input.files && input.files[0]) {
//           var reader = new FileReader();
//           reader.onload = function (e) {
//             $('#cover').attr('src', e.target.result);
//           }

//           reader.readAsDataURL(input.files[0]);
//       }
  
  
</script>