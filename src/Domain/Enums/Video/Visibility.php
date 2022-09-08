<?php

namespace App\Domain\Enums\Video;

enum Visibility: string
{
    case PUBLIC = 'public';
    case PRIVATE = 'private';
}
