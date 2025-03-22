<?php

namespace App\Controller\Admin\User;

use App\Controller\Common\EditControllerTrait;
use App\Entity\User;
use App\Form\Admin\User\AdminUserFormType;
use App\Message\Command\User\EditUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    path: '/user/{id:user}/edit',
    name: RouteCollection::EDIT->value,
    methods: [Request::METHOD_GET, Request::METHOD_POST],
)]
class EditController extends AbstractController
{
    use EditControllerTrait;

    public function __invoke(
        Request $request,
        User $user,
    ): Response {
        $form = $this->createForm(AdminUserFormType::class, new EditUser($user));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handle($form->getData());

            $this->addFlash(
                type: 'success',
                message: 'flash.edited',
            );

            return $this->redirectToRoute(RouteCollection::LIST->prefixed());
        }

        return $this->render(
            view: 'admin/user/edit.html.twig',
            parameters: [
                'entity' => $user,
                'form' => $form->createView(),
            ],
        );
    }
}
