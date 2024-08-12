<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package RosatomProcedures\DataAdmin\Controller
 */
class LevelViewController extends AbstractController
{

    #[Route("/level/view/{level}", name: "level.view")]
    public function index(string $level): Response
    {
        return $this->json(['ok']);
    }
}