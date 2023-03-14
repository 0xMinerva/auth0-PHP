on:
  pull_request_target:
    types:
      - labeled
  push:
    branches:
      - main

jobs:
  rector:
    name: Rector
    runs-on: ${{ matrix.operating-systems }}

    if: github.event.label.name == 'run' || github.ref_name == 'main'

    strategy:
      fail-fast: true
      max-parallel: 10
      matrix:
        operating-systems:
          - ubuntu-latest
        php-versions:
          - 8.0
          - 8.1
          - 8.2

    steps:
      - uses: shivammathur/setup-php@v2
        with:
          php-version: ${{ matrix.php }}
          coverage: none
          extensions: mbstring, openssl

      - uses: actions/checkout@v3
      - run: composer update --no-ansi --no-interaction --no-progress --prefer-dist --prefer-stable
      - run: vendor/bin/rector process src --dry-run