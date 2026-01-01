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

if ( ! array_key_exists('contact', $_REQUEST) ) {
	$_SESSION[$ERROR_MESSAGE_KEY] = "Missing contact id";
    httpRedirect( $base_href, "/" );
}

$id = $_REQUEST['contact'];
$contact = Contact::findById( $contacts, $id );
if ( ! $contact ) {
	$_SESSION[$ERROR_MESSAGE_KEY] = "Unknown contact id";
    httpRedirect( $base_href, "/" );
}

if ( array_key_exists( 'update', $_REQUEST ) ) {

	$name = array_key_exists( 'name', $_REQUEST ) ? trim(stripslashes($_REQUEST['name'])) : "Unnamed";
	$email = array_key_exists( 'email', $_REQUEST ) ? trim(stripslashes($_REQUEST['email'])) : "";
	$telephone = array_key_exists( 'telephone', $_REQUEST ) ? trim(stripslashes($_REQUEST['telephone'])) : "";

	try {
		$contact
			->setName($name)
			->setEmail($email)
			->setTelephone($telephone);
		$repository->updateContact( $contact );
		$_SESSION[$INFORMATION_MESSAGE_KEY] = "Updated " . makeContactSummaryHtml( $contact );
        httpRedirect($base_href, 'contacts-page.php');
	}
	catch ( Exception $e ) {
		$_SESSION['name'] = $name;
		$_SESSION['email'] = $email;
		$_SESSION['telephone'] = $telephone;
		$_SESSION[$ERROR_MESSAGE_KEY] = "Unable to update contact due to error: ".$e->getMessage().' '.$e->getTraceAsString();
        httpRedirect($base_href, 'update-contact-page.php');
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

			<h1>Update Contact</h1>
<?php
			require_once 'message-include.php';
?>
			<form method="POST">
				<input type="hidden" name="contact" value="<?= $contact->getId() ?>"/>
				<table class="volunteer">
					<tr>
						<th>Name</th>
						<td><input type="text" name="name" value="<?= htmlspecialchars( $contact->getName() ) ?>"/></td>
					</tr>
					<tr>
						<th>Email</th>
						<td><input type="text" name="email" value="<?= htmlspecialchars( $contact->getEmail() ) ?>"/></td>
					</tr>
					<tr>
						<th>Telephone</th>
						<td><input type="text" name="telephone" value="<?= htmlspecialchars( $contact->getTelephone() ) ?>"/></td>
					</tr>
				</table>
				<input type="submit" name="update" value="Update Contact"/>
			</form>

			<div class="bottom-links">
				<a href="contacts-page.php">Back to contacts</a>
			</div>
		</div>
	</body>
</html>
