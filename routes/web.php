<?php

# Default route when accessing the website
Route::get('/', 'WebstoreController@index');

# The home route, which is used in the authentication scaffolding
# We update the closure argument to the index function of our controller
Route::get('/home', 'WebstoreController@index')->name('home');

# Adding a product to the shopping cart
Route::get('/add/{product}', 'WebstoreController@addToCart')->name('add');

# Removing an product from the shopping cart
Route::get('/remove/{productId}', 'WebstoreController@removeProductFromCart')->name('remove');

# Clearing all products from the shopping cart
Route::get('/empty', 'WebstoreController@destroyCart')->name('empty');

# Paygate checkout
Route::get('checkout', 'PaygateController@payThroughPaygate')->name('checkout');

# Paygate checkout
Route::match(['get', 'post'], 'payment', 'PaygateController@payment')->name('payment');

# Paygate status callback
Route::get('status', 'PaygateController@getPaymentStatus');

# Generated routes for authentication
Auth::routes();
