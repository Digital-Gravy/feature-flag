<?php
/**
 * @package DigitalGravy\FeatureFlag
 */

namespace DigitalGravy\FeatureFlag\Tests;

use DigitalGravy\FeatureFlag\FeatureFlag;
use DigitalGravy\FeatureFlag\FeatureFlagStore;
use PHPUnit\Framework\TestCase;
use DigitalGravy\FeatureFlag\Storage\PHPConstant;

class PHPConstantTest extends TestCase {

	/**
	 * @test
	 * @description Storage raises error when no constant names are provided
	 */
	public function storage_raises_error_when_no_constant_names_are_provided(): void {
		$this->expectException( \ArgumentCountError::class );
		new PHPConstant();
	}

	/**
	 * @test
	 * @description Storage returns no flags when empty source is provided
	 */
	public function storage_returns_no_flags_when_empty_source_is_provided(): void {
		$storage = new PHPConstant( array() );
		$this->assertEmpty( $storage->get_flags() );
	}

	/**
	 * @test
	 * @description Storage returns no flags when source contains no valid flags
	 */
	public function storage_returns_no_flags_when_source_contains_no_valid_flags(): void {
		define( 'TEST_INVALID', 'invalid' );
		$storage = new PHPConstant( array( 'TEST_INVALID' ) );
		$this->assertEmpty( $storage->get_flags() );
	}

	/**
	 * @test
	 * @description Storage injects flags into store when source contains valid flags
	 */
	public function storage_injects_flags_into_store_when_source_contains_valid_flags(): void {
		define( 'TEST_ON', 'on' );
		define( 'TEST_OFF', 'off' );
		define( 'TEST_TRUE', true );
		define( 'TEST_FALSE', false );
		$storage = new PHPConstant( array( 'TEST_ON', 'TEST_OFF', 'TEST_TRUE', 'TEST_FALSE' ) );
		$store = new FeatureFlagStore( $storage->get_flags() );
		$this->assertTrue( $store->is_on( 'TEST_ON' ) );
		$this->assertFalse( $store->is_on( 'TEST_OFF' ) );
		$this->assertTrue( $store->is_on( 'TEST_TRUE' ) );
		$this->assertFalse( $store->is_on( 'TEST_FALSE' ) );
	}
}
