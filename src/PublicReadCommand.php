<?php

namespace S3ChangeAcl;

class PublicReadCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function getAcl()
    {
        return 'public-read';
    }
}
