<?php

class Writer {
	
	private $tab;
	private $null;
	private $indent = 0;
	private $text = "";
	
	function __construct( $tab, $null ) {
		$this->tab = $tab;
		$this->null = $null;
		return $this;
	}
	
	function inc() {
		$this->indent += 1;
		return $this;
	}
	
	function dec() {
		$this->indent -= 1;
		if ( $this->indent < 0 ) {
			throw new Exception("indent must be 0 or greater");
		}
		return $this;
	}
	
	function write() {
		$this->text .= str_repeat( $this->tab, $this->indent );
		for( $i = 0; $i < func_num_args(); $i++) {
			$a = func_get_arg($i);
			$this->text .= ( is_null($a) ? $this->null : $a );
		}
		$this->text .= "\n";
		return $this;
	}

	function text() {
		return $this->text;
	}
	
}

?>
