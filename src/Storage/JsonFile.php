<?php
/**
 * JSON File Storage
 *
 * @package DigitalGravy\FeatureFlag\Storage
 */

namespace DigitalGravy\FeatureFlag\Storage;

use DigitalGravy\FeatureFlag\FeatureFlag;

class JsonFile {

	private array $flags;

	public function __construct( array|string $flagsOrFilePath = array() ) {
		$flags = array();
		$flags_clean = array();
		if ( is_string( $flagsOrFilePath ) ) {
			if ( ! file_exists( $flagsOrFilePath ) ) {
				throw new \Exception( 'JSON file not found' );
			}
			$flags = json_decode( file_get_contents( $flagsOrFilePath ), true );
		} else {
			$flags = $flagsOrFilePath;
		}
		foreach ( $flags as $key => $value ) {
			try {
				$flag = new FeatureFlag( $key, $value );
			} catch ( \Exception $e ) {
				continue;
			}
			$flags_clean[ $flag->key ] = $flag->value;
		}
		$this->flags = $flags_clean;
	}

	public function is_empty(): bool {
		return empty( $this->flags );
	}

	public function get_flags(): array {
		return $this->flags;
	}
}
