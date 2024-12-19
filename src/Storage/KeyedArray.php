<?php
/**
 * A KeyedArray is an array that is indexed by a key.
 *
 * @package DigitalGravy\FeatureFlag
 */

namespace DigitalGravy\FeatureFlag\Storage;

use DigitalGravy\FeatureFlag\FeatureFlag;

class KeyedArray {

	public static function get_from( array $flags = array() ) {
		$clean_flags = array();
		foreach ( $flags as $key => $value ) {
			try {
				$flag = new FeatureFlag( $key, $value );
				$clean_flags[ $flag->key ] = $flag->value;
			} catch ( \Exception $e ) {
				continue;
			}
		}
		return $clean_flags;
	}
}
