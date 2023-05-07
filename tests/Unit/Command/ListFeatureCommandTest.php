<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Unit\Command;

use Novaway\Bundle\FeatureFlagBundle\Command\ListFeatureCommand;
use Novaway\Bundle\FeatureFlagBundle\Storage\ArrayStorage;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class ListFeatureCommandTest extends TestCase
{
    private const TEST_DATA = [
        'empty-features' => [
            'features' => [],
            'output' => [
                'table' => <<<OUTPUT

Novaway\Bundle\FeatureFlagBundle\Storage\ArrayStorage
=====================================================

+------+---------+-------------+
| Name | Enabled | Description |
+------+---------+-------------+

OUTPUT,
                'json' => <<<JSON
{
    "Novaway\\\\Bundle\\\\FeatureFlagBundle\\\\Storage\\\\ArrayStorage": []
}

JSON,
                'csv' => <<<CSV
Storage,Name,Enabled,Description

CSV,
            ],
        ],
        'with-features' => [
            'features' => [
                'feature1' => [
                    'enabled' => true,
                    'description' => 'Feature 1 description',
                ],
                'feature2' => [
                    'enabled' => false,
                    'description' => 'Feature 2 description',
                ],
                'feature3' => [
                    'enabled' => true,
                    'description' => 'Feature 3 description',
                ],
            ],
            'output' => [
                'table' => <<<OUTPUT

Novaway\Bundle\FeatureFlagBundle\Storage\ArrayStorage
=====================================================

+----------+---------+-----------------------+
| Name     | Enabled | Description           |
+----------+---------+-----------------------+
| feature1 | Yes     | Feature 1 description |
| feature2 | No      | Feature 2 description |
| feature3 | Yes     | Feature 3 description |
+----------+---------+-----------------------+

OUTPUT,
                'json' => <<<JSON
{
    "Novaway\\\\Bundle\\\\FeatureFlagBundle\\\\Storage\\\\ArrayStorage": {
        "feature1": {
            "key": "feature1",
            "enabled": true,
            "description": "Feature 1 description"
        },
        "feature2": {
            "key": "feature2",
            "enabled": false,
            "description": "Feature 2 description"
        },
        "feature3": {
            "key": "feature3",
            "enabled": true,
            "description": "Feature 3 description"
        }
    }
}

JSON,
                'csv' => <<<CSV
Storage,Name,Enabled,Description
"Novaway\Bundle\FeatureFlagBundle\Storage\ArrayStorage",feature1,1,"Feature 1 description"
"Novaway\Bundle\FeatureFlagBundle\Storage\ArrayStorage",feature2,,"Feature 2 description"
"Novaway\Bundle\FeatureFlagBundle\Storage\ArrayStorage",feature3,1,"Feature 3 description"

CSV,
            ],
        ],
    ];

    public function testAnErrorOccuredIfInvalidFormatIsProvided(): void
    {
        $commandTester = $this->createCommandTester();
        $commandTester->execute(['--format' => 'invalid']);

        static::assertNotSame(0, $commandTester->getStatusCode());
        static::assertSame(<<<OUTPUT
Invalid format: invalid

OUTPUT, $commandTester->getDisplay());
    }

    #[DataProvider('featuresProvider')]
    public function testConfiguredFeaturesAreDisplayedInAskedFormat(array $features, string $outputFormat, string $expectedOutput): void
    {
        $commandTester = $this->createCommandTester($features);
        $commandTester->execute(['--format' => $outputFormat]);

        //        static::assertSame(0, $commandTester->getStatusCode());
        static::assertSame($expectedOutput, $commandTester->getDisplay());
    }

    public static function featuresProvider(): iterable
    {
        foreach (self::TEST_DATA as $caseDescription => $testData) {
            foreach ($testData['output'] as $format => $expectedOutput) {
                yield "$caseDescription in $format format" => [$testData['features'], $format, $expectedOutput];
            }
        }
    }

    private function createCommandTester(array $features = []): CommandTester
    {
        $command = new ListFeatureCommand([ArrayStorage::fromArray($features)]);

        return new CommandTester($command);
    }
}
