# WonderNetwork Coding Standard for PHP

WonderNetwork uses [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer/) to keep our code pretty. [PSR-2](http://www.php-fig.org/psr/psr-2/) is the base of our standard, with a few extras thrown in. You can see
what we've added and removed in the [`ruleset.xml`](WonderNetwork/ruleset.xml), and check out our customizations in [`Sniffs`](WonderNetwork/Sniffs).

_(The astute may notice that the code in this repo doesn't conform to our own standard. We don't care.)_

## How to make your code look like our code

1. Add `wondersniffer` to your `composer.json`:

  ```{json}
  {
    "repositories": [
      {
        "url": "https://github.com/WonderNetwork/wondersniffer",
        "type": "git"
      }
    ],
    "require-dev": {
      "wondernetwork/wondersniffer": "dev-master"
    }
  }
  ```
2. Update `composer`:

  ```
  $ composer update
  ```

3. Add the WonderNetwork standard to your `ruleset.xml`:

  ```
  <?xml version="1.0"?>
  <ruleset>
      <rule ref="vendor/wondernetwork/wondersniffer/WonderNetwork" />
  </ruleset>
  ```

4. Run the sniffer against your codebase:

  ```
  $ ./vendor/bin/phpcs --standard=ruleset.xml your-source-code
  ```

## Credits

- [MediaWiki](https://github.com/wikimedia/mediawiki-tools-codesniffer) for (unwittingly!) providing the scaffolding for a customized standard.
- [CakePHP](https://github.com/cakephp/cakephp-codesniffer) for providing the template MediaWiki used!
