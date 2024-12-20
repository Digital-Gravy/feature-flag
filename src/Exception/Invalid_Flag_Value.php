<?php
/**
 * @package DigitalGravy\FeatureFlag
 */

namespace DigitalGravy\FeatureFlag\Exception;

use function DigitalGravy\FeatureFlag\Helpers\escape_output;

class Invalid_Flag_Value extends \Exception {

	/**
	 * @param string $key The flag key.
	 * @param string $value The invalid flag value.
	 */
	public function __construct( string $key, string $value ) {
		$message = sprintf( 'Flag value must be "on" or "off" ~ received: %s for key: %s', escape_output( $value ), escape_output( $key ) );
		parent::__construct( $message );
	}
}
