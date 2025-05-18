<?php

namespace App\Controller\Admin\Project;

use App\Controller\Common\GetControllerTrait;
use App\Entity\Project;
use App\Form\Filter\ProjectFormFilter;
use App\Message\Query\PaginateEntities;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    path: '/projects',
    name: RouteCollection::LIST->value,
    methods: [Request::METHOD_GET],
)]
class ListController extends AbstractController
{
    use GetControllerTrait;

    public function __invoke(
        Request $request,
    ): Response {
        $filterForm = $this->createForm(ProjectFormFilter::class);
        $filterForm->handleRequest($request);

        $pagination = $this->handle(
            new PaginateEntities(
                class: Project::class,
                sort: $request->get('_sort', 'updatedAt'),
                page: $request->get('page', 1),
                form: $filterForm,
            ),
        );

        return $this->render(
            view: 'admin/project/list.html.twig',
            parameters: [
                'pagination' => $pagination,
                'filterForm' => $filterForm,
            ],
        );
    }
}
