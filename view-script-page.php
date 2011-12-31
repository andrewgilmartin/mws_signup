<?php
require_once 'common-include.php';
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="common.css">
		<link media="screen" rel="stylesheet" type="text/css" href="browser.css">
		<link media="handheld, only screen and (max-device-width: 480px)" href="mobile.css" type="text/css" rel="stylesheet" />
	</head>
	<body>
		<div class="page-content">
			<h1>View Script</h1>
<?php
			require_once 'message-include.php';
?>
			<pre>
<?=htmlspecialchars(file_get_contents($scriptFilename))?>			
			</pre>
			<div class="bottom-links">			
				<a href="edit-script-page.php">Edit</a> |
				<a href="index.php">Back to schedule</a>
			</div>
		</div>
	</body>
</html>	