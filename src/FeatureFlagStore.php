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

	public function __construct( array $flags = array(), array $local_flags = array() ) {
		$flags_clean = array();
		$all_flags = array_merge( $flags, $local_flags );
		foreach ( $all_flags as $flag_key => $flag_value ) {
			$flag = new FeatureFlag( $flag_key );
			$flag_key = (string) $flag;
			if ( isset( $flags_clean[ $flag_key ] ) ) {
				throw new \Exception( 'Duplicate flag key: ' . $flag_key ); // @codingStandardsIgnoreLine
			}
			$flags_clean[ $flag_key ] = $flag_value;
		}
		$this->flags = $flags_clean;
		$this->isEmpty = empty( $this->flags );
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
