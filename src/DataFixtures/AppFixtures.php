<?php

namespace App\DataFixtures;

use App\Entity\CurrencyRates;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $currencyRepo = $manager->getRepository(CurrencyRates::class);
        $firstRates   = $currencyRepo->findFirstCurrencies();

        foreach ($firstRates as $rate) {
            $baseRate = $rate->getRate();
            $date     = clone $rate->getDate();

            for ($i = 0; $i < 25; $i++) {
                $randomRate   = $rate->getCurrencyTo() === CurrencyRates::BASE_CURRENCY ? 1 : $baseRate + rand(1, 10) / 10000;
                $lastUpdate   = $date->modify('-1 day');
                $currencyRate = new CurrencyRates();

                $currencyRate->setCurrencyFrom(CurrencyRates::BASE_CURRENCY);
                $currencyRate->setCurrencyTo($rate->getCurrencyTo());
                $currencyRate->setRate($randomRate);
                $currencyRate->setDate(clone $lastUpdate);
                $manager->persist($currencyRate);
            }

            $manager->flush();
            $manager->clear();
        }
    }
}
