<?php

namespace DigitalGravy\FeatureFlag\Tests;

use PHPUnit\Framework\TestCase;
use DigitalGravy\FeatureFlag\FeatureFlag;

class FeatureFlagTest extends TestCase
{

    /**
     * Features:
     * - Digital Gravy plugin authors can share feature flags across website
     * - The website stores the feature flags in a keyed array
     * - Feature flag keys are unique alphanumeric strings
     * - Feature flags can be 'on' or 'off'
     * - DG plugin authors can override feature flags with a local .env file
     */

     /**
      * Test list:
      * - Website's feature flag store is empty by default
      * - Website's FF store cannot be written to, only initialized from a storage
      * - Website's FF store cannnot be reinitialized
      * - Empty store raises error when feature flag is requested
      * - FF is 'on' when store initialized its key to 'on'
      * - FF is 'off' when store initialized its key to 'off'
      * - FF raises error when key is not found in store
      */

    public function testStoreIsEmptyByDefault(): void
    {
        $store = new FeatureFlagStore();
        $this->assertTrue($store->isEmpty());
    }
}

class FeatureFlagStore {

    public function isEmpty(): bool
    {
        return true;
    }
}
