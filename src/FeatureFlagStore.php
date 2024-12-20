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
			$flag = $flag_value instanceof FeatureFlag ? $flag_value : new FeatureFlag( $flag_key, $flag_value );
			if ( isset( $flags_clean[ $flag->key ] ) ) {
				throw new \Exception( "Duplicate flag key: {$flag->key}" ); // @codingStandardsIgnoreLine
			}
			$flags_clean[ $flag->key ] = $flag->value;
		}
		return $flags_clean;
	}

	public function is_empty(): bool {
		return $this->is_empty;
	}

	public function is_on( string $flag_key ): bool {
		try {
			$flag_key = FeatureFlag::sanitize_key( $flag_key );
			return 'on' === $this->flags[ $flag_key ];
		} catch ( \Throwable $e ) {
			throw new \Exception( 'Key not found in store' );
		}
	}
}
