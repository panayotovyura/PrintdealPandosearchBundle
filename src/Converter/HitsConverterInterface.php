<?php

namespace Printdeal\PandosearchBundle\Converter;

interface HitsConverterInterface
{
    /**
     * @param array $hits
     * @return array
     */
    public function convert(array $hits): array;
}
