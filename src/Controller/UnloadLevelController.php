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
            ->xpath('/Oni/PNTA/Positions/Vector3');

        $list = (new ArrayCollection($pnta))->map(function (\SimpleXMLElement $vector) {
            $vectorString = explode(' ', (string)$vector);
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
            ->xpath('/Oni/PLEA/Planes/Plane');

        $list = (new ArrayCollection($plea))->map(function ($value) {
            $value = explode(' ', (string)$value);

            return [
                'a' => (float)$value[0],
                'b' => (float)$value[1],
                'c' => (float)$value[2],
                'd' => (float)$value[3],
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