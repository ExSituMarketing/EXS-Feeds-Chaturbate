<?php

namespace EXS\FeedsChaturbateBundle\Command;

use EXS\FeedsChaturbateBundle\Service\FeedsReader;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class RefreshLivePerformersCommand
 *
 * @package EXS\FeedsChaturbateBundle\Command
 */
class RefreshLivePerformersCommand extends ContainerAwareCommand
{
    /**
     * @var SymfonyStyle
     */
    private $style;

    /**
     * @var int
     */
    private $ttl;

    /**
     * @var FeedsReader
     */
    private $reader;

    /**
     * {@inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('feeds:chaturbate:refresh-live-performers')
            ->setDescription('Reads Chaturbate api and refreshes live performer information in memcached.')
            ->addOption('ttl', null, InputOption::VALUE_OPTIONAL, 'Memcached entry\'s time to live.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->style = new SymfonyStyle($input, $output);

        if (null === $this->ttl = $input->getOption('ttl')) {
            $this->ttl = $this->getContainer()->getParameter('exs_feeds_chaturbate.cache_ttl');
        }

        $this->reader = $this->getContainer()->get('exs_feeds_chaturbate.feeds_reader');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $performers = $this->reader->refreshLivePerformers($this->ttl);

        if (0 < count($performers)) {
            $this->style->block([sprintf('Cache refreshed with %d performers.', count($performers))], null, 'info');
        } else {
            $this->style->block(['Impossible to get performers information.', 'Cache not refreshed.'], null, 'error');
        }
    }
}
