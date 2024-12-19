<?php
/**
 * A KeyedArray is an array that is indexed by a key.
 *
 * @package DigitalGravy\FeatureFlag
 */

namespace DigitalGravy\FeatureFlag\Storage;

use DigitalGravy\FeatureFlag\FeatureFlag;

class KeyedArray implements FlagStorageInterface {

	public static function get_flags_from( $source = null ): array {
		$clean_flags = array();
		if ( is_null( $source ) ) {
			return $clean_flags;
		}
		foreach ( $source as $key => $value ) {
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
