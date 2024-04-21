<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\CurrencyRates;
use App\Service\AnyApi;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:fetch-and-store-rates',
    description: 'Add a short description for your command',
)]
class FetchAndStoreRatesCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private AnyApi                 $api
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Fetching rates");

        try {
            $rates = $this->api->getRates(CurrencyRates::BASE_CURRENCY);
            $date  = new \DateTime('@' . $rates['lastUpdate']);

            foreach ($rates['rates'] as $to => $value) {
                $newRate = new CurrencyRates();
                $newRate
                    ->setCurrencyFrom(CurrencyRates::BASE_CURRENCY)
                    ->setCurrencyTo($to)
                    ->setRate($value)
                    ->setDate($date)
                ;

                $this->em->persist($newRate);
            }

            $this->em->flush();
            $output->writeln("Rates imported");

            return true;
        } catch (\Exception $e) {
            $output->writeln("Rates import failed at: " . date('Y-m-d H:i:s') . " - " . $e->getMessage());

            return false;
        }
    }
}
