<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Miky\Bundle\UserBundle\Form\Handler;

use FOS\UserBundle\Form\Model\ChangePassword;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class ChangePasswordFormHandler
{
    protected $request;
    protected $userManager;
    protected $form;

    public function __construct(FormInterface $form, Request $request, UserManagerInterface $userManager)
    {
        $this->form = $form;
        $this->request = $request;
        $this->userManager = $userManager;
    }

    /**
     * @return string
     */
    public function getNewPassword()
    {
        return $this->form->getData()->new;
    }

    public function process(UserInterface $user)
    {
        $this->form->setData(new ChangePassword());

        if ('POST' === $this->request->getMethod()) {
            $this->form->bind($this->request);

            if ($this->form->isValid()) {
                $this->onSuccess($user);

                return true;
            }
        }

        return false;
    }

    protected function onSuccess(UserInterface $user)
    {
        $user->setPlainPassword($this->getNewPassword());
        $this->userManager->updateUser($user);
    }
}
