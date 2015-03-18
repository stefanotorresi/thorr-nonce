<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace Thorr\Nonce\DataMapper;

use InvalidArgumentException;
use Rhumsaa\Uuid\Uuid;
use Thorr\Nonce\Entity\Nonce;
use Thorr\Persistence\Doctrine\DataMapper\DoctrineAdapter;

class DoctrineMapperAdapter extends DoctrineAdapter implements NonceMapperInterface
{
    /**
     * @param string|Uuid $uuid
     * @param string      $namespace
     *
     * @return Nonce|null
     */
    public function find($uuid, $namespace)
    {
        if (! $uuid instanceof Uuid) {
            try {
                $uuid = Uuid::fromString($uuid);
            } catch (InvalidArgumentException $e) {
                return;
            }
        }

        return $this->getObjectManager()->getRepository($this->getEntityClass())->findOneBy([
            'uuid'      => $uuid->toString(),
            'namespace' => $namespace,
        ]);
    }

}
