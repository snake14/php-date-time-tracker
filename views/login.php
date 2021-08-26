<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

<script src="https://apis.google.com/js/platform.js" async defer></script>
<meta name="google-signin-client_id" content="<?=GOOGLE_CLIENT_ID?>">
<script>
	// If we made it this far on the local network, that means that we're already logged in.
	var isLocalNetwork = <?=$isLocalNetwork?>;
	var allowed_user_emails = <?=ALLOWED_USER_EMAILS?>;
	
	if(isLocalNetwork) {
		window.location = '/tracker';
	}

	function onSignIn(googleUser) {
		var profile = googleUser.getBasicProfile();
		var email = profile.getEmail();
		// If the email is in the list of allowed email addresses, redirect to the main page.
		if($.inArray(email, allowed_user_emails) !== -1) {
			window.location = '/tracker';
		}
	}
</script>
<div id="mainDiv" class="container" >
	<div class="card">
		<div class="card-header">
			<div class="row">
				<div class="col" >
					<h1>Please log in.</h1>
					<h4 id="signInLabel" >You must log in using a gmail address and that address must be authorized to use this site.</h4>
				</div>
			</div>
		</div>
		<div class="card-body" >
			<div class="g-signin2 py-4" data-onsuccess="onSignIn" id="signInBtn" ></div>
		</div>
	</div>
</div>
