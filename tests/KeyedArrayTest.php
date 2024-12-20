<?php
/**
 * KeyedArray tests.
 *
 * @package DigitalGravy\FeatureFlag
 */

namespace DigitalGravy\FeatureFlag\Tests;

use DigitalGravy\FeatureFlag\FeatureFlagStore;
use DigitalGravy\FeatureFlag\Storage\KeyedArray;
use PHPUnit\Framework\TestCase;

class KeyedArrayTest extends TestCase {

	/**
	 * Features:
	 * - DG plugin authors can add flags directly to the KeyedArray.
	 * - KeyedArray cleans the array of invalid flags.
	 */

	/**
	 * @test
	 * @description Storage raises error when no source is provided
	 */
	public function storage_raises_error_when_no_source_is_provided(): void {
		$this->expectException( \ArgumentCountError::class );
		new KeyedArray();
	}

	/**
	 * @test
	 * @description Storage returns no flags when empty source is provided
	 */
	public function storage_returns_no_flags_when_empty_source_is_provided(): void {
		$storage = new KeyedArray( array() );
		$this->assertEmpty( $storage->get_flags() );
	}

	/**
	 * @test
	 * @description Storage returns no flags when source contains no valid flags
	 */
	public function storage_returns_no_flags_when_source_contains_no_valid_flags(): void {
		$storage = new KeyedArray( array( 'inval!d-key' => 'invalid-value' ) );
		$this->assertEmpty( $storage->get_flags() );
	}

	/**
	 * @test
	 * @description Storage returns flags when source contains valid flags
	 */
	public function storage_returns_flags_when_source_contains_valid_flags(): void {
		$storage = new KeyedArray(
			array(
				'test-on' => 'on',
				'test-off' => 'off',
			)
		);
		$flags = $storage->get_flags();
		$this->assertCount( 2, $flags );
		$this->assertArrayHasKey( 'test-on', $flags );
		$this->assertArrayHasKey( 'test-off', $flags );
	}
}
