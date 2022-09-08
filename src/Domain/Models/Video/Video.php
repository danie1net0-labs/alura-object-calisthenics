<?php

namespace App\Domain\Models\Video;

use App\Domain\Enums\Video\Visibility;

class Video
{
    private Visibility $visibility = Visibility::PRIVATE;
    private int $ageLimit;

    public function publish(): void
    {
        $this->visibility = Visibility::PUBLIC;
    }

    public function getVisibility(): Visibility
    {
        return $this->visibility;
    }

    public function getAgeLimit(): int
    {
        return $this->ageLimit;
    }

    public function setAgeLimit(int $ageLimit): void
    {
        $this->ageLimit = $ageLimit;
    }
}
