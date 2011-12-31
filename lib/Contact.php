<?php

class Contact {
	
	private static $nextId = 1;
	
	private $id;
	private $name;
	private $email;
	private $telephone;

	function __construct() {
		$this->id = Contact::$nextId++;
	}
		
	function setId( $id ) {
		$this->id = $id;
		return $this;
	}

	function getId() {
		return $this->id;
	}
	
	function setName( $name ) {
		$this->name = $name;
		return $this;
	}
	
	function getName() {
		return $this->name;
	}
	
	function setEmail( $email ) {
		$this->email = $email;
		return $this;
	}
	
	function getEmail() {
		return $this->email;
	}
	
	function setTelephone( $telephone ) {
		$this->telephone = $telephone;
		return $this;
	}
	
	function getTelephone() {
		return $this->telephone;
	}
	
	static function pickContact( $contacts, $name, $email, $telephone ) {
		foreach ( $contacts as $contact ) {
			if ( $contact->getName() == $name ) {
				return $contact;
			}
		}
		return false;
	}
	
	static function findById( $contacts, $id ) {
		foreach ( $contacts as $contact ) {
			if ( $contact->getId() == $id ) {
				return $contact;
			}
		}
		return false;
	}
}

?>