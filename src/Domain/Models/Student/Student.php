<?php

namespace App\Domain\Models\Student;

use App\Domain\Models\Address\Address;
use App\Domain\Models\Email\Email;
use App\Domain\Models\Video\Video;
use DateTimeImmutable;
use DateTimeInterface;

class Student
{
    private WatchedVideos $watchedVideos;

    public function __construct(
        private readonly Email $email,
        private readonly DateTimeInterface $birthDate,
        private readonly Name  $name,
        private readonly Address $address,
    )
    {
        $this->watchedVideos = new WatchedVideos();
    }

    public function fullName(): string
    {
        return $this->name;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function fullAddress(): string
    {
        return $this->address;
    }

    public function watch(Video $video, DateTimeInterface $date): void
    {
        $this->watchedVideos->add($video, $date);
    }

    public function hasAccess(): bool
    {
        if ($this->watchedVideos->count() === 0) {
            return true;
        }

        return $this->isFirstVideoWasWatchedInLessThanNinetyDays();
    }

    private function isFirstVideoWasWatchedInLessThanNinetyDays(): bool
    {
        $firstDate = $this->watchedVideos->dateOfFirstVideo();
        $today = new DateTimeImmutable();

        return $firstDate->diff($today)->days < 90;
    }

    public function age(): int
    {
        $today = new DateTimeImmutable();

        return $this->birthDate->diff($today)->y;
    }
}
