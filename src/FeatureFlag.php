<?php
/**
 * Feature Flag
 *
 * @package DigitalGravy\FeatureFlag
 */

namespace DigitalGravy\FeatureFlag;

class FeatureFlag {

	private string $key;

	public function __construct( string $key ) {
		if ( ! preg_match( '/^[a-zA-Z0-9_-]+$/', $key ) ) {
			throw new \Exception( 'Flag key must contain only alphanumeric characters, underscores, and dashes' );
		}
		$this->key = strtolower( $key );
	}

	public function __toString(): string {
		return $this->key;
	}

	public static function is_valid( string $key, mixed $value ): bool {
		return preg_match( '/^[a-zA-Z0-9_-]+$/', $key ) && in_array( $value, array( 'on', 'off' ), true );
	}
}
