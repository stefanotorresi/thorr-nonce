<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace Thorr\Nonce\Service\Exception;

use Exception;

class NonceHasExpiredException extends NonceServiceException
{
    public function __construct($message = 'Nonce has expired', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
