# UNFraud

## Run your own version

### First installation

1. Create an environment variable called `DATABASE_URL` that includes the url of your postgres database.
   The easiest way to do it is to create a file called `.env.local` at the root of the project.
2. If your database does not exist yet, create it with the console command `php bin/console doctrine:database:create`.

### Run locally

1. Run `composer install` to install the last packages and install dependencies automatically
2. Launch the server using the symfony client: `symfony serve`
3. Open it in your browser (usually http://127.0.0.1:8000)
