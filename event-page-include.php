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

class HoursLayout {
	var $hours;
	var $duration;
	function __construct( $duration ) {
		$this->duration = $duration;
		$this->hours = array();
	}
	function anyAssigned( $starting, $ending ) {
		for ( $h = $starting; $h < $ending; $h++ ) {
			if ( array_key_exists( $h, $this->hours ) ) {
				return true;
			}
		}
		return false;
	}
	function assign( $assignment, $starting, $ending ) {
		for ( $h = $starting; $h < $ending; $h++ ) {
			$this->hours[$h] = $assignment;
		}
		return $this;
	}
}

class ActivityLayout {
	var $activity;
	var $isActive;
	var $hoursLayouts = array(); // of HoursLayout
	function __construct( $activity, $duration ) {
		$this->activity = $activity;
		$this->hoursLayouts[] = new HoursLayout( $duration );
		$this->isActive = false;
	}
}

class DayLayout {
	var $day;
	var $activityLayouts = array();
	function __construct( $day ) {
		$this->day = $day;
	}
}

class EventLayout {
	var $event;
	var $dayLayouts = array();
	var $earliestStarting;
	var $latestEnding;
	var $duration;
	var $hideActivitiesWithoutShifts = false;
	function __construct( $event ) {
		$this->event = $event;
		$this->earliestStarting = Day::earliestStarting( $event->getDays() );
		$this->latestEnding = Day::latestEnding( $event->getDays() );
		$this->duration = $this->latestEnding - $this->earliestStarting;
		$this->hideActivitiesWithoutShifts = strtobool( $event->getHint( "hide-activities-without-shifts" ) );
	}
}

$eventLayout = new EventLayout($event);

foreach ( $event->getDays() as $day ) {
	$dayLayout = new DayLayout($day);
	foreach ( $event->getActivities() as $activity ) {
		$activityLayout = new ActivityLayout($activity, $eventLayout->duration);
		foreach ( $activity->getShifts() as $shift ) {
			if ( $shift->getDay() !== $day ) {
				continue;
			}
			$hoursLayout = null;
			foreach ( $activityLayout->hoursLayouts as $hl ) {
				if ( ! $hl->anyAssigned( $shift->getStarting(), $shift->getEnding() ) ) {
					$hoursLayout = $hl;
					break;
				}
			}
			if ( is_null( $hoursLayout ) ) {
				$hoursLayout = new HoursLayout( $eventLayout->duration );
				$activityLayout->hoursLayouts[] = $hoursLayout;
			}
			$hoursLayout->assign( $shift, $shift->getStarting(), $shift->getEnding() );
			$activityLayout->isActive = true;
		}
		$dayLayout->activityLayouts[] = $activityLayout;
	}
	$eventLayout->dayLayouts[] = $dayLayout;
}

if ( !isset($dayId) ) {
	$dayId = $eventLayout->dayLayouts[0]->day->getId();
}

?>
