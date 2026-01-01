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

if ( array_key_exists( 'create', $_REQUEST ) ) {

	$name = array_key_exists( 'name', $_REQUEST ) ? trim(stripslashes($_REQUEST['name'])) : "Unnamed";
	$email = array_key_exists( 'email', $_REQUEST ) ? trim(stripslashes($_REQUEST['email'])) : "";
	$telephone = array_key_exists( 'telephone', $_REQUEST ) ? trim(stripslashes($_REQUEST['telephone'])) : "";

	try {
		$contact = new Contact( $name );
		$contact
			->setEmail($email)
			->setTelephone($telephone);
		$repository->addContact( $contact );
		$_SESSION[$INFORMATION_MESSAGE_KEY] = "Created " . makeContactSummaryHtml( $contact );
        httpRedirect($base_href, 'contacts-page.php');
	}
	catch ( Exception $e ) {
		$_SESSION['name'] = $name;
		$_SESSION['email'] = $email;
		$_SESSION['telephone'] = $telephone;
		$_SESSION[$ERROR_MESSAGE_KEY] = "Unable to create contact due to error: ".$e->getMessage().' '.$e->getTraceAsString();
        httpRedirect($base_href, 'create-contact-page.php');
	}
}
else {
	$name = array_key_exists( 'name', $_SESSION ) ? $_SESSION['name'] : "";
	$email = array_key_exists( 'email', $_SESSION ) ? $_SESSION['email'] : "";
	$telephone = array_key_exists( 'telephone', $_SESSION ) ? $_SESSION['telephone'] : "";
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

			<h1>Create Contact</h1>
<?php
			require_once 'message-include.php';
?>
			<form method="POST">
				<table class="volunteer">
					<tr>
						<th>Name</th>
						<td><input type="text" name="name" value="<?= htmlspecialchars($name) ?>"/></td>
					</tr>
					<tr>
						<th>Email</th>
						<td><input type="text" name="email" value="<?= htmlspecialchars($email) ?>"/></td>
					</tr>
					<tr>
						<th>Telephone</th>
						<td><input type="text" name="telephone" value="<?= htmlspecialchars($telephone) ?>"/></td>
					</tr>
				</table>
				<input type="submit" name="create" value="Create Contact"/>
			</form>

			<div class="bottom-links">
				<a href="contacts-page.php">Back to contacts</a>
			</div>
		</div>
	</body>
</html>
