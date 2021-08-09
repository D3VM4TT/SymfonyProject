<?php


namespace App\Command;


use App\Service\Greeting;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class HelloCommand
 * @package App\Command
 */
class HelloCommand extends Command
{

    /**
     * @var string
     */
    protected static $defaultName = 'app:say-hello';

    /**
     * @var Greeting
     */
    protected $greeting;


    /**
     * HelloCommand constructor.
     * @param Greeting $greeting
     */
    public function __construct(Greeting $greeting)
    {
        $this->greeting = $greeting;
        parent::__construct();
    }


    /**
     *
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Outputs a polite greeting to the console')
            ->addArgument('name', InputArgument::REQUIRED, 'Name of the person being greeted');

    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $greeting_msg = $this->greeting->greet($input->getArgument('name'));

        $output->writeln([
           'This is a message from your custom command',
           '==========================================',
           "$greeting_msg"
        ]);

        return Command::SUCCESS;
    }


}