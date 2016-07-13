# S3 Change ACL

Command-line utility to batch change the ACL to for all files in an Amazon S3 bucket.

## Installation

```
composer require rsanchez/s3-change-acl
```

Or [download the phar](https://github.com/rsanchez/s3-change-acl/releases/latest).

## Usage

### Change all files to public-read ACL

```
vendor/bin/s3-change-acl public-read <bucket> <access-key> <secret-key> <region>
```

### Change all files to private ACL

```
vendor/bin/s3-change-acl private <bucket> <access-key> <secret-key> <region>
```
