<?php

declare(strict_types=1);

namespace NicolasJoubert\GrabitFrontFeedBundle\DependencyInjection\Compiler;

use Doctrine\ORM\Events;
use NicolasJoubert\GrabitFrontFeedBundle\Model\FeedInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ResolveTargetEntityPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $container->findDefinition('doctrine.orm.listeners.resolve_target_entity')
            ->addMethodCall(
                'addResolveTargetEntity',
                [FeedInterface::class, $container->getParameter('grabit_front_feed.model.feed.class'), []],
            )
            ->addTag('doctrine.event_listener', ['event' => Events::loadClassMetadata])
            ->addTag('doctrine.event_listener', ['event' => Events::onClassMetadataNotFound])
        ;
    }
}
