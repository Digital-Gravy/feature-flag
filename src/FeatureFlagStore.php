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
		$this->flags = $flags;
	}

	public function is_empty(): bool {
		return $this->isEmpty;
	}

	public function read( FeatureFlag $flag ): void {
		throw new \Exception( 'Store is empty' );
	}

	public function is_on( FeatureFlag $flag ): bool {
		try {
			return 'on' === $this->flags[ (string) $flag ];
		} catch ( \Throwable $e ) {
			throw new \Exception( 'Key not found in store' );
		}
	}
}
