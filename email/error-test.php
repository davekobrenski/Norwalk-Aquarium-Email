<?php
	header('Content-Type: text/html');
	ini_set('default_charset', 'utf-8');
	
	
?><html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>JFK Performance Logs</title>
		
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
				<a class="navbar-brand" href="index.php">JFK Library</a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					
					<li<?=($filename=="index" ? ' class="active"' : '')?>><a href="index.php">Email test</a></li>
					<li<?=($filename=="server" ? ' class="active"' : '')?>><a href="server.php">Performance Logs</a></li>
					<li<?=($filename=="error-test" ? ' class="active"' : '')?>><a href="error-test.php">Error Test</a></li>
				</ul>
				<p class="navbar-text navbar-right">Server: <?=$_SERVER["SERVER_NAME"]?></p>
			</div><!--/.nav-collapse -->
		</div>
	</div>
	<div class="container top" role="main">
	
		<div class="row">
			<div class="col-md-12">
				<h3>Error Handling</h3>
				<p>We'll force PHP to throw an error, using <code>trigger_error()</code>, to see how the server handles it. </p>
				<p>The PHP error below should not cause the script to die, so anything below the error should still get output.</p>
				<hr>
			</div>
			
			<div class="col-md-12">
				<pre><code class="language-php"><?php echo htmlentities('<?php
	trigger_error("This is a non-fatal error.", E_USER_NOTICE);
?>'); ?></code></pre>
			</div>
				
			<div class="col-md-12">	
				<pre><code class="language-markup"><?php
					trigger_error("This is a non-fatal error.", E_USER_NOTICE);
				?></code></pre>
			</div>	

			<div class="col-md-12">
				<hr>
				<p>Still alive!</p>
			</div>
		</div>
	
	</div>


	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="http://getbootstrap.com/dist/js/bootstrap.min.js"></script>
	<script src="js/app.js?v=1.39"></script>
</body>
</html>