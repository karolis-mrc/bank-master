<?php

declare(strict_types=1);

namespace Bank\CommissionTask\Classes;

class Rate
{
    private $currency;
    private $rate;

    public function __construct(string $currency)
    {
        $this->currency = $currency;
    }

    public function getRate()
    {
        $rates_array = file_get_contents('./rates.json');
        $json_rates = json_decode($rates_array, true);

        foreach ($json_rates as $json_currency => $rate) {
            if($json_currency == $this->currency) {
                $this->rate = $rate;
            }
        }
        return $this->rate;
    }
}