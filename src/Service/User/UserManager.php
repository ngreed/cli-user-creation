<?php

namespace App\Service\User;

use App\Entity\User;
use App\Service\User\Validation\DataValidator;
use App\Service\User\Validation\DuplicateValidator;
use Doctrine\ORM\EntityManagerInterface;

class UserManager
{
    const INDEX_SUCCESS = 'success';
    const INDEX_ERROR = 'error';
    const INDEX_DUPLICATE = 'duplicate';
    const INDEX_INVALID = 'invalid';
    const INDEX_NOT_FOUND = '404';

	private EntityManagerInterface $entityManager;
	private DataValidator $dataValidator;
	private DuplicateValidator $duplicateValidator;

    /**
     * @param EntityManagerInterface $entityManager
     * @param DataValidator          $dataValidator
     * @param DuplicateValidator     $duplicateValidator
     */
	public function __construct(
	    EntityManagerInterface $entityManager,
        DataValidator $dataValidator,
        DuplicateValidator $duplicateValidator
    ) {
        $this->entityManager = $entityManager;
        $this->dataValidator = $dataValidator;
        $this->duplicateValidator = $duplicateValidator;
    }

    /**
     * @param string      $email
     * @param string|null $firstName
     * @param string|null $lastName
     * @param string|null $phoneNumber1
     * @param string|null $phoneNumber2
     * @param string|null $comment
     *
     * @return array
     */
	public function create(
        string $email,
		?string $firstName = null,
		?string $lastName = null,
		?string $phoneNumber1 = null,
		?string $phoneNumber2 = null,
		?string $comment = null
	) : array {
        if (!$this->dataValidator->validate(
            $email,
            $firstName,
            $lastName,
            $phoneNumber1,
            $phoneNumber2,
            $comment
        )) {
            return [self::INDEX_INVALID => $email];
        }

        if (!$this->duplicateValidator->isUnique(
            $email,
            $firstName,
            $lastName,
            $phoneNumber1,
            $phoneNumber2,
            $comment
        )) {
            return [self::INDEX_DUPLICATE => $email];
        }

        $user = new User;
		$user
			->setFirstName($firstName)
			->setLastName($lastName)
			->setEmail($email)
			->setPhoneNumber1($phoneNumber1)
			->setPhoneNumber2($phoneNumber2)
			->setComment($comment)
			->setDoc(new \DateTime());

		try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            error_log(
                sprintf(
                    'There was an exception in %s class %s function: %s',
                    self::class,
                    __FUNCTION__ . '()',
                    $e->getMessage()
                )
            );

            return [self::INDEX_ERROR => $email];
        }

        return [self::INDEX_SUCCESS => $email];
	}

    /**
     * @param string $email
     *
     * @return array
     */
	public function delete(string $email) : array
    {
        $user = $this->find($email);
        if (!$user instanceof User) {
            return [self::INDEX_NOT_FOUND => $email];
        }

        try {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            error_log(
                sprintf(
                    'There was an exception in %s class %s function: %s',
                    self::class,
                    __FUNCTION__ . '()',
                    $e->getMessage()
                )
            );

            return [self::INDEX_ERROR => $email];
        }

        return [self::INDEX_SUCCESS => $email];
    }

    /**
     * @param string $email
     *
     * @return User|null
     */
    public function find(string $email) : ?User
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
    }

    /**
     * @param array $userData
     *
     * @return array
     */
    public function createMultiple(array $userData) : array
    {
        $createdUsers = [];
        foreach ($userData as $singleUser) {
            $createdUsers = array_merge_recursive(
                $createdUsers,
                $this->create(
                    $singleUser[0],
                    $singleUser[1],
                    $singleUser[2],
                    $singleUser[3],
                    $singleUser[4],
                    $singleUser[5]
                )
            );
        }

        return $createdUsers;
    }
}