<?php

class Event {

	private static $nextId = 1;

	private $id;
	private $name;
	private $description;
	private $contact;
	private $passcode;
	private $version;
	private $script;
	
	private $nameToDay = array();
	private $idToDay = array();
	private $activities = array();
	private $nameToRole = array();
	private $nameToHint = array();
	private $nameToVolunteerProperty = array();
	
	function __construct() {
		$this->id = Event::$nextId++;
		$this->version = 1;
		return $this;
	}
		
	function setId( $id ) {
		$this->id = $id;
		return $this;
	}

	function getId() {
		return $this->id;
	}
	
	function setPasscode( $passcode ) {
		$this->passcode = $passcode;
		return $this;
	}
	
	function getPasscode() {
		return $this->passcode;
	}
	
	function setVersion( $version ) {
		$this->version = $version;
		return $this;
	}
	
	function getVersion() {
		return $this->version;
	}
	
	function setScript( $script ) {
		$this->script = $script;
		return $this;
	}
	
	function getScript() {
		return $this->script;
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
	
	function addActivity( $activity ) {
		$this->activities[] = $activity;
		$activity->setEvent($this);
		return $this;
	}
	
	function getActivities() {
		return $this->activities;
	}
	
	function findShiftById( $id ) {
		foreach ( $this->getActivities() as $activity ) {
			foreach ( $activity->getShifts() as $shift ) {
				if ( $shift->getId() == $id ) {
					return $shift;
				}
			}
		}
		return null;
	}
	
	function getDays() {
		return Day::sortByDate( array_values( $this->nameToDay ) );
	}
	
	function addDay($day) {
		$this->nameToDay[ $day->getName() ] = $day;
		$this->idToDay[ $day->getId() ] = $day;
		$day->setEvent($this);
		return $this;
	}

	function getDay($name) {
		return $this->nameToDay[$name];
	}
	
	function findDayById($id) {
		return $this->idToDay[$id];
	}
	
	function addRole( $role ) {
		$this->nameToRole[ $role->getName() ] = $role;
		return $this;
	}
	
	function getRoles() {
		return array_values( $this->nameToRole );
	}
	
	function getRole( $name ) {
		return $nameToRole[$name];
	}
	
	function getHint( $name ) {
		if ( array_key_exists( $name, $this->nameToHint ) ) {
			return $this->nameToHint[ $name ]->getValue();
		}
		return null;
	}
	
	function getHints() {
		return array_values($this->nameToHint);
	}
	
	function addHint( $hint ) {
		$this->nameToHint[ $hint->getName() ] = $hint;
	}
	
	function getVolunteerProperties() {
		return array_values($this->nameToVolunteerProperty);
	}
	
	function addVolunteerProperty( $volunteerProperty ) {
		$this->nameToVolunteerProperty[ $volunteerProperty->getName() ] = $volunteerProperty;
	}
}

?>