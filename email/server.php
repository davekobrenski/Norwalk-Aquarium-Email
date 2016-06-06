<?php include('functions/all_fns.php'); ?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Maritime Aquarium Performance Logs</title>
		
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
					<li<?=($filename=="server" ? ' class="active"' : '')?>><a href="server.php">Performance Logs</a></li>
				</ul>
				<p class="navbar-text navbar-right">Server: <?=$_SERVER["SERVER_NAME"]?></p>
			</div><!--/.nav-collapse -->
		</div>
	</div>
	
	<div class="container top" role="main">
		
		<h3>Server &amp; Mail Performance Logs</h3>
		<p>Mail processing speed &amp; user wait times, by web server and SMTP mail server.</p>
		<p><a href="server.php" class="btn btn-md btn-success"><i class="fa fa-refresh"></i> Reload Results</a></p>
		<hr style="margin: 30px 0;">
		
		<p>Data below is live. Add to this data by running more tests using the buttons for each server below.</p>
		
		<div class="row">	
			<?php
				$logFiles = array();
				//$logFiles["norwalk"] = "http://jbkinteractive.org/email/log.txt";
				$logFiles["rlmg"] = "http://norwalk.rlmg2.com/email/log.txt";
				
				//$testUrls["norwalk"] = "http://jbkinteractive.org/email/index.php";
				$testUrls["rlmg"] = "http://norwalk.rlmg2.com/email/index.php";
				
				$logData = array();
				$averages = array();
				$errors = array();
				$allAttachments = array();
				$rawData = array();
				//clean up log files
				foreach($logFiles as $server=>$file) {
					$data = file_get_contents($file);
					$rawData["$server"] = $data;
					$lines = explode("\n",$data);
					if(is_array($lines) && count($lines) > 2) {
						foreach($lines as $line) {
							$data = explode("|", $line);
							if(count($data) == 4) {
								$mailKey = trim($data[1]);
								$attachments = trim($data[2]);
								$time = trim(str_replace(" seconds", "", $data[3]));
								if(stripos($time, 'error') === false) {
									$averages["$server"]["$mailKey"][] = (float)$time;
								} else {
									$errors["$server"]["$mailKey"][] = 1;
								}
								$allAttachments["$server"]["$mailKey"][] = (float)$attachments;
								$logData["$server"]["$mailKey"][] = $data;
							}
						}
					}
				}
				
				//ouput results
				foreach($logData as $server=>$data) {
					$avgs = $averages["$server"];
					echo '<div class="col-sm-12">
						<div class="panel panel-'.($server =='rlmg' ? 'info' : 'success').'">
							<div class="panel-heading">
								<h3 class="panel-title">
									'.strtoupper($server).' Server 
								</h3>
							</div>
							
								<table class="table table-striped">
									<thead>
										<tr>
											<th>Mail server</th>
											<th>Avg. send time <span class="small hidden-xs hidden-sm" style="font-weight:normal">(seconds)</span></th>
											<!-- <th>Avg. attachments</th> -->
											<th>Errors</th>
											<th>Tests</th>
											<th>Success Rate</th>
										</tr>
									</thead>';
	
									foreach($avgs as $smtp=>$times) {
										if($smtp == "rlmg.json") {
											$name = "mail.rlmg2.com";
										} else {
											$name = "mail.rlmg2.com"; //need to update eventually with client actual server if different...
										}
										$averageTime = number_format(array_sum($times) / count($times), 2);
										$averageAttach = ceil(array_sum($allAttachments["$server"]["$smtp"]) / count($times));
										
										$serverErrors = 0;
										
										if(isset($errors["$server"]["$smtp"])) {
											$serverErrors = array_sum($errors["$server"]["$smtp"]);
										}
										
										$testsDone = count($logData["$server"]["$smtp"]);
										
										$successRate = number_format((($testsDone - $serverErrors) * 100) / $testsDone, 1);
										
										if( ($server =='rlmg' && $smtp == 'rlmg.json') || ($server =='jfk' && $smtp == 'smtp.json')) {
											echo '<tr>
												<td>'.$name.'</td>
												<td>'.$averageTime.'</td>
												<!-- <td>'.$averageAttach.'</td> -->
												<td>'.$serverErrors.'</td>
												<td>'.$testsDone.'</td>
												<td>'.$successRate.'%</td>
											</tr>';
										}	
									}
								echo '</table>
							<div class="panel-footer">
								<a href="'.$testUrls["$server"].'" target="_blank" class="_pull-right btn btn-xs btn-default">Run Tests</a> 
								<a href="'.$logFiles["$server"].'" target="_blank" class="btn btn-xs btn-default">View Log</a>
							</div>
						</div>
					</div>';
				}
			?>
			<div class="col-sm-12">
				<h2>Server Logs</h2>
				<p>Below are the logs for the mail send scripts for each of the servers we're testing on, from which the above statistics are derived. In the second column of data, the <code>.json</code> file references which mail settings were used for that send instance: <mark>rlmg.json</mark> uses the RLMG mail server, and <mark>smtp.json</mark> uses the client server mail settings.</p>
			<hr></div>
		</div>
	</div>
	<div class="container" style="margin-bottom:50px">
		<div class="row">
			<?php	
				foreach($rawData as $server=>$data) {
					echo '<div id="log-ouput" class="col-md-12" style="margin-bottom:15px">
						<h4>'.strtoupper($server).' Server Log:</h4>
						<div class="log-data" style="height:300px;overflow-y:scroll">
							'.arr($data, true, "_language-markup").'
						</div>
					</div>';
				}	
			?>		
		</div>
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="http://getbootstrap.com/dist/js/bootstrap.min.js"></script>
	<script src="js/app.js?v=1.39"></script>
	</body>
</html>