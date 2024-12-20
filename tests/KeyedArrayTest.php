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
	 * @description KeyedArray is empty when newly instantiated.
	 */
	public function keyed_array_is_empty_when_newly_instantiated() {
		$flags = KeyedArray::get_flags_from();
		$this->assertEmpty( $flags );
	}

	/**
	 * @test
	 * @description KeyedArray is empty when instantiated with an empty array.
	 */
	public function keyed_array_is_empty_when_instantiated_with_an_empty_array() {
		$flags = KeyedArray::get_flags_from( array() );
		$this->assertEmpty( $flags );
	}

	/**
	 * @test
	 * @description KeyedArray is empty when instantiated with an array of invalid flags.
	 */
	public function keyed_array_is_empty_when_instantiated_with_an_array_of_invalid_flags() {
		$flags = KeyedArray::get_flags_from( array( 'invalid-flag' ) );
		$this->assertEmpty( $flags );
	}

	/**
	 * @test
	 * @description KeyedArray is not empty when initialized with a valid flag.
	 */
	public function keyed_array_is_not_empty_when_initialized_with_a_valid_flag() {
		$flags = KeyedArray::get_flags_from( array( 'test' => 'on' ) );
		$this->assertNotEmpty( $flags );
	}

	/**
	 * @test
	 * @description FlagStore can be initialized with a KeyedArray.
	 */
	public function flag_store_can_be_initialized_with_a_keyed_array() {
		$flag_store = new FeatureFlagStore( KeyedArray::get_flags_from( array( 'test' => 'on' ) ) );
		$this->assertTrue( $flag_store->is_on( 'test' ) );
	}
}
