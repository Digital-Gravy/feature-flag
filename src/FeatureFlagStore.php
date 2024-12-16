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
		$flags_clean = array();
		$all_flags = array();
		foreach ( $sources as $source ) {
			$all_flags = array_merge( $all_flags, $source );
		}
		foreach ( $all_flags as $flag_key => $flag_value ) {
			$flag = new FeatureFlag( $flag_key );
			$flag_key = (string) $flag;
			if ( isset( $flags_clean[ $flag_key ] ) ) {
				throw new \Exception( 'Duplicate flag key: ' . $flag_key ); // @codingStandardsIgnoreLine
			}
			$flags_clean[ $flag_key ] = $flag_value;
		}
		$this->flags = $flags_clean;
		$this->is_empty = empty( $this->flags );
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
