<?php

namespace App\Domain\Models\Video;

use App\Domain\Models\Student\Student;

interface VideoRepository
{
    public function add(Video $video): void;
    public function videosFor(Student $student): array;
}
