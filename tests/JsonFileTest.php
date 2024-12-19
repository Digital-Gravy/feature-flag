<?php
/**
 * JSON File Test
 *
 * @package DigitalGravy\FeatureFlag\Tests
 */

namespace DigitalGravy\FeatureFlag\Tests;

use DigitalGravy\FeatureFlag\Storage\JsonFile;
use PHPUnit\Framework\TestCase;

class JsonFileTest extends TestCase {

	/**
	 * Features:
	 * - DG plugin authors can read feature flags from a JSON file through a Storage Helper
	 * - Storage Helper informs DG plugin authors when using flags incorrectly
	 */

	/**
	 * @test
	 * @description Storage Helper is empty when newly instantiated
	 */
	public function storage_helper_is_empty_when_newly_instantiated(): void {
		$flags = JsonFile::get_flags_from();
		$this->assertEmpty( $flags );
	}

	/**
	 * @test
	 * @description Storage Helper is empty when instantiated with empty array
	 */
	public function storage_helper_is_empty_when_instantiated_with_empty_array(): void {
		$flags = JsonFile::get_flags_from( array() );
		$this->assertEmpty( $flags );
	}

	/**
	 * @test
	 * @description Storage Helper is not empty when instantiated with valid flags
	 */
	public function storage_helper_is_not_empty_when_instantiated_with_valid_flags(): void {
		$flags = JsonFile::get_flags_from( array( 'test' => 'on' ) );
		$this->assertNotEmpty( $flags );
	}

	/**
	 * @test
	 * @description Storage Helper skips over invalid flags
	 */
	public function storage_helper_skips_over_invalid_flags(): void {
		$flags = JsonFile::get_flags_from( array( 'test' => 'illegal' ) );
		$this->assertNotContains( 'test', $flags );
	}

	/**
	 * @test
	 * @description Storage Helper raises error when JSON file is not found
	 */
	public function storage_helper_raises_error_when_json_file_is_not_found(): void {
		$this->expectException( \Exception::class );
		JsonFile::get_flags_from( 'non-existent-file.json' );
	}

	/**
	 * @test
	 * @description Storage Helper reads flags from JSON file
	 */
	public function storage_helper_reads_flags_from_json_file(): void {
		$flags = JsonFile::get_flags_from( __DIR__ . '/data/flags.json' );
		$this->assertEquals(
			array(
				'test-on' => 'on',
				'test-off' => 'off',
			),
			$flags
		);
	}
}
