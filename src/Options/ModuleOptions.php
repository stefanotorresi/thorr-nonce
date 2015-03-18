<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace Thorr\Nonce\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $defaultNonceExpirationInterval = 'P7D';

    /**
     * @return string
     */
    public function getDefaultNonceExpirationInterval()
    {
        return $this->defaultNonceExpirationInterval;
    }

    /**
     * @param string $defaultNonceExpirationInterval
     */
    public function setDefaultNonceExpirationInterval($defaultNonceExpirationInterval)
    {
        $this->defaultNonceExpirationInterval = $defaultNonceExpirationInterval;
    }
}
