<?php
/**
 * @package DigitalGravy\FeatureFlag
 */

namespace DigitalGravy\FeatureFlag\Storage;

use DigitalGravy\FeatureFlag\FeatureFlag;

class PHPConstant implements FlagStorageInterface {

	public static function get_flags_from( $source = null ): array {
		$flags = array();
		if ( ! is_array( $source ) ) {
			return $flags;
		}
		foreach ( $source as $flag_key => $flag_value ) {
			try {
				$flag_data = self::clean_flag( $flag_key, $flag_value );
				$flag = new FeatureFlag( $flag_data[0], $flag_data[1] );
				$flags[ $flag->key ] = $flag->value;
			} catch ( \Exception $e ) {
				continue;
			}
		}
		return $flags;
	}

	public static function clean_flag( $flag_key, $flag_value ): array {
		if ( is_numeric( $flag_key ) && defined( $flag_value ) ) {
			// When we have a numeric key, treat the value as a constant name.
			$constant_name = $flag_value;
			$constant_value = constant( $constant_name );

			// Convert boolean constants to strings.
			if ( is_bool( $constant_value ) ) {
				$constant_value = $constant_value ? 'on' : 'off';
			}

			$flag_key = $constant_name;
			$flag_value = $constant_value;
		}
		return array( $flag_key, $flag_value );
	}
}
