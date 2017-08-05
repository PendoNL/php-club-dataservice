# Sportlink Club.Dataservice
This package contains a PHP wrapper around the Sportlink Club.Dataservice API. It aims to make working with the API a breeze.

#### Installation

Install the package using composer:
```
composer require pendonl/php-club-dataservice:dev-master
```

Require the vendor/autoload.php file:
```
require 'vendor/autoload.php';
```

Create an instance of the API using your Sportlink Club.Dataservice Client ID:
```
use \PendoNL\ClubDataservice\Api as KnvbApi;
$api = new Api('client_id');
```

It al starts by getting the club details.
```
$club = $api->getClub()
```

All properties are made public, so you are free to use `$club->clubnaam` for example. Once you get the club details you can proceed by requesting the teams and related entities.

```
$teams = $club->getTeams();
foreach($teams as $team) {

    // Get competitions
    $competitions = $team->getCompetitions();
    
    foreach($competitions as $competition) {
    
        // Fixtures
        $competition->getFixtures();
        
        // Results
        $competition->getResults();
        
        // Table standings
        $competition->table();
        
    }
    
}
```

There are still methods left to add (like getting results and fixtures from the Club entitity).

### Thanks to

- [@barryvdh](https://github.com/barryvdh)
- [KNVB Dataservice API Wrapper](https://github.com/fruitcake/php-knvb-dataservice-api) by @barryvdh

### Security

If you discover any security related issues, please email joshua@pendo.nl instead of using the issue tracker.

### About Pendo

Pendo is a webdevelopment agency based in Maastricht, Netherlands. If you'd like, you can [visit our website](https://pendo.nl).

### License

The MIT License (MIT). Please see [License File](LICENSE) for more information.