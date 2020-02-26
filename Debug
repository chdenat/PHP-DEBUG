<?php


class Debug {

	static public function log( $args = null ) {

		$d = self::analyse( debug_backtrace() );

		ob_start();
		print '-----------------------------------------';
		print PHP_EOL . '                           ' . $d['var'];
		print  PHP_EOL . '--------------------------------------------------------------------' . PHP_EOL;
		echo self::print( $args );
		print '--------------------------------------------------------------------' . PHP_EOL;

		error_log( ob_get_clean() );

	}

	private static function analyse( $bt ) {
		$file = file( $bt[0]['file'] );
		// select exact print_var_name($varname) line
		$src = $file[ $bt[0]['line'] - 1 ];
		// search pattern
		$pat = '#(.*)' . debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 2 )[1]['function'] . ' *?\( *?(.*) *?\)(.*)#i';
		// extract $varname from match no 2
		$var = trim( preg_replace( $pat, '$2', $src ) );

		return [
			'file' => $file,
			'line' => $bt[0]['line'],
			'var'  => $var
		];
	}

	private static function print( $to_debug ) {

		global $debug_mode; // 1 = print_r, 2=var_dump;
		self::init();

		ob_start();
		if ( is_array( $to_debug ) || is_object( $to_debug ) ) {

			if ( $debug_mode === 2 ) {
				var_dump( $to_debug );
			} else {
				print_r( $to_debug );
			}
		} else if ( empty( $to_debug ) ) {
			print 'NO DATA' . PHP_EOL;
		} else {
			print $to_debug . PHP_EOL;
		}

		return ( ob_get_clean() );
	}

	static public function init( $debug_mode = 2 ) {
		global $debug_mode; // 1 = print_r, 2=var_dump;
	}

	static public function echo( $args = null ) {

		$d = self::analyse( debug_backtrace() );

		ob_start();
		echo '<div style="background-color:orangered;color:white;width:100%;padding:0.5rem">' . $d['var'];
		echo '<pre style="background-color:#3c3c3c;color:white;border-left:2rem solid orangered;padding:0.5rem;margin: 1rem 0">';
		echo self::print( $args );
		echo '</pre></div>';
	}
}