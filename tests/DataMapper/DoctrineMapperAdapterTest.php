<?php
/**
 * @author  Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace Thorr\Nonce\Test\DataMapper;

use Doctrine\Common\Persistence\ObjectManager;
use PHPUnit_Framework_TestCase as TestCase;
use Thorr\Nonce\DataMapper\DoctrineMapperAdapter;
use Thorr\Nonce\Entity\Nonce;

class DoctrineMapperAdapterTest extends TestCase
{
    public function testFindInvalidUuidReturnsNull()
    {
        $objectManager = $this->getMock(ObjectManager::class);
        $adapter       = new DoctrineMapperAdapter(Nonce::class, $objectManager);

        $this->assertNull($adapter->find('foobar', 'barbaz'));
    }
}
