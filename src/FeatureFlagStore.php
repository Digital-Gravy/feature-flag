<?php
/**
 * Feature Flag Store
 *
 * @package DigitalGravy\FeatureFlag
 */

namespace DigitalGravy\FeatureFlag;

class FeatureFlagStore {

	private bool $is_empty = true;
	private array $flags = array();

	public function __construct( array ...$sources ) {
		$this->flags = static::clean_flags( static::merge_sources( ...$sources ) );
		$this->is_empty = empty( $this->flags );
	}

	private static function merge_sources( ...$sources ) {
		return array_merge( ...$sources );
	}

	private static function clean_flags( array $flags_dirty ) {
		$flags_clean = array();
		foreach ( $flags_dirty as $flag_key => $flag_value ) {
			$flag = new FeatureFlag( $flag_key );
			$flag_key = (string) $flag;
			if ( isset( $flags_clean[ $flag_key ] ) ) {
				throw new \Exception( "Duplicate flag key: {$flag_key}" ); // @codingStandardsIgnoreLine
			}
			if ( ! in_array( $flag_value, array( 'on', 'off' ), true ) ) {
				throw new \Exception( "Invalid value for flag {$flag_key}: {$flag_value}" ); // @codingStandardsIgnoreLine
			}
			$flags_clean[ $flag_key ] = $flag_value;
		}
		return $flags_clean;
	}

	public function is_empty(): bool {
		return $this->is_empty;
	}

	public function is_on( FeatureFlag|string $flagOrFlagKey ): bool {
		try {
			$flag_key = $flagOrFlagKey instanceof FeatureFlag ? (string) $flagOrFlagKey : $flagOrFlagKey;
			return 'on' === $this->flags[ $flag_key ];
		} catch ( \Throwable $e ) {
			throw new \Exception( 'Key not found in store' );
		}
	}
}
