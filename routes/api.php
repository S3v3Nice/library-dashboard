<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(
    function () {
        Route::get('/users', [\App\Http\Controllers\UserController::class, 'getUsers']);
        Route::post('/user', [\App\Http\Controllers\UserController::class, 'addUser']);
        Route::put('/user/{user}', [\App\Http\Controllers\UserController::class, 'updateUser']);
        Route::delete('/user/{user}', [\App\Http\Controllers\UserController::class, 'deleteUser']);

        Route::get('/authors', [\App\Http\Controllers\BookAuthorController::class, 'getAuthors']);
        Route::post('/author', [\App\Http\Controllers\BookAuthorController::class, 'addAuthor']);
        Route::put('/author/{author}', [\App\Http\Controllers\BookAuthorController::class, 'updateAuthor']);
        Route::delete('/author/{author}', [\App\Http\Controllers\BookAuthorController::class, 'deleteAuthor']);

        Route::get('/genres', [\App\Http\Controllers\BookGenreController::class, 'getGenres']);
        Route::post('/genre', [\App\Http\Controllers\BookGenreController::class, 'addGenre']);
        Route::put('/genre/{genre}', [\App\Http\Controllers\BookGenreController::class, 'updateGenre']);
        Route::delete('/genre/{genre}', [\App\Http\Controllers\BookGenreController::class, 'deleteGenre']);

        Route::get('/books', [\App\Http\Controllers\BookController::class, 'getBooks']);
        Route::post('/book', [\App\Http\Controllers\BookController::class, 'addBook']);
        Route::put('/book/{book}', [\App\Http\Controllers\BookController::class, 'updateBook']);
        Route::delete('/book/{book}', [\App\Http\Controllers\BookController::class, 'deleteBook']);

        Route::get('/job-positions', [\App\Http\Controllers\JobPositionController::class, 'getJobPositions']);
        Route::post('/job-position', [\App\Http\Controllers\JobPositionController::class, 'addJobPosition']);
        Route::put('/job-position/{jobPosition}', [\App\Http\Controllers\JobPositionController::class, 'updateJobPosition']);
        Route::delete('/job-position/{jobPosition}', [\App\Http\Controllers\JobPositionController::class, 'deleteJobPosition']);

        Route::get('/employees', [\App\Http\Controllers\EmployeeController::class, 'getEmployees']);
        Route::post('/employee', [\App\Http\Controllers\EmployeeController::class, 'addEmployee']);
        Route::put('/employee/{employee}', [\App\Http\Controllers\EmployeeController::class, 'updateEmployee']);
        Route::delete('/employee/{employee}', [\App\Http\Controllers\EmployeeController::class, 'deleteEmployee']);

        Route::get('/readers', [\App\Http\Controllers\ReaderController::class, 'getReaders']);
        Route::post('/reader', [\App\Http\Controllers\ReaderController::class, 'addReader']);
        Route::put('/reader/{reader}', [\App\Http\Controllers\ReaderController::class, 'updateReader']);
        Route::delete('/reader/{reader}', [\App\Http\Controllers\ReaderController::class, 'deleteReader']);
    }
);
