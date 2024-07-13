<?php

declare(strict_types=1);

namespace App\Entity\VO;

final readonly class FilmId
{
    public function __construct(
        public int $id,
    ) {
    }
}
