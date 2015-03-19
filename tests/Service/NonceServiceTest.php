<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace Thorr\Nonce\Test\Service;

use DateInterval;
use DateTime;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase as TestCase;
use Rhumsaa\Uuid\Uuid;
use Thorr\Nonce\DataMapper\NonceMapperInterface;
use Thorr\Nonce\Entity\Nonce;
use Thorr\Nonce\Entity\NonceOwnerInterface;
use Thorr\Nonce\Options\ModuleOptions;
use Thorr\Nonce\Service\Exception\NonceHasExpiredException;
use Thorr\Nonce\Service\Exception\NonceNotFoundException;
use Thorr\Nonce\Service\NonceService;

class NonceServiceTest extends TestCase
{
    /**
     * @var NonceMapperInterface|MockObject
     */
    protected $nonceMapper;

    /**
     * @var ModuleOptions
     */
    protected $moduleOptions;

    /**
     * @var NonceService
     */
    protected $nonceService;

    public function setUp()
    {
        $this->nonceMapper   = $this->getMock(NonceMapperInterface::class);
        $this->moduleOptions = new ModuleOptions();
        $this->nonceService  = new NonceService($this->nonceMapper, $this->moduleOptions);
    }

    public function testCreateNonce()
    {
        $owner          = $this->getMock(NonceOwnerInterface::class);
        $expirationDate = new DateTime();

        $this->nonceMapper->expects($this->atLeastOnce())
                          ->method('save');

        $nonce = $this->nonceService->createNonce($owner, $expirationDate);

        $this->assertInstanceOf(Nonce::class, $nonce);
        $this->assertSame($expirationDate, $nonce->getExpirationDate());
        $this->assertSame($owner, $nonce->getOwner());
    }

    public function testCreateWithDefaultExpirationDate()
    {
        $owner        = $this->getMock(NonceOwnerInterface::class);
        $nonce        = $this->nonceService->createNonce($owner);
        $expectedDate = (new DateTime())->add(new DateInterval($this->moduleOptions->getDefaultNonceExpirationInterval()));

        $this->assertInstanceOf(Nonce::class, $nonce);
        $this->assertNotNull($nonce->getExpirationDate());
        $this->assertTrue($expectedDate >= $nonce->getExpirationDate());
    }

    public function testConsumeNonce()
    {
        $nonce = new Nonce();

        $result = $this->nonceService->consumeNonce($nonce);

        $now   = new DateTime();

        $this->assertTrue($result);
        $this->assertNotNull($nonce->getExpirationDate());
        $this->assertTrue($now >= $nonce->getExpirationDate());
    }

    public function testConsumeNonceWithExpirationDate()
    {
        $owner = $this->getMock(NonceOwnerInterface::class);
        $nonce = $this->nonceService->createNonce($owner);

        $result = $this->nonceService->consumeNonce($nonce);

        $now   = new DateTime();

        $this->assertTrue($result);
        $this->assertTrue($now >= $nonce->getExpirationDate());
    }

    public function testConsumeExpiredNonceWillThrowException()
    {
        $expirationDate = (new DateTime())->sub(new DateInterval('PT1S'));
        $nonce          = new Nonce(null, $expirationDate);

        $this->setExpectedException(NonceHasExpiredException::class);

        $this->nonceService->consumeNonce($nonce);
    }

    public function testCreateNonceWithNamespace()
    {
        $owner = $this->getMock(NonceOwnerInterface::class);
        $nonce = $this->nonceService->createNonce($owner, null, 'foobar');

        $this->assertSame('foobar', $nonce->getNamespace());
    }

    public function testFindNonExistentNonceWillThrowException()
    {
        $this->setExpectedException(NonceNotFoundException::class);

        $this->nonceService->findNonce('foobar');
    }

    public function testFindNonceWithParamsWillInvokeTheMapperFindMethodWithSameParams()
    {
        $uuid      = Uuid::uuid4();
        $namespace = 'foobar';
        $nonce     = new Nonce();

        $this->nonceMapper
            ->expects($this->atLeastOnce())
            ->method('find')
            ->with($uuid, $namespace)
            ->willReturn($nonce)
        ;

        $this->assertSame($nonce, $this->nonceService->findNonce($uuid, $namespace));
    }
}
