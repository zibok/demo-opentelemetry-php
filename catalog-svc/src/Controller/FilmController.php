<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Film;
use App\Exception\FilmNotFoundException;
use App\Repository\FilmRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FilmController
{
    public function __construct(private FilmRepositoryInterface $filmRepository)
    {
    }

    #[Route(path: '/films/{id}', name: 'app_film_getInfos', methods: ['GET'])]
    public function getFilmInfos(int $id): Response
    {
        try {
            $film = $this->filmRepository->getById($id);

            return new JsonResponse($this->normalizeFilm($film), Response::HTTP_OK);
        } catch (FilmNotFoundException $e) {
            return new JsonResponse(
                [
                    'error' => "Unable to get film $id",
                ],
                Response::HTTP_NOT_FOUND,
            );
        }
    }

    #[Route(path: '/search/films', name: 'app_film_search', methods: ['GET'])]
    public function search(Request $request): Response
    {
        $films = $this->filmRepository->search($request->query->getString('search'));

        return new JsonResponse(
            [
                'items' => array_map(fn ($film) => $this->normalizeFilm($film), $films),
            ],
            Response::HTTP_OK,
        );
    }

    private function normalizeFilm(Film $film): array
    {
        return [
            'filmId' => $film->getId(),
            'title' => $film->getTitle(),
            'author' => $film->getAuthor(),
            'genre' => $film->getGenre(),
        ];
    }
}
