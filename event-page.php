<?php
require_once 'event-page-include.php';
?>
<html>
	<head>
	<title><?= htmlspecialchars($event->getName()) ?></title>
    	<link rel="stylesheet" type="text/css" href="common.css">
    	<link media="screen" rel="stylesheet" type="text/css" href="browser.css">
		<link media="handheld, only screen and (max-device-width: 480px)" href="mobile.css" type="text/css" rel="stylesheet" />	
		
		<style type="text/css">
		</style>	
	</head>
<body>
	<div class="page-content">

	<h1><?= htmlspecialchars($event->getName()) ?></h1>

	<p><?= htmlspecialchars($event->getDescription()) ?></p>
<?php
	if ( $event->getContact() ) {
?>
		<p>For more information about this event contact <?= makeContactSummaryHtml( $event->getContact() ) ?>.</p>
<?php
	}

	require_once 'message-include.php';
?>

<table style="margin-bottom: 1em; border-collapse:collapse;">
<tr>
<td style="border-bottom: 2px solid gray; padding: 1ex;">&nbsp;</td>
<?php
	foreach ( $eventLayout->dayLayouts as $dayLayout ) {
		
		if ( $dayLayout->day->getId() == $dayId ) {
?>			
            <td style="border: 2px solid gray; border-bottom: 0; border-top: 2px solid gray; padding: 1em; padding-top: 0.5em; padding-bottom: 0.5em">
                <a style="text-decoration: none; color: black;" href="?event=<?=$eventId?>&day=<?= $dayLayout->day->getId() ?>">
					<span style="font-size: 100%;"><?= htmlspecialchars($dayLayout->day->getName()) ?></span><br/>
					<span style="color: gray;"><?= timetostr( $dayLayout->day->getDate() )?></span>
				 </a>
            </td>
<?php
		}
		else {
?>
            <td style="border-bottom: 2px solid gray; padding: 1em; padding-top: 0.5em; padding-bottom: 0.5em">
                <a style="text-decoration: none; color: black;" href="?event=<?=$eventId?>&day=<?= $dayLayout->day->getId() ?>">
					<span style="font-size: 100%;color: gray;"><?= htmlspecialchars($dayLayout->day->getName()) ?></span><br/>
					<span style="color: gray;"><?= timetostr( $dayLayout->day->getDate() )?></span>
				 </a>
            </td>
<?php
		}		
?>
<?php
}
?>
	<td style="border-bottom: 2px solid gray; padding: 1em; padding-top: 0.5em; padding-bottom: 0.5em">&nbsp;</td>
</tr>
</table>


<?php
	foreach ( $eventLayout->dayLayouts as $dayLayout ) {

	if ( $dayLayout->day->getId() == $dayId ) {
?>
	<p style="padding-bottom: 1em">
	Showing activities and shifts for <?= htmlspecialchars($dayLayout->day->getName()) ?>, <?= timetostr( $dayLayout->day->getDate() )?>.
	</p>
				<table style="font-size: 80%; background: white;">
					<tr>
						<td>Legend: </td>
						<td class="available">&nbsp;</td><td>Needs Volunteer</td>
						<td class="assigned">&nbsp;</td><td>Has Volunteer</td>
						<td class="open-hours-name">&nbsp;</td><td>Event hours</td>
					</tr>
				</table>
		
				<table class="schedule">
				<tr>
					<td class="closed-hours-name" rowspan="1">&nbsp;</td>
<?php		
					$lastEnding = $eventLayout->earliestStarting;
					foreach ( $dayLayout->day->getHours() as $hours ) {
						if ( $lastEnding < $hours->getStarting() ) {
?>					
							<td class="closed-hours-name" colspan="<?= $hours->getStarting() - $lastEnding  ?>">&nbsp;</td>
<?php					
						}
?>
						<td class="open-hours-name" colspan="<?= $hours->getEnding() - $hours->getStarting() ?>"><?= $hours->getName() ? $hours->getName() : '&nbsp;' ?></td>
<?php
						$lastEnding = $hours->getEnding();
					}
					if ( $lastEnding < $eventLayout->latestEnding + 1 ) {
?>
						<td class="closed-hours-name" colspan="<?= $eventLayout->latestEnding - $lastEnding + 1 ?>">&nbsp;</td>
<?php
					}
			
?>			
				</tr>
				<tr>
					<td class="closed-hours-name">Hours</td>
<?php	
					for ( $h = $eventLayout->earliestStarting; $h < $eventLayout->latestEnding + 1; $h++ ) {
?>			
						<td class="<?= $dayLayout->day->isOpen( $h ) ? 'open-hours-name' : 'closed-hours-name' ?>"><?= $h < 13 ? $h : ( $h - 12 )  ?></td>
<?php			
					}
?>
				</tr>
<?php
				$activityOffset = 0;
				foreach ( $dayLayout->activityLayouts as $activityLayout ) {
					if ( ! $activityLayout->isActive && $eventLayout->hideActivitiesWithoutShifts ) {
						continue;
					}
					$openClass = "activity-open-" . ( $activityOffset % 2 );
					$closedClass = "activity-closed-" . ( $activityOffset % 2 );
					$activityOffset += 1;
?>		
					<!-- activity id <?= $activityLayout->activity->getId() ?> -->
					<tr>
						<td class="<?= $closedClass ?>" rowspan="<?= count( $activityLayout->hoursLayouts ) ?>">
							<?= htmlspecialchars($activityLayout->activity->getName()) ?>
						</td>
<?php		
						$first = true;
						foreach ( $activityLayout->hoursLayouts as $hoursLayout ) {
							if ( ! $first ) {
?>				
					</tr>
					<tr>
<?php				
							}
							$first = false;
							for ( $h = $eventLayout->earliestStarting; $h < $eventLayout->latestEnding + 1; /* nop */ ) {
								if ( array_key_exists( $h, $hoursLayout->hours ) ) {
									$shift = $hoursLayout->hours[$h];
?>					
								<!-- shift id <?= $shift->getId() ?> -->
								<td colspan="<?= $shift->getDuration() ?>" class="<?= $shift->getVolunteer() ? 'assigned' : 'available'?>"><a class="<?= $shift->getVolunteer() ? 'assigned' : 'available'?>" href="volunteer-page.php?event=<?=$eventId?>&day=<?=$dayId?>&shift=<?= $shift->getId()?>"><?= htmlspecialchars($shift->getRole()->getName()) ?></a></td>
<?php					
									$h += $shift->getDuration();
								}
								else {
?>
								<td class="<?= $dayLayout->day->isOpen( $h ) ? $openClass : $closedClass ?>">&nbsp;</td>
<?php
									$h += 1;					
								}
							}
						}
?>		
					</tr>
<?php		
				}
?>
		</table>
		
		<table style="font-size: 80%; background: white;">
			<tr>
				<td>Legend: </td>
				<td class="available">&nbsp;</td><td>Needs Volunteer</td>
				<td class="assigned">&nbsp;</td><td>Has Volunteer</td>
				<td class="open-hours-name">&nbsp;</td><td>Event hours</td>
			</tr>
		</table>

<?php		
	}
	}
?>

<div class="bottom-links">
<a href="volunteer-summary-page.php?event=<?=$eventId?>">View Summary by Day</a> |
<a href="volunteer-summary-by-name-page.php?event=<?=$eventId?>">View Summary by Contact</a> |
<a href="edit-script-page.php?event=<?=$eventId?>">Edit script</a> (For office use only)
</div>
</div>
</body>
</html>
