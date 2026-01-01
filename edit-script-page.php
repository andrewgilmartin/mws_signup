<?php
require_once 'edit-script-page-include.php';

global
    $base_href,
    $magicnumber,
    $SUCCESS_MESSAGE_KEY,
    $INFORMATION_MESSAGE_KEY,
    $ERROR_MESSAGE_KEY,
    $repository,
    $contacts,
    $eventId,
    $event,
    $dayId,
    $day,
    $shiftId,
    $shift;

?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="common.css">
		<link media="screen" rel="stylesheet" type="text/css" href="browser.css">
		<link media="handheld, only screen and (max-device-width: 480px)" href="mobile.css" type="text/css" rel="stylesheet" />
	</head>
	<body>
		<div class="page-content">
			<h1>Edit Script</h1>
<?php
			require_once 'message-include.php';
?>
			<p>
				See the <a href="create-event-help-page.php" target="_new">help</a> page for details on scripting an event.
			</p>
			<form method="POST">
				<input type="hidden" name="version" value="<?=htmlspecialchars($version)?>"/>
				<p>
				<textarea name="script" rows="20" style="width: 100%" wrap="off"><?=htmlspecialchars($event->getScript())?></textarea>
				</p>
				<p>
				<b>Passcode:</b>&nbsp;<input type="text" name="passcode" size="36" value="" autocomplete="off" />
				(The passcode starts with <tt><?= substr( $event->getPasscode(), 0, 7 ) ?></tt>)
				</p>
				<p>
				<input type="submit" value="Save Script (with backup)"/>
				</p>
			</form>
			<div class="bottom-links">
				<a href="event-page.php?event=<?=$eventId?>">Back to schedule</a>
			</div>
		</div>
	</body>
</html>
