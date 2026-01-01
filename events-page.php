<?php
require_once 'event-page-include.php';

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

$eventRecords = $repository->getEventRecords();

?>
<html>
	<head>
    	<link rel="stylesheet" type="text/css" href="common.css">
    	<link media="screen" rel="stylesheet" type="text/css" href="browser.css">
		<link media="handheld, only screen and (max-device-width: 480px)" href="mobile.css" type="text/css" rel="stylesheet" />
	</head>
	<body>
		<div class="page-content">

			<h1>Events</h1>

<?php
			require_once 'message-include.php';

			if ( count($eventRecords) == 0 ) {
?>
				<p>There are no events currently</p>
<?php
			}
			else {
?>
				<ul>
<?php
					foreach ( $eventRecords as $eventRecord ) {
?>
						<li><a href="event-page.php?event=<?=$eventRecord['id']?>"><?= htmlspecialchars($eventRecord['name'])?></a></li>
<?php
					}
?>
				</ul>
<?php
			}
?>
			<div class="bottom-links">
				<a href="create-event-page.php">Create event</a>
			</div>
		</div>
	</body>
</html>
