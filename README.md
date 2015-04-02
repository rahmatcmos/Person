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
<<<<<<< d689855d309cf12ac9bf162d72d0fb45051477bc
  php artisan db:seed --clsas=ThunderID\\Person\\seeds\\DatabaseSeeder
=======
  php artisan db:seed --class=ThunderID\\Person\\seeds\\DatabaseSeeder
>>>>>>> dc42ed836666958c13a790f8b088b2a2b39fe13e
```

