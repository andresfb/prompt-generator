# Prompt Generator
Randomly generated prompts to use in creative writing.

## Preparation

### After cloning this repo clone the database files:
```sh
cd prompt-generator
git clone git@github.com:andresfb/promptgendata.git storage/app/public/promptgendata
```
**Note:** The above-listed Git repository is private as it contains copyrighted data.

### Create the main User:
```sh
php artisan create:user
```
### Generate an API token for the User:
```sh
php artisan create:token
```
### Refresh the Movie Info database for the Mashups from the Meilisearch index (from a separate project)
```sh
php artisan refresh:mashup
```
### Import all the Prompt datasets
```sh
php artisan import:data -a
```
