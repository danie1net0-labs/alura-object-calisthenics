<?php

namespace App\Domain\Models\Video;

use App\Domain\Models\Student\Student;
use DateTimeImmutable;

class InMemoryVideoRepository implements VideoRepository
{
    private array $videos;

    public function add(Video $video): void
    {
        $this->videos[] = $video;
    }

    public function videosFor(Student $student): array
    {
        return array_filter(
            $this->videos,
            static fn (Video $video) => $video->getAgeLimit() <= $student->age()
        );
    }
}
