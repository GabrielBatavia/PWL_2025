<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', [PageController::class, 'index'] );


Route::get('/hello', function () { 
    return 'Hello World'; 
});


Route::get('/world', function () {
    return 'World';
});

// Route::get('/about', function () {
//     return 'Nim : 2341720184 <br> Nama : Gabriel Batavia Xaverius';
// });

Route::get('/about', [PageController::class, 'about'] );

Route::get('/user/{name}', function($name) {
    return 'Nama saya '.$name;
});

Route::get('/posts/{post}/comments/{comment}', function($postId, $commentId) {
    return 'Pos ke-'.$postId." Komentar ke-: ".$commentId;
});

// Route::get('/articles/{id}', function($id) {
//     return 'Halaman Artikel dengan ID '.$id;
// });

Route::get('/articles/{id}', [PageController::class, 'articles'] );

Route::get('/user/{name?}', function($name='Jhon') {
    return 'Nama saya '.$name;
});

// - Route Name
// Route::get('/user/profile', function () {
//     //
// }) -> name ('profile');

// Route::get(
//     '/user/profile',
//     [UserProfileController::class, 'show']
// ) ->name('profile');

// // Generating URLs
// $url = route('profile');

// // Generating Redirects
// return redirect()->route('profile');




// - Route Group dan Route Prefixes 
// Route::middleware(['first', 'second'])->group(function () { 
//     Route::get('/', function () { 
//         // Uses first & second middleware... 
//     }); 
 
// Route::get('/user/profile', function () { 
//         // Uses first & second middleware... 
//     }); 
// }); 
 
// Route::domain('{account}.example.com')->group(function () { 
//     Route::get('user/{id}', function ($account, $id) { 
//         // 
//     }); 
// }); 
 
// Route::middleware('auth')->group(function () { 
//  Route::get('/user', [UserController::class, 'index']); 
//  Route::get('/post', [PostController::class, 'index']); 
//  Route::get('/event', [EventController::class, 'index']); 
  
// }); 


// - Route Prefixes 
// Route::prefix('admin')->group(function () { 
//     Route::get('/user', [UserController::class, 'index']); 
//     Route::get('/post', [PostController::class, 'index']); 
//     Route::get('/event', [EventController::class, 'index']); 
    
//    }); 

//  - Redirect Routes 
// Route::redirect('/here', '/there'); 

// - View Routes
// Route::view('/welcome', 'welcome'); 
// Route::view('/welcome', 'welcome', ['name' => 'Taylor']); 


Route::get('/hello', [WelcomeController::class,'hello']);


Route::resource('photos', PhotoController::class);

Route::resource('photos', PhotoController::class)->only([ 
    'index', 'show' 
    ]); 
    Route::resource('photos', PhotoController::class)->except([ 
    'create', 'store', 'update', 'destroy' 
    ]);


// Route::get('/greeting', function () { 
//     return view('blog.hello', ['name' => 'Andi']); 
// }); 

Route::get('/greeting', [WelcomeController::class, 'greeting']);