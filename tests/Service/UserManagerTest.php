<?php

namespace App\Tests\Service;

use App\Service\User\UserManager;
use App\Service\User\Validation\DataValidator;
use App\Service\User\Validation\DuplicateValidator;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class UserManagerTest extends TestCase
{
    /**
     * @dataProvider createInvalidEmailDataProvider
     *
     * @param string $email
     * @param array  $expectedValue
     */
    public function testCreateInvalidEmail(
        string $email,
        array $expectedValue
    ) : void {
        $dataValidator = $this->createMock(DataValidator::class);
        $dataValidator->method('validate')->willReturn(false);
        $userManager = new UserManager(
            $this->createMock(EntityManager::class),
            $dataValidator,
            $this->createMock(DuplicateValidator::class)
        );

        $this->assertEquals(
            $expectedValue,
            $userManager->create($email)
        );
    }

    /**
     * @return array
     */
    public function createInvalidEmailDataProvider() : array
    {
        $email1 = 'in!@valid.com';
        $email2 = '@nother@invalid.com';

        return [
            [
                $email1,
                [UserManager::INDEX_INVALID => $email1],
            ],
            [
                $email2,
                [UserManager::INDEX_INVALID => $email2],
            ],
        ];
    }

    /**
     * @dataProvider createDuplicateDataProvider
     *
     * @param string $email
     * @param array  $expectedValue
     */
    public function testCreateDuplicate(
        string $email,
        array $expectedValue
    ) : void {
        $dataValidator = $this->createMock(DataValidator::class);
        $dataValidator->method('validate')->willReturn(true);
        $duplicateValidator = $this->createMock(DuplicateValidator::class);
        $duplicateValidator->method('isUnique')->willReturn(false);

        $userManager = new UserManager(
            $this->createMock(EntityManager::class),
            $dataValidator,
            $duplicateValidator
        );

        $this->assertEquals(
            $expectedValue,
            $userManager->create($email)
        );
    }

    /**
     * @return array
     */
    public function createDuplicateDataProvider() : array
    {
        $email1 = 'duplicate@email.com';
        $email2 = 'duplicate2@email.com';

        return [
            [
                $email1,
                [UserManager::INDEX_DUPLICATE => $email1],
            ],
            [
                $email2,
                [UserManager::INDEX_DUPLICATE => $email2],
            ],
        ];
    }

    /**
     * @dataProvider createExceptionDataProvider
     *
     * @param string $email
     * @param array  $expectedValue
     */
    public function testCreateException(
        string $email,
        array $expectedValue
    ) : void {
        $dataValidator = $this->createMock(DataValidator::class);
        $dataValidator->method('validate')->willReturn(true);
        $duplicateValidator = $this->createMock(DuplicateValidator::class);
        $duplicateValidator->method('isUnique')->willReturn(true);

        $entityManager = $this->createMock(EntityManager::class);
        $entityManager
            ->method('persist')
            ->willThrowException(new \Exception());

        $userManager = $this
            ->getMockBuilder(UserManager::class)
            ->setConstructorArgs([
                $entityManager,
                $dataValidator,
                $duplicateValidator
            ])->onlyMethods(['find'])
            ->getMock();
        $userManager->method('find')->with($email)->willReturn(null);

        $this->assertEquals(
            $expectedValue,
            $userManager->create($email)
        );
    }

    /**
     * @return array
     */
    public function createExceptionDataProvider() : array
    {
        $email1 = 'valid@email.com';
        $email2 = 'valid2@email.com';

        return [
            [
                $email1,
                [UserManager::INDEX_ERROR => $email1],
            ],
            [
                $email2,
                [UserManager::INDEX_ERROR => $email2],
            ],
        ];
    }

    /**
     * @dataProvider createSuccessDataProvider
     *
     * @param string $email
     * @param array  $expectedValue
     */
    public function testCreateSuccess(
        string $email,
        array $expectedValue
    ) : void {
        $dataValidator = $this->createMock(DataValidator::class);
        $dataValidator->method('validate')->willReturn(true);
        $duplicateValidator = $this->createMock(DuplicateValidator::class);
        $duplicateValidator->method('isUnique')->willReturn(true);

        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->method('persist');
        $entityManager->method('flush');

        $userManager = $this
            ->getMockBuilder(UserManager::class)
            ->setConstructorArgs([
                $entityManager,
                $dataValidator,
                $duplicateValidator
            ])->onlyMethods(['find'])
            ->getMock();
        $userManager->method('find')->with($email)->willReturn(null);

        $this->assertEquals(
            $expectedValue,
            $userManager->create($email)
        );
    }

    /**
     * @return array
     */
    public function createSuccessDataProvider() : array
    {
        $email1 = 'valid@email.com';
        $email2 = 'valid2@email.com';

        return [
            [
                $email1,
                [UserManager::INDEX_SUCCESS => $email1],
            ],
            [
                $email2,
                [UserManager::INDEX_SUCCESS => $email2],
            ],
        ];
    }
}