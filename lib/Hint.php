<?php

class Hint {

	private static int $nextId = 1;

	private int $id;
	private string $name;
	private string $value;

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
