<?php include('functions/all_fns.php'); ?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Maritime Aquarium email sending test</title>
		
		<link href="http://getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet">
		<link href="http://getbootstrap.com/dist/css/bootstrap-theme.min.css" rel="stylesheet">
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
		<link href="css/styles.css" rel="stylesheet">
		
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body role="document">
	
	<?php $filename = pathinfo($_SERVER["SCRIPT_NAME"], PATHINFO_FILENAME); ?>
	<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="index.php">Maritime Aquarium</a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<li<?=($filename=="index" ? ' class="active"' : '')?>><a href="index.php">Email test</a></li>
					<li<?=($filename=="server" ? ' class="active"' : '')?>><a href="server.php">Server Performance Log</a></li>
				</ul>
				
				<p class="navbar-text navbar-right">Server: <?=$_SERVER["SERVER_NAME"]?></p>
			</div><!--/.nav-collapse -->
		</div>
	</div>
	
	<div class="container top" role="main">
		
		<h3>Maritime Aquarium Email test</h3>
		<p>Send POST data to:</p>
		
		<?php
			arr("http://" . $_SERVER["HTTP_HOST"]  . dirname($_SERVER["SCRIPT_NAME"]) . "/send.php", false, "language-markup");
			arr("PHP Version: ".phpversion(), false, "language-markup");
			
			if (in_array('tls', stream_get_transports())) {
				arr("TLS ok", false, "language-markup");
			} else {
				arr("No TLS", false, "language-markup");
			}
			
			arr("Server date: " . date("F j, Y \\a\\t g:ia"), false, "language-markup");
		?>
		
		<hr style="margin-top: 30px;">
		
		<div class="row">
			<div class="col-md-12">
				<form role="form" id="send-email" style="padding-bottom:20px">
					<h4>Form data:</h4>
					
					<div class="well">
						<div class="form-group">
							<label for="user">Username: </label> <code>user</code>
							<input type="text" class="form-control" name="user" placeholder="Username" value="rlmg">
						</div>
						
						<div class="form-group">
							<label>Password:</label> <code>password</code>
							<input type="password" class="form-control" name="password" placeholder="******" value="norwalk">
						</div>
						
						<div class="form-group">
							<label>Recipient's name</label> <code>name</code>
							<input type="text" class="form-control" name="name" placeholder="Recipient's name" value="">
						</div>
						
						<div class="form-group">
							<label>Recipient's email address</label> <code>email</code>
							<input type="email" class="form-control" name="email" placeholder="Recipient's email" value="">
						</div>
						
						<div class="form-group">
							<label>Image attachment</label> <code>fileUpload</code>
							<textarea class="form-control" name="fileUpload" rows="3" placeholder="paste binary data here, eg, data:image/jpeg;base64,/..."></textarea>
						</div>
						
						<!-- <div class="form-group">
							<strong>Opt-in to the list?</strong> <code>join-list</code> <small><samp>boolean 1 or 0</samp></small>
							<div class="checkbox">
								<label><input type="checkbox" name="join-list" value="1"> Yes, sign me up</label>
							</div>
						</div> -->
						
						<div class="form-group">
							<label>SMTP Server</label> <code>smtp</code>
							<select class="form-control" name="smtp" style="width:auto">
								<?php
									if($_SERVER["SERVER_NAME"] == 'norwalk.rlmg2.com') {
										echo '<option value="smtp.json">smtp.json</option>';
									} else {
										echo '<option value="smtp.json" selected>smtp.json</option>';
									}
								?>
							</select>
						</div>
					</div>

					<button type="submit" class="btn btn-default">Send Email</button> <span id="spinner" style="display:none"><i class="fa fa-spin fa-spinner"></i></span>
				</form>
			</div>
			<div class="col-md-12" style="margin-bottom: 100px">
				<div id="form-results" style="display:none;">
					<h4>JSON Result:</h4>
					<pre><code class="language-javascript"></code></pre>
				</div>
			</div>
		</div>
		
		<?php	
			//arr($_SERVER);
		?>
		
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="http://getbootstrap.com/dist/js/bootstrap.min.js"></script>
	<script src="js/app.js?v=1.39"></script>
	</body>
</html>