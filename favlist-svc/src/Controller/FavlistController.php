<?php

namespace App\Controller;

use App\Entity\Favlist;
use App\Entity\VO\FilmId;
use App\Repository\FavlistRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FavlistController
{
    public function __construct(private FavlistRepositoryInterface $favlistRepository)
    {
    }

    #[Route('/user/{userId}/favlists', name: 'app_favlist_list', methods: ['GET'])]
    public function list(int $userId): Response
    {
        $favlists = $this->favlistRepository->findFavlistsByOwner($userId);

        $result = [];
        foreach ($favlists as $favlist) {
            $result[] = $this->normalizeFavlist($favlist);
        }

        return new JsonResponse(
            ['items' => $result],
            Response::HTTP_OK,
        );
    }

    #[Route('/create', name: 'app_favlist_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $payload = $request->getContent();
        $data = json_decode($payload);
        $this->favlistRepository->createNewFavlist($data->ownerId, $data->name);

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    #[Route('/favlist/{favlistId}', name: 'app_favlist_get', methods: ['GET'])]
    public function getFavlistById(int $favlistId): Response
    {
        $favlist = $this->favlistRepository->findFavlistById($favlistId);

        return new JsonResponse(
            $this->normalizeFavlist($favlist),
            Response::HTTP_OK,
        );
    }

    #[Route('/favlist/{favlistId}/films', name: 'app_favlist_addfilms', methods: ['POST'])]
    public function addFilms(int $favlistId, Request $request): Response
    {
        $payload = json_decode($request->getContent());
        $filmIdsToAdd = $payload->filmIds;

        $filmsToAdd = array_map(
            fn ($filmId) => new FilmId($filmId),
            $filmIdsToAdd,
        );

        $favlist = $this->favlistRepository->findFavlistById($favlistId);

        $favlist->setFilmIdList(
            array_merge(
                $favlist->getFilmIdList(),
                array_map(
                    fn ($filmId) => new FilmId($filmId),
                    $filmIdsToAdd,
                ),
            )
        );

        $this->favlistRepository->save($favlist);

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    private function normalizeFavlist(Favlist $favlist): array
    {
        return [
            'id' => $favlist->getId(),
            'name' => $favlist->getName(),
            'filmList' => array_map(
                fn ($item) => ['filmId' => $item->id],
                $favlist->getFilmIdList(),
            ),
        ];
    }
}
