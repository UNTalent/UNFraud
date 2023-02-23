# UNFraud

## API documentation

### Endpoints

#### GET `/api/ratings`

Displays the list of all ratings available.

```javascript
{
   "ratings"
:
   [
      {
         "title": "Pay attention", // The title of the rating
         "description": "This source has content that may or may not be legit",
         "is_dangerous": false | true, // Whether the source is dangerous or not. Available if probability is high enough
         "level": 0.5 // A number between 0 (scam) and 1 (reliable)
      },
      ...
   ]
}
```

#### GET `/api/check/{DOMAIN|EMAIL}`

Returns the rating for an email address or a domain name (an url without path).

```javascript
{
   "is_analysed"
:
   true, // Whether the domain has an analysis or not
           "domain"
:
   {
      "host"
   :
      "unicef.org", // The extracted host
              "analysis"
   :
      { // Analysis for that domain, if is_analysed is true
         "rating"
      :
         { // The rating of the analysis
            "title"
         :
            "Official source",
                    "description"
         :
            "This address is an official source that you can trust.",
                    "is_dangerous"
         :
            false,
                    "level"
         :
            0.99
         }
      ,
         "title"
      :
         "Official UN website" // A title for the analysis
      }
   }
}
```

## Run your own version

### First installation

1. Create an environment variable called `DATABASE_URL` that includes the url of your postgres database.
   The easiest way to do it is to create a file called `.env.local` at the root of the project.
2. If your database does not exist yet, create it with the console command `php bin/console doctrine:database:create`.

### Run locally

1. Run `composer install` to install the dependencies and run the migrations automatically
2. Launch the server using the symfony client: `symfony serve`
3. Open it in your browser (usually http://127.0.0.1:8000)
