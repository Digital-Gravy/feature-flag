<?php
/**
 * Feature Flag Store
 *
 * @package DigitalGravy\FeatureFlag
 */

namespace DigitalGravy\FeatureFlag;

class FeatureFlagStore {

	private bool $isEmpty = true;
	private array $flags = array();

	public function __construct( array $flags = array() ) {
		$this->isEmpty = empty( $flags );
		$flags_clean = array();
		foreach ( $flags as $flag_key => $flag_value ) {
			$flag = new FeatureFlag( $flag_key );
			$flag_key = (string) $flag;
			if ( isset( $flags_clean[ $flag_key ] ) ) {
				throw new \Exception( 'Duplicate flag key: ' . $flag_key ); // @codingStandardsIgnoreLine
			}
			$flags_clean[ $flag_key ] = $flag_value;
		}
		$this->flags = $flags_clean;
	}

	public function is_empty(): bool {
		return $this->isEmpty;
	}

	public function is_on( FeatureFlag $flag ): bool {
		try {
			return 'on' === $this->flags[ (string) $flag ];
		} catch ( \Throwable $e ) {
			throw new \Exception( 'Key not found in store' );
		}
	}
}
