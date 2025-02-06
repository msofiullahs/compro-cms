<h1 align="center">Headless CMS - Compro</h1>
<table>
    <tr>
        <td valign="center">
            <div align="left">
                <img src="https://laravel.com/img/logomark.min.svg" display="inline" style="margin-right 5px" /> <img src="https://laravel.com/img/logotype.min.svg" display="inline" />
            </div>
        </td>
        <td valign="center">
            <img src="https://jetstream.laravel.com/logo-dark.svg">
        </td>
        <td valign="center">
            <img src="https://drive.google.com/uc?export=view&id=1e4YAIgBhrXPjcX4mxzMlsYIKqkVfIPLv" width="250" />
        </td>
    </tr>
</table>

## Installation
- Clone this project
- `cd /path-to-this-project`
- `cp .env-example .env`
- You need to update value on `.env` file
- `php artisan key:generate`
- `composer install`
- `npm install`
- `php artisan migrate --seed`

### Development
- First terminal >> `php artisan serve`
- Second terminal >> `npm run dev`

### Production
- `npm run build`

## API
For clear documentation, you can import `api-doc.postman_collection.json` into your Postman.
