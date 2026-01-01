<?php

date_default_timezone_set('America/New_York');

function strtobool( $str ) {
    switch (strtolower($str || "false")) {
        case ("true"): return true;
        default: return false;
    }
}

function strtohour( $str ) {
	if ( preg_match( "/^\s*(\d+)\s*([AP])M\s*$/i", $str, $matches) ) {
		# 12 PM .. 11 AM -> 0 .. 11
		# 12 PM .. 11 PM -> 12 .. 23
		$hour = $matches[1];
		$am = strtoupper($matches[2]) == "A" ? true : false;
		if ( $am ) {
			if ( $hour == 12 ) {
				$hour = 0;
			}
		}
		else {
			if ( $hour != 12 ) {
				$hour = $hour + 12;
			}
		}
		return ($hour+0);
	}
	return -1;
}

function hourtostr( $hour ) {
	if ( $hour == 0 ) {
		return "12 AM";
	}
	if ( $hour < 12 ) {
		return "$hour AM";
	}
	if ( $hour == 12 ) {
		return "12 PM";
	}
	return ( $hour - 12 ) . " PM";
}

function timetostr( $time ) {
	return date("Y-m-d",$time*1);
}

function to_hour( $hour ) {
	if ( is_string( $hour ) ) {
		$hour = strtohour( $hour );
	}
	return $hour;
}

function to_date( $date ) {
	if ( is_string( $date ) ) {
		$date = strtotime( $date );
	}
	return $date;
}

function cmp( $a, $b ) {
	return $a < $b ? -1 : ( $b < $a ? 1 : 0 );
}

function minimum( $a, $b ) {
	return is_null($a)
		? $b
		: ( is_null($b)
			? $a
			: min( $a, $b ) );
}

function maximum( $a, $b ) {
	return is_null($a)
		? $b
		: ( is_null($b)
			? $a
			: max( $a, $b ) );
}

function is_empty( $s ) {
	return strlen( trim( $s ) ) == 0;
}

function is_not_empty( $s ) {
	return ! is_empty( $s );
}

function quotedCode( $s ) {
	return '"' . ( $s ? addslashes( $s ) : "" ) . '"';
}

function unquoteCode( $s ) {
	if ( preg_match( '/^\"(.*)\"$/s', $s, $matches ) ) {
		return stripslashes( $matches[1] );
	}
	elseif ( preg_match( '/^\'(.*)\'$/s', $s, $matches ) ) {
		return stripslashes( $matches[1] );
	}
	else {
		return $s;
	}
}

function updatefile( $filename, $content ) {
	$backupFilename = tempnam( "script-backups", "$filename-" );
	copy( $filename, $backupFilename );
	chmod( $backupFilename, 0666 );
	file_put_contents($filename, $content);
	return $backupFilename;
}

$nextId = 1000;

function nextId() {
	global $nextId;
	return $nextId++;
}

function makeContactSummaryHtml( $contact ) {
	$summary = $contact->getName();
	$connector = " at ";
	if ( $contact->getTelephone() ) {
		$summary .= $connector . htmlspecialchars( $contact->getTelephone() ) ;
		$connector = " or ";
	}
	if ( $contact->getEmail() ) {
		$summary .= $connector . " <a href=\"mailto:" . htmlspecialchars( $contact->getEmail() ) . "\">" . htmlspecialchars( $contact->getEmail() ) . "</a>";
	}
	return $summary;
}

// httpRedirect( [ page (, [name, value] )* ] )
function httpRedirect() {
	$url = func_get_arg(0);
	if ( func_num_args() > 0 ) {
		$url .= func_get_arg(1); // path
		$connector = "?";
		for( $i = 2; $i < func_num_args(); $i += 2) {
			$name = func_get_arg($i);
			$value = func_get_arg($i+1);
			$url .= ( $connector.urlencode($name)."=".urlencode($value) );
			$connector = "&";
		}
	}
	Header( "HTTP/1.1 302 Moved" );
	Header( "Location: $url" );
	ob_end_flush();
	flush();
	exit();
}

// make_url( [ page (, [name, value] )* ] )
function make_url() {
	$url = "";
	if ( func_num_args() > 0 ) {
		$url .= "/../".func_get_arg(0); // path
		$connector = "?";
		for( $i = 1; $i < func_num_args(); $i += 2) {
			$name = func_get_arg($i);
			$value = func_get_arg($i+1);
			$url .= ( $connector.urlencode($name)."=".urlencode($value) );
			$connector = "&";
		}
	}
	return $url;
}

function gen_uuid() {
	// see http://stackoverflow.com/questions/2040240/php-function-to-generate-v4-uuid
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

function is_http_post_request() {
	return $_SERVER['REQUEST_METHOD'] == 'POST';
}

function is_http_get_request() {
	return $_SERVER['REQUEST_METHOD'] == 'GET';
}

function rs( $name, $default = null ) {
	if ( array_key_exists( $name, $_REQUEST ) ) {
		return trim(stripslashes($_REQUEST[$name]));
	}
	else if ( array_key_exists( $name, $_SESSION ) ) {
		return trim(stripslashes($_SESSION[$name]));
	}
	else {
		return $default;
	}
}

function XDUMP() {
	print "<pre>";
	for( $i = 0; $i < func_num_args(); $i++) {
		$a = func_get_arg($i);
		print var_dump($a) . "\n";
	}
	print "</pre>";
}

function DUMP() {
	for( $i = 0; $i < func_num_args(); $i++) {
		$a = func_get_arg($i);
		print var_dump($a) . "\n";
	}
}

?>
