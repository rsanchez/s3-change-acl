<?php

namespace S3ChangeAcl;

class PrivateCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function getAcl()
    {
        return 'private';
    }
}
