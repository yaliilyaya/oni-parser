<?php


namespace App\Controller;

use App\Repository\LevelRepository;
use App\Serializer\Xml2ArraySerializer;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package RosatomProcedures\DataAdmin\Controller
 */
class UnloadLevelController extends AbstractController
{
    public function __construct(
        private readonly LevelRepository $levelRepository,
        private readonly Xml2ArraySerializer $xml2ArraySerializer,
    ) {
    }

    #[Route("/unload-level/{level}/pnta", name: "unload-level.pnta")]
    public function pnta(
        string $level
    ): Response
    {
        $pnta = $this->levelRepository->loadLevel($level)
            ->xpath('/Oni/PNTA');

        /** @var \SimpleXMLElement $pntaItem */
        $pntaItem = $pnta[0];

        $list = $this->xml2ArraySerializer->desiralize($pntaItem->Positions->Vector3);
        $list = (new ArrayCollection($list))->map(function ($vectorString) {
            $vectorString = explode(' ', $vectorString);
            return [
                'x' => (float)$vectorString[0],
                'y' => (float)$vectorString[1],
                'z' => (float)$vectorString[2],
            ];
        })->toArray();

        $this->levelRepository->saveSource($level, 'pnta', $list);

        $count = count($list);
        $startIndex = rand(0, $count);
        return $this->json([
            'count' => $count,
            'startIndex' => $startIndex,
            'current' => array_splice($list, $startIndex, 100)
        ]);
    }

    #[Route("/unload-level/{level}/plea", name: "unload-level.plea")]
    public function plea(
        string $level
    ): Response
    {
        $plea = $this->levelRepository->loadLevel($level)
            ->xpath('/Oni/PLEA');
        /** @var \SimpleXMLElement $pntaItem */
        $pleaItem = $plea[0];

        $list = $this->xml2ArraySerializer->desiralize($pleaItem->Planes->Plane);
        $list = (new ArrayCollection($list))->map(function ($vectorString) {
            $vectorString = explode(' ', $vectorString);
            return [
                'a' => (float)$vectorString[0],
                'b' => (float)$vectorString[1],
                'c' => (float)$vectorString[2],
                'd' => (float)$vectorString[3],
            ];
        })->toArray();

        $this->levelRepository->saveSource($level, 'plea', $list);

        $count = count($list);
        $startIndex = rand(0, $count);
        return $this->json([
            'count' => $count,
            'startIndex' => $startIndex,
            'current' => array_splice($list, $startIndex, 100)
        ]);
    }
}