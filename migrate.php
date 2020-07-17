#!/usr/bin/php
<?php
require 'load.php';

// to do not break anything it's better to end with a slash if your domain is short
$from = $argv[1] ?? null; // "http://test.sangirolamobari.com"
$to   = $argv[2] ?? null; // "http://www.sangirolamobari.com"

if( !$from ) {
	echo "Missing FROMURL\n";
	exit;
}

if( !$to ) {
	echo "Missing TOURL\n";
	exit;
}

// make relative URLs in image pathnames
foreach( [ 'post_content', 'post_excerpt' ] as $text_columm ) {
	foreach( [ $from, $to ] as $url ) {
		// make relative URLs in images
		$url_attribute = esc_html( $url );
		( new Query() )
			->from( 'posts' )
			->where( 1 )
			->update( col_replace( $text_columm, " src=\"$url_attribute", ' src="/' ) );

		( new Query() )
			->from( 'posts' )
			->where( 1 )
			->update( col_replace( $text_columm, " href=\"$url_attribute", ' href="/' ) );
	}
}

// update all the option values
$options = ( new Query() )
	->from( 'options' )
	->queryGenerator();

// each option must be evaluated manually because they may be serialized
foreach( $options as $option ) {

	// get the raw value
	$id    = $option->get( 'option_id'    );
	$value = $option->get( 'option_value' );
	$value_unserialized = @unserialize( $value );

	$new_value = null;

	if( $value_unserialized === false ) {
		if( strpos( $value, $from ) !== false ) {
			$new_value = url_replace( $from, $to, $value );
		}
	} else {
		if( is_object( $value_unserialized ) ) {
			replace_object( $from, $to, $value_unserialized, $replaced );
			if( $replaced ) {
				$new_value = serialize( $value_unserialized );
			}
		} elseif( is_string( $value_unserialized ) ) {
			if( strpos( $value_unserialized, $from ) !== false ) {
				$value_unserialized = url_replace( $from, $to, $value_unserialized );
				$new_value = serialize( $value_unserialized );
			}
		}
	}

	if( $new_value ) {
		echo "\n";
		echo "From:\n";
		echo $value;
		echo "\n";
		echo "To:\n";
		print_r( $new_value );

		echo "\nSave [Y/n]?\n";
		$y = readline();
		if( $y === 'y' || empty( $y ) ) {
			( new Query() )
				->from( 'options' )
				->whereStr( 'option_id', $id )
				->update( [
					'option_value' => $new_value,
				] );

			echo "Saved\n";
		} else {
			echo "Skipped\n";
		}
	} else {
		echo "Nothing to do from $value\n";
	}
}

// update all the option values
$options = ( new Query() )
	->from( 'postmeta' )
	->queryGenerator();

// each option must be evaluated manually because they may be serialized
foreach( $options as $option ) {

	// get the raw value
	$id    = $option->get( 'meta_id'    );
	$value = $option->get( 'meta_value' );
	$value_unserialized = @unserialize( $value );

	$new_value = null;

	if( $value_unserialized === false ) {
		if( strpos( $value, $from ) !== false ) {
			$new_value = url_replace( $from, $to, $value );
		}
	} else {
		if( is_object( $value_unserialized ) ) {
			replace_object( $from, $to, $value_unserialized, $replaced );
			if( $replaced ) {
				$new_value = serialize( $value_unserialized );
			}
		} elseif( is_string( $value_unserialized ) ) {
			if( strpos( $value_unserialized, $from ) !== false ) {
				$value_unserialized = url_replace( $from, $to, $value_unserialized );
				$new_value = serialize( $value_unserialized );
			}
		}
	}

	if( $new_value ) {
		echo "\n";
		echo "From:\n";
		echo $value;
		echo "\n";
		echo "To:\n";
		print_r( $new_value );

		echo "\nSave [Y/n]?\n";
		$y = readline();
		if( $y === 'y' || empty( $y ) ) {
			( new Query() )
				->from( 'postmeta' )
				->whereStr( 'meta_id', $id )
				->update( [
					'meta_value' => $new_value,
				] );

			echo "Saved\n";
		} else {
			echo "Skipped\n";
		}
	} else {
		echo "Nothing to do from $value\n";
	}
}

/**
 * Replace something from an object
 */
function replace_object( $from, $to, $obj, & $replaced = false ) {

	// for each attribute
	foreach( $obj as $attr => $value ) {

		if( is_object( $value ) ) {
			// eventually proceed in the leafs
			replace_object( $from, $to, $value );
		} elseif( is_string( $value ) ) {
			if( strpos( $value, $from ) !== false ) {
				if( is_array( $obj ) ) {
					$obj[ $attr ] = str_replace( $from, $to, $value );
				} else {
					$obj->$attr   = str_replace( $from, $to, $value );
				}
				$replaced = true;
			}
		}
	}

}


function sql_replace( $col, $from, $to ) {
	return sprintf(
		"REPLACE( %s, '%s', '%s' )",
		$col,
		esc_sql( $from ),
		esc_sql( $to )
	);
}

function col_replace( $col, $from, $to ) {
	return new DBCol(
		$col,
		sql_replace( $col, $from, $to ),
		'-'
	);
}

/**
 * Replace an URL from a string
 */
function url_replace( $from, $to, $value ) {

	$value = str_replace( $from, $to, $value );

	// make relative URLs in images
	$value = str_replace( " src=\"$to", ' src="/', $value );

	// make relative URLs in links
	$value = str_replace( " href=\"$to", ' href="/', $value );

	return $value;
}
