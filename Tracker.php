<?php
/**
 * Class Tracker
 *
 * Tiny class used  to track variable in a log file or to the browser
 *
 * Usage :
 *
 * $Tracker::log($my_var);
 * $Tracker::echo($my_var);
 *
 */

namespace NOLEAM\Tools;

class Tracker {
	/**
	 * Tracker constructor.
	 *
	 * @param $args:array
	 *
	 *  'color-1' => main background color for echo method
	 *  'color-2' => second background color for echo method
	 *  'text'    => 'text color for echo method
	 *  'mode'    => Display mode  - 1 = print_r, 2=var_dump;
	 *
	 * @since 1.0
	 *
	 */
	public function __construct( $args ) {
		$args['instance'] = true;
		self::init( $args );
	}

	/**
	 * Initialisation
	 *
	 * @param null $args
	 *
	 * return $debug_args[] as global var
	 *
	 * @since 1.0
	 *
	 */
	public static function init( $args=null): void {
		global $debug_args;

		$defaults = [
			'color-1' => '#3c3c3c',
			'color-2' => 'orangered',
			'text'    => '#fff',
			'mode'    => 1, // 1 = print_r, 2=var_dump;
			'instance' => false
		];
		// if there is no defined instance, we set values
		// else we take them as defined
		if (!$debug_args['instance']) {
			$debug_args = wp_parse_args( $args, $defaults );
		}
	}

	/**
	 * Used to log function to error log file
	 *
	 * @param $tracked : variable to track
	 * @param null $args : config
	 *
	 * @since 1.0
	 */
	public static function log( $tracked,$args = null ): void {

		$d = self::analyse( debug_backtrace() );

		ob_start();
		print '-----------------------------------------';
		print PHP_EOL . '                           ' . $d['var'];
		print  PHP_EOL . '--------------------------------------------------------------------' . PHP_EOL;
		echo self::print($tracked,$args );
		print '--------------------------------------------------------------------' . PHP_EOL;

		error_log( ob_get_clean() );

	}

	/**
	 * This function is used to analyse the backtrace in order to retrieve some information
     *
     * see : https://stackoverflow.com/questions/255312/how-to-get-a-variable-name-as-a-string-in-php
	 *
	 * @param $back_trace
	 *
	 * @return array containing file, line and variable name
	 *
	 * @since 1.0
	 */
	private static function analyse( $back_trace ) :array {
		$file = file( $back_trace[0]['file'] );
		$line = $back_trace[0]['line'];
		$var_name = trim(
			preg_replace( '#(.*)' . debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 2 )[1]['function'] . ' *?\( *?(.*) *?\)(.*)#i',
				'$2',
				$file[ $line- 1 ]) );

		return [
			'file' => $file,
			'line' => $line,
			'var'  => $var_name
		];
	}

	/**
	 * @param $tracked : variable to track
	 * @param null $args : config
	 *
	 * @return false|string
	 */
	private static function print( $tracked,$args = null ) : void    {

		global $debug_args;
		self::init($args);

		ob_start();
		if ( is_array( $tracked ) || is_object( $tracked ) ) {

			if ( $debug_args['mode'] === 2 ) {
				var_dump( $tracked );
			} else {
				print_r( $tracked );
			}
		} else if ( empty( $tracked ) ) {
			print 'NO DATA' . PHP_EOL;
		} else {
			print $tracked . PHP_EOL;
		}

		return ( ob_get_clean() );
	}

	/**
     * Used to display information in a browser
     *
	 * @param $tracked : variable to track
	 * @param null $args : config
	 *
	 */
	public static function echo( $tracked,$args = null ): void {
		global $debug_args;
		self::init();

		$d = self::analyse( debug_backtrace() );

		ob_start();
		?>
        <div style="background-color:<?= $debug_args['color-2'] ?>;
                color:<?= $debug_args['text'] ?>;
                width:100%;
                padding:0.5rem">
			<?= $d['var'] ?>
            <pre style="background-color:<?= $debug_args['color-1'] ?>;
                    color:<?= $debug_args['text'] ?>;
                    border-left:2rem solid <?= $debug_args['color-2'] ?>;
                    padding:0.5rem;
                    margin: 1rem 0"><?= self::print( $tracked,$args ) ?></pre>
        </div>
		<?php
		echo ob_get_clean();
	}
}