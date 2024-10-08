<?php

namespace App\Services;

use App\DTO\AddressDTO;
use App\DTO\CompanyDTO;
use App\Enums\KVKAddressType;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class KVKService
{
    const BASIC_PROFILE_MAIN_COMPANY_URL = 'https://api.kvk.nl/test/api/v1/basisprofielen/%s/hoofdvestiging';

    public function getCompanyDetails(string $kvk): ?CompanyDTO
    {
        try {
            $response = $this->getJsonDecodedRequest(self::BASIC_PROFILE_MAIN_COMPANY_URL, $kvk);
        } catch (ConnectionException) {
            return null;
        }

        $address = $response->adressen[0];
        $addressDTO = $this->getAddress($address);

        return new CompanyDTO(
            $response->eersteHandelsnaam,
            $response->kvkNummer,
            $addressDTO->getStreetAddress(),
            $addressDTO->getCity(),
            $addressDTO->getPostalCode(),
            $addressDTO->getCountry(),
        );
    }

    private function getAddress(object $address): ?AddressDTO
    {
        if ($address->type === KVKAddressType::CORRESPONDANCE->value) {
            $streetname = (property_exists($address, 'straatnaam'))
                ? $address->straatnaam
                : 'postbus';

            return new AddressDTO(
                sprintf('%s %s', $streetname, $address->postbusnummer),
                $address->plaats,
                $address->postcode,
                $address->land,
            );
        }

        if ($address->type === KVKAddressType::VISIT->value) {
            return new AddressDTO(
                sprintf('%s %s%s', $address->straatnaam, $address->huisnummer, $address->huisletter),
                $address->plaats,
                $address->postcode,
                $address->land,
            );
        }

        return null;
    }

    /**
     * @throws ConnectionException
     */
    private function getJsonDecodedRequest(string $url, string $kvk): mixed
    {
        $request = $this->getRequest($url, $kvk);

        if ($request->status() !== ResponseAlias::HTTP_OK) {
            throw new ConnectionException();
        }

        return json_decode($this->getRequest($url, $kvk)->body());
    }

    /**
     * @throws ConnectionException
     */
    private function getRequest(string $url, string $kvk): PromiseInterface|Response
    {
        return \Http::withHeaders([
            'apikey' => config('kvk.api_key')
        ])->get(sprintf($url, $kvk));
    }
}
