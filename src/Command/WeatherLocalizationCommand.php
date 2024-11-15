<?php

namespace App\Command;

use App\Repository\LocalizationRepository;
use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:localization',
    description: 'Add a short description for your command',
)]
class WeatherLocalizationCommand extends Command
{
    public function __construct(private LocalizationRepository $localizationRepository, private WeatherUtil $weatherUtil)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('id', InputArgument::REQUIRED, 'The id of the localization')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $localizationId = $input->getArgument('id');
        $localization = $this->localizationRepository->findOneBy(['id' => $localizationId]);

        $weather = $this->weatherUtil->getWeatherForLocalization($localization);
        $io->writeln(sprintf('Localization: %s', $localization->getCity()));
        foreach ($weather as $w) {
            $io->writeln(sprintf("\t%s: %s", $w->getDate()->format('Y-m-d'), $w->getTemperature()));
        }

        return Command::SUCCESS;
    }
}
