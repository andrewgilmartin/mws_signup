<?php
require_once 'common-include.php';

$VOLUNTEER_PROPERTY_INPUT_PREFIX = "volunteer-property-";

if ( ! isset($shift) ) {
	$_SESSION[$ERROR_MESSAGE_KEY] = "Valid shift id is missing. Unable to display shift details.";
	http_redirect( $base_href, "event-page.php", 'event', $eventId, 'day', $dayId );
}

$shiftSummary = sprintf( "%s %s shift at %s-%s on %s %s",
	$shift->getActivity()->getName(),
	$shift->getRole()->getName(),
	hourtostr($shift->getStarting()),
	hourtostr($shift->getEnding()),
	$shift->getDay()->getName(),
	timetostr($shift->getDay()->getDate())
);

if ( is_http_post_request() ) {	
	if ( array_key_exists('assign',$_REQUEST) ) {		

		if ( $shift->getVolunteer() ) {
			$_SESSION[$ERROR_MESSAGE_KEY] = $shift->getVolunteer()->getContact()->getName() . " has already volunteered for $shiftSummary.";
			http_redirect( $base_href, 'volunteer-page.php', 'event', $eventId, 'day', $dayId, 'shift', $shiftId );
		}		
		
		$name = array_key_exists('name',$_REQUEST ) ? trim($_REQUEST["name"]) : "";
		$email = array_key_exists( 'email', $_REQUEST ) ? trim($_REQUEST['email']) : "";
		$telephone = array_key_exists( 'telephone', $_REQUEST ) ? trim($_REQUEST['telephone']) : "";

		$contact = Contact::pickContact( $contacts, $name, $email, $telephone );
		if ( ! $contact ) {
			
			if ( is_empty( $name ) || ( is_empty( $email ) && is_empty( $telephone ) ) ) {
				$_SESSION['name'] = $name;
				$_SESSION['email'] = $email;
				$_SESSION['telephone'] = $telephone;
				$_SESSION[$ERROR_MESSAGE_KEY] = "Must provide a name and either an email address or/and a telephone number.";
				http_redirect( $base_href, 'volunteer-page.php', 'event', $eventId, 'day', $dayId, 'shift', $shiftId );
			}
			
			$contact = new Contact();
			$contact
				->setName($name)
				->setEmail($email)
				->setTelephone($telephone);
			$repository->addContact($contact);
		}
	
		$volunteer = new Volunteer();
		$volunteer->setContact($contact);
		foreach ( $event->getVolunteerProperties() as $volunteerProperty ) {
			$inputname = $VOLUNTEER_PROPERTY_INPUT_PREFIX.$volunteerProperty->getId();
			if ( array_key_exists( $inputname, $_REQUEST ) ) {
				$value = trim(stripslashes($_REQUEST[$inputname]));
				$volunteer->addProperty( $volunteerProperty->getName(), $value );
			}
		}
		$shift->setVolunteer($volunteer);
		$repository->addVolunteer($volunteer);
				
		unset($_SESSION['name']);
		unset($_SESSION['email']);
		unset($_SESSION['telephone']);
		
		$_SESSION[$SUCCESS_MESSAGE_KEY] = $contact->getName() . " volunteered for $shiftSummary.";
	}
	elseif ( array_key_exists('cancel',$_REQUEST)) {
		if ( $shift->getVolunteer() ) {
			$c = $shift->getVolunteer()->getContact();
			$repository->removeVolunteer($shift->getVolunteer()->getId());
			$shift->setVolunteer(null);
			$_SESSION[$SUCCESS_MESSAGE_KEY] = $c->getName()." canceled for $shiftSummary.";
		}
		else {
			$_SESSION[$WARNING_MESSAGE_KEY] = "There is no volunteer for $shiftSummary.";
		}
	}

	http_redirect( $base_href, 'event-page.php', 'event', $eventId, 'day', $dayId );
}
else {
	if ( $shift->getVolunteer() ) {
		$name = $shift->getVolunteer()->getContact()->getName();
		$email = $shift->getVolunteer()->getContact()->getEmail();
		$telephone = $shift->getVolunteer()->getContact()->getTelephone();
	}
	else {
		$name = array_key_exists('name', $_SESSION) ? $_SESSION['name'] : "";
		$email = array_key_exists('email', $_SESSION) ? $_SESSION['email'] : "";
		$telephone = array_key_exists('telephone', $_SESSION) ? $_SESSION['telephone'] : "";
	}
}

?>
