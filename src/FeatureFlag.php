<?php
/**
 * Feature Flag
 *
 * @package DigitalGravy\FeatureFlag
 */

namespace DigitalGravy\FeatureFlag;

class FeatureFlag {

	private string $key;
	private string $value;

	public function __construct( string $key, string $value ) {
		self::validate( $key, $value );
		$this->key = self::sanitize_key( $key );
		$this->value = $value;
	}

	public function __get( string $name ) {
		if ( ! in_array( $name, array( 'key', 'value' ), true ) ) {
			throw new \Exception( 'Invalid property' );
		}
		return $this->{$name};
	}

	public static function validate( string $key, mixed $value ): bool {
		if ( ! self::is_valid_key( $key ) ) {
			throw new \Exception( "Flag key must contain only alphanumeric characters, underscores, and dashes ~ received: {$key}" ); // @codingStandardsIgnoreLine
		}
		if ( ! self::is_valid_value( $value ) ) {
			throw new \Exception( "Flag value must be 'on' or 'off' ~ received: {$value} for key: {$key}" ); // @codingStandardsIgnoreLine
		}
		return true;
	}

	public static function is_valid( string $key, mixed $value ): bool {
		return self::is_valid_key( $key ) && self::is_valid_value( $value );
	}

	public static function is_valid_key( string $key ): bool {
		return preg_match( '/^[a-zA-Z0-9_-]+$/', $key );
	}

	public static function is_valid_value( mixed $value ): bool {
		return in_array( $value, array( 'on', 'off' ), true );
	}

	public static function sanitize_key( string $key ): string {
		return strtolower( $key );
	}
}
