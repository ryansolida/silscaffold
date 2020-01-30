<?php
Route::get('/admin/{slug}','\Sil\Scaffold\SilScaffoldController@list');
Route::get('/admin/{slug}/new','\Sil\Scaffold\SilScaffoldController@view');
Route::get('/admin/{slug}/list','\Sil\Scaffold\SilScaffoldController@list');
Route::get('/admin/{slug}/view/{id}','\Sil\Scaffold\SilScaffoldController@view');
Route::post('/admin/{slug}/post','\Sil\Scaffold\SilScaffoldController@update');
Route::post('/admin/{slug}/post/{id}','\Sil\Scaffold\SilScaffoldController@update');
Route::post('/admin/{slug}/delete/{id}','\Sil\Scaffold\SilScaffoldController@delete');
/*
foreach (config('scaffolds') as $model_name=>$data){
    $slug = $data['slug'];
    Route::get('/admin/'.$slug,function(){
        echo "HERE";
        exit;
    });

    Route::get('/admin/'.$slug.'/create',function(){
        echo "CREATE";
        exit;
    });

    Route::get('/admin/{slug}/view/{id}','\Sil\Scaffold\SilScaffoldController@view');
}*/