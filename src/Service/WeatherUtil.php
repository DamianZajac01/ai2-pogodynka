<?php

namespace App\Service;

use App\Entity\Localization;
use App\Entity\Weather;
use App\Repository\LocalizationRepository;
use App\Repository\WeatherRepository;

class WeatherUtil
{
    public function __construct(private LocalizationRepository $localizationRepository, private WeatherRepository $weatherRepository)
    {
    }

    /**
     * @return Weather[]
     */
    public function getWeatherForLocalization(Localization $localization): array
    {
        return $this->weatherRepository->findByLocalization($localization);
    }

    /**
     * @return Weather[]
     */
    public function getWeatherForCountryAndCity(string $countryCode, string $city): array
    {
        $localization = $this->localizationRepository->findByCountryAndCity($countryCode, $city);

        return $this->weatherRepository->findByLocalization($localization);
    }
}