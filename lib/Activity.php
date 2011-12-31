<?php

class Activity {

	private static $nextId = 1;
	
	private $id;
	private $name;
	private $description;
	private $contact;
	private $event;
	private $shifts = array();

	function __construct() {
		$this->id = Activity::$nextId++;
	}
	
	static function earliestStarting( $activities ) {
		$hour = null;
		foreach ( $activities as $activity ) {
			$hour = minimum( $hour, Shift::earlistStarting($activity->getShifts() ) );
		}
		return $hour;
	}
	
	static function latestEnding( $activities ) {
		$hour = null;
		foreach ( $activities as $activity ) {
			$hour = maximum( $hour, Shift::latestEnding($activity->getShifts() ) );
		}
		return $hour;
	}
	
	function setId( $id ) {
		$this->id = $id;
		return $this;
	}

	function getId() {
		return $this->id;
	}
	
	function setEvent( $event ) {
		$this->event = $event;
	}
	
	function getEvent() {
		return $this->event;
	}
	
	function setName( $name ) {
		$this->name = $name;
		return $this;
	}
	
	function getName() {
		return $this->name;
	}
	
	function setDescription( $description ) {
		$this->description = $description;
		return $this;
	}
	
	function getDescription() {
		return $this->description;
	}
	
	function setContact( $contact ) {
		$this->contact = $contact;
		return $this;
	}
	
	function getContact() {
		return $this->contact;
	}
	
	function addShift( $shift ) {
		$this->shifts[] = $shift;
		$shift->setActivity($this);
		return $this;
	}
	
	function getShifts() {
		return $this->shifts;
	}
	
	function findShiftsForDay( $day ) {
		$shifts = array();
		foreach ( $this->shifts as $shift ) {
			if ( $shift->getDay() == $day ) {
				$shifts[] = $shifts;
			}
		}
		return $shifts;
	}
	
}

?>