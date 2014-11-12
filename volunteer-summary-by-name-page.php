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
			
			<table>
				<tr>
					<th align="left">Name</th>
					<th align="left">Email</th>
					<th align="left">Telephone</th>
					<th align="left">Shift</th>
					<th align="left">Activity</th>
					<th align="left" colspan="2">Day</th>
					<th align="left" colspan="2"">Time</th>
				</tr>
<?php
			require_once 'message-include.php';

			$lastContextId = -1;
			
			foreach ( $repository->getContactVolunteerRecords( $event->getId() ) as $contactVolunteerRecord ) {
				$shift = $event->findShiftById( $contactVolunteerRecord['shift_id']);
				if ( ! $shift ) { 
?>
					<td colspan="3"></td>
<?php
					continue;
				}   
?>				
				<tr>
<?php
				if ( $contactVolunteerRecord['contact_id'] == $lastContextId ) {
?>			
					<td colspan="3"></td>
<?php
				}
				else {
?>			
					<td>
						<?=htmlspecialchars($contactVolunteerRecord['name'])?>
					</td>
					<td>
						<a href="mailto:<?=$contactVolunteerRecord['email']?>?subject=[<?=$event->getName()?>]">
							<?=htmlspecialchars($contactVolunteerRecord['email'])?>
						</a>
					</td>
					<td><?=htmlspecialchars($contactVolunteerRecord['telephone'])?></td>
<?php
					$lastContextId = $contactVolunteerRecord['contact_id'];
				}
?>			
					<td>
						<a href="volunteer-page.php?event=<?=$event->getId()?>&day=<?=$shift->getDay()->getId()?>&shift=<?= $shift->getId()?>">
							<?=htmlspecialchars($shift->getRole()->getName())?>
						</a>
					</td>
					<td><?=htmlspecialchars($shift->getActivity()->getName())?></td>
					<td><?=htmlspecialchars($shift->getDay()->getName())?></td>
					<td><?=timetostr($shift->getDay()->getDate())?></td>
					<td align="right"><?=hourtostr($shift->getStarting())?></td>
					<td align="right"><?=hourtostr($shift->getEnding())?></td>
				</tr>
<?php				
			}
?>			
			</table>
			
			<div class="bottom-links">			
				<a href="event-page.php?event=<?=$eventId?>">Back to schedule</a>
			</div>
		</div>
	</body>
</html>






