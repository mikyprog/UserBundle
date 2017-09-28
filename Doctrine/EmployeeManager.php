<?php
/**
 * Created by PhpStorm.
 * User: miky
 * Date: 17/08/16
 * Time: 11:52
 */

namespace Miky\Bundle\UserBundle\Doctrine;


use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Doctrine\UserManager;
use FOS\UserBundle\Util\CanonicalFieldsUpdater;
use FOS\UserBundle\Util\PasswordUpdaterInterface;
use Miky\Bundle\UserBundle\Model\Employee;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class EmployeeManager extends UserManager
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    public function __construct(PasswordUpdaterInterface $passwordUpdater, CanonicalFieldsUpdater $canonicalFieldsUpdater, ObjectManager $om, TokenStorageInterface $tokenStorage, $class)
    {
        parent::__construct($passwordUpdater, $canonicalFieldsUpdater, $om, $class);
        $this->tokenStorage = $tokenStorage;
    }

    public function getCurrentUser()
    {
        if (null === $token = $this->tokenStorage->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return;
        }

        if (!$user instanceof Employee) {
            return;
        }
        return $user;
    }
}