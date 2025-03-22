<?php

namespace App\Controller\Admin\User;

use App\Controller\Common\CreateControllerTrait;
use App\Form\Admin\User\AdminUserFormType;
use App\Message\Command\User\CreateUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    path: '/user/create',
    name: RouteCollection::CREATE->value,
    methods: [Request::METHOD_GET, Request::METHOD_POST],
)]
class CreateController extends AbstractController
{
    use CreateControllerTrait;

    public function __invoke(
        Request $request,
    ): Response {
        $form = $this->createForm(AdminUserFormType::class, new CreateUser('', '', ''), [
            'creating' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handle($form->getData());

            $this->addFlash(
                type: 'success',
                message: 'flash.created',
            );

            return $this->redirectToRoute(RouteCollection::LIST->prefixed());
        }

        return $this->render(
            view: 'admin/user/create.html.twig',
            parameters: [
                'form' => $form->createView(),
            ],
        );
    }
}
