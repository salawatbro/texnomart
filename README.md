### Installation
1. Clone the repo
2. Install the requirements
```composer install```
3. Run Docker
```docker-compose up -d```
4. Run the migrations in the container
```docker-compose exec app php artisan migrate```
5. Run the seeder in the container
```docker-compose exec app php artisan db:seed```

Postman collection: [texnomart.postman_collection.json](texnomart.postman_collection.json)
