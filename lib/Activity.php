<?php

class Activity {

	private static int $nextId = 1;

	private int $id;
	private string $name;
	private string $description = "";
	private ?Contact $contact;
	private ?Event $event;
	private array $shifts = array();

	function __construct( $name )
    {
		$this->id = Activity::$nextId++;
		$this->name = $name;
	}

	static function earliestStarting( $activities ) {
		$hour = null;
		foreach ( $activities as $activity ) {
			$hour = minimum( $hour, Shift::earliestStarting($activity->getShifts() ) );
		}
		return $hour;
	}

	static function latestEnding( $activities ) {
		$hour = null;
		foreach ( $activities as $activity ) {
			$hour = maximum( $hour, Shift::latestEnding($activity->getShifts() ) );
		}
		return $hour;
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

	function setEvent( $event ): void
    {
		$this->event = $event;
	}

	function getEvent(): ?Event
    {
		return $this->event ?? null;
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

	function addShift( $shift ): static
    {
		$this->shifts[] = $shift;
		$shift->setActivity($this);
		return $this;
	}

	function getShifts(): array
    {
		return $this->shifts;
	}

	function findShiftsForDay( $day ): array
    {
		$shifts = array();
		foreach ( $this->shifts as $shift ) {
			if ( $shift->getDay() == $day ) {
				$shifts[] = $shifts;
			}
		}
		return $shifts;
	}

}

?>
