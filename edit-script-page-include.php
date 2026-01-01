<?php
require_once 'common-include.php';

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

if ( array_key_exists('script',$_REQUEST)) {
	$script = trim(stripslashes($_REQUEST['script']));
}

if ( array_key_exists( 'passcode', $_REQUEST ) ) {
	$passcode = trim(stripslashes($_REQUEST['passcode']));
}

if ( array_key_exists( 'version', $_REQUEST ) ) {
	$version = trim(stripslashes($_REQUEST['version']));
}

if ( is_http_post_request() && array_key_exists('script',$_REQUEST) ) {
	if ( ! isset($passcode) || $passcode != $event->getPasscode() ) {
		$_SESSION[$ERROR_MESSAGE_KEY] = "Passcode is incorrect. Not saving changes.";
	}
	else if ( ! isset( $version ) || $version != $event->getVersion() ) {
		$_SESSION[$WARNING_MESSAGE_KEY] = "Version number is incorrect. The event's script changed during editing. Not saving changes.";
	}
	else if ( ! isset( $script ) ) {
		$_SESSION[$ERROR_MESSAGE_KEY] = "Script is empty. Not saving changes.";
	}
	else {
		try {
			$new_event = EventParser::fromScript($script, $contacts);
			if ( $new_event ) {
				$new_event->setId($event->getId());
				$new_event->setPasscode($event->getPasscode());
				$new_event->setVersion($event->getVersion());
				$new_event->setScript($script);
				$repository->updateEvent($new_event);
				$event = $new_event;
				$_SESSION[$INFORMATION_MESSAGE_KEY] = "Event updated and backup made.";
			}
			else {
				$_SESSION[$ERROR_MESSAGE_KEY] = "No event or contacts where found in the script. Not saving changes.";
			}
		}
		catch ( Exception $e ) {
			$_SESSION[$ERROR_MESSAGE_KEY] = "Error parsing script. ". $e->getMessage().' '.$e->getTraceAsString();
		}
	}
}

if ( ! isset($script) ) {
	$script = $event->getScript();
}

$version = $event->getVersion();

?>
