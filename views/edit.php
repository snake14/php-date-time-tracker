<?php
// $date_time = '01/31/21 02:00 pm';
?>
<script>
	$(function() {
		$('#date_time').daterangepicker({
			singleDatePicker: true,
			timePicker: true,
			locale: {
				format: 'MM/DD/YY hh:mm a'
			}
		});

		$('#save_edit').on('click', function() {
			var request = $.ajax({
				url: 'tracking/<?=$id?>/edit',
				type: "post",
				data: { date_time: $('#date_time').val() }
			});

			request.done(function (json_response, textStatus, jqXHR){
				var response = $.parseJSON(json_response);
				if(response.success) {
					$('.date-time[data-id="<?=$id?>"]').text($('#date_time').val());
					$('#myModal').modal('hide');
				} else {
					console.log(json_response);
					alert('There was an issue updating the system');
				}
			});

			request.fail(function (jqXHR, textStatus, errorThrown){
				console.error(
					"The following error occurred: "+
					textStatus, errorThrown
				);
				alert('There was an error while updating the record.');
			});
		});
	});
</script>
<div class="modal-header">
	<h5 class="modal-title">Edit Record</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>
<div class="modal-body">
	<form>
		<input type="hidden" id="id" name="id" value="<?=$id?>" >
		<input type="text" class="form-control" id="date_time" name="date_time" value="<?=$date_time?>" >
	</form>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
	<button type="button" class="btn btn-primary" id="save_edit" >Save</button>
</div>