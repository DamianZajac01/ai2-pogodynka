<?php

namespace App\Controller;

use App\Entity\Weather;
use App\Service\WeatherUtil;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class WeatherApiController extends AbstractController
{
    public function __construct(private WeatherUtil $weatherUtil)
    {
    }

    #[Route('/api/v1/weather', name: 'app_weather_api')]
    public function index(
        #[MapQueryParameter('country')] string $country,
        #[MapQueryParameter('city')] string $city,
        #[MapQueryParameter('format')] string $format = 'json',
        #[MapQueryParameter('twig')] bool $twig = false
    ): JsonResponse | Response
    {
        $weather = $this->weatherUtil->getWeatherForCountryAndCity($country, $city);

        if ($format == 'csv') {
            if ($twig) {
                return $this->render('weather_api/index.csv.twig', [
                    'city' => $city,
                    'country' => $country,
                    'weather' => $weather,
                ]);
            }

            $csv = "City,Country,Date,Celsius,Fahrenheit\n".implode(
                    "\n",
                    array_map(fn(Weather $w) => sprintf(
                        '%s,%s,%s,%s,%s',
                        $city,
                        $country,
                        $w->getDate()->format('Y-m-d'),
                        $w->getTemperature(),
                        $w->getFahrenheit()
                    ), $weather)
                );

            return new Response($csv);
        }

        if ($twig) {
            return $this->render('weather_api/index.json.twig', [
                'city' => $city,
                'country' => $country,
                'weather' => $weather,
            ]);
        }

        return $this->json([
            'city' => $city,
            'country' => $country,
            'weather' => array_map(fn(Weather $w) => [
                'date' => $w->getDate()->format('Y-m-d'),
                'celsius' => $w->getTemperature(),
                'fahrenheit' => $w->getFahrenheit()
            ], $weather),
        ]);
    }
}
