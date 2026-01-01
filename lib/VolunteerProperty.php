<?php

class VolunteerProperty {

	private static int $nextId = 1;

	private int $id;
	private string $name;
	private string $description;

	public function __construct( $name, $description )
    {
		$this->id = VolunteerProperty::$nextId++;
		$this->name = $name;
		$this->description = $description;
	}

	public function getId(): int
    {
		return $this->id;
	}

	public function getName(): string
    {
		return $this->name;
	}

	public function getDescription(): string
    {
		return $this->description;
	}
}

?>
