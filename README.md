# Person Overview

Package contain migrations, seeds and models for Person (HR). Documentation will be written in wiki.

# Installation

composer.json:
```
	"thunderid/person": "dev-master"
```

run
```
	composer update
```

```
	composer dump-autoload
```

# Usage

migration
```
  php artisan migrate --path=vendor/thunderid/person/src/migrations
```

seed
```
  php artisan db:seed --clsas=ThunderID\\Person\\seeds\\DatabaseSeeder
```

