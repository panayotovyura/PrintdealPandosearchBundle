<?php

namespace Printdeal\PandosearchBundle\Exception;

use Throwable;

class ClientNotFoundException extends RequestException
{
    const MESSAGE_TEMPLATE = 'Http Client for %s localization not found';

    /**
     * ClientNotFoundException constructor.
     * @param string $localization
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $localization, $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf(self::MESSAGE_TEMPLATE, $localization), $code, $previous);
    }
}
