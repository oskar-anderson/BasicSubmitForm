# BasicSubmitForm

Submit form with database demo. Handling recursive datastructures.

## Local installation

1. Download/`git clone` the project
2. Run `composer install` in root folder to generate dependencies and autoloading.
3. Create `.env` file from `.env_template` file and adjust parameters
4. Copy `backupCSV` folder to `site/db` and generate db with sample data `cd site/db` then `php -r "require './Initializer.php'; App\db\Initializer::Initialize();"`
5. Go back to root path and start php server by running command `php -S localhost:8080`
6. Open in browser http://127.0.0.1:8080

## Dependencies
* [phpdotenv](https://github.com/vlucas/phpdotenv) - Loads environment variables from `.env` to `getenv()`, `$_ENV` and `$_SERVER` automagically.
