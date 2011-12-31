<?php

class MySql {

	private $database;
	private $address;
	private $name;
	private $password;
	private $link;

	function __construct( $address, $database, $name, $password ) {
		$this->address = $address;
		$this->database = $database;
		$this->name = $name;
		$this->password = $password;
		return $this;
	}
	
	function __destruct() {
		if ( $this->link ) {
			mysqli_close($this->link);
		}
	}
	
	function connect() {
		$result = mysqli_connect($this->address, $this->name, $this->password);
		if ( $result === FALSE ) {
			throw new Exception("unable to connect to server: address=".$this->address."; name=".$this->name);
		}
		$this->link = $result;
		$result = mysqli_select_db($this->link,$this->database);
		if ( $result === FALSE ) {
			throw new Exception("Could not select database ".$this->database.": ".mysqli_error($this->link));
		}
		return $this;
	}

	function query( $paramterizedStatement, $bindings = array() ) {
		$statement = $this->bind( $paramterizedStatement, $bindings );
		$result = mysqli_query($this->link,$statement);
		if ( is_null($result) ) {
			return new MySqlEmptyResult(0);
		}
		else if ( $result === FALSE ) {
			throw new Exception("unable to perform query: ".mysqli_error($this->link));
		}
		else if ( $result === TRUE ) {
			return new MySqlEmptyResult();
		}
		else {
			return new MySqlResult($result);
		}
	}

	function insert( $paramterizedStatement, $bindings = array() ) {
		$statement = $this->bind( $paramterizedStatement, $bindings );
		$result = mysqli_query($this->link,$statement);
		if ( $result === FALSE ) {
			throw new Exception("unable to perform insert: ".mysqli_error($this->link));
		}
		else if ( $result == TRUE ) {
			return mysqli_insert_id($this->link);
		}
		else {
			throw new Exception( "don't understand result \"$result\"");
		}
	}
	
	private function bind( $paramterizedStatement, $bindings ) {
		if ( ! is_array( $bindings ) ) {
			throw new Exception( "bindings is not an array");
		}
		$statement = $paramterizedStatement;
		$offset = 0;
		$argi = 0;
		$argc = count($bindings);
		while ( preg_match( '/(\'\?\')|(\?timestamp)|(\?date)|(\?)/', $statement, $matches, PREG_OFFSET_CAPTURE, $offset ) ) {
			if ( $argi == $argc ) {
				throw new Exception( "too few bindings for parameters: paramterizedStatement=$paramterizedStatement" );
			}
			$found = $matches[0][0];
			$index = $matches[0][1];
			if ( $found == '?' ) {
				$arg = mysqli_real_escape_string($this->link,$bindings[$argi++]);
				$statement = substr_replace( $statement, $arg, $index, 1 );
				$offset = $index + strlen($arg);
			}
			else if ( $found == "'?'" ){
				$arg = mysqli_real_escape_string($this->link,$bindings[$argi++]);
				$statement = substr_replace( $statement, $arg, $index + 1, 1 );
				$offset = $index + strlen($arg) + 2;
			}
			else if ( $found == '?timestamp' ) {
				$arg = $bindings[$argi++]->format( '\'Y-m-d H:i:s\'' );
				$statement = substr_replace( $statement, $arg, $index, 10 );
				$offset = $index + strlen($arg);
			}
			else if ( $found == '?date' ) {
				$arg = $bindings[$argi++]->format( '\'Y-m-d\'' );
				$statement = substr_replace( $statement, $arg, $index, 5 );
				$offset = $index + strlen($arg);
			}
		}
		error_log( $statement );
		return $statement;
	}
}

class MySqlEmptyResult {

	function fetch() {
		return null;
	}
}

class MySqlResult {
	
	private $resource;
	
	function __construct( $resource ) {
		if ( $resource === null ) throw new Exception( "resource must not be null");
		$this->resource = $resource;
	}
	
	function __destruct() {
		mysqli_free_result($this->resource);
	}

	function fetch() {
		return mysqli_fetch_assoc($this->resource);
	}
}

?>