<?php


namespace AppBundle\Command;


use AppBundle\Controller\DefaultController;
use League\Csv\Reader;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ReadCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('read:file')
            ->setDescription('Reads a file from the system')
            ->addArgument(
                'filename',
                InputArgument::REQUIRED,
                'File name '
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $filename = $input->getArgument('filename');
        $path = '%kernel.rood.dir%/../src/AppBundle/Data/' . $filename;

        // check if the file exists before performing an operation
        // used this function because file_exists() does not work for some reason
        if (file_get_contents($path)) {

            // we need to create the kernel because we need to set the container manually
            // to the controller as it is not defined in a configuration file.
            $kernel = new \AppKernel('dev', true);
            $kernel->loadClassCache();
            $kernel->boot();

            $io->title('Importing CSV data from ' . $filename);
            $defaultController = new DefaultController();
            $defaultController->setContainer($kernel->getContainer());
            $defaultController->importDataFromFile($path);

        } else {
            $io->error("File not found");
        }


      $io->success('Everything went well');
    }
}