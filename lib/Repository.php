<?php

class Repository
{
    private $database_address;
    private $database_user;
    private $database_password;
    private $db;

    function __construct($address, $user, $password)
    {
        $this->database_address = $address;
        $this->database_user = $user;
        $this->database_password = $password;
        return $this;
    }

    function connect(): static
    {
        $this->db = new PDO($this->database_address, $this->database_user, $this->database_password);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Set default fetch mode
        return $this;
    }

    function getEventRecords(): array
    {
        $events = [];
        if ($rs = $this->execute("select id, name, version, created_on, updated_on from su_events order by name", [])) {
            while ($event = $rs->fetch()) {
                $events[] = $event;
            }
        }
        return $events;
    }

    function findEventById($id, $contacts = null): ?Event
    {
        if (!$contacts) {
            $contacts = $this->getContacts();
        }
        $rc = $this->execute("select script, passcode, version from su_events where id = ?", [$id]);
        if ($rc) {
            if ($r = $rc->fetch()) {
                $event = EventParser::fromScript($r['script'], $contacts);
                $event->setId($id);
                $event->setScript($r['script']);
                $event->setPasscode($r['passcode']);
                $event->setVersion($r['version']);

                $volunteer_records = $this->getVolunteerRecords($id);
                foreach ($volunteer_records as $volunteer_record) {
                    $shift = $event->findShiftById($volunteer_record['shift_id']);
                    $contact = Contact::findById($contacts, $volunteer_record['contact_id']);
                    if ($shift && $contact) {
                        $volunteer = new Volunteer();
                        $volunteer->setId($volunteer_record['id']);
                        $volunteer->setContact($contact);
                        foreach ($volunteer_record['properties'] as $property) {
                            $volunteer->addProperty($property['name'], $property['value']);
                        }
                        $shift->setVolunteer($volunteer);
                    } else {
                        error_log("unable to find shift and/or contact for volunteer");
                    }
                }
                return $event;
            }
        }
        return null;
    }

    function addEvent($event): static
    {
        $id = $this->insert("insert into su_events ( created_on, name, passcode, script ) values ( now(), ?, ?, ? )", [
            $event->getName(),
            $event->getPasscode(),
            $event->getScript()
        ]);
        $event->setId($id);
        return $this;
    }

    function updateEvent($event): static
    {
        $this->insert("insert into su_event_histories ( event_id, script, version ) select id, script, version from su_events where id = ?", [$event->getId()]);
        $rc = $this->execute("update su_events set name = ?, script = ?, version = version + 1, updated_on = now() where id = ? and version = ?", [
            $event->getName(),
            $event->getScript(),
            $event->getId(),
            $event->getVersion()
        ]);
        if (!$rc) {
            throw new Exception("unable to update event");
        }
        return $this;
    }

    function addContact($contact): static
    {
        $id = $this->insert("insert into su_contacts ( created_on, name, email, telephone ) values ( now(), ?, ?, ? )", [
            $contact->getName(),
            $contact->getEmail(),
            $contact->getTelephone()
        ]);
        $contact->setId($id);
        return $this;
    }

    function updateContact($contact): static
    {
        $this->execute("update su_contacts set updated_on = now(), name = ?, email = ?, telephone = ? where id = ?", [
            $contact->getName(),
            $contact->getEmail(),
            $contact->getTelephone(),
            $contact->getId()
        ]);
        return $this;
    }

    function getContacts(): array
    {
        $contacts = array();
        $rs = $this->execute("select id, name, email, telephone from su_contacts", []);
        if ($rs) {
            while ($r = $rs->fetch()) {
                $contact = new Contact( $r['name'] );
                $contact->setId($r['id']);
                $contact->setEmail($r['email']);
                $contact->setTelephone($r['telephone']);
                $contacts[] = $contact;
            }
        }
        return $contacts;
    }

    function addVolunteer($volunteer): void
    {
        $id = $this->insert("insert into su_volunteers ( created_on, event_id, shift_id, contact_id ) values ( now(), ?, ?, ? )", [
            $volunteer->getShift()->getActivity()->getEvent()->getId(),
            $volunteer->getShift()->getId(),
            $volunteer->getContact()->getId()
        ]);
        $volunteer->setId($id);
        foreach ($volunteer->getProperties() as $name => $value) {
            $this->insert("insert into su_volunteer_properties ( volunteer_id, name, value ) values ( ?, ?, ? )", [
                $id,
                $name,
                $value
            ]);
        }
    }

    function removeVolunteer($id): void
    {
        $rs = $this->execute("delete from su_volunteer_properties where volunteer_id = ?", [$id]);
        $rs = $this->execute("delete from su_volunteers where id = ?", [$id]);
    }

    function getVolunteerRecords($eventId): array
    {
        $idToRecord = array();
        if ($rs = $this->execute("select * from su_volunteers where event_id = ?", [$eventId])) {
            while ($record = $rs->fetch()) {
                $record['properties'] = array();
                $idToRecord[$record['id']] = $record;
            }
            unset($rs);
        }
        if ($rs = $this->execute("
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
            [$eventId])) {
            while ($p = $rs->fetch()) {
                $idToRecord[$p['volunteer_id']]['properties'][] = $p;
            }
        }
        return array_values($idToRecord);
    }

    function getContactVolunteerRecords($eventId): array
    {
        $records = array();
        if ($rs = $this->execute(
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
            , [$eventId])) {
            while ($record = $rs->fetch()) {
                $records[] = $record;
            }
            unset($rs);
        }
        return $records;
    }

    function execute($sql, $bindings)
    {
        $statement = $this->db->prepare($sql);
        $statement->execute($bindings);
        return $statement;
    }

    function insert($sql, $bindings) {
        $this->execute($sql, $bindings);
        return $this->db->lastInsertId();
    }



}

?>
