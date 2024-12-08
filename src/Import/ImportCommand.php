<?php

namespace App\Import;

use App\Entity\Card;
use App\Entity\CardSet;
use App\Entity\Option;
use App\Enum\Card\CardAttribute;
use App\Enum\Card\CardRarity;
use App\Enum\Card\CardType;
use App\Enum\OptionCategory;
use App\Service\Cache;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Exception;
use League\Csv\InvalidArgument;
use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\SyntaxError;
use League\Csv\UnavailableStream;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(name: 'app:import', description: 'Import data from CSV file')]
class ImportCommand extends Command
{
    private const int BATCH_SIZE = 500;

    private ?SymfonyStyle $io = null;
    private Filesystem $fs;

    public function __construct(
        #[Autowire('%kernel.project_dir%/src/Import/Resources/dataset.csv')] private readonly string $filePath,
        #[Autowire('%kernel.project_dir%/src/Import/Resources/images')] private readonly string $imagesDirectory,
        #[Autowire('%kernel.project_dir%/public/uploads/cards/')] private readonly string $publicDirectory,
        private readonly EntityManagerInterface $em,
        private readonly ImportHelper $helper,
        private readonly Cache $cache,
    ) {
        $this->fs = new Filesystem();

        parent::__construct();
    }

    /**
     * @throws InvalidArgument
     * @throws \ReflectionException
     * @throws SyntaxError
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->log('Importing data ...');

        try {
            $csv = Reader::createFromPath($this->filePath);
            $csv->setHeaderOffset(0);
        } catch (UnavailableStream $e) {
            $this->error('File not found.');

            return self::FAILURE;
        } catch (Exception $e) {
            $this->error('Invalid file.');

            return self::FAILURE;
        }

        $this->helper->disableSQLLog();
        $this->helper->truncate(Card::class);
        $this->helper->truncate(CardSet::class);
        $this->helper->truncate(Option::class);

        $offset = 0;
        while (true) {
            $stmt = Statement::create()
                ->offset($offset)
                ->limit(100);

            $records = $stmt->process($csv);
            if (0 === $records->count()) {
                break;
            }

            /** @var string[] $record */
            foreach ($records as $record) {
                $this->importLine(\array_values($record));
            }

            $this->flushAndClear();
            $this->log(sprintf('... %d', $offset));

            $offset += self::BATCH_SIZE;
        }

        $this->flushAndClear();
        $this->success('Done.');

        return self::SUCCESS;
    }

    /**
     * @param string[] $data
     */
    private function importLine(array $data): void
    {
        $set = $this->importCardSet($data);
        $card = $this->importCard($data);

        $set->addCard($card);
    }

    /**
     * @param string[] $data
     */
    private function importCardSet(array $data): CardSet
    {
        $setName = \trim($data[1]);

        $set = $this->cache->findCardSet($setName);
        if (null === $set) {
            $set = new CardSet();
            $set->setName($setName);
        }

        $this->em->persist($set);
        $this->cache->register($set);

        return $set;
    }

    /**
     * @param string[] $data
     */
    private function importCard(array $data): Card
    {
        $name = \trim($data[4]);

        $card = $this->cache->findCard($name);
        if (null === $card) {
            $card = new Card();
            $card->setName($name);
        }

        $number = \trim($data[2]);
        if (!empty($number)) {
            $card->setNumber($number);
        }

        $rarity = \trim(\strtolower(\str_replace(' ', '_', $data[3])));
        if (!empty($rarity)) {
            $card->setRarity(CardRarity::from($rarity));
        }

        $otherName = \trim($data[5]);
        if (!empty($otherName)) {
            $card->setOtherName($otherName);
        }

        $type = \trim(\strtolower(\str_replace(' ', '_', $data[6])));
        if (!empty($type)) {
            $card->setType(CardType::from($type));
        }

        $attribute = \trim(\strtolower(\str_replace(' ', '_', $data[7])));
        if (!empty($attribute)) {
            $card->setAttribute(CardAttribute::from($attribute));
        }

        $types = \array_map('trim', \explode('/', $data[8]));
        $_types = [];
        foreach ($types as $type) {
            $type = \trim($type);
            $case = CardType::tryFrom($type);
            if (null !== $case && !\in_array($case, $_types)) {
                $_types[] = CardType::from($type);
            }
        }

        $card->setTypes($_types);

        $level = \trim($data[9]);
        $card->setLevel(empty($level) ? null : \intval($level));

        if (!empty($data[10])) {
            [$attack, $defense] = \array_map('trim', \explode('/', $data[10]));
            $card->setAttack(empty($attack) ? null : \intval($attack));
            $card->setDefense(empty($defense) ? null : \intval($defense));
        }

        $password = \trim($data[11]);
        if (!empty($password)) {
            $card->setPassword($password);
        }

        $statuses = \preg_split('/\s*,\s*/', \preg_replace('/[\[\]\']/', '', $data[12]) ?? '');
        if (is_array($statuses)) {
            foreach ($statuses as $status) {
                $option = $this->importCardStatus($status);
                $card->addStatus($option);
            }
        }

        // image
        $_image = $data[0];
        $exists = $this->fs->exists(\sprintf('%s/%s', $this->imagesDirectory, $_image));

        if ($exists) {
            $from = \sprintf('%s/%s', $this->imagesDirectory, $_image);
            $to = \sprintf('%s/%s', $this->publicDirectory, $_image);
            $this->fs->copy($from, $to, true);

            $card->setImageFilename($_image);
        }

        $this->em->persist($card);
        $this->cache->register($card);

        return $card;
    }

    private function importCardStatus(string $status): Option
    {
        $option = $this->cache->findOption($status, OptionCategory::CARD_STATUS);
        if (null === $option) {
            $option = new Option();
            $option->setCategory(OptionCategory::CARD_STATUS);
            $option->setLabel($status);
        }

        $this->em->persist($option);
        $this->cache->register($option);

        return $option;
    }

    // helper

    private function log(string $message): void
    {
        $this->io?->writeln($message);
    }

    private function error(string $message): void
    {
        $this->io?->error($message);
    }

    private function success(string $message): void
    {
        $this->io?->success($message);
    }

    private function flushAndClear(): void
    {
        $this->em->flush();
        $this->em->clear();
        $this->cache->clear();
        gc_collect_cycles();
    }
}
