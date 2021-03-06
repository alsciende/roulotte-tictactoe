<?php

namespace App\Command;

use App\Entity\Game;
use App\Message\CreateGameMessage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

#[AsCommand(
    name: 'app:games:create',
    description: 'Create a new game',
)]
class GamesCreateCommand extends Command
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Name of the game', 'new_game')
            ->addOption('min', null, InputOption::VALUE_REQUIRED, 'Min number of players', 0)
            ->addOption('max', null, InputOption::VALUE_REQUIRED, 'Max number of players', 10)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $name = $input->getArgument('name');
        if (false === is_string($name)) {
            throw new \UnexpectedValueException("Expected string for argument 'name', got " . gettype($name));
        }


        $min = $input->getOption('min');
        if (false === is_int($min)) {
            throw new \UnexpectedValueException("Expected int for option 'min', got " . gettype($name));
        }

        $max = $input->getOption('max');
        if (false === is_int($max)) {
            throw new \UnexpectedValueException("Expected int for option 'max', got " . gettype($name));
        }

        $message = new CreateGameMessage($name, $min, $max);

        /** @var HandledStamp $stamp */
        $stamp = $this->bus->dispatch($message)->last(HandledStamp::class);
        /** @var Game $game */
        $game = $stamp->getResult();

        $io->success('Successfully created game ' . $game->getId());

        return Command::SUCCESS;
    }
}
