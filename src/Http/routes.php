<?php

Route::get('/social/redirect/{provider}', [
    'as'   => 'social.redirect',
    'uses' => 'AuthController@getRedirect'
]);
Route::get('/social/handle/{provider}', [
    'as'   => 'social.handle',
    'uses' => 'AuthController@getAuthLink'
]);
