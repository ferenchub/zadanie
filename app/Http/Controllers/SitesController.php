<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;

class SitesController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $response = Http::get('https://app.linkhouse.co/rekrutacja/strony');
        $requested_site = $response->json()['requested_site'];
        foreach ($response->json()['sites'] as $site) {
            if ($site['site'] == $requested_site) {
                $traffic = $site['traffic'];
                $quality = $site['quality'];
                $price = $site['price'];
//                dump($site);
            }
        }
        $array2 = [];
        foreach ($response->json()['sites'] as $sites) {
            if ($sites['price'] == $price || $sites['quality'] == $quality) {
                if ($sites['site'] != $requested_site) {
                    $array2 = Arr::prepend($array2, $sites);
                }
            }
        }
        $result = [];
        for ($i = 1; $i <= 10; $i++) {
            $reply = $this->getClosest($traffic, $array2);
            $result = Arr::prepend($result, $reply[0]);
            Arr::forget($array2, $reply[1]);
        }

        $sorted = Arr::sortRecursive($result);
        return response()->json($sorted);
//        dd($sorted);
    }

    function getClosest($search, $arr): array
    {
        $closest = null;
        foreach ($arr as $key => $value) {
            if ($closest === null || abs($search - $closest) > abs($value['traffic'] - $search)) {
                $closest = $value['traffic'];
                $new = $key;
                $arra = $value;
            }
        }
        return [$arra, $new];
    }

    public function other(): \Illuminate\Http\JsonResponse
    {
        $response = Http::get('https://app.linkhouse.co/rekrutacja/strony');
        $requested_site = $response->json()['requested_site'];
        foreach ($response->json()['sites'] as $site) {
            if ($site['site'] == $requested_site) {
                $traffic = $site['traffic'];
                $quality = $site['quality'];
                $price = $site['price'];
//                dump($site);
            }
        }
        $factor = $traffic + $quality + $price;
        $array2 = [];
        foreach ($response->json()['sites'] as $sites) {
            $factor_n = $sites['price'] + $sites['quality'] + $sites['traffic'];
            if ($sites['site'] != $requested_site) {
                $sites = Arr::prepend($sites, $factor_n);
                $array2 = Arr::prepend($array2, $sites);
            }
        }
        $result = [];
        for ($i = 1; $i <= 10; $i++) {
            $reply = $this->getClosest($factor, $array2);
            $result = Arr::prepend($result, $reply[0]);
            Arr::forget($array2, $reply[1]);
        }

        $sorted = Arr::sortRecursive($result);
        return response()->json($sorted);
//        dd($sorted);
    }

    function getClosestFactor($search, $arr): array
    {
        $closest = null;
        foreach ($arr as $key => $value) {
            if ($closest === null || abs($search - $closest) > abs($value[0] - $search)) {
                $closest = $value[0];
                $new = $key;
                $arra = $value;
            }
        }
        return [$arra, $new];
    }
}

