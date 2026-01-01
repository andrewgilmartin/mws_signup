<?php

class Event {

	private static int $nextId = 1;

	private int $id;
	private int $version;
	private string $name;
	private ?string $description;
	private ?Contact $contact;
	private ?string $passcode;
	private ?string $script;

	private array $nameToDay = array();
	private array $idToDay = array();
	private array $activities = array();
	private array $nameToRole = array();
	private array $nameToHint = array();
	private array $nameToVolunteerProperty = array();

	function __construct( $name ) {
		$this->id = Event::$nextId++;
		$this->version = 1;
		$this->name = $name;
		return $this;
	}

	function setId( $id ): static
    {
		$this->id = $id;
		return $this;
	}

	function getId(): int
    {
		return $this->id;
	}

	function setPasscode( $passcode ): static
    {
		$this->passcode = $passcode;
		return $this;
	}

	function getPasscode(): string
    {
		return $this->passcode;
	}

	function setVersion( $version ): static
    {
		$this->version = $version;
		return $this;
	}

	function getVersion(): int
    {
		return $this->version;
	}

	function setScript( $script ): static
    {
		$this->script = $script;
		return $this;
	}

	function getScript(): string
    {
		return $this->script;
	}

	function getName(): string
    {
		return $this->name;
	}

	function setDescription( $description ): static
    {
		$this->description = $description;
		return $this;
	}

	function getDescription(): string
    {
		return $this->description;
	}

	function setContact( $contact ): static
    {
		$this->contact = $contact;
		return $this;
	}

	function getContact(): ?Contact
    {
		return $this->contact ?? null;
	}

	function addActivity( $activity ): static
    {
		$this->activities[] = $activity;
		$activity->setEvent($this);
		return $this;
	}

	function getActivities(): array
    {
		return $this->activities;
	}

	function findShiftById( $id ): ?Shift
    {
		foreach ( $this->getActivities() as $activity ) {
			foreach ( $activity->getShifts() as $shift ) {
				if ( $shift->getId() == $id ) {
					return $shift;
				}
			}
		}
		return null;
	}

	function getDays(): array
    {
		return Day::sortByDate( array_values( $this->nameToDay ) );
	}

	function addDay($day): static
    {
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

	function getRoles(): array
    {
		return array_values( $this->nameToRole );
	}

	function getRole( $name ) {
		return $nameToRole[$name];
	}

	function getHint( $name ): ?string {
		if ( array_key_exists( $name, $this->nameToHint ) ) {
			return $this->nameToHint[ $name ]->getValue();
		}
		return null;
	}

	function getHints(): array
    {
		return array_values($this->nameToHint);
	}

	function addHint( $hint ): void
    {
		$this->nameToHint[ $hint->getName() ] = $hint;
	}

	function getVolunteerProperties(): array
    {
		return array_values($this->nameToVolunteerProperty);
	}

	function addVolunteerProperty( $volunteerProperty ): void
    {
		$this->nameToVolunteerProperty[ $volunteerProperty->getName() ] = $volunteerProperty;
	}
}

?>
