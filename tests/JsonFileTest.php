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
	 * @description Storage raises error when no file path is provided
	 */
	public function storage_raises_error_when_no_file_path_is_provided(): void {
		$this->expectException( \InvalidArgumentException::class );
		new JsonFile( '' );
	}

	/**
	 * @test
	 * @description Storage raises error when file is not found
	 */
	public function storage_raises_error_when_file_is_not_found(): void {
		$this->expectException( \Exception::class );
		$storage = new JsonFile( 'nonexistent.json' );
		$storage->get_flags();
	}

	/**
	 * @test
	 * @description Storage raises error when file is not readable
	 */
	public function storage_raises_error_when_file_is_not_readable(): void {
		$this->expectException( \Exception::class );
		$storage = new JsonFile( 'tests/data/flags-non-readable.json' );
		$storage->get_flags();
	}

	/**
	 * @test
	 * @description Storage raises error when file is not a valid JSON file
	 */
	public function storage_raises_error_when_file_is_not_a_valid_json_file(): void {
		$this->expectException( \Exception::class );
		$storage = new JsonFile( 'tests/data/flags-non-json.json' );
		$storage->get_flags();
	}

	/**
	 * @test
	 * @description Storage returns no flags when file is empty
	 */
	public function storage_returns_no_flags_when_file_is_empty(): void {
		$storage = new JsonFile( 'tests/data/flags-empty.json' );
		$this->assertEmpty( $storage->get_flags() );
	}

	/**
	 * @test
	 * @description Storage returns no flags when file contains no valid flags
	 */
	public function storage_returns_no_flags_when_file_contains_no_valid_flags(): void {
		$storage = new JsonFile( 'tests/data/flags-invalid.json' );
		$this->assertEmpty( $storage->get_flags() );
	}

	/**
	 * @test
	 * @description Storage returns flags when file contains valid flags
	 */
	public function storage_returns_flags_when_file_contains_valid_flags(): void {
		$storage = new JsonFile( 'tests/data/flags-valid.json' );
		$flags = $storage->get_flags();
		$this->assertCount( 2, $flags );
		$this->assertArrayHasKey( 'test-on', $flags );
		$this->assertArrayHasKey( 'test-off', $flags );
	}
}
