<?php

namespace S3ChangeAcl;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Aws\S3\S3Client;

abstract class AbstractCommand extends Command
{
    /**
     * Get the canned ACL string
     * @return string
     */
    abstract protected function getAcl();

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName($this->getAcl());
        $this->setDescription(sprintf('Set ACL of all files in the specified bucket to %s.', $this->getAcl()));
        $this->addArgument('bucket', InputArgument::REQUIRED, 'S3 Bucket');
        $this->addArgument('access-key', InputArgument::OPTIONAL, 'AWS Access Key');
        $this->addArgument('secret-key', InputArgument::OPTIONAL, 'AWS Secret Key');
        $this->addArgument('region', InputArgument::OPTIONAL, 'S3 Region', 'us-east-1');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getArgument('access-key')) {
            putenv('AWS_ACCESS_KEY_ID='.$input->getArgument('access-key'));
        }

        if ($input->getArgument('secret-key')) {
            putenv('AWS_SECRET_ACCESS_KEY='.$input->getArgument('secret-key'));
        }

        $client = new S3Client([
            'version' => 'latest',
            'region'  => $input->getArgument('region'),
        ]);

        $result = $client->listObjects([
            'Bucket' => $input->getArgument('bucket'),
        ]);

        $total = count($result['Contents']);

        while ($total > 0) {
            $output->writeln(sprintf('<info>Updating next %d files</info>', $total));

            foreach ($result['Contents'] as $i => $object) {
                $output->writeln(sprintf('<info>Updating %d of %d: %s</info>', $i + 1, $total, $object['Key']));

                $result = $client->putObjectAcl([
                    'ACL' => $this->getAcl(),
                    'Bucket' => $input->getArgument('bucket'),
                    'Key' => $object['Key'],
                ]);
            }

            $result = $client->listObjects([
                'Bucket' => $input->getArgument('bucket'),
                'Marker' => $object['Key'],
            ]);

            $total = count($result['Contents']);
        }

        $output->writeln('<info>Done!</info>');
    }
}
