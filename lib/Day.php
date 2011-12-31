<?php

class Day {
	
	private static $nextId = 1;
	
	private $id;
	private $event;
	private $name;
	private $description;
	private $contact;
	private $date;
	private $hours = array();
	private $shifts = array();
	
	function __construct() {
		$this->id = Day::$nextId++;
	}
		
	static function earliestStarting( $days ) {
		$hour = null;
		foreach ( $days as $day ) {
			$hour = minimum( $hour, $day->getEarliestStarting() );
		}
		return $hour;
	}
	
	static function latestEnding( $days ) {
		$hour = null;
		foreach ( $days as $day ) {
			$hour = maximum( $hour, $day->getLastestEnding() );
		}
		return $hour;
	}

	static function sortByDate( $days ) {
		$v = array();
		foreach ( $days as $day ) {
			$v[$day->getDate()] = $day;
		}
		ksort($v);
		$days = array_values($v);
		return $days;
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
		return $this;
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
	
	function setDate( $date ) {
		$this->date = to_date($date);
		return $this;
	}

	function getDate() {
		return $this->date;
	}
	
	function setContact( $contact ) {
		$this->contact = $contact;
		return $this;
	}
	
	function getContact() {
		return $this->contact;
	}
	
	function addShift($shift) {
		$this->shifts[] = $shift;
		$shift->setDay($this);
		return $this;
	}
	
	function getShifts() {
		return $this->shifts;
	}
	
	function addHours( $hours ) {
		$this->hours[] = $hours;
		return $this;
	}
	
	function getHours() {
		return $this->hours;
	}
	
	function isOpen( $h ) {
		foreach ( $this->hours as $hs ) {
			if ( $hs->isOpen( $h ) ) {
				return true;
			}
		}
		return false;
	}
	
	function getEarliestStarting() {
		$hour = null;
		foreach ( $this->hours as $hs ) {
			$hour = minimum( $hour, $hs->getStarting() );
		}
		$hour = minimum( $hour, Shift::earlistStarting( $this->shifts ) );
		return $hour;
	}
	
	function getLastestEnding() {
		$hour = null;
		foreach ( $this->hours as $hs ) {
			$hour = maximum( $hour, $hs->getEnding() );
		}
		$hour = maximum( $hour, Shift::latestEnding( $this->shifts ) );
		return $hour;
	}
}

?>
