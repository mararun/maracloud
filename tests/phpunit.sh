#!/usr/bin/env bash
DIR="$( cd "$( dirname "$0"  )" && pwd  )"
PHP=/usr/local/php-7.0.13/bin/php
INI=${DIR}/php.ini
PHPUNIT=/usr/local/php/bin/phpunit
XML=${DIR}/phpunit.xml
COMMAND="${PHP} -c ${INI} ${PHPUNIT} --stderr --configuration ${XML}"
echo ${COMMAND}
${COMMAND}
