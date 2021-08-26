<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

<!-- Icons -->
<script src="https://kit.fontawesome.com/13e3bb2a51.js" crossorigin="anonymous"></script>

<script src="https://apis.google.com/js/client:platform.js?onload=start" async defer></script>
<script>
	var auth2;
	var isLocalNetwork = <?=$isLocalNetwork?>;
	var hasHttpAuth = <?=$hasHttpAuth?>;
	var allowed_user_emails = <?=ALLOWED_USER_EMAILS?>;

	$(function(){
		$('.trigger-modal').on('click',function(){
			var dataURL = $(this).attr('data-href');
			$('.modal-content').load(dataURL,function(){
				$('#myModal').modal({show:true});
			});
		});

		$('#addRecord').on('click', function() {
			// Add a new record to the table
			request = $.ajax({
				url: "tracking/create",
				type: "post",
			});

			request.done(function (json_response, textStatus, jqXHR){
				var response = $.parseJSON(json_response);
				if(response.success) {
					location.reload();
				} else {
					console.log(json_response);
					alert('There was an issue adding the record');
				}
			});

			request.fail(function (jqXHR, textStatus, errorThrown){
				console.error(
					"The following error occurred: "+
					textStatus, errorThrown
				);
				alert('There was an error while adding the record');
			});
		});

		$('.delete-btn').on('click', function(){12-31-1969
			if (confirm('Are you sure you want to delete the record?')){
				$.post('tracking/' + $(this).data('id') + '/delete', {}, function(result) {
					var response = $.parseJSON(result);
					if(response.success) {
						location.reload();
					} else {
						console.log(result);
						alert('There was an issue deleting the record');
					}
				}).fail(function() {
					alert('There was an issue deleting the record');
				});
			}
		});

		$('.edit-btn').on('click', function(){
			$.get('tracking/' + $(this).data('id') + '/edit', function() {});
		});

		$('#signOutBtn').on('click', function() {
			signOut();
		});

		if(isLocalNetwork) {
			$('#signOutBtn').hide();
		}
	});

	function signOut() {
		var auth2 = gapi.auth2.getAuthInstance();
		auth2.signOut().then(function () {
			window.location = 'login';
		});
	}

	function start() {
		gapi.load('auth2', function() {
			auth2 = gapi.auth2.init({
				client_id: '<?=GOOGLE_CLIENT_ID?>',
				// Scopes to request in addition to 'profile' and 'email'
				//scope: 'additional_scope'
			}).then(function(auth2) {
				if(auth2.isSignedIn.get()) {
					var profile = auth2.currentUser.get().getBasicProfile();
					var email = profile.getEmail();
					// If the email isn't in the list of allowed email addresses, sign out.
					if($.inArray(email, allowed_user_emails) === -1) {
						signOut();
					}
				} else {
					if(!isLocalNetwork || !hasHttpAuth) {
						signOut();
					}
				}
			});

			
		});
	}
</script>

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

<body >
	<div class="container h-100" >
		<div class="card h-100">
			<div class="card-header">
				<div class="row">
					<div class="col"><h1>Tracker</h1></div>
					<div class="col text-right"><span id="signOutBtn" class="btn btn-secondary" >Sign Out</span></div>
				</div>
				<button id="addRecord" class="btn btn-primary" >Record</button>
				<div class="pt-3"><?=$diff_result?></div>
			</div>
			<div class="card-body p-0 overflow-auto" >
			<?php
			if(!is_array($result) || empty($result) || !$result['success'] || empty($result['records'])) {
				echo '<h3>No records found</h3>';
			} else { ?>
				<div class="list-group">
				<?php foreach($result['records'] as $record) { ?>
					<div class="list-group-item">
						<span class="pr-2 date-time" data-id="<?=$record['id']?>" ><?=(date('m/d/y h:i a', strtotime($record['dt'])))?></span>
						<a class="btn fas fa-pencil-alt trigger-modal" href="javascript:void(0);" data-href="tracking/<?=$record['id']?>/edit" title="Edit date/time" ></a>
						<i class="btn fas fa-trash-alt delete-btn" data-id="<?=$record['id']?>" title="Delete record"></i>
					</div>
				<?php } ?>
				</div>
			<?php } ?>
			</div>
			<div class="card-footer"></div>
		</div>
	</div>
	<div class="modal fade" id="myModal" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content"></div>
		</div>
	</div>
</body>