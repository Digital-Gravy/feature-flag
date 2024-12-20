<?php
/**
 * @package DigitalGravy\FeatureFlag
 */

namespace DigitalGravy\FeatureFlag\Tests;

use PHPUnit\Framework\TestCase;
use DigitalGravy\FeatureFlag\Storage\PHPConstant;

class PHPConstantTest extends TestCase {

	/**
	 * @test
	 * @description PHPConstant is empty when newly instantiated.
	 */
	public function php_constant_is_empty_when_newly_instantiated() {
		$flags = PHPConstant::get_flags_from();
		$this->assertEmpty( $flags );
	}

	/**
	 * @test
	 * @description KeyedArray is empty when instantiated with an empty array.
	 */
	public function php_constant_is_empty_when_instantiated_with_an_empty_array() {
		$flags = PHPConstant::get_flags_from( array() );
		$this->assertEmpty( $flags );
	}

	/**
	 * @test
	 * @description KeyedArray is empty when instantiated with an array of invalid flags.
	 */
	public function php_constant_is_empty_when_instantiated_with_an_array_of_invalid_flags() {
		$flags = PHPConstant::get_flags_from( array( 'invalid-flag' ) );
		$this->assertEmpty( $flags );
	}

	/**
	 * @test
	 * @description KeyedArray is not empty when initialized with a valid flag.
	 */
	public function php_constant_is_not_empty_when_initialized_with_a_valid_flag() {
		$flags = PHPConstant::get_flags_from( array( 'test' => 'on' ) );
		$this->assertNotEmpty( $flags );
	}

	/**
	 * @test
	 * @description PHPConstant reads flags from PHP constants
	 */
	public function php_constant_reads_flags_from_php_constants() {
		define( 'TEST_ON', 'on' );
		define( 'TEST_OFF', 'off' );
		define( 'TEST_TRUE', true );
		define( 'TEST_FALSE', false );
		$flags = PHPConstant::get_flags_from( array( 'TEST_ON', 'TEST_OFF', 'TEST_TRUE', 'TEST_FALSE', 'TEST_UNDEFINED' ) );
		$this->assertEquals(
			array(
				'test_on' => 'on',
				'test_off' => 'off',
				'test_true' => 'on',
				'test_false' => 'off',
			),
			$flags
		);
	}
}
