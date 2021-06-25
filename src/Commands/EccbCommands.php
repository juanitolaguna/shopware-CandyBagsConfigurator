<?php declare(strict_types=1);


namespace EventCandyCandyBags\Commands;


use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * @package EventCandy\LabelMe\Commands
 *
 * Create EntityFolders with Dummy Images:
 *  - bin/console eccb:utils --folders=5 --imagePrefix=candy
 *
 * Execute Tinker Code
 *  - bin/console eccb:utils --tinker=true
 */
class EccbCommands extends Command
{
    protected static $defaultName = 'eccb:utils';

    /**
     * @var EntityRepositoryInterface
     */
    private $productRepository;


    /**
     * @var Connection
     */
    private $connection;


    /**
     * @param Connection $connection
     * @param EntityRepositoryInterface $productRepository
     */
    public function __construct(Connection $connection, EntityRepositoryInterface $productRepository)
    {
        parent::__construct();
        $this->connection = $connection;
        $this->productRepository = $productRepository;
    }


    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Plugin Utils & Automation')
            ->addOption(
                'tinker',
                't',
                InputOption::VALUE_OPTIONAL,
                'Execute & Test Code',
                false)
            ->addOption(
                'uuids',
                'u',
                InputOption::VALUE_OPTIONAL,
                'Generate Uuids',
                false);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('uuids')) {
            $this->generateUuids($input, $output);
        }


        if ($input->getOption('tinker')) {
            $this->tinker($input, $output);
        }
    }


    private function tinker(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Tinker...');

        $criteria = new Criteria(['b8365eddd43e4ceaadd9ceb6442e2700', 'b8365eddd43e4ceaadd9ceb6442e2700', 'b8365eddd43e4ceaadd9ceb6442e2700']);
        $products = $this->productRepository->search($criteria, Context::createDefaultContext());

        $output->writeln(count($products));

    }

    private function generateUuids(InputInterface $input, OutputInterface $output)
    {

        $output->writeln("generate uuid's");

//        for ($i = 0; $i < $input->getOption('uuids'); $i++) {
//            $uuid = Uuid::randomHex();
//            $output->writeln($uuid);
//        }

        $uuid1 = Uuid::randomHex();
        $output->writeln($uuid1);

        $uuid2 = Uuid::randomHex();
        $output->writeln($uuid2);

        $uuid3 = Uuid::randomHex();
        $output->writeln($uuid3);

        $base = hash('md5', $uuid1 . $uuid2 . $uuid3 . $uuid3);

        $output->writeln($base);


        $uuid3 = Uuid::isValid($base);
        $output->writeln($uuid3);
    }
}
