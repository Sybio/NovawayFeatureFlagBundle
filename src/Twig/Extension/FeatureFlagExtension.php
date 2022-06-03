<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Twig\Extension;

use Novaway\Bundle\FeatureFlagBundle\Storage\StorageInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FeatureFlagExtension extends AbstractExtension
{
    /** @var StorageInterface */
    private $storage;

    /**
     * Constructor
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('isFeatureEnabled', [$this->storage, 'isEnabled']),
            new TwigFunction('isFeatureDisabled', [$this->storage, 'isDisabled']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'feature_flag_extension';
    }
}
