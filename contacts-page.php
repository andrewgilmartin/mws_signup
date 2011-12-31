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

			<h1>Contacts</h1>

<?php
			require_once 'message-include.php';
			
			if ( count($contacts) == 0 ) {
?>
				<p>There are no contacts currently</p>
<?php
			}
			else {
?>
				<table class="activity-volunteer-summary">
					<tr>
						<th>Name</th>
						<th>Email</th>
						<th>Telephone</th>
					</tr>
<?php
					foreach ( $contacts as $contact ) {						
?>
						<tr>
							<td>
								<a href="update-contact-page.php?contact=<?=$contact->getId()?>"><?= htmlspecialchars($contact->getName())?></a>
							</td>
							<td>
<?php							
								if ( $contact->getEmail() ) {
?>									
									<?= htmlspecialchars( $contact->getEmail() )?>
<?php							
								}
?>								
							</td>
							<td>
<?php							
								if ( $contact->getTelephone() ) {
?>									
									<?= htmlspecialchars($contact->getTelephone())?>
<?php							
								}
?>																
							</td>
						</tr>
<?php
					}
?>		
				</table>
<?php
			}
?>
			<div class="bottom-links">
				<a href="create-contact-page.php">Create Contact</a> | 
				<a href="./">Back to events</a>
			</div>	
		</div>
	</body>
</html>