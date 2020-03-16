<?php

namespace App\Command;

use App\Service\CountryService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SyncCountriesCommand extends Command
{
    /**
     * @var CountryService
     */
    private CountryService $countryService;

    public function __construct(string $name = null, CountryService $countryService)
    {
        parent::__construct($name);
        $this->countryService = $countryService;
    }

    protected static $defaultName = 'app:syncCountries';

    protected function configure()
    {
        $this
            ->setDescription('Synchronize all the cloud service endpoint restcountries.eu')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->countryService->syncCountries();

        $io->success('The command finished executing successfully!.');

        return 0;
    }
}
