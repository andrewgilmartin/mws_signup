<?php

class Repository {

	private $db;
	
	function __construct($address,$database,$name,$password) {
		$this->db = new MySql( $address, $database, $name, $password );
		return $this;
	}
	
	function connect() {
		$this->db->connect();
		return $this;
	}

	function getEventRecords() {
		$events = array();
		if ( $rs = $this->db->query("select id, name, version, created_on, updated_on from su_events order by name") ) {
			while ( $event = $rs->fetch() ) {
				$events[] = $event;
			}
		}
		return $events;
	}
	
	function findEventById( $id, $contacts = null ) {
		if ( ! $contacts ) {
			$contacts = $this->getContacts();
		}
		$rc = $this->db->query( "select script, passcode, version from su_events where id = ?", array( $id ) );
		if ( $rc ) {
			if ( $r = $rc->fetch() ) {
				$event = EventParser::fromScript($r['script'], $contacts);
				$event->setId($id);
				$event->setScript($r['script']);
				$event->setPasscode($r['passcode']);
				$event->setVersion($r['version']);
			
				$volunteer_records = $this->getVolunteerRecords( $id );
				foreach ( $volunteer_records as $volunteer_record ) {
					$shift = $event->findShiftById( $volunteer_record['shift_id'] );
					$contact = Contact::findById( $contacts, $volunteer_record['contact_id'] );
					if ( $shift && $contact ) {
						$volunteer = new Volunteer();
						$volunteer->setId( $volunteer_record['id']);
						$volunteer->setContact($contact);
						foreach ( $volunteer_record['properties'] as $property ) {
							$volunteer->addProperty( $property['name'], $property['value'] );
						}						
						$shift->setVolunteer($volunteer);
					}
					else {
						error_log( "unable to find shift and/or contact for volunteer");
					}
				}
				return $event;
			}
		}
		return null;
	}
	
	function addEvent( $event ) {
		$event->setId(null);
		$id = $this->db->insert( "insert into su_events ( created_on, name, passcode, script ) values ( now(), '?', '?', '?' )", array(
			$event->getName(), 
			$event->getPasscode(), 
			$event->getScript()
		) );
		$event->setId($id);
		return $this;
	}
		
	function updateEvent( $event ) {
		$this->db->insert( "insert into su_event_histories ( event_id, script, version ) select id, script, version from su_events where id = ?", array($event->getId()));
		$rc = $this->db->query( "update su_events set name = '?', script = '?', version = version + 1, updated_on = now() where id = ? and version = ?", array(
			$event->getName(), 
			$event->getScript(), 
			$event->getId(),
			$event->getVersion(),
		) );
		if ( ! $rc ) {
			throw new Exception( "unable to update event");
		}
		return $this;
	}
		
	function addContact( $contact ) {
		$id = $this->db->insert( "insert into su_contacts ( created_on, name, email, telephone ) values ( now(), '?', '?', '?' )", array(
			$contact->getName(), 
			$contact->getEmail(), 
			$contact->getTelephone() 
		) );
		$contact->setId($id);
		return $this;
	}

	function updateContact( $contact ) {
		$this->db->query("update su_contacts set updated_on = now(), name = '?', email = '?', telephone = '?' where id = ?", array(
			$contact->getName(),
			$contact->getEmail(),
			$contact->getTelephone(),
			$contact->getId() 
		) );
		return $this;
	}
	
	function getContacts() {
		$contacts = array();
		$rs = $this->db->query("select id, name, email, telephone from su_contacts");
		if ( $rs ) {
			while ( $r = $rs->fetch() ) {
				$contact = new Contact();
				$contact->setId($r['id']);
				$contact->setName($r['name']);
				$contact->setEmail($r['email']);
				$contact->setTelephone($r['telephone']);
				$contacts[] = $contact;
			}
		}
		return $contacts;
	}
	
	function addVolunteer( $volunteer ) {
		$id = $this->db->insert( "insert into su_volunteers ( created_on, event_id, shift_id, contact_id ) values ( now(), ?, ?, ? )", array(
			$volunteer->getShift()->getActivity()->getEvent()->getId(), 
			$volunteer->getShift()->getId(), 
			$volunteer->getContact()->getId() ) );
		$volunteer->setId($id);
		foreach ( $volunteer->getProperties() as $name => $value ) {
			$this->db->insert( "insert into su_volunteer_properties ( volunteer_id, name, value ) values ( ?, '?', '?' )", array(
				$id,
				$name,
				$value				
			));
		}
	}
	
	function removeVolunteer( $id ) {
		$rs = $this->db->query( "delete from su_volunteer_properties where volunteer_id = ?", array($id) );
		$rs = $this->db->query( "delete from su_volunteers where id = ?", array($id) );
	}
	
	function getVolunteerRecords( $eventId ) {
		$idToRecord = array();
		if ( $rs = $this->db->query("select * from su_volunteers where event_id = ?", array($eventId) ) ) {
			while ( $record = $rs->fetch() ) {
 				$record['properties'] = array();
				$idToRecord[$record['id']] = $record;
			}
			unset( $rs );
		}
		if ( $rs = $this->db->query("
				select 
					p.volunteer_id as volunteer_id, 
					p.name as name, 
					p.value as value 
				from 
					su_volunteer_properties p, 
					su_volunteers v 
				where 
					v.event_id = ? and 
					v.id = p.volunteer_id", 
				array($eventId) ) ) {
			while ( $p = $rs->fetch() ) {
				$idToRecord[$p['volunteer_id']]['properties'][] = $p;
			}
		}
		return array_values($idToRecord);
	}
	
	function getContactVolunteerRecords( $eventId ) {
		$records = array();
		if ( $rs = $this->db->query(
				"select	
					c.id contact_id,
					c.name name,
					right(c.name, locate(' ', reverse(c.name))) surname,
				    c.email email, 
				    c.telephone telephone,
					v.shift_id shift_id
				from
					su_volunteers v,
					su_contacts c
				where
					v.event_id = ? and
					c.id = v.contact_id
				order by
					surname"
				, array($eventId) ) ) {
			while ( $record = $rs->fetch() ) {
				$records[] = $record;
			}
			unset( $rs );
		}
		return $records;
	}
	
	function create() {
		$statements = array(
			"create table su_contacts (
				id integer auto_increment,
				created_on timestamp not null,
				updated_on timestamp null,
				name varchar(100),
				email varchar(100),
				telephone varchar(12),
				primary key (id)
			)",
			"create table su_events (
				id integer auto_increment,
				version integer not null default 1,
				created_on timestamp not null,
				updated_on timestamp null,
				contact_id integer,
				name varchar(255) not null,
				script blob not null,
				passcode varchar(36) not null,
				primary key(id)
			)",
			"create table su_event_histories (
				id integer auto_increment,
				created_on timestamp not null,
				updated_on timestamp null,
				event_id integer not null,				
				script blob not null,
				version integer not null,
				primary key(id)
			)",
			"create table su_volunteers (
				id integer auto_increment,
				created_on timestamp not null,
				updated_on timestamp null,
				event_id integer not null,
				shift_id integer not null,
				contact_id integer not null,		
				primary key(id)
			)",
			"create table su_volunteer_properties (
				volunteer_id integer not null,
				name  varchar(100) not null,
				value blob not null,
				primary key(volunteer_id,name)
			)",
			"insert into su_contacts ( created_on, name, email, telephone ) values ( now(), 'Andrew Gilmartin', 'andrew@andrewgilmartin.com', '401-789-3077' )",
			"insert into su_contacts ( created_on, name, email, telephone ) values ( now(), 'Trish Jones', 'trishjones722@yahoo.com', '401-364-3581' )"
		);
		foreach ( $statements as $statement ) {
			$rc = $this->db->query($statement);
			if ( ! $rc ) {
				throw new Exception( "unable to execute statement $statement");
			}
		}
		return $this;
	}
	
	function remove() {
		$statements = array(
			"drop table if exists su_volunteer_properties",
			"drop table if exists su_volunteers",
			"drop table if exists su_events",
			"drop table if exists su_event_histories",
			"drop table if exists su_contacts"
		);
		foreach ( $statements as $statement ) {
			$rc = $this->db->query($statement);
			if ( ! $rc ) {
				throw new Exception( "unable to execute statement $statement");
			}
		}
		return $this;
	}
}

?>
