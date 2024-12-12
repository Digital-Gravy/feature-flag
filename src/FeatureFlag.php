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
		$this->key = $key;
	}

	public function __toString(): string {
		return $this->key;
	}
}
