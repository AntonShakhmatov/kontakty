Initial setup:
 php.ini:
  - extension=openssl (uncomment)

- Install symfony and composer

 app:
  - Inside the app, run composer update
  - In .env file, configure database connection in the DATABASE_URL line (example connection to localhost as root user, without a password: DATABASE_URL="mysql://root:@127.0.0.1:3306/symfony_kontakty?charset=utf8")
  - Start the server using the command: "symfony serve"
  - Create a database named "symfony_kontakty" in the database
  - Run the migration of fields into the database using the command: php bin/console/doctrine:migrations:migrate
  - Now you can access the app at localhost:8000.