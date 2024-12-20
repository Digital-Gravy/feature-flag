<?php
/**
 * Feature Flag Store
 *
 * @package DigitalGravy\FeatureFlag
 */

namespace DigitalGravy\FeatureFlag;

class FeatureFlagStore {

	/** @var bool */
	private bool $is_empty = true;

	/** @var array<FeatureFlag> */
	private array $flags = array();

	/**
	 * @param array<array<FeatureFlag>> ...$sources The sources for the flags.
	 */
	public function __construct( array ...$sources ) {
		$this->flags = self::merge_sources( ...$sources );
		$this->is_empty = empty( $this->flags );
	}

	/**
	 * @param array<array<FeatureFlag>> ...$sources The sources to merge.
	 * @return array<FeatureFlag>
	 * @throws \Exception If there are invalid flags.
	 */
	private static function merge_sources( array ...$sources ): array {
		$merged = array();
		foreach ( $sources as $source ) {
			foreach ( $source as $flag ) {
				if ( ! $flag instanceof FeatureFlag ) {
					throw new \Exception( 'Invalid flag type' );
				}
				$merged[ $flag->key ] = $flag;
			}
		}
		return $merged;
	}

	public function is_empty(): bool {
		return $this->is_empty;
	}

	public function is_on( string $flag_key ): bool {
		try {
			$flag_key = FeatureFlag::sanitize_key( $flag_key );
			return 'on' === $this->flags[ $flag_key ]->value;
		} catch ( \Throwable $e ) {
			throw new \Exception( 'Key not found in store' );
		}
	}
}
