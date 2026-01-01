<?php

class Volunteer {

	private static int $nextId = 1;

	private int $id;
	private ?Shift $shift;
	private ?Contact $contact;
	private ?Event $event;
	private array $nameToProperty = array();

	function __construct()
    {
		$this->id = Volunteer::$nextId++;
	}

	function setId( $id ): void
    {
		$this->id = $id;
	}

	function getId(): int
    {
		return $this->id;
	}

	function setShift( $shift ): void
    {
		$this->shift = $shift;
	}

	function getShift(): ?Shift
    {
		return $this->shift ?? null;
	}

	function setContact( $contact ): void
    {
		$this->contact = $contact;
	}

	function getContact(): ?Contact
    {
		return $this->contact ?? null;
	}

	function setEvent($event): void
    {
		$this->event = $event;
	}

	function getEvent(): ?Event
    {
		return $this->event ?? null;
	}

	function addProperty( $name, $value ): static
    {
		$this->nameToProperty[ $name ] = $value;
		return $this;
	}

	function getProperty( $name )
    {
		return array_key_exists( $name, $this->nameToProperty ) ? $this->nameToProperty[$name] : null;
	}

	function getProperties(): array
    {
		return $this->nameToProperty;
	}
}

# END
