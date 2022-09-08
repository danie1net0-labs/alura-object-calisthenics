<?php

namespace App\Tests\Unit\Domain\Models\Video;

use App\Domain\Models\Student\Student;
use App\Domain\Models\Video\InMemoryVideoRepository;
use App\Domain\Models\Video\Video;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class InMemoryVideoRepositoryTest extends TestCase
{
    public function testFindingVideosForAStudentMustFilterAgeLimit(): void
    {
        $repository = new InMemoryVideoRepository();

        // [21, 20, 19, 18, 17]
        for ($i = 21; $i >= 17; $i--) {
            $video = new Video();
            $video->setAgeLimit($i);
            $repository->add($video);
        }

        $student = $this->createStub(Student::class);
        $student->method('age')->willReturn(19);

        $videoList = $repository->videosFor($student);

        self::assertCount(3, $videoList);
    }
}
