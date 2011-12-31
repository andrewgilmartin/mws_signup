<?php

class Shift {
	
	private static $nextId = 1;
	
	private $id;
	private $activity;
	private $role;
	private $day;
	private $volunteer;
	private $starting;
	private $ending;

	function __construct() {
		$this->id = Shift::$nextId++;
	}
	
	function copy() {
		$shift = new Shift();
		$shift
			->setRole( $this->role) 
			->setStarting( $this->starting )
			->setEnding( $this->ending );
		return $shift;
	}
	
	static function earlistStarting( $shifts ) {
		$hour = null;
		foreach ( $shifts as $shift ) {
			$hour = minimum( $hour, $shift->getStarting() );
		}
		return $hour;
	}
	
	static function latestEnding( $shifts ) {
		$hour = null;
		foreach ( $shifts as $shift ) {
			$hour = maximum( $hour, $shift->getEnding() );
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
	
	function setActivity( $activity ) {
		$this->activity = $activity;
		return $this;
	}

	function getActivity() {
		return $this->activity;
	}
	
	function setRole( $role ) {
		$this->role = $role;
		return $this;
	}
	
	function getRole() {
		return $this->role;
	}
	
	function setDay( $day ) {
		$this->day = $day;
		return $this;
	}
	
	function getDay() {
		return $this->day;
	}
	
	function setVolunteer($volunteer) {
		$this->volunteer = $volunteer;
		if ( $this->volunteer ) {
			$this->volunteer->setShift($this);
		}
		return $this;
	}
	
	function getVolunteer() {
		return $this->volunteer;
	}
	
	function setStarting( $starting ) {
		$this->starting = to_hour($starting);
		if ( !is_null($this->starting) && !is_null($this->ending) && $this->ending < $this->starting ) {
			throw new Exception( "shift's starting time must be before ending time" );
		}
		return $this;
	}
	
	function getStarting() {
		return $this->starting;
	}
	
	function setEnding( $ending ) {
		$this->ending = to_hour($ending);
		if ( !is_null($this->starting) && !is_null($this->ending) && $this->ending < $this->starting ) {
			throw new Exception( "shift's starting time must be before ending time" );
		}
		return $this;
	}
	
	function getEnding() {
		return $this->ending;
	}

	function getDuration() {
		return $this->ending - $this->starting;
	}
}

?>