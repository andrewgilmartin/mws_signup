<?php

class Lexer {
	
	private $tokens;
	private $cursor;
	private $count;
	
	function __construct( $tokens ) {
		if ( is_string($tokens) ) {
			$tokens = preg_split( '/( (?:[\s]+) | (?:[^\s"]+) | (?:\"[^\"]*\") )/sx', $tokens, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY );
		}
		$tokens = array_filter( $tokens, 'is_not_empty' );
		$tokens = array_values(array_map( 'unquoteCode', $tokens ));
		$this->tokens = $tokens;
		$this->count = count( $this->tokens );
		$this->cursor = 0;
	}

	function t() {
		$t = $this->cursor < $this->count ? $this->tokens[ $this->cursor++ ] : null;
		return $t;
	}
	
	function context() {
		$cursor = max( 0, $this->cursor - 1 );
		$left = $cursor > 0
			? join( 
					" ", 
					array_map(
						'quotedCode',
						array_slice( 
							$this->tokens, 
							max( 0, $cursor - 10 ), 
							min( $cursor, 10 ) 
						)
					)
				)
			: "";
		$right = $cursor < $this->count
			? join( 
					" ", 
					array_map(
						'quotedCode',
						array_slice( 
							$this->tokens, 
							$cursor + 1, 
							min( 10, $this->count - $cursor ) 
						) 
					)
				)
			: "";
		$center = $cursor < $this->count 
			? $this->tokens[ $cursor ]
			: "";
		return "... $left >> $center << $right ...";
	}
}

?>