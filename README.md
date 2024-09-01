
# Task 3

Task 3 pdf in repo files

## 1- Create a new Laravel Project (Laravel +8)

```bash
composer create-project laravel/laravel:^10.0 task-3
```

- Current Laravel version: 10

## 2- Use SQLite Database

- Create a `database.sqlite` file in the `database` folder.

- Update the `.env` file:

```dotenv
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=
```

## 3- Make Authentication System using Sanctum

```bash
composer require laravel/sanctum
php artisan make:controller Api/AuthController
```
- Implement the logic in the `AuthController`.

## 4- Create Tags API Resource

```bash
php artisan make:model Post -mcr
```
- Implement the logic in the `PostController`.

## 5- Create Posts API Resource

```bash
php artisan make:model Tag -mcr
```
- Implement the logic in the `TagController`.

## 6- Create a Job that Runs Daily and Force-Deletes All Softly-Deleted Posts Older than 30 Days

```bash
php artisan make:job DeleteOldSoftDeletedPosts
```

- Implement the logic in jobs folder `DeleteOldSoftDeletedPosts`.
- Schedule the task in `Kernel.php` under `Console`.

## 7- Create a Job that Runs Every Six Hours and Makes an HTTP Request

- The job will fetch results from the endpoint `https://randomuser.me/api/` and log only the results object in the response.

```bash
php artisan make:job FetchResults
```
- Implement the logic in jobs folder `FetchResults`.
- Schedule the task in `Kernel.php` under `Console`.

## 8- Make /stats API Endpoint

- Add the route:

```php
Route::get('/stats', [StatsController::class, 'index']);
```
- Implement the logic in `StatsController`.

## b. Cache the Results and Update with Every Update to the Related Models (User and Post)

```bash
php artisan make:observer UserStatsObserver --model=User
php artisan make:observer PostStatsObserver --model=Post
```
- Implement the logic in Observers.
- Register observers in `AppServiceProvider`.

## All Routes

```php

//3.a. /register endpoint that receives and validates the following:
Route::post("register",[AuthController::class,"register"]);

//3.b. /login endpoint.
Route::post("login",[AuthController::class,"login"]);

//3.f. Make an endpoint that verifies the code sent to the user.
Route::post('/verify', [AuthController::class, 'verify']);

//8- Make /stats API endpoint.
Route::get('/stats', [StatsController::class, 'index']);


Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    //4- Create tags API resource.
    Route::resource('tags',TagController::class)->only(["index","store","update","destroy"]);

    //5- Create posts API resource.
    Route::resource('posts',PostController::class);

    //5.f. Authenticated users can view their deleted posts.
    Route::get("deletedPosts",[PostController::class,"softDeletedPosts"]);
    
    //5.g. Authenticated users can restore one of their deleted posts. 
    Route::post("posts/restore/{post}",[PostController::class,"restore"]);
    
    //Route to pin posts 
    Route::post("posts/pin/{post}",[PostController::class,"pin"]);


});
// test route to test jobs logic
Route::get('/test', [StatsController::class, 'test']);

```

