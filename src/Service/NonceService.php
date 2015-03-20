<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace Thorr\Nonce\Service;

use DateInterval;
use DateTime;
use Thorr\Nonce\DataMapper\NonceMapperInterface;
use Thorr\Nonce\Entity\Nonce;
use Thorr\Nonce\Entity\NonceOwnerInterface;
use Thorr\Nonce\Options\ModuleOptions;

class NonceService implements NonceServiceInterface
{
    /**
     * @var NonceMapperInterface
     */
    protected $nonceMapper;

    /**
     * @var ModuleOptions
     */
    protected $moduleOptions;

    /**
     * @param NonceMapperInterface $nonceMapper
     * @param ModuleOptions        $moduleOptions
     */
    public function __construct(NonceMapperInterface $nonceMapper, ModuleOptions $moduleOptions)
    {
        $this->nonceMapper   = $nonceMapper;
        $this->moduleOptions = $moduleOptions;
    }

    /**
     * {@inheritdoc}
     */
    public function createNonce(NonceOwnerInterface $owner, DateTime $expirationDate = null, $namespace = null)
    {
        if (! $expirationDate) {
            $interval       = new DateInterval($this->moduleOptions->getDefaultNonceExpirationInterval());
            $expirationDate = (new DateTime())->add($interval);
        }

        $nonce = new Nonce(null, $expirationDate, $namespace);
        $nonce->setOwner($owner);

        $this->nonceMapper->save($nonce);

        return $nonce;
    }

    /**
     * {@inheritdoc}
     *
     * proxies to the data mapper
     */
    public function findNonce($uuid, $namespace = null)
    {
        return $this->nonceMapper->find($uuid, $namespace);
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(Nonce $nonce)
    {
        $now = new DateTime();

        if ($nonce->getExpirationDate() && $nonce->getExpirationDate() <= $now) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function consumeNonce(Nonce $nonce)
    {
        $now = new DateTime();

        if (! $this->isValid($nonce)) {
            throw new Exception\NonceHasExpiredException();
        }

        $nonce->setExpirationDate($now);

        $this->nonceMapper->remove($nonce);

        return true;
    }
}
