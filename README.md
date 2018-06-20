# apparat/artefakt-core

[![Build Status][travis-image]][travis-url] [![Coverage Status][coveralls-image]][coveralls-url] [![Scrutinizer Code Quality][scrutinizer-image]][scrutinizer-url] [![Code Climate][codeclimate-image]][codeclimate-url] [![Documentation Status][readthedocs-image]][readthedocs-url] [![Clear architecture][clear-architecture-image]][clear-architecture-url]

> Artefakt Pattern Library Core

## Documentation

Please find the [project documentation](doc/index.md) in the `doc` directory. We recommend [reading it](http://apparat-artefakt-core.readthedocs.io/) via *Read the Docs*.

## Installation

This library requires PHP >=7.2 or later. I recommend using the latest available version of PHP as a matter of principle. It has no userland dependencies.

## Dependencies

![Composer dependency graph](https://rawgit.com/apparat/artefakt-core/master/doc/dependencies.svg)

## Quality

To run the unit tests at the command line, issue `composer install` and then `phpunit` at the package root. This requires [Composer](http://getcomposer.org/) to be available as `composer`, and [PHPUnit](http://phpunit.de/manual/) to be available as `phpunit`.

This library attempts to comply with [PSR-1][], [PSR-2][], and [PSR-4][]. If you notice compliance oversights, please send a patch via pull request.

## Contributing

Found a bug or have a feature request? [Please have a look at the known issues](https://github.com/apparat/artefakt-core/issues) first and open a new issue if necessary. Please see [contributing](CONTRIBUTING.md) and [conduct](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email joschi@kuphal.net instead of using the issue tracker.

## Credits

- [Joschi Kuphal][author-url]
- [All Contributors](../../contributors)

## License

Copyright © 2018 [Joschi Kuphal][author-url] / joschi@kuphal.net. Licensed under the terms of the [MIT license](LICENSE).


[travis-image]: https://secure.travis-ci.org/apparat/artefakt-core.svg
[travis-url]: https://travis-ci.org/apparat/artefakt-core
[coveralls-image]: https://coveralls.io/repos/apparat/artefakt-core/badge.svg?branch=master&service=github
[coveralls-url]: https://coveralls.io/github/apparat/artefakt-core?branch=master
[scrutinizer-image]: https://scrutinizer-ci.com/g/apparat/artefakt-core/badges/quality-score.png?b=master
[scrutinizer-url]: https://scrutinizer-ci.com/g/apparat/artefakt-core/?branch=master
[codeclimate-image]: https://lima.codeclimate.com/github/apparat/artefakt-core/badges/gpa.svg
[codeclimate-url]: https://lima.codeclimate.com/github/apparat/artefakt-core
[readthedocs-image]: https://readthedocs.org/projects/apparat-artefakt-core/badge/?version=latest
[readthedocs-url]: http://apparat-artefakt-core.readthedocs.io/en/latest/?badge=latest
[clear-architecture-image]: https://img.shields.io/badge/Clear%20Architecture-%E2%9C%94-brightgreen.svg
[clear-architecture-url]: https://github.com/jkphl/clear-architecture
[author-url]: https://jkphl.is
[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md
