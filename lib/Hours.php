<?php

class Hours {

	private static int $nextId = 1;

	private int $id;
	private string $name;
	private ?int $starting;
	private ?int $ending;
	private ?Day $day;

	function __construct( $name ) {
		$this->id = Hours::$nextId++;
		$this->name = $name;
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

	function getName(): string
    {
		return $this->name;
	}

	function setStarting( $starting ): static
    {
		$this->starting = to_hour($starting);
		return $this;
	}

	function getStarting(): ?int
    {
		return $this->starting;
	}

	function setEnding( $ending ): static
    {
		$this->ending = to_hour($ending);
		return $this;
	}

	function getEnding(): ?int
    {
		return $this->ending ?? null;
	}

	function getDuration(): ?int
    {
		return $this->ending - $this->starting;
	}

	function isOpen( $hour ): bool
    {
		return $this->starting <= $hour && $hour < $this->ending;
	}

	function setDay($day): void
    {
		$this->day = $day;
	}

	function getDay(): ?Day
    {
		return $this->day;
	}
}

?>
