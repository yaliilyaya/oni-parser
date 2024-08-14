<?php

namespace App\Serializer;

class Xml2ArraySerializer
{
    public function desiralize($xmlObject, $out = array () ): array
    {
        foreach ( (array) $xmlObject as $index => $node )
            $out[$index] = ( is_object ( $node ) ) ? $this->desiralize( $node ) : $node;

        return $out;
    }
}