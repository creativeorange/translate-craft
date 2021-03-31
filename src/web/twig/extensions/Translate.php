<?php

namespace creativeorange\translate\web\twig\extensions;

use Twig\Extension\ExtensionInterface;
use Twig\TwigFilter;

class Translate implements ExtensionInterface
{
    public function getFilters()
    {
        return [
            new TwigFilter('translate', [
                \creativeorange\translate\services\Translate::class,
                'translate',
            ]),
            new TwigFilter('t', [
                \creativeorange\translate\services\Translate::class,
                'translate',
            ])
        ];
    }

    public function getFunctions()
    {
        return [];
    }

    public function getTokenParsers()
    {
        return [];
    }

    public function getNodeVisitors()
    {
        return [];
    }

    public function getTests()
    {
        return [];
    }

    public function getOperators()
    {
        return [];
    }
}