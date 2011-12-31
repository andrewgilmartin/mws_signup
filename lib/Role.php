<?php

class Role {
	
	private static $nextId = 1;
	
	private $id;
	private $name;
	private $description;

	function __construct() {
		$this->id = Role::$nextId++;
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
	
	function setDescription( $description ) {
		$this->description = $description;
		return $this;
	}

	function getDescription() {
		return $this->description;
	}
}

?>