<?php

namespace DigitalGravy\FeatureFlag;

class FeatureFlagStore {

	private bool $isEmpty = true;
	private array $flags = array();

	public function __construct( array $flags = array() ) {
		$this->isEmpty = empty( $flags );
		$this->flags = $flags;
	}

	public function isEmpty(): bool {
		return $this->isEmpty;
	}

	public function read( FeatureFlag $flag ): void {
		throw new \Exception( 'Store is empty' );
	}

	public function is_on( FeatureFlag $flag ): bool {
		try {
			return $this->flags[ (string) $flag ] === 'on';
		} catch ( \Throwable $e ) {
			throw new \Exception( 'Key not found in store' );
		}
	}
}
