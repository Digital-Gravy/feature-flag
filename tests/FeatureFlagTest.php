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
use Throwable;

class FeatureFlagTest extends TestCase {

	/**
	 * Features:
	 * - Digital Gravy plugin authors can share feature flags across website
	 * - The website stores the feature flags in a keyed array
	 * - Feature flag keys are unique alphanumeric strings
	 * - Feature flags can be 'on' or 'off'
	 * - DG plugin authors can override feature flags with a local .env file
	 */
	public function testStoreIsEmptyByDefault(): void {
		$store = new FeatureFlagStore();
		$this->assertTrue( $store->is_empty() );
	}

	public function testInitializedStoreIsNotEmpty(): void {
		$store = new FeatureFlagStore( array( 'test' => 'on' ) );
		$this->assertFalse( $store->is_empty() );
	}

	public function testEmptyStoreRaisesErrorWhenFeatureFlagIsRequested(): void {
		$store = new FeatureFlagStore();
		$this->expectException( \Exception::class );
		$store->read( new FeatureFlag() );
	}

	public function testFeatureFlag_IsOn_When_Store_Initialized_Its_Key_To_On(): void {
		$store = new FeatureFlagStore( array( 'test' => 'on' ) );
		$this->assertTrue( $store->is_on( new FeatureFlag( 'test' ) ) );
	}

	public function testFeatureFlag_IsOff_When_Store_Initialized_Its_Key_To_Off(): void {
		$store = new FeatureFlagStore( array( 'test' => 'off' ) );
		$this->assertFalse( $store->is_on( new FeatureFlag( 'test' ) ) );
	}

	public function testFeatureFlag_RaisesError_When_Key_Is_Not_Found_In_Store(): void {
		$store = new FeatureFlagStore( array( 'test' => 'on' ) );
		$this->expectException( \Exception::class );
		$store->is_on( new FeatureFlag( 'not_found' ) );
	}
}
