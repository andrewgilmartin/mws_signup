<?php
require_once 'common-include.php';

$passcode = rs('passcode', gen_uuid());
$script = rs('script', "");

if ( array_key_exists( 'reset', $_REQUEST ) ) {
	unset( $_SESSION['script'] );
	unset( $_SESSION['passcode'] );
	unset( $_REQUEST['script'] );
	unset( $_REQUEST['passcode'] );
	http_redirect($base_href, "create-event-page.php");
}
else if ( array_key_exists( 'create', $_REQUEST ) ) {
	if ( rs('magicnumber') != $magicnumber ) {
		$_SESSION['script'] = $script;
		$_SESSION['passcode'] = $passcode;
		$_SESSION[$ERROR_MESSAGE_KEY] = "Magic number is incorrect. Not saving changes.";		
		http_redirect($base_href, "create-event-page.php");
	}
	else {
		try {
			$event = EventParser::fromScript($script,$contacts);
			$event->setPassCode($passcode);
			$event->setScript($script);
			$repository->addEvent($event);			
			unset( $_SESSION['script'] );
			unset( $_SESSION['passcode'] );
			http_redirect($base_href, 'event-page.php', 'event', $event->getId() );
		}
		catch ( Exception $e ) {
			$_SESSION['script'] = $script;
			$_SESSION['passcode'] = $passcode;
			$_SESSION[$ERROR_MESSAGE_KEY] = "Unable to create event due to error: ".$e->getMessage().' '.$e->getTraceAsString();
			http_redirect($base_href, "create-event-page.php");
		}		
	}
}
?>
<html>
	<head>
    	<link rel="stylesheet" type="text/css" href="common.css">
    	<link media="screen" rel="stylesheet" type="text/css" href="browser.css">
		<link media="handheld, only screen and (max-device-width: 480px)" href="mobile.css" type="text/css" rel="stylesheet" />
	</head>
	<body>
		<div class="page-content">

			<h1>Create Event</h1>

<?php
			require_once 'message-include.php';
?>
			<p>
				See the <a href="create-event-help-page.php" target="_new">help</a> page for details on scripting an event.
			</p>
			<form method="POST">
			    <input type="hidden" name="passcode" value="<?=htmlspecialchars($passcode)?>" />
				<p>
				<textarea name="script" rows="20" style="width: 100%" wrap="off"><?=htmlspecialchars($script)?></textarea>
				</p>
				<p>
				<b>Passcode:</b>&nbsp;<?=htmlspecialchars($passcode)?>
				</p>
				<p>
				<b>Magic Number:</b>&nbsp;<input type="text" name="magicnumber" size="36" value="" autocomplete="off" />
				(The magic number starts with <tt><?= substr( $magicnumber, 0, 7 ) ?></tt>)
				</p>
				<p>
				<input type="submit" name="create" value="Create Event"/>
				<input type="submit" name="reset" value="*** Start Over ***"/>
				</p>
			</form>
			<div class="bottom-links">
				<a href="events-page.php">Back to events</a>
			</div>	
		</div>
	</body>
</html>
