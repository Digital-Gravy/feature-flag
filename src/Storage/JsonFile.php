<?php
/**
 * JSON File Storage
 *
 * @package DigitalGravy\FeatureFlag\Storage
 */

namespace DigitalGravy\FeatureFlag\Storage;

use DigitalGravy\FeatureFlag\FeatureFlag;

class JsonFile implements FlagStorageInterface {

	private string $file_path;

	/**
	 * @param string $file_path Path to JSON file containing feature flags.
	 * @throws \InvalidArgumentException If file path is invalid.
	 */
	public function __construct( string $file_path ) {
		if ( empty( $file_path ) ) {
			throw new \InvalidArgumentException( 'File path cannot be empty' );
		}

		$this->file_path = $file_path;
	}

	public function get_flags(): array {
		$flags_clean = array();
		$flags_dirty = $this->get_flags_from_file();
		foreach ( $flags_dirty as $key => $value ) {
			try {
				if ( ! is_string( $key ) || ! is_string( $value ) ) {
					continue;
				}
				$flag = new FeatureFlag( $key, $value );
				$flags_clean[ $flag->key ] = $flag;
			} catch ( \Throwable $e ) {
				continue;
			}
		}

		return $flags_clean;
	}

	/**
	 * @return array<mixed,mixed> Array of contents from JSON file
	 * @throws \Exception If unable to retrieve flags.
	 */
	private function get_flags_from_file(): array {
		$this->validate_file_path();

		$file_contents = file_get_contents( $this->file_path );
		if ( false === $file_contents ) {
			throw new \Exception( 'Failed to read JSON file' );
		}

		$flags_dirty = json_decode( $file_contents, true );
		if ( JSON_ERROR_NONE !== json_last_error() ) {
			throw new \Exception( 'Invalid JSON file' );
		}

		if ( ! is_array( $flags_dirty ) ) {
			throw new \Exception( 'Could not decode JSON file' );
		}

		return $flags_dirty;
	}

	/**
	 * @throws \Exception If file path is invalid.
	 */
	private function validate_file_path(): void {
		if ( ! file_exists( $this->file_path ) ) {
			throw new \Exception( 'JSON file not found' );
		}

		if ( ! is_readable( $this->file_path ) ) {
			throw new \Exception( 'JSON file is not readable' );
		}
	}
}
