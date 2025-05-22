<?php

declare(strict_types=1);

namespace App\Controller;

use Dullahan\Main\Contract\NotDoubleSubmitAuthenticatedController;
use Dullahan\Main\Contract\NotTokenAuthenticatedController;
use Dullahan\Main\Service\Util\HttpUtilService;
use OpenApi\Attributes as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[SWG\Tag('Health check')]
class DefaultController extends AbstractController implements
    NotTokenAuthenticatedController,
    NotDoubleSubmitAuthenticatedController
{
    public function __construct(
        protected HttpUtilService $httpUtilService,
    ) {
    }

    #[Route('/', name: 'api_app', methods: 'GET')]
    public function index(): JsonResponse
    {
        return $this->httpUtilService->jsonResponse('Healthy');
    }
}
