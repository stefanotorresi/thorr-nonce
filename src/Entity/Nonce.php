<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace Thorr\Nonce\Entity;

use DateTime;
use Ramsey\Uuid\Uuid;
use Thorr\Persistence\Entity\AbstractEntity;

class Nonce extends AbstractEntity
{
    /**
     * Null value means nonce doesn't expire, ever
     *
     * @var DateTime
     */
    protected $expirationDate;

    /**
     * @var NonceOwnerInterface
     */
    protected $owner;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * {@inheritdoc}
     *
     * @param DateTime $expirationDate
     * @param string   $namespace
     */
    public function __construct(Uuid $uuid = null, DateTime $expirationDate = null, $namespace = null)
    {
        parent::__construct($uuid);

        $this->setExpirationDate($expirationDate);
        $this->setNamespace($namespace);
    }

    /**
     * @param DateTime|null $expirationDate
     */
    public function setExpirationDate(DateTime $expirationDate = null)
    {
        $this->expirationDate = $expirationDate;
    }

    /**
     * @return DateTime|null
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * @param NonceOwnerInterface $owner
     */
    public function setOwner(NonceOwnerInterface $owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return NonceOwnerInterface
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }
}
