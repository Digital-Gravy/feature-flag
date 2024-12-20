<?php
/**
 * Feature Flag
 *
 * @package DigitalGravy\FeatureFlag
 */

namespace DigitalGravy\FeatureFlag;

/**
 * @property-read string $key The flag key.
 * @property-read string $value The flag value.
 */
class FeatureFlag {

	private string $key;
	private string $value;

	public function __construct( string $key, string $value ) {
		self::validate( $key, $value );
		$this->key = self::sanitize_key( $key );
		$this->value = $value;
	}

	/**
	 * @param string $name The property name.
	 * @return string
	 * @throws \Exception If the property is not valid.
	 */
	public function __get( string $name ): string {
		if ( ! in_array( $name, array( 'key', 'value' ), true ) ) {
			throw new \Exception( 'Invalid property' );
		}
		return $this->{$name};
	}

	/**
	 * @param string $key The flag key.
	 * @param string $value The flag value.
	 * @throws \Exception If the key or value is invalid.
	 */
	public static function validate( string $key, string $value ): bool {
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
		return preg_match( '/^[a-zA-Z0-9_-]+$/', $key ) === 1;
	}

	public static function is_valid_value( mixed $value ): bool {
		return in_array( $value, array( 'on', 'off' ), true );
	}

	public static function sanitize_key( string $key ): string {
		return strtolower( $key );
	}
}
