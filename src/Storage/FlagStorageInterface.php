<?php
/**
 * Interface for flag storage.
 *
 * @package DigitalGravy\FeatureFlag
 */

namespace DigitalGravy\FeatureFlag\Storage;

interface FlagStorageInterface {
	/**
	 * @param mixed $source The source of the flags.
	 * @return array<string,string> Array of flag states where values are 'on' or 'off'
	 * @throws \Exception If unable to retrieve flags.
	 */
	public static function get_flags_from( $source = null ): array;
}
