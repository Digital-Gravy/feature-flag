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

    public function testStoreIsEmptyByDefault(): void
    {
        $store = new FeatureFlagStore();
        $this->assertTrue($store->isEmpty());
    }

    public function testInitializedStoreIsNotEmpty(): void
    {
        $store = new FeatureFlagStore(['test' => 'on']);
        $this->assertFalse($store->isEmpty());
    }


    public function testEmptyStoreRaisesErrorWhenFeatureFlagIsRequested(): void
    {
        $store = new FeatureFlagStore();
        $this->expectException(\Exception::class);
        $store->read(new FeatureFlag());
    }
    /**
      * Test list:
      * - FF is 'on' when store initialized its key to 'on'
      * - FF is 'off' when store initialized its key to 'off'
      * - FF raises error when key is not found in store
      */

    public function testFeatureFlag_IsOn_When_Store_Initialized_Its_Key_To_On(): void
    {
        $store = new FeatureFlagStore(['test' => 'on']);
        $this->assertTrue($store->is_on(new FeatureFlag('test')));
    }
}

class FeatureFlagStore {

    private bool $isEmpty = true;

    public function __construct(array $flags = [])
    {
        $this->isEmpty = empty($flags);
    }

    public function isEmpty(): bool
    {
        return $this->isEmpty;
    }

    public function read(FeatureFlag $flag): void
    {
        throw new \Exception('Store is empty');
    }

    public function is_on(FeatureFlag $flag): bool {
        return true;
    }
}
