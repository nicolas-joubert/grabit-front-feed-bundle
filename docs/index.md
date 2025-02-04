# Documentation

## Installation

Open a command console, enter your project directory and install it using composer:

```bash
composer require nicolas-joubert/grabit-front-feed-bundle
```

Remember to add the following line to `config/bundles.php` (not required if Symfony Flex is used)

```php
// config/bundles.php

return [
    // ...
    NicolasJoubert\GrabitFrontFeedBundle\GrabitFrontFeedBundle::class => ['all' => true],
];
```

## Configuration

### Doctrine ORM Configuration

Add these in the config mapping definition (or enable [auto_mapping](https://symfony.com/doc/current/reference/configuration/doctrine.html#mapping-configuration)):

```php
# config/packages/doctrine.yaml

doctrine:
    orm:
        mappings:
            GrabitFrontFeedBundle: ~
```

And then create the corresponding entities:

```php
// src/Entity/Feed.php

use Doctrine\ORM\Mapping as ORM;
use NicolasJoubert\GrabitFrontFeedBundle\Entity\Feed as BaseFeed;

#[ORM\Entity]
#[ORM\Table(name: 'grabit_feed')]
class Feed extends BaseFeed {}
```

Update Source entity as following:

```php
...
use NicolasJoubert\GrabitBundle\Entity\Source as BaseSource;
use NicolasJoubert\GrabitFrontFeedBundle\Entity\SourceFeedTrait as FrontFeedSourceFeedTrait;
use NicolasJoubert\GrabitFrontFeedBundle\Model\SourceInterface as FrontFeedSourceInterface;

...
class Source extends BaseSource implements FrontFeedSourceInterface
{
    use FrontFeedSourceFeedTrait;
...
```

The only thing left is to update your schema:

```bash
bin/console doctrine:schema:update --force
```

## Full configuration

```yaml
# config/packages/grabit_front_feed.yaml

grabit_front_feed:
  class:
    # Models
    feed: App\Entity\Feed
  use_cache: true
```

### Others Configuration

Add the related routing information:

```yaml
# config/routes.yaml

_grabit_front_feed:
  resource: '@GrabitFrontFeedBundle/config/routing.yaml'
```

## Basic Usage

First, follow the basic usage of [GrabitBundle](https://github.com/nicolas-joubert/grabit-bundle/blob/main/docs/index.md#basic-usage). 

Then, create a Feed:

```sql
INSERT INTO app.grabit_feed (title, slug, description)
VALUES ('my feed', 'my_feed', 'my feed description');
```

And, link it to Source using `app.grabit_feeds_sources` table

Finally, go to frontend page `/feed/my_feed.xml` and you will see the feed.
