<?php
require_once 'common-include.php';

$hideActivitiesWithoutShifts = $event->getHint( 'hide-activities-without-shifts' ) == 'true';

$volunteerPropertyCount = count($event->getVolunteerProperties());

?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="common.css">
		<link media="screen" rel="stylesheet" type="text/css" href="browser.css">
		<link media="handheld, only screen and (max-device-width: 480px)" href="mobile.css" type="text/css" rel="stylesheet" />
	</head>
	<body>
		<div class="page-content">
			<h1><?=$event->getName()?></h1>
<?php
			require_once 'message-include.php';

			foreach ( $event->getDays() as $day ) {
?>
				<h2><?=$day->getName()?>, <?=timetostr($day->getDate())?></h1>
<?php
				foreach ( $event->getActivities() as $activity ) {
					if ( $hideActivitiesWithoutShifts && ! $activity->findShiftsForDay($day) ) {
						continue;
					}
?>
					<h3><?= htmlspecialchars($activity->getName()) ?></h3>
					<p>
<?php
					if ( $activity->getContact() ) {
?>						
						Contact is <?= makeContactSummaryHtml( $activity->getContact() ) ?>
<?php						
					}
					else {
?>						
						<i>No contact</i>
<?php						
					}
?>					
					</p>
					<table class="activity-volunteer-summary">
						<tr>
							<th>Start</th>
							<th>End</th>
							<th>Role</th>
							<th colspan="3">Volunteer</th>
<?php
							foreach ( $event->getVolunteerProperties() as $vp ) {
?>								
								<th><?= $vp->getName() ?></th>
<?php								
							}
?>							
						</tr>
<?php
						$isActive = false;
						foreach ( $activity->getShifts() as $shift ) {
							if ( $shift->getDay() === $day ) {
								$isActive = true;
?>
								<tr>
									<td align="right">
										<?=hourtostr($shift->getStarting())?>
									</td>
									<td align="right">
										<?=hourtostr($shift->getEnding())?>
									</td>
									<td>
										<?=$shift->getRole()->getName()?>
									</td>
<?php
									if ( $shift->getVolunteer() ) {
										$v = $shift->getVolunteer();
?>
										<td>
											<?= htmlspecialchars($v->getContact()->getName()) ?>
										</td>
										<td>
<?php
											if ( $v->getContact()->getEmail() ) {
?>												
												<a href="mailto:<?=$v->getContact()->getEmail()?>"><?=htmlspecialchars($v->getContact()->getEmail())?></a>
<?php												
											}
?>										
										</td>
											<td>
											<?=htmlspecialchars($v->getContact()->getTelephone())?>
										</td>
<?php
										foreach ( $event->getVolunteerProperties() as $vp ) {
											if ( $v->getProperty( $vp->getName() ) ) {
?>												
												<td>
													<?= $v->getProperty( $vp->getName() ) ?>
												</td>
<?php												
											}
											else {
?>										
												<td>
													<i>None</i>
												</td>
<?php												
											}
										}
									}
									else {
?>
										<td colspan="<?= $volunteerPropertyCount + 3?>">
											<i>None</i>
										</td>
<?php
									}
?>
									</td>
								</tr>
<?php
							}
						}
						if ( ! $isActive ) {
?>
							<tr>
								<td colspan="4"><i>No shifts</i></td>						
							</tr>
<?php
						}
?>		
					</table>
<?php
				}
			}
?>
			<div class="bottom-links">			
				<a href="event-page.php?event=<?=$eventId?>">Back to schedule</a>
			</div>
		</div>
	</body>
</html>
