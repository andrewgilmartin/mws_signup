<?php

class Shift {

	private static int $nextId = 1;

	private int $id;
	private ?Activity $activity;
	private ?Role $role;
	private ?Day $day;
	private ?Volunteer $volunteer;
	private ?int $starting;
	private ?int $ending;

	function __construct() {
		$this->id = Shift::$nextId++;
	}

    /**
     * @throws Exception
     */
    function copy(): Shift
    {
		$shift = new Shift();
		$shift
			->setRole( $this->role)
			->setStarting( $this->starting )
			->setEnding( $this->ending );
		return $shift;
	}

	static function earliestStarting($shifts ) {
		$hour = null;
		foreach ( $shifts as $shift ) {
			$hour = minimum( $hour, $shift->getStarting() );
		}
		return $hour;
	}

	static function latestEnding( $shifts ) {
		$hour = null;
		foreach ( $shifts as $shift ) {
			$hour = maximum( $hour, $shift->getEnding() );
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

	function setActivity( $activity ): static
    {
		$this->activity = $activity;
		return $this;
	}

	function getActivity(): ?Activity
    {
		return $this->activity;
	}

	function setRole( $role ): static
    {
		$this->role = $role;
		return $this;
	}

	function getRole(): ?Role
    {
		return $this->role;
	}

	function setDay( $day ): static
    {
		$this->day = $day;
		return $this;
	}

	function getDay(): ?Day
    {
		return $this->day;
	}

	function setVolunteer($volunteer): static
    {
		$this->volunteer = $volunteer;
        $this->volunteer?->setShift($this);
		return $this;
	}

	function getVolunteer(): ?Volunteer
    {
		return $this->volunteer ?? null;
	}

    /**
     * @throws Exception
     */
    function setStarting($starting ): static
    {
		$this->starting = to_hour($starting);
        if ( ! isset($this->ending) ) {
            $this->ending = $this->starting + 1;
        }
		else if ( $this->ending < $this->starting ) {
			throw new Exception( "shift's starting time must be before ending time" );
		}
		return $this;
	}

	function getStarting(): ?int
    {
		return $this->starting ?? null;
	}

    /**
     * @throws Exception
     */
    function setEnding($ending ): static
    {
		$this->ending = to_hour($ending);
        if ( ! isset($this->starting) ) {
            $this->ending = $this->ending - 1;
        }
		else if ( $this->ending < $this->starting ) {
			throw new Exception( "shift's starting time must be before ending time" );
		}
		return $this;
	}

	function getEnding(): ?int
    {
		return $this->ending ?? null;
	}

	function getDuration(): int
    {
		return $this->ending - $this->starting;
	}
}

?>
