<?php

namespace App\Tests\Unit\Domain\Models\Video;

use App\Domain\Enums\Video\Visibility;
use App\Domain\Models\Video\Video;
use PHPUnit\Framework\TestCase;

class VideoTest extends TestCase
{
    public function testMakingAVideoPublicMustWork(): void
    {
        $video = new Video();

        $video->publish();

        self::assertSame(Visibility::PUBLIC, $video->getVisibility());
    }
}
