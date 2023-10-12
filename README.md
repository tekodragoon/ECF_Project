## QUAI ANTIQUE ##

* * *

### Description ###

This project is a website for a fake restaurant.<br>
It is a evaluation project for the STUDI Graduate Developer PHP/Symfony course.<br>
I made this project with Symfony 6.2.6.

* * *

### Installation ###

* Clone this project
* Install dependencies with `composer install`
* Install npm dependencies with `npm install`
* Create a `.env.local` file at the root of the project.
* Fill the `.env.local` file with your database credentials.<br>
* Add your credentials for the `MAILER_DSN` variable - i use Sendgrid.<br>
* Add your credentials for the `MAILER_SENDER` variable.
* Update the `app.contact.email` parameter inside `services.yaml`.
* Create the database with `php bin/console doctrine:database:create`
* Create the tables with `php bin/console doctrine:migrations:migrate`
* Load the fixtures with `php bin/console doctrine:fixtures:load`
* Run the server with `symfony server:start`
* Run "npm run watch" to start webpack-encore.
* Run "php bin/console messenger:consume liip_imagine --time-limit=3600 --memory-limit=256M"
  for running liip-imagine worker.
* Go to `localhost:8000` in your browser
* Enjoy !

* * *

### License ###

This project is under the GNU GPL v3 license.
