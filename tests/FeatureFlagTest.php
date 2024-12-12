<?php
/**
 * Feature Flag Test
 *
 * @package DigitalGravy\FeatureFlag\Tests
 */

namespace DigitalGravy\FeatureFlag\Tests;

use PHPUnit\Framework\TestCase;
use DigitalGravy\FeatureFlag\FeatureFlag;
use DigitalGravy\FeatureFlag\FeatureFlagStore;

class FeatureFlagTest extends TestCase {

	/**
	 * Features:
	 * - Digital Gravy plugin authors can share feature flags across website
	 * - The website stores the feature flags in a keyed array
	 * - Feature flag keys are unique alphanumeric strings
	 * - Feature flags can be 'on' or 'off'
	 * - DG plugin authors can override feature flags with a local .env file
	 */

	/**
	 * @test
	 * @description Feature flag store should be empty when newly instantiated
	 */
	public function store_is_empty_by_default(): void {
		$store = new FeatureFlagStore();
		$this->assertTrue( $store->is_empty() );
	}

	/**
	 * @test
	 * @description Store should not be empty when initialized with values
	 */
	public function initialized_store_is_not_empty(): void {
		$store = new FeatureFlagStore( array( 'test' => 'on' ) );
		$this->assertFalse( $store->is_empty() );
	}

	/**
	 * @test
	 * @description Should raise an exception when requesting a feature flag from an empty store
	 */
	public function empty_store_raises_error_when_feature_flag_is_requested(): void {
		$store = new FeatureFlagStore();
		$this->expectException( \Exception::class );
		$store->is_on( new FeatureFlag( 'test' ) );
	}

	/**
	 * @test
	 * @description Feature flag should be on when store is initialized with that key set to on
	 */
	public function feature_flag_is_on_when_store_initialized_its_key_to_on(): void {
		$store = new FeatureFlagStore( array( 'test' => 'on' ) );
		$this->assertTrue( $store->is_on( new FeatureFlag( 'test' ) ) );
	}

	/**
	 * @test
	 * @description Feature flag should be off when store is initialized with that key set to off
	 */
	public function feature_flag_is_off_when_store_initialized_its_key_to_off(): void {
		$store = new FeatureFlagStore( array( 'test' => 'off' ) );
		$this->assertFalse( $store->is_on( new FeatureFlag( 'test' ) ) );
	}

	/**
	 * @test
	 * @description Should raise an exception when requesting a non-existent feature flag
	 */
	public function feature_flag_raises_error_when_key_is_not_found_in_store(): void {
		$store = new FeatureFlagStore( array( 'test' => 'on' ) );
		$this->expectException( \Exception::class );
		$store->is_on( new FeatureFlag( 'not_found' ) );
	}
}
