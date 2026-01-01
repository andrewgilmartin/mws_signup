<?php
ob_start();

global $database_address, $database_user, $database_password;

//function exceptions_error_handler($severity, $message, $filename, $lineno): void
//{
//        ?><!--<pre>--><?php
//        var_dump(debug_backtrace());
//        ?><!--</pre>--><?php
//}
//set_error_handler('exceptions_error_handler');

session_start();

require_once "site-include.php";
require_once "lib/functions.php";
require_once "lib/Activity.php";
require_once "lib/Contact.php";
require_once "lib/Day.php";
require_once "lib/Event.php";
require_once "lib/Hint.php";
require_once "lib/Hours.php";
require_once "lib/Lexer.php";
require_once "lib/Role.php";
require_once "lib/EventParser.php";
require_once "lib/EventWriter.php";
require_once "lib/Shift.php";
require_once "lib/Volunteer.php";
require_once "lib/Writer.php";
require_once "lib/Repository.php";
require_once "lib/VolunteerProperty.php";

$SUCCESS_MESSAGE_KEY = "success-message";
$ERROR_MESSAGE_KEY = "error-message";
$WARNING_MESSAGE_KEY = "warning-message";
$INFORMATION_MESSAGE_KEY = "information-message";
$ALL_MESSAGE_KEYS = array( $SUCCESS_MESSAGE_KEY, $ERROR_MESSAGE_KEY, $WARNING_MESSAGE_KEY, $INFORMATION_MESSAGE_KEY );

$repository = new Repository( $database_address, $database_user, $database_password );
$repository->connect();

$contacts = $repository->getContacts();

if ( array_key_exists( 'event', $_REQUEST ) && is_numeric( $_REQUEST['event'] ) ) {
	$eventId = $_REQUEST['event'];

	$event = $repository->findEventById($eventId, $contacts);

	if ( array_key_exists( 'day', $_REQUEST ) && is_numeric( $_REQUEST['day'] ) ) {
		$dayId = $_REQUEST['day'];
		$day = $event->findDayById($dayId);
	}

	if ( array_key_exists( 'shift', $_REQUEST ) && is_numeric( $_REQUEST['shift'] ) ) {
		$shiftId = $_REQUEST['shift'];
		$shift = $event->findShiftById($shiftId);
	}
}

?>
