<?php

class VolunteerProperty {
	
	private static $nextId = 1;
	
	private $id;
	private $name;
	private $description;

	public function __construct( $name, $description ) {
		$this->id = VolunteerProperty::$nextId++;
		$this->name = $name;
		$this->description = $description;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getDescription() {
		return $this->description;
	}
}

?>