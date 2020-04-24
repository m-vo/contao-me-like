<?php

declare(strict_types=1);

/*
 * @author  Moritz Vondano
 * @license MIT
 */

namespace Mvo\ContaoMeLike\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Config\ConfigPluginInterface;
use Contao\ManagerPlugin\Routing\RoutingPluginInterface;
use Mvo\ContaoMeLike\MvoContaoMeLikeBundle;
use Mvo\MeLike\MvoMeLikeBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class Plugin implements BundlePluginInterface, RoutingPluginInterface, ConfigPluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(MvoMeLikeBundle::class),
            BundleConfig::create(MvoContaoMeLikeBundle::class)
                ->setLoadAfter([
                    MvoMeLikeBundle::class,
                    ContaoCoreBundle::class,
                ]),
        ];
    }

    public function getRouteCollection(LoaderResolverInterface $resolver, KernelInterface $kernel)
    {
        $file = '@MvoMeLikeBundle/Resources/config/routing.yaml';

        $loader = $resolver->resolve($file);

        if (false === $loader) {
            throw new \RuntimeException('Could not load routing configuration.');
        }

        return $loader->load($file);
    }

    public function registerContainerConfiguration(LoaderInterface $loader, array $managerConfig): void
    {
        $loader->load('@MvoContaoMeLikeBundle/Resources/config/config.yaml');
    }
}
