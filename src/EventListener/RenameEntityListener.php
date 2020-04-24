<?php

declare(strict_types=1);

/*
 * @author  Moritz Vondano
 * @license MIT
 */

namespace Mvo\ContaoMeLike\EventListener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Mvo\MeLike\Entity\Like;

class RenameEntityListener
{
    public function __invoke(LoadClassMetadataEventArgs $args): void
    {
        $classMetadata = $args->getClassMetadata();

        if (Like::class !== $classMetadata->getName()) {
            return;
        }

        // rename to tl_*, so that Contao tracks it when installing
        $classMetadata->setPrimaryTable(['name' => 'tl_mvo_me_like']);
    }
}
