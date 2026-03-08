<?php

namespace App\Presentation\Http\Rest\Geo;

use App\Application\Geo\Query\GetAllCountriesQuery;
use App\Application\Geo\Query\GetCountryByIdQuery;
use App\Application\Geo\Query\GetRegionsByCountryQuery;
use App\Application\Geo\Query\GetRegionByIdQuery;
use App\Application\Geo\Query\GetStatesByRegionQuery;
use App\Application\Geo\Query\GetStateByIdQuery;
use App\Application\Geo\Query\GetDistrictsByStateQuery;
use App\Application\Geo\Query\GetDistrictByIdQuery;
use App\Application\Geo\Query\GetCitiesByDistrictQuery;
use App\Application\Geo\Query\GetCityByIdQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1')]
class GeoController extends AbstractController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    #[Route('/countries', name: 'geo_countries_list', methods: ['GET'])]
    public function listCountries(): JsonResponse
    {
        $countries = $this->handle(new GetAllCountriesQuery());
        return $this->json($countries);
    }

    #[Route('/countries/{countryId}', name: 'geo_country_show', methods: ['GET'])]
    public function showCountry(int $countryId): JsonResponse
    {
        $country = $this->handle(new GetCountryByIdQuery($countryId));
        return $this->json($country);
    }

    #[Route('/countries/{countryId}/regions', name: 'geo_regions_list', methods: ['GET'])]
    public function listRegions(int $countryId): JsonResponse
    {
        $regions = $this->handle(new GetRegionsByCountryQuery($countryId));
        return $this->json($regions);
    }

    #[Route('/countries/{countryId}/regions/{regionId}', name: 'geo_region_show', methods: ['GET'])]
    public function showRegion(int $countryId, int $regionId): JsonResponse
    {
        $region = $this->handle(new GetRegionByIdQuery($countryId, $regionId));
        return $this->json($region);
    }

    #[Route('/countries/{countryId}/regions/{regionId}/states', name: 'geo_states_list', methods: ['GET'])]
    public function listStates(int $countryId, int $regionId): JsonResponse
    {
        $states = $this->handle(new GetStatesByRegionQuery($countryId, $regionId));
        return $this->json($states);
    }

    #[Route('/countries/{countryId}/regions/{regionId}/states/{stateId}', name: 'geo_state_show', methods: ['GET'])]
    public function showState(int $countryId, int $regionId, int $stateId): JsonResponse
    {
        $state = $this->handle(new GetStateByIdQuery($countryId, $regionId, $stateId));
        return $this->json($state);
    }

    #[Route('/countries/{countryId}/regions/{regionId}/states/{stateId}/districts', name: 'geo_districts_list', methods: ['GET'])]
    public function listDistricts(int $countryId, int $regionId, int $stateId): JsonResponse
    {
        $districts = $this->handle(new GetDistrictsByStateQuery($countryId, $regionId, $stateId));
        return $this->json($districts);
    }

    #[Route('/countries/{countryId}/regions/{regionId}/states/{stateId}/districts/{districtId}', name: 'geo_district_show', methods: ['GET'])]
    public function showDistrict(int $countryId, int $regionId, int $stateId, int $districtId): JsonResponse
    {
        $district = $this->handle(new GetDistrictByIdQuery($countryId, $regionId, $stateId, $districtId));
        return $this->json($district);
    }

    #[Route('/countries/{countryId}/regions/{regionId}/states/{stateId}/districts/{districtId}/cities', name: 'geo_cities_list', methods: ['GET'])]
    public function listCities(int $countryId, int $regionId, int $stateId, int $districtId): JsonResponse
    {
        $cities = $this->handle(new GetCitiesByDistrictQuery($countryId, $regionId, $stateId, $districtId));
        return $this->json($cities);
    }

    #[Route('/countries/{countryId}/regions/{regionId}/states/{stateId}/districts/{districtId}/cities/{cityId}', name: 'geo_city_show', methods: ['GET'])]
    public function showCity(int $countryId, int $regionId, int $stateId, int $districtId, int $cityId): JsonResponse
    {
        $city = $this->handle(new GetCityByIdQuery($countryId, $regionId, $stateId, $districtId, $cityId));
        return $this->json($city);
    }
}
