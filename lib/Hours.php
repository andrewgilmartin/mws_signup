<?php

class Hours {
	
	private static $nextId = 1;
	
	private $id;
	private $name;
	private $opening;
	private $closing;
	private $day;
	
	function __construct() {
		$this->id = Hours::$nextId++;
	}
	
	function setId( $id ) {
		$this->id = $id;
		return $this;
	}
	
	function getId() {
		return $this->id;
	}
	
	function setName( $name ) {
		$this->name = $name;
		return $this;
	}
	
	function getName() {
		return $this->name;
	}
	
	function setStarting( $opening ) {
		$this->opening = to_hour($opening);
		return $this;
	}
	
	function getStarting() {
		return $this->opening;
	}
	
	function setEnding( $closing ) {
		$this->closing = to_hour($closing);
		return $this;
	}

	function getEnding() {
		return $this->closing;
	}
	
	function getDuration() {
		return $this->closing - $this->opening;
	}
	
	function isOpen( $hour ) {
		return $this->opening <= $hour && $hour < $this->closing;
	}
	
	function setDay($day) {
		$this->day = $day;
	}
	
	function getDay() {
		return $this->day;
	}
}

?>