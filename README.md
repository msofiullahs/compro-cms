<h1 align="center">Headless CMS - Compro</h1>
<div align="left">
<img src="https://laravel.com/img/logomark.min.svg" display="inline" /> <img src="https://laravel.com/img/logotype.min.svg" display="inline" />
</div>
<img src="https://jetstream.laravel.com/logo-dark.svg">
<img src="https://drive.google.com/uc?export=view&id=1e4YAIgBhrXPjcX4mxzMlsYIKqkVfIPLv" width="250" />
## Installation
- Clone this project
- `cd /path-to-this-project`
- `cp .env-example .env`
- You need to update value on `.env` file
- `php artisan key:generate`
- `composer install`
- `npm install`
- `php artisan migrate --seed`
- `php artisan db:seed`

### Development
- First terminal >> `php artisan serve`
- Second terminal >> `npm run dev`

### Production
- `npm run build`

## API
For clear documentation, you can import `api-doc.postman_collection.json` into your Postman.
