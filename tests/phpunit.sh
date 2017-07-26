#!/usr/bin/env bash
DIR="$( cd "$( dirname "$0"  )" && pwd  )"
PHP=/usr/local/php-7.0.13/bin/php
PHPUNIT=/usr/local/php/bin/phpunit
XML=${DIR}/phpunit.xml
COMMAND="${PHP} ${PHPUNIT} --configuration ${XML}"
echo ${COMMAND}
${COMMAND}
