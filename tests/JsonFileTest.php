<?php
/**
 * JSON File Test
 *
 * @package DigitalGravy\FeatureFlag\Tests
 */

namespace DigitalGravy\FeatureFlag\Tests;

use DigitalGravy\FeatureFlag\FeatureFlagStore;
use DigitalGravy\FeatureFlag\Storage\JsonFile;
use DigitalGravy\FeatureFlag\Storage\Exception\FileNotFoundException;
use DigitalGravy\FeatureFlag\Storage\Exception\FileNotReadableException;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class JsonFileTest extends TestCase {

	private vfsStreamDirectory $fs_root;

	protected function setUp(): void {
		parent::setUp();
		$this->fs_root = vfsStream::setup( 'root' );
	}

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
		$this->expectException( FileNotFoundException::class );
		$storage = new JsonFile( vfsStream::url( 'root/nonexistent.json' ) );
		$storage->get_flags();
	}

	/**
	 * @test
	 * @description Storage raises error when file is not readable
	 */
	public function storage_raises_error_when_file_is_not_readable(): void {
		$file = vfsStream::newFile( 'flags-non-readable.json' )
			->withContent( '{}' )
			->at( $this->fs_root );
		$file->chmod( 0000 ); // Remove all permissions.

		$this->expectException( FileNotReadableException::class );
		$storage = new JsonFile( vfsStream::url( 'root/flags-non-readable.json' ) );
		$storage->get_flags();
	}

	/**
	 * @test
	 * @description Storage raises error when file is not a valid JSON file
	 */
	public function storage_raises_error_when_file_is_not_a_valid_json_file(): void {
		$this->expectException( \JsonException::class );
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
	 * @description Storage injects flags into store when file contains valid flags
	 */
	public function storage_injects_flags_into_store_when_file_contains_valid_flags(): void {
		$storage = new JsonFile( 'tests/data/flags-valid.json' );
		$store = new FeatureFlagStore( $storage->get_flags() );
		$this->assertTrue( $store->is_on( 'test-on' ) );
		$this->assertFalse( $store->is_on( 'test-off' ) );
	}
}
