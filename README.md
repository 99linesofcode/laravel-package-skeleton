# laravel-package-skeleton

Domain Driven Laravel Package Development skeleton project for modular Laravel application development.

Based on <https://github.com/spatie/laravel-package-tools>, this starter ships with Testbench, Pest, PHPStan, Rector, Livewire and Filament with the appropriate and required configuration necessary to develop and test from within the package/module itself.

## How to use

This repository serves as a starting point to a modular approach to app development according to Domain Driven Design and Hexagonal Architecture principles. To get started:

Run `/instantiate` from an empty package directory to do all of this for you. By hand:

1. Create your package directory and run `git init`;
1. Add the skeleton as a remote named after it: `git remote add laravel-package-skeleton git@github.com:99linesofcode/laravel-package-skeleton.git`;
1. Fetch it: `git fetch laravel-package-skeleton`;
1. Merge it: `git merge --allow-unrelated-histories laravel-package-skeleton/main`.

Updates are a plain `git fetch laravel-package-skeleton && git merge laravel-package-skeleton/main`: merge, never rebase or squash (git-skeleton's ADR 0001).

If you have access to the Nix package manager you can initialize a local development environment like so:

1. Initialize the `devshell` submodule that is included by default using `git submodule update --init --recursive`;
1. Run `echo use flake ./devshell >> .envrc`;
1. Run `direnv allow`.

## Using the package in an application

In production the host application requires the package by version constraint. During development, point the host at your local checkout with a Composer path repository and `"symlink": true`, so changes show up without a release.

The package owns no database: it ships migrations, and the host application's database holds its tables. Its own tests and the workbench run against a throwaway SQLite database that `composer prepare` creates.

## Contributing

Please review the [Contribution Guidelines](https://github.com/99linesofcode/.github/blob/main/.github/CONTRIBUTING.md).

## Code of conduct

In order to ensure that the community is welcoming to all, please review and abide by the [Code of Conduct](https://github.com/99linesofcode/.github?tab=coc-ov-file).

## Security vulnerabilities

Please review the [security policy](https://github.com/99linesofcode/.github?tab=security-ov-file) on how to report security vulnerabilities.

## License

This software is open source and licensed under the [MIT license](https://github.com/99linesofcode/.github?tab=MIT-1-ov-file).
