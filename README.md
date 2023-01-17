## Tech Stack

| Tech               | Version |
| ------------------ | ------- |
| PHP                | >= 8.0  |
| Laravel            | 9.xx    |
| MySQL / PostgreSQL | xxx     |

## Step to reproduce

```$ git clone https://github.com/xprbs/lci-test.git
$ cd lci-test
$ composer install && composer update
$ cp .env.example .env
$ php artisan key:generate
$ php artisan storage:link
$ php artisan jwt:secret
$ php artisan migrate
$ php artisan optimize
$ php artisan serve
```

> !! Set SMTP Configuration in .env file !!

## Documentation

Postman : https://documenter.getpostman.com/view/24061768/2s8ZDVZNis

#### Personal Information

Fullname: Pandu Prabu Trilaksono
E-Mail : panduprabu1337@gmail.com / workprabs@gmail.com
Whatsapp : (62)-851-6289-1164
