<?php
Route::group(['middleware'=>['web']],function(){
    Route::get('/admin/login','\Sil\Scaffold\SilScaffoldController@loginForm');
    Route::post('/admin/login','\Sil\Scaffold\SilScaffoldController@login');

    Route::group(['middleware'=>['sil-scaffold-middleware']],function(){
        Route::redirect('/admin/{slug}/post/','/admin/{slug}/post',307);
        Route::get('/admin','\Sil\Scaffold\SilScaffoldController@home');
        Route::get('/admin/{slug}','\Sil\Scaffold\SilScaffoldController@list');
        Route::get('/admin/{slug}/new','\Sil\Scaffold\SilScaffoldController@view');
        Route::get('/admin/{slug}/list','\Sil\Scaffold\SilScaffoldController@list');
        Route::get('/admin/{slug}/view/{id}','\Sil\Scaffold\SilScaffoldController@view');
        Route::post('/admin/{slug}/post','\Sil\Scaffold\SilScaffoldController@update');
        Route::post('/admin/{slug}/post/{id}','\Sil\Scaffold\SilScaffoldController@update');
        Route::post('/admin/{slug}/delete/{id}','\Sil\Scaffold\SilScaffoldController@delete');
        
    });
});



