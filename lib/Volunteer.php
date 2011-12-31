<?php

class Volunteer {

	private static $nextId = 1;

	private $id;
	private $shift;
	private $contact;
	private $event;
	private $nameToProperty = array();
	
	function __construct() {
		$this->id = Volunteer::$nextId++;
	}
		
	function setId( $id ) {
		$this->id = $id;
	}
	
	function getId() {
		return $this->id;
	}
	
	function setShift( $shift ) {
		$this->shift = $shift;
	}
	
	function getShift() {
		return $this->shift;
	}
	
	function setContact( $contact ) {
		$this->contact = $contact;
	}
	
	function getContact() {
		return $this->contact;
	}
	
	function setEvent($event) {
		$this->event = $event;
	}
	
	function getEvent() {
		return $this->event;
	}
	
	function addProperty( $name, $value ) {
		$this->nameToProperty[ $name ] = $value;
		return $this;
	}
	
	function getProperty( $name ) {
		return array_key_exists( $name, $this->nameToProperty ) ? $this->nameToProperty[$name] : null;
	}

	function getProperties() {
		return $this->nameToProperty;
	}
}

# END
