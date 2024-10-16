<?php

namespace App\Controller;

use App\Entity\Localization;
use App\Repository\LocalizationRepository;
use App\Repository\WeatherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WeatherController extends AbstractController
{
    #[Route('/weather/{city}', name: 'app_weather', requirements: ['city' => '[a-zA-Z]+'])]
    public function city(string $city, WeatherRepository $weatherRepository,
                         LocalizationRepository $localizationRepository): Response
    {
        $localization = $localizationRepository->findByCity($city);
        $weather = $weatherRepository->findByLocalization($localization);

        return $this->render('weather/city.html.twig', [
            'localization' => $localization,
            'weather' => $weather,
        ]);
    }
}
