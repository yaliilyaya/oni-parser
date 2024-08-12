<?php


namespace App\Controller;

use App\Model\TemplateConfig;
use App\Model\TemplateParam;
use App\Service\Packet\PacketGeneratorService;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package RosatomProcedures\DataAdmin\Controller
 */
class OniLavelParserController extends AbstractController
{

    #[Route("/oni/level/pnta", name: "oni.level.pnta")]
    public function pnta(): Response
    {
        $AKEVcompound = file_get_contents(__DIR__ . '/AKEVcompound.xml');
        $xml = new \SimpleXMLElement($AKEVcompound);

        $pnta = $xml->xpath('/Oni/PNTA');
        /** @var \SimpleXMLElement $pntaItem */
        $pntaItem = $pnta[0];

        $list = $this->xml2array($pntaItem->Positions->Vector3);
        $list = (new ArrayCollection($list))->map(function ($vectorString) {
            $vectorString = explode(' ', $vectorString);
            return [
                'x' => (float)$vectorString[0],
                'y' => (float)$vectorString[1],
                'z' => (float)$vectorString[2],
            ];
        })->toArray();

        file_put_contents(__DIR__ . '/AKEVcompound.pnta.json', json_encode($list));


        $count = count($list);
        $startIndex = rand(0, $count);
        return $this->json([
            'count' => $count,
            'startIndex' => $startIndex,
            'current' => array_splice($list, $startIndex, 100),

        ]);
    }

    #[Route("/oni/level/plea", name: "oni.level.plea")]
    public function plea(): Response
    {
        $AKEVcompound = file_get_contents(__DIR__ . '/AKEVcompound.xml');
        $xml = new \SimpleXMLElement($AKEVcompound);

        $plea = $xml->xpath('/Oni/PLEA');
        /** @var \SimpleXMLElement $pntaItem */
        $pleaItem = $plea[0];


        $list = $this->xml2array($pleaItem->Planes->Plane);
        $list = (new ArrayCollection($list))->map(function ($vectorString) {
            $vectorString = explode(' ', $vectorString);
            return [
                'a' => (float)$vectorString[0],
                'b' => (float)$vectorString[1],
                'c' => (float)$vectorString[2],
                'd' => (float)$vectorString[3],
            ];
        })->toArray();

        file_put_contents(__DIR__ . '/AKEVcompound.plea.json', json_encode($list));

        $count = count($list);
        $startIndex = rand(0, $count);
        return $this->json([
            'count' => $count,
            'startIndex' => $startIndex,
            'current' => array_splice($list, $startIndex, 100),

        ]);
    }

    private function xml2array ( $xmlObject, $out = array () )
    {
        foreach ( (array) $xmlObject as $index => $node )
            $out[$index] = ( is_object ( $node ) ) ? $this->xml2array ( $node ) : $node;

        return $out;
    }

}