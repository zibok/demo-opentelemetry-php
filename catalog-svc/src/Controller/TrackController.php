<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Track;
use App\Exception\TrackNotFoundException;
use App\Repository\TrackRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TrackController
{
    public function __construct(private TrackRepositoryInterface $trackRepository)
    {
    }

    #[Route(path: '/tracks/{id}', name: 'app_track_getInfos', methods: ['GET'])]
    public function getTrackInfos(int $id): Response
    {
        try {
            $track = $this->trackRepository->getById($id);

            return new JsonResponse($this->normalizeTrack($track), 200);
        } catch (TrackNotFoundException $e) {
            return new JsonResponse(
                [
                    'error' => "Unable to get track $id",
                ],
                404,
            );
        }
    }

    #[Route(path: '/search/tracks', name: 'app_track_search', methods: ['GET'])]
    public function search(Request $request): Response
    {
        $tracks = $this->trackRepository->search($request->query->getString('search'));

        return new JsonResponse(
            [
                'items' => array_map(fn ($track) => $this->normalizeTrack($track), $tracks),
            ],
            200,
        );
    }

    private function normalizeTrack(Track $track): array
    {
        return [
            'id' => $track->getId(),
            'title' => $track->getTitle(),
            'author' => $track->getAuthor(),
            'link' => $track->getLink(),
        ];
    }
}
