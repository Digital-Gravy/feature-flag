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
	 * @throws \RuntimeException If unable to retrieve flags.
	 * @throws \UnexpectedValueException If JSON file does not decode to an array.
	 */
	private function get_flags_from_file(): array {
		$this->validate_file_path();

		$file_contents = file_get_contents( $this->file_path );
		if ( false === $file_contents ) {
			throw new \RuntimeException( 'Failed to read JSON file' );
		}

		$flags_dirty = json_decode( $file_contents, true, 512, JSON_THROW_ON_ERROR );

		if ( ! is_array( $flags_dirty ) ) {
			throw new \UnexpectedValueException( 'JSON file must decode to an array' );
		}

		return $flags_dirty;
	}

	/**
	 * @throws \RuntimeException If file path is invalid.
	 */
	private function validate_file_path(): void {
		if ( ! file_exists( $this->file_path ) ) {
			throw new \RuntimeException( 'JSON file not found' );
		}

		if ( ! is_readable( $this->file_path ) ) {
			throw new \RuntimeException( 'JSON file is not readable' );
		}
	}
}
