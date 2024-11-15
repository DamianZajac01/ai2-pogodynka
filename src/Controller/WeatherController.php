<?php

namespace App\Controller;

use App\Entity\Localization;
use App\Entity\Weather;
use App\Form\WeatherType;
use App\Repository\WeatherRepository;
use App\Service\WeatherUtil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/weather')]
final class WeatherController extends AbstractController
{
    #[Route(name: 'app_weather_index', methods: ['GET'])]
    #[IsGranted('ROLE_WEATHER_INDEX')]
    public function index(WeatherRepository $weatherRepository): Response
    {
        return $this->render('weather/index.html.twig', [
            'weather' => $weatherRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_weather_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_WEATHER_NEW')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $weather = new Weather();
        $form = $this->createForm(WeatherType::class, $weather, [
                'validation_groups' => 'create',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($weather);
            $entityManager->flush();

            return $this->redirectToRoute('app_weather_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('weather/new.html.twig', [
            'weather' => $weather,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_weather_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    #[IsGranted('ROLE_WEATHER_SHOW')]
    public function show(Weather $weather): Response
    {
        return $this->render('weather/show.html.twig', [
            'weather' => $weather,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_weather_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_WEATHER_EDIT')]
    public function edit(Request $request, Weather $weather, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WeatherType::class, $weather, [
                'validation_groups' => 'edit',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_weather_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('weather/edit.html.twig', [
            'weather' => $weather,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_weather_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsGranted('ROLE_WEATHER_DELETE')]
    public function delete(Request $request, Weather $weather, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$weather->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($weather);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_weather_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{country}/{city}', name: 'app_weather_city', requirements: ['country' => '[a-zA-Z]+', 'city' => '[a-zA-Z]+'])]
    public function city(
        #[MapEntity(mapping: ['country' => 'country', 'city' => 'city'])]
        Localization $localization,
        WeatherUtil $weatherUtil
    ): Response
    {
        $weather = $weatherUtil->getWeatherForLocalization($localization);

        return $this->render('weather/city.html.twig', [
            'localization' => $localization,
            'weather' => $weather,
        ]);
    }
}
