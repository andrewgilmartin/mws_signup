<?php

class Hint {

	private static $nextId = 1;

	private $id;
	private $name;
	private $value;

	function __construct() {
		$this->id = Hint::$nextId++;
	}

	function setId($id) {
		$this->id = $id;
	}
	
	function getId() {
		return $this->id;
	}
	
	function setName($name) {
		$this->name = $name;
	}
	
	function getName() {
		return $this->name;
	}
	
	function setValue($value) {
		return $this->value = $value;
	}
	
	function getValue() {
		return $this->value;
	}
}

?>