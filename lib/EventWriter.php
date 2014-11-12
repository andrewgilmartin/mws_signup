<?php

class EventWriter {
	
	private $writer;
	
	function __construct() {
		$this->writer = new Writer( '  ', '""' );
	}
	
	static function toScript( $event ) {
		$writer = new EventWriter();
		$script = $writer->write($event);
		return $script;
	}
	
	function write($event) {
		$this->writeEvent($event);
		return $this->writer->text();
	}

	private function writeEvent($event) {
		$this->writer->write( 'event ', quotedCode( $event->getName() ) )->inc();
		$this->writer->write( 'id ', quotedCode( $event->getId() ) );
		if ( $event->getDescription() ) {
			$this->writer->write( 'description ', quotedCode( $event->getDescription() ) );
		}
		if ( $event->getContact() ) {
			$this->writer->write( 'contact ', quotedCode( $event->getContact()->getName() ));
		}
		foreach ( $event->getHints() as $hint ) {
			$this->writeHint($hint);
		}
		foreach ( $event->getVolunteerProperties() as $volunteerProperty ) {
			$this->writeVolunteerProperty($volunteerProperty);
		}
		foreach ( $event->getRoles() as $role ) {
			$this->writeRole($role);
		}
		foreach ( $event->getDays() as $day ) {
			$this->writeDay($day);
		}
		foreach ( $event->getActivities() as $activity ) {
			$this->writeActivity($activity);
		}
		$this->writer->dec()->write('end');
		return $this;
	}
	
	private function writeHint($hint) {
		$this->writer->write( 'hint ', quotedCode($hint->getName()), ' ', quotedCode( $hint->getValue() ) );
		return $this;
	}

	private function writeVolunteerProperty($volunteerProperty) {
		$this->writer->write( 'volunteer-property', quotedCode( $volunteerProperty->getName() ) )->inc();
		$this->writer->write( 'description', quotedCode( $volunteerProperty->getDescription() ) );
		$this->writer->dec()->write( 'end' );
		return $this;
	}
	
	private function writeRole($role) {
		$this->writer->write( 'role ' . quotedCode( $role->getName() ) )->inc();
		$this->writer->write( 'id ', quotedCode( $role->getId() ) );
		if ( $role->getDescription() ) {
			$this->writer->write( 'description ', quotedCode( $role->getDescription() ) );
		}
		$this->writer->dec()->write( 'end' );
		return $this;
	}

	private function writeDay($day) {
		$this->writer->write( 'day ', quotedCode( $day->getName() ) )->inc();
		$this->writer->write( 'id ', quotedCode( $day->getId() ) );
		if ( $day->getDescription() ) {
			$this->writer->write( 'description ', quotedCode( $day->getDescription() ) );
		}
		if ( $day->getDate() ) {
			$this->writer->write( 'date ', timetostr( $day->getDate() ) );
		}
		if ( $day->getContact() ) {
			$this->writer->write( 'contact ', $day->getContact()->getName() );
		}
		foreach ( $day->getHours() as $hours ) {
			$this->writeHours($hours);
		}
		$this->writer->dec()->write( 'end' );
		return $this;
	}
	
	private function writeActivity($activity) {
		$this->writer->write( 'activity ', quotedCode( $activity->getName() ) )->inc();
		$this->writer->write( 'id ', quotedCode( $activity->getId() ) );
		if ( $activity->getDescription() ) {
			$this->writer->write( 'description ', quotedCode( $activity->getDescription() ) );
		}
		if ( $activity->getContact() ) {
			$this->writer->write( 'contact ', quotedCode( $activity->getContact()->getName() ) );
		}
		foreach ( $activity->getEvent()->getDays() as $day ) {
			$this->writer->write( 'day', quotedCode( $day->getName() ) )->inc();
			foreach ( $activity->getShifts() as $shift ) {
				if ( $shift->getDay() == $day ) {
					$this->writeShift($shift);
				}
			}
			$this->writer->dec()->write( 'end' );
		}
		$this->writer->dec()->write('end');
		return $this;	
	}
	
	private function writeShift( $shift ) {
		$this->writer->write( 'shift ', quotedCode( $shift->getRole()->getName() ) )->inc();
		$this->writer->write( 'id ', quotedCode( $shift->getId() ) );
		if ( $shift->getStarting() ) {
			$this->writer->write( 'starting ', quotedCode( hourtostr( $shift->getStarting() ) ) );
		}
		if ( $shift->getEnding() ) {
			$this->writer->write( 'ending ', quotedCode( hourtostr( $shift->getEnding() ) ) );
		}
		$this->writer->dec()->write( 'end' );
		return $this;
	}
		
	private function writeContact($contact) {
		$this->writer->write( 'contact ', quotedCode( $contact->getName() ) )->inc();
		$this->writer->write( 'id ', quotedCode( $contact->getId() ) );
		if ( $contact->getEmail() ) {
			$this->writer->write( 'email ', quotedCode( $contact->getEmail() ) );
		}
		if ( $contact->getTelephone() ) {
			$this->writer->write( 'telephone ', quotedCode($contact->getTelephone()) );
		}
		$this->writer->dec()->write( 'end' );
		return $this;
	}

	private function writeHours($hours) {
		$this->writer->write( 'hours ', quotedCode( $hours->getName() ) )->inc();
		$this->writer->write( 'id ', quotedCode( $hours->getId() ) );
		if ( $hours->getStarting() ) {
			$this->writer->write( 'starting ', quotedCode( hourtostr( $hours->getStarting() ) ) );
		}
		if ( $hours->getEnding() ) {
			$this->writer->write( 'ending ', quotedCode( hourtostr( $hours->getEnding() ) ) );
		}
		$this->writer->dec()->write( 'end' );
		return $this;	
	}
}

?>
