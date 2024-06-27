<?php

declare(strict_types=1);

namespace App\Controller;
use App\Exception\TrackNotFoundException;
use App\Repository\TrackRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TrackController
{
    public function __construct(private TrackRepositoryInterface $trackRepository)
    {
    }

    #[Route(path:"/tracks/{id}", name:"app_track_getInfos", methods: ["GET"])]
    public function getTrackInfos(int $id): Response
    {
        try {
            $track = $this->trackRepository->getById($id);
            return new JsonResponse(
                [
                    'id' => $id,
                    'title' => $track->getTitle(),
                    'author' => $track->getAuthor(),
                    'link' => $track->getLink(),
                ],
                200,
            );
        } catch (TrackNotFoundException $e) {
            return new JsonResponse(
                [
                    'error' => "Unable to get track $id",
                ],
                404,
            );  

        }
    }
}