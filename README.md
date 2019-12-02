<p align="center"><img src="https://i.ibb.co/YZ0tGTX/Screenshot-8.png" width="100%"></p>

<p align="center"><img src="https://i.ibb.co/xsgJdqw/Screenshot-6.png" width="100%"></p>



# VivaStreet scraping


Live Demo: http://obscure-journey-81618.herokuapp.com/cars 


## Getting Started

These instructions will get you a copy of the project

### Prerequisites

```
Composer, Xampp (PHP 7.3, Aapache, MySql)
```

### Installing


```
composer install
```

```
cp .env.example .env
```
```
php artisan key:generate
```
```
php artisan migrate
```
```
php artisan serve
```

Check it out http://localhost:8000/cars

## How it works
Scraping only available on cars category - https://search.vivastreet.co.uk/cars/gb

1. Validate submitted link to match this regex: 
```$xslt
^(https|http):\/\/search\.vivastreet\.co\.uk\/cars\/gb(\?.*)?$
```

2. Fetching main list of cars on sale 

3. Fetching all individual pages of cars in order to get more info

4. Fetching images and other data

5. Storing info about car in database

6. Storing resources in storage.

7. Creating blade file and pupulating it with data

I had to put images directly into the public folder, because heroku doesn't support symlinks



