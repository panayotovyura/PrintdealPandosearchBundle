<?php

namespace Printdeal\PandosearchBundle\Exception;

class UnknownLocalizationException extends ClientNotFoundException
{
    const MESSAGE_TEMPLATE = 'Localization \'%s\' not supported at this project';
}
