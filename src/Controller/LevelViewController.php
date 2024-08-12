<?php


namespace App\Controller;

use App\Repository\LevelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package RosatomProcedures\DataAdmin\Controller
 */
class LevelViewController extends AbstractController
{
    public function __construct(
        private readonly LevelRepository $levelRepository
    ) {
    }

    #[Route("/level/view/{level}", name: "level.view")]
    public function view(
        Request $request,
        string $level
    ): Response
    {
        $xpath = $request->get('x', '/Oni');
        $xmlXpath = $this->levelRepository->loadLevel($level);
        $result = $xmlXpath->xpath($xpath);


        /** @var \SimpleXMLElement|null $resultElement  */
        $resultElements = $result[0] ?? [];
        $list = [];
        $listCount = [];
        /** @var \SimpleXMLElement $resultElement */
        foreach ($resultElements as $resultElement) {

            $listCount[$resultElement->getName()] = ($listCount[$resultElement->getName()] ?? 0) + 1;
            $list[] = [
                'name' => $resultElement->getName(),
                'number' => $listCount[$resultElement->getName()],
            ];
        }

        return $this->render('level.view.html.twig',[
            'level' => $level,
            'root' => $xpath,
            'list' => $list
        ]);
    }

    #[Route("/level/view-xml/{level}", name: "level.view-xml")]
    public function viewXml(
        Request $request,
        string $level
    ): Response
    {
        $xpath = $request->get('x', '/Oni');
        $xmlXpath = $this->levelRepository->loadLevel($level);
        $result = $xmlXpath->xpath($xpath);

        /** @var \SimpleXMLElement|null $resultElement  */
        $resultElements = $result[0] ?? null;

        if (!$resultElements) {
            throw $this->createNotFoundException('Не найден элемент');
        }

        $response = new Response($resultElements->saveXML());
        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }
}