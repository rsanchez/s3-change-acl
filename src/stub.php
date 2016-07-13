#!/usr/bin/env php
<?php

Phar::mapPhar('s3-change-acl.phar');

require 'phar://'.__FILE__.'/bin/s3-change-acl';

__HALT_COMPILER();