<?php

/**
 * Contao toolkit.
 *
 * @package    contao-toolkit
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2015-2020 netzmacht David Molineus.
 * @license    LGPL-3.0-or-later https://github.com/netzmacht/contao-toolkit/blob/master/LICENSE
 * @filesource
 */

declare(strict_types=1);

namespace Netzmacht\Contao\Toolkit\Bundle\DependencyInjection\Compiler;

use Netzmacht\Contao\Toolkit\Assertion\Assert;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ComponentFactoriesPass.
 *
 * @package Netzmacht\Contao\Toolkit\DependencyInjection\Compiler
 */
final class ComponentDecoratorPass implements CompilerPassInterface
{
    /**
     * Name of the tag.
     *
     * @var string
     */
    private $tagName;

    /**
     * Index of the argument which should get the tagged references.
     *
     * @var int
     */
    private $argumentIndex;

    /**
     * ComponentFactoryCompilePass constructor.
     *
     * @param string $tagName       Name of the tag.
     * @param int    $argumentIndex Index of the argument which should get the tagged references.
     */
    public function __construct(string $tagName, int $argumentIndex)
    {
        $this->tagName       = $tagName;
        $this->argumentIndex = $argumentIndex;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        $serviceId = 'netzmacht.contao_toolkit.listeners.register_component_decorators';

        if (!$container->has($serviceId)) {
            return;
        }

        $definition       = $container->findDefinition($serviceId);
        $taggedServiceIds = $container->findTaggedServiceIds($this->tagName);
        $components       = (array) $definition->getArgument($this->argumentIndex);

        foreach ($taggedServiceIds as $tags) {
            foreach ($tags as $tag) {
                Assert::that($tag)->keyExists('category');
                Assert::that($tag['category'])->string();

                $key = isset($tag['alias']) ? 'alias' : 'type';
                Assert::that($tag)->keyExists($key);
                Assert::that($tag[$key])->string();

                $components[$tag['category']][] = $tag[$key];
            }
        }

        $definition->replaceArgument($this->argumentIndex, $components);
    }
}
