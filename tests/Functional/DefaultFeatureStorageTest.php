<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Functional;

use Novaway\Bundle\FeatureFlagBundle\Manager\ChainedFeatureManager;
use Novaway\Bundle\FeatureFlagBundle\Manager\FeatureManager;
use Novaway\Bundle\FeatureFlagBundle\Model\FeatureFlag;
use Novaway\Bundle\FeatureFlagBundle\Storage\Storage;

final class DefaultFeatureStorageTest extends WebTestCase
{
    /** @var Storage */
    private $defaultRegisteredStorage;

    protected function setUp(): void
    {
        $this->defaultRegisteredStorage = static::getContainer()->get(ChainedFeatureManager::class);
    }

    public function testDefaultFeatureManagerIsFeatureManager(): void
    {
        self::markTestSkipped('TODO');

        static::assertInstanceOf(FeatureManager::class, $this->defaultRegisteredStorage);
    }

    public function testAccessAllRegisteredFeatures(): void
    {
        self::markTestSkipped('TODO');

        $features = $this->defaultRegisteredStorage->all();

        static::assertCount(4, $features);
        static::assertEquals(
            [
                'override' => new FeatureFlag('override', false),
                'foo' => new FeatureFlag('foo', true),
                'bar' => new FeatureFlag('bar', false, 'Bar feature description'),
                'env_var' => new FeatureFlag('env_var', false),
            ],
            $features,
        );
    }
}
