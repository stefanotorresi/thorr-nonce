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
     * Finds a nonce.
     *
     * @param Uuid|string $uuid
     * @param string      $namespace
     *
     * @return Nonce|null
     */
    public function findNonce($uuid, $namespace = null);

    /**
     * Validates a nonce, i.e. checks its expiration date
     *
     * Null expiration date means nonce is always valid until consumption
     *
     * @param Nonce $nonce
     *
     * @return bool
     */
    public function isValid(Nonce $nonce);

    /**
     * Consumes a nonce. The nonce is deleted upon consumption.
     *
     * @param Nonce $nonce
     *
     * @throws Exception\NonceHasExpiredException
     *
     * @return bool returns true on success
     */
    public function consumeNonce(Nonce $nonce);
}
