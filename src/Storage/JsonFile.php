<?php
/**
 * JSON File Storage
 *
 * @package DigitalGravy\FeatureFlag\Storage
 */

namespace DigitalGravy\FeatureFlag\Storage;

use DigitalGravy\FeatureFlag\FeatureFlag;

class JsonFile implements FlagStorageInterface {

	/**
	 * @param mixed $source The source of the flags: a string path to a JSON file or an array of flags.
	 * @return array<string,string> Array of flag states where values are 'on' or 'off'
	 * @throws \Exception If unable to retrieve flags.
	 */
	public static function get_flags_from( $source = null ): array {
		$flags = array();
		$flags_clean = array();
		if ( is_null( $source ) ) {
			return $flags_clean;
		} else if ( is_string( $source ) ) {
			if ( ! file_exists( $source ) ) {
				throw new \Exception( 'JSON file not found' );
			}
			$flags = json_decode( file_get_contents( $source ), true );
		} else {
			$flags = $source;
		}
		foreach ( $flags as $key => $value ) {
			try {
				$flag = new FeatureFlag( $key, $value );
				$flags_clean[ $flag->key ] = $flag->value;
			} catch ( \Exception $e ) {
				continue;
			}
		}
		return $flags_clean;
	}
}
