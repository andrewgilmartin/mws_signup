<?php
require_once 'volunteer-page-include.php';

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

global
    $VOLUNTEER_PROPERTY_INPUT_PREFIX,
    $name,
    $email,
    $telephone;

?>
<html>
	<head>
		<title><?= htmlspecialchars($event->getName()) ?></title>
		<link rel="stylesheet" type="text/css" href="common.css">
		<link media="screen" rel="stylesheet" type="text/css" href="browser.css">
		<link media="handheld, only screen and (max-device-width: 480px)" href="mobile.css" type="text/css" rel="stylesheet" />
	</head>
	<body>
		<div class="page-content">

			<h1><?= htmlspecialchars( $event->getName() ) ?></h1>
			<p><?= htmlspecialchars($event->getDescription() )?></p>
			<?php
			        if ( $event->getContact() ) {
			?>
                        <p>For more information about this event contact <?= makeContactSummaryHtml( $event->getContact() ) ?>.</p>
            <?php
                    }

			        require_once 'message-include.php';
            ?>
<h2><?= htmlspecialchars($shift->getActivity()->getName()) ?></h2>
<p><?= htmlspecialchars($shift->getActivity()->getDescription()) ?></p>
<?php
if ( $shift->getActivity()->getContact() ) {
?>
<p>For more information about this activity contact <b><?= makeContactSummaryHtml( $shift->getActivity()->getContact() ) ?></b>.</p>
<?php
}
?>
			<form method="POST">
				<input type="hidden" name="shift" value="<?= $shift->getId() ?>" />
				<table class="volunteer">
					<tr>
						<th><?= htmlspecialchars($shift->getRole()->getName()) ?></th>
						<td>
							<?= htmlspecialchars($shift->getRole()->getDescription()) ?>
						</td>
					</tr>
					<tr>
						<th>Day &amp; Time</th>
						<td><?= $shift->getDay()->getName()?>, <?= timetostr( $shift->getDay()->getDate() )?>, <?= hourtostr( $shift->getStarting() )?> - <?= hourtostr( $shift->getEnding() )?></td>
					</tr>
					<tr>
						<th>Volunteer Name</th>
						<td>
<?php
							if ( $shift->getVolunteer() ) {
?>
								<?= htmlspecialchars($name) ?>
<?php
							}
							else {
?>
								<input type="text" name="name" value="<?= htmlspecialchars($name) ?>" class="input"/>
<?php
							}
?>
						</td>
					</tr>
					<tr>
						<th>Email</th>
						<td>
<?php
							if ( $shift->getVolunteer() ) {
?>
								<?= htmlspecialchars($email) ?>
<?php
							}
							else {
?>
								<input type="text" name="email" value="<?= htmlspecialchars($email) ?>" class="input"/>
<?php
							}
?>
						</td>
					</tr>
					<tr>
						<th>Telephone</th>
						<td>
<?php
							if ( $shift->getVolunteer() ) {
?>
								<?= htmlspecialchars($telephone) ?>
<?php
							}
							else {
?>
								<input type="text" name="telephone" value="<?= htmlspecialchars($telephone)?>" class="input"/>
<?php
							}
?>
						</td>
					</tr>
<?php
					foreach ( $event->getVolunteerProperties() as $volunteerProperty ) {
?>
						<tr>
							<th><?= htmlspecialchars($volunteerProperty->getName()) ?></th>
							<td>
<?php
								if ( $shift->getVolunteer() ) {
									$description = $volunteerProperty->getDescription();
									$value = $shift->getVolunteer()->getProperty($volunteerProperty->getName());
									if ( is_empty( $value ) ) {
										$value = "<i>None</i>";
									}
									else {
										$value = htmlspecialchars( $value );
									}
?>
									<p><?= htmlspecialchars( $description ) ?></p>
									<?= $value ?>
<?php
								}
								else {
?>
									<p><?= htmlspecialchars( $volunteerProperty->getDescription() ) ?></p>
									<input type="text" name="<?=$VOLUNTEER_PROPERTY_INPUT_PREFIX?><?=$volunteerProperty->getId()?>" class="input" />
<?php
								}
?>
							</td>
						</tr>
<?php
					}
?>
					<tr>
						<th>&nbsp;</th>
						<td>
<?php
							if ( $shift->getVolunteer() ) {
?>
								<input type="submit" name="cancel" value="*** Cancel Volunteer ***"/>
<?php
							}
							else {
?>
								<input type="submit" name="assign" value="Volunteer"/>
<?php
							}
?>
						</td>
					</tr>
				</table>
			</form>
			<a href="event-page.php?event=<?=$eventId?>&day=<?=$dayId?>">Back to schedule</a> |
			<a href="javascript:print()">Print this page as a reminder</a>
		</div>
	</body>
</html>
