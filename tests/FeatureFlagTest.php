<?php

namespace DigitalGravy\FeatureFlag\Tests;

use PHPUnit\Framework\TestCase;
use DigitalGravy\FeatureFlag\FeatureFlag;

class FeatureFlagTest extends TestCase
{
    public function testDoSomething(): void
    {
        $class = new FeatureFlag();
        $this->assertEquals('Hello World!', $class->doSomething());
    }
}
