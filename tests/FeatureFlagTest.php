<?php
/**
 * Feature Flag Test
 *
 * @package DigitalGravy\FeatureFlag\Tests
 */

namespace DigitalGravy\FeatureFlag\Tests;

use DigitalGravy\FeatureFlag\Exception\Flag_Key_Not_Found;
use DigitalGravy\FeatureFlag\Exception\Invalid_Flag_Key;
use DigitalGravy\FeatureFlag\Exception\Invalid_Flag_Value;
use DigitalGravy\FeatureFlag\FeatureFlag;
use PHPUnit\Framework\TestCase;
use DigitalGravy\FeatureFlag\FeatureFlagStore;

class FeatureFlagTest extends TestCase {

	/**
	 * Features:
	 * - Digital Gravy plugin authors can share feature flags across website
	 * - The website stores the feature flags in a keyed array
	 * - Feature flag keys are unique alphanumeric strings with underscores and dashes
	 * - Feature flags can be 'on' or 'off'
	 * - The website can store feature flags from multiple sources simultaneously
	 * - When multiple sources are used, the last source that defines a feature flag wins
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
		$store = new FeatureFlagStore( array( new FeatureFlag( 'test', 'on' ) ) );
		$this->assertFalse( $store->is_empty() );
	}

	/**
	 * @test
	 * @description Feature flag should be on when store is initialized with that key set to on
	 */
	public function feature_flag_is_on_when_store_initialized_its_key_to_on(): void {
		$store = new FeatureFlagStore( array( new FeatureFlag( 'test', 'on' ) ) );
		$this->assertTrue( $store->is_on( 'test' ) );
	}

	/**
	 * @test
	 * @description Feature flag should be off when store is initialized with that key set to off
	 */
	public function feature_flag_is_off_when_store_initialized_its_key_to_off(): void {
		$store = new FeatureFlagStore( array( new FeatureFlag( 'test', 'off' ) ) );
		$this->assertFalse( $store->is_on( 'test' ) );
	}

	/**
	 * @test
	 * @description Should raise an exception when requesting a non-existent feature flag
	 */
	public function feature_flag_raises_error_when_key_is_not_found_in_store(): void {
		$store = new FeatureFlagStore( array( new FeatureFlag( 'test', 'on' ) ) );
		$this->expectException( Flag_Key_Not_Found::class );
		$store->is_on( 'not_found' );
	}

	/**
	 * @test
	 * @description Store raises error when key uses illegal characters
	 */
	public function store_raises_error_when_key_uses_illegal_characters(): void {
		$this->expectException( Invalid_Flag_Key::class );
		new FeatureFlagStore( array( new FeatureFlag( 'test!', 'on' ) ) );
	}

	/**
	 * @test
	 * @description Store accepts alphanumeric keys
	 */
	public function store_accepts_alphanumeric_keys(): void {
		$this->expectNotToPerformAssertions();
		$store = new FeatureFlagStore( array( new FeatureFlag( 'test123', 'on' ) ) );
		$store->is_on( 'test123' );
	}

	/**
	 * @test
	 * @description Store accepts keys with underscores
	 */
	public function store_accepts_keys_with_underscores(): void {
		$this->expectNotToPerformAssertions();
		$store = new FeatureFlagStore( array( new FeatureFlag( 'test_123', 'on' ) ) );
		$store->is_on( 'test_123' );
	}

	/**
	 * @test
	 * @description Store accepts keys with dashes
	 */
	public function store_accepts_keys_with_dashes(): void {
		$this->expectNotToPerformAssertions();
		$store = new FeatureFlagStore( array( new FeatureFlag( 'test-123', 'on' ) ) );
		$store->is_on( 'test-123' );
	}

	/**
	 * @test
	 * @description Feature flag key is case-insensitive
	 */
	public function feature_flag_key_is_case_insensitive(): void {
		$store = new FeatureFlagStore( array( new FeatureFlag( 'test', 'on' ) ) );
		$this->assertTrue( $store->is_on( 'TEST' ) );
	}

	/**
	 * @test
	 * @description Store uses last source that defines key when multiple sources are used
	 */
	public function store_uses_last_source_that_defines_key_when_multiple_sources_are_used(): void {
		$source1 = array( new FeatureFlag( 'test', 'on' ) );
		$source2 = array( new FeatureFlag( 'test', 'on' ) );
		$source3 = array( new FeatureFlag( 'test', 'off' ) );
		$store = new FeatureFlagStore( $source1, $source2, $source3 );
		$this->assertFalse( $store->is_on( 'test' ) );
	}

	/**
	 * @test
	 * @description Store raises error when source is not an array
	 */
	public function store_raises_error_when_source_is_not_an_array(): void {
		$this->expectException( \TypeError::class );
		new FeatureFlagStore( 'not_an_array' );
	}

	/**
	 * @test
	 * @description Store accepts feature flag key as string when checking its value
	 */
	public function store_accepts_feature_flag_key_as_string_when_checking_its_value(): void {
		$store = new FeatureFlagStore( array( new FeatureFlag( 'test', 'on' ) ) );
		$this->assertTrue( $store->is_on( 'test' ) );
	}

	/**
	 * @test
	 * @description Store raises error when feature flag value is not 'on' or 'off'
	 */
	public function store_raises_error_when_feature_flag_value_is_illegal(): void {
		$this->expectException( Invalid_Flag_Value::class );
		$store = new FeatureFlagStore( array( new FeatureFlag( 'test', true ) ) );
		$store->is_on( 'test' );
	}
}
