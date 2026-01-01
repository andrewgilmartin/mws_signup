<?php

class Day {

	private static int $nextId = 1;

	private int $id;
	private ?Event $event;
	private string $name;
	private ?string $description;
	private ?Contact $contact;
	private $date;
	private array $hours = array();
	private array $shifts = array();

	function __construct( $name ) {
		$this->id = Day::$nextId++;
		$this->name = $name;
	}

	static function earliestStarting( $days ) {
		$hour = null;
		foreach ( $days as $day ) {
			$hour = minimum( $hour, $day->getEarliestStarting() );
		}
		return $hour;
	}

	static function latestEnding( $days ) {
		$hour = null;
		foreach ( $days as $day ) {
			$hour = maximum( $hour, $day->getLastestEnding() );
		}
		return $hour;
	}

	static function sortByDate( $days ): array
    {
		$v = array();
		foreach ( $days as $day ) {
			$v[$day->getDate()] = $day;
		}
		ksort($v);
		$days = array_values($v);
		return $days;
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

	function setEvent( $event ): static
    {
		$this->event = $event;
		return $this;
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

	function getDescription(): ?string
    {
		return $this->description ?? null;
	}

	function setDate( $date ): static
    {
		$this->date = to_date($date);
		return $this;
	}

	function getDate() {
		return $this->date;
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

	function addShift($shift): static
    {
		$this->shifts[] = $shift;
		$shift->setDay($this);
		return $this;
	}

	function getShifts(): array
    {
		return $this->shifts;
	}

	function addHours( $hours ): static
    {
		$this->hours[] = $hours;
		return $this;
	}

	function getHours(): array
    {
		return $this->hours;
	}

	function isOpen( $h ): bool
    {
		foreach ( $this->hours as $hs ) {
			if ( $hs->isOpen( $h ) ) {
				return true;
			}
		}
		return false;
	}

	function getEarliestStarting() {
		$hour = null;
		foreach ( $this->hours as $hs ) {
			$hour = minimum( $hour, $hs->getStarting() );
		}
		$hour = minimum( $hour, Shift::earliestStarting( $this->shifts ) );
		return $hour;
	}

	function getLastestEnding() {
		$hour = null;
		foreach ( $this->hours as $hs ) {
			$hour = maximum( $hour, $hs->getEnding() );
		}
		$hour = maximum( $hour, Shift::latestEnding( $this->shifts ) );
		return $hour;
	}
}

?>
