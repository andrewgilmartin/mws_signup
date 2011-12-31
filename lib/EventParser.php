<?php

class EventParser {
	
	private $lexer;
	private $keyToData = array();

	function __construct( $tokens, $contacts ) {
		$this->lexer = new Lexer($tokens);
		foreach ( $contacts as $contact ) {
			$this->put($contact->getId(),$contact);
			$this->put($contact->getName(),$contact);
		}
	}

	static function fromScript( $script, $contacts = array() ) {
		$parser = new EventParser($script,$contacts);
		return $parser->parse();
	}

	function parse() {
		try {
			$event = null;
			while ( $t = $this->t() ) {
				switch ( $t ) {
					case 'event':
						$event = $this->parseEvent();
						break;
					case 'comment':
						$comment = $this->t();
						break;
					default:
						throw new Exception( "unexpected token \"$t\" while parsing script");
				}
			}
			return $event;
		}
		catch ( Exception $e ) {
			throw new Exception( 
				"Exception \""
				.$e->getMessage()
				."\" in parsing context "
				.$this->context()
				." in file "
				.$e->getFile().":".$e->getLine()
				.' with stack '
				.$e->getTraceAsString()
			);
		}
		return null;
	}

	private function parseEvent() {
		$event = new Event();
		$event->setName( $this->t() );
		while ( $t = $this->t() ) {
			switch( $t ) {
				case 'id':
					$event->setId( $this->t() );
					break;
				case 'description': 
					$event->setDescription( $this->t() );
					break;
				case 'contact':
					$contact = $this->get( $this->t() );
					if ( $contact ) {
						$event->setContact( $contact );
					}
					else {
						throw new Exception( "expected contact while parsing event");
					}					
					break;
				case 'role':
					$role = $this->parseRole();
					if ( $role ) {
						$event->addRole($role);
						$this->put( $role->getName(), $role );
					}
					else {
						throw new Exception( "expected role while parsing event");
					}
					break;
				case 'day':
					$day = $this->parseDay();
					if ( $day ) {
						$event->addDay($day);
						$this->put( $day->getName(), $day );
					}
					else {
						throw new Exception( "expected day while parsing event");
					}
					break;
				case 'activity':
					$activity = $this->parseActivity();
					if ( $activity ) {
						$event->addActivity($activity);
					}
					else {
						throw new Exception( "expected activity while parsing event");
					}
					break;
				case 'hint':
					$hint = $this->parseHint();
					$event->addHint($hint);
					break;
				case 'volunteer-property': {
					$volunteerProperty = $this->parseVolunteerProperty();
					$event->addVolunteerProperty($volunteerProperty);
					break;
				}
				case 'end':
					return $event;
				case 'comment':
					$comment = $this->t();
					break;
				default:
					throw new Exception( "unexpected token $t while parsing event");
			}
		}
		return null;
	}
	
	private function parseHint() {
		$hint = new Hint();
		$hint->setName($this->t());
		$hint->setValue($this->t());
		return $hint;
	}
	
	private function parseVolunteerProperty() {
		$name = $this->t();
		while ( $t = $this->t() ) {
			switch( $t ) {									
				case 'description': {
					$description = $this->t();
					break;
				}
				case 'end': {
					$volunteerProperty = new VolunteerProperty( $name, $description );
					return $volunteerProperty;
				}
				default: {
					throw new Exception( "unexpected token $t while parsing volunteer property");
				}
			}
		}
		return null;
	}
	
	private function parseDay() {
		$day = new Day();
		$day->setName( $this->t() );
		while ( $t = $this->t() ) {
			switch( $t ) {									
				case 'id':
					$day->setId( $this->t() );
					break;
				case 'date':
					$day->setDate( $this->t() );
					break;
				case 'contact':
					$name = $this->t();
					$contact = $this->get( $name );
					if ( $contact ) {
						$day->setContact($contact);
					}
					else {
						throw new Exception( "expecting contact name but found \"$name\"");
					}
					break;
				case 'hours':
					$hours = $this->parseHours();
					$day->addHours( $hours );
					break;
				case 'end': {
					return $day;										
				}										
				case 'comment':
					$comment = $this->t();
					break;
				default: {
					throw new Exception( "unexpected token $t while parsing day");
				}
			}
		}
		return null;
	}
	
	private function parseHours() {
		$hours = new Hours();
		$hours->setName( $this->t() );
		while ( $t = $this->t() ) {
			switch( $t ) {
				case 'id':
					$hours->setId( $this->t() );
					break;
				case 'starting':
					$hours->setStarting( $this->t() );
					break;
				case 'ending':
					$hours->setEnding( $this->t() );
					break;
				case 'end':
					return $hours;
				case 'comment':
					$comment = $this->t();
					break;
				default:
					throw new Exception( "unexpected token $t while parsing hours");
			}
		}
		return null;
	}
	
	private function parseRole() {
		$role = new Role();
		$role->setName( $this->t() );
		while ( $t = $this->t() ) {
			switch( $t ) {
				case 'id':
					$role->setId( $this->t() );
					break;
				case 'description':
					$role->setDescription( $this->t() );
					break;
				case 'end':
					return $role;
				case 'comment':
					$comment = $this->t();
					break;
				default:
					throw new Exception( "unexpected token $t while parsing role");
			}
		}
		return null;
	}
	
	private function parseActivity() {
		$activity = new Activity();
		$activity->setName( $this->t() );
		while ( $t = $this->t() ) {
			switch( $t ) {										
				case 'id':
					$activity->setId( $this->t() );
					break;
				case 'description':
					$activity->setDescription( $this->t() );
					break;
				case 'contact':
					$name = $this->t();
					$contact = $this->get( $name );
					if ( $contact ) {
						$activity->setContact($contact);
					}
					else {
						throw new Exception( "expected contact while parsing activity but found name \"$name\"");
					}
					break;
				case 'day':
					$name = $this->t();
					$day = $this->get( $name );
					if ( $day ) {
						$shifts = $this->parseShifts();
						foreach ( $shifts as $shift ) {
							$day->addShift($shift);
							$activity->addShift($shift);
						}
					}
					else {
						throw new Exception( "expected day while parsing activity but found name \"$name\"");
					}
					break;
				case 'end': {
					return $activity;
				}
				case 'comment':
					$comment = $this->t();
					break;
				default: {
					throw new Exception( "unexpected token \"$t\" while parsing event's activity");
				}
			}
		}
		return null;
	}
	
	private function parseShifts() {
		$shifts = array();
		while ( $t = $this->t() ) {
			switch( $t ) {
				case 'shift': {
					foreach ( $this->parseShift() as $shift ) {
						$shifts[] = $shift;
					}
					break;
				}
				case 'end': {
					return $shifts;
				}
				default: {
					throw new Exception( "unexpected token \"$t\" while parsing activity's day's shifts");
				}
			}
		}
		return null;
	}
	
	private function parseShift() {
		$shift = new Shift();
		$count = 1;
		$role = $this->get( $this->t() );
		if ( $role ) {
			$shift->setRole( $role );
		}
		else {
			throw new Exception("expecting a role name while parsing shift");
		}
		while ( $t = $this->t() ) {
			switch( $t ) {
				case 'id':
					$shift->setId( $this->t() );
					break;
				case 'count': {
					$count = $this->t();
					break;
				}
				case 'starting':
					$shift->setStarting( $this->t() );
					break;
				case 'ending':
					$shift->setEnding( $this->t() );
					break;
				case 'end':
					$shifts = array( $shift );
					while ( $count > 1 ) {
						$copy = $shift->copy();
						$shifts[] = $copy;
						$count -= 1;
					}
					return $shifts;
				case 'comment':
					$comment = $this->t();
					break;
				default:
					throw new Exception( "unexpected token $t while parsing shift");
			}
		}
		return null;
	}
	
	private function parseVolunteer() {
		$volunteer = new Volunteer();
		$contact = $this->get( $this->t() );
		if ( $contact ) {
			$volunteer->setContact( $contact );
		}
		else {
			throw new Exception("expecting a contact name while parsing volunteer");
		}
		while ( $t = $this->t() ) {
			switch( $t ) {
				case 'id':
					$volunteer->setId( $this->t() );
					break;
				case 'property':
					$name = $this->t();
					$value = $this->t();
					$volunteer->addProperty( $name, $value );
					break;
				case 'end':
					return $volunteer;
				case 'comment':
					$comment = $this->t();
					break;
				default:
					throw new Exception( "unexpected token \"$t\" while parsing volunteer");
			}
		}
		return null;
	}
	
	private function put( $key, $data ) {
		$this->keyToData[ $key ] = $data;
		return $this;
	}

	private function get( $key ) {
		return $this->keyToData[$key];
	}
		
	private function t() {
		return $this->lexer->t();
	}

	private function context() {
		return $this->lexer->context();
	}
}

?>