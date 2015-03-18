<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace Thorr\Nonce\DataMapper;

use Rhumsaa\Uuid\Uuid;
use Thorr\Nonce\Entity\Nonce;
use Thorr\Persistence\DataMapper;

interface NonceMapperInterface extends
    DataMapper\EntitySaverInterface,
    DataMapper\EntityRemoverInterface
{
    /**
     * @param string|Uuid $uuid
     * @param string      $namespace
     *
     * @return Nonce
     */
    public function find($uuid, $namespace);
}
