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
		$this->key = $key;
	}

	public function __toString(): string {
		return $this->key;
	}
}
