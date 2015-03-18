<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace Thorr\Nonce\Service;

use DateTime;
use Rhumsaa\Uuid\Uuid;
use Thorr\Nonce\Entity\Nonce;
use Thorr\Nonce\Entity\NonceOwnerInterface;

interface NonceServiceInterface
{
    /**
     * Creates a nonce. By default it sets the expiration date at one week from now
     *
     * @param NonceOwnerInterface $owner
     * @param DateTime            $expirationDate
     * @param string              $namespace
     *
     * @return Nonce
     */
    public function createNonce(NonceOwnerInterface $owner, DateTime $expirationDate = null, $namespace = null);

    /**
     * Consumes a nonce. The nonce is deleted upon consumption.
     *
     * @param Nonce|Uuid|string $uuidOrNonce
     * @param string            $namespace
     *
     * @throws Exception\NonceNotFoundException
     * @throws Exception\NonceHasExpiredException
     *
     * @return bool returns true on success
     */
    public function consumeNonce($uuidOrNonce, $namespace = null);
}
