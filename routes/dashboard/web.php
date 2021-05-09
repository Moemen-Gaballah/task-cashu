<?php 

Route::group(
[
	'prefix' => LaravelLocalization::setLocale(),
	'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
], 
function(){ 

	Route::prefix('dashboard')->name('dashboard.')->middleware(['auth', 'verified'])->group(function() {
		Route::get('/', 'WelcomeController@index')->name('welcome');
		

		// Route::get('/test', function(){
		// 	echo LaravelLocalization::getCurrentLocale();
		// });

		// Sales routes
		Route::resource('sales', 'SalesController')->except(['show']);

	
		// user routes
		Route::resource('users', 'UserController')->except(['show']);



	}); // end of dashboard routes

});

Route::get('/', function (){
	return redirect()->route('dashboard.welcome');
});


