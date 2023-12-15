# How To Deploy

### For first time only !
- `docker-compose up -d`
- `docker-compose exec php bash`
- `composer setup`

### From the second time onwards
- `docker-compose up -d`
- `docker-compose exec php bash`

# How To Access

### access web
- http://localhost

### access api with postman file on repo
- http://localhost/api

### user and password
-  `user : alice@mail.com, password : 123456`
-  `user : bob@mail.com, password : 123456`

# How To Test
- `docker-compose exec php bash`
- `php artisan test`