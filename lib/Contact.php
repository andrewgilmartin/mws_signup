<?php

class Contact {

	private static int $nextId = 1;

	private int $id;
	private string $name;
	private ?string $email;
	private ?string $telephone;

	function __construct( $name ) {
		$this->id = Contact::$nextId++;
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

	function setEmail( $email ): static
    {
		$this->email = $email;
		return $this;
	}

	function getEmail(): ?string
    {
		return $this->email ?? null;
	}

	function setTelephone( $telephone ): static
    {
		$this->telephone = $telephone;
		return $this;
	}

	function getTelephone(): ?string
    {
		return $this->telephone ?? null;
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
