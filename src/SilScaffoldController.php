<?php

namespace Sil\Scaffold;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class SilScaffoldController extends \App\Http\Controllers\Controller
{
    
    function __construct(){
        Inertia::setRootView('silscaffold::admin');
    }

    public function list($slug,Request $request){
        $model = SilScaffold::getModelPathFromSlug($slug);
        $items = $model::all();
        Inertia::setRootView('silscaffold::admin');
        return Inertia::render('RecordList', [
            'items'=>$items,
            'slug'=>$slug,
            'scaffold'=>new SilScaffold($slug)
        ]);
    }

    public function view($slug,$id=FALSE,Request $request){
        $model = "\\App\\".SilScaffold::getScaffoldFromSlug($slug)['model'];
        $scaffold = new SilScaffold($slug);

        $fields = SilScaffold::getFields($slug);

        if ( $id ){
            $item = $model::find($id);
        } else {
            $item = new $model;
            foreach ($fields as $f){
                $item->{$f->name} = '';
            }
        }
        
        foreach ($fields as $f){
            if ( $f->relationship ){
                if ( $f->relationship->type == 'belongsTo' ){
                    $item->{$f->name} = $item->{$f->name.'_id'};
                    foreach ($f->options as $option_key=>$option_value){
                        $f->options[$option_key] = $option_value->{$f->relationship->display_field};
                    }
                }
                if ( $f->relationship->type == 'belongsToMany' ){
                    $item->{$f->name} = $item->{$f->name};
                }
            }

            if ( $f->json ){
                $item->{$f->name} = json_decode($item->{$f->name});
            }
        }

        

        return Inertia::render('RecordView',[
            'item'=>$item,
            'slug'=>$slug,
            'scaffold'=>$scaffold,
            'fields'=>$fields
        ]);
    }

    public function update($slug,$id=FALSE,Request $request){
        $scaffold = new SilScaffold($slug);
        
        $model = "\\App\\".SilScaffold::getScaffoldFromSlug($slug)['model'];

        $fields = SilScaffold::getFields($slug);

        if ( $id ){
            $record = $model::find($id);
        } else {
            $record = new $model;
        }

        if ( $record ){
            foreach ($fields as $field){
                if ( $request->has($field->name) ){

                    $field_data = $request->{$field->name};
                    $field_name = $field->name;

                    if ( $field->relationship ){
                        $model = $field->relationship->model;
                        $to_attach = $model::find($field_data);
                        if ($field->relationship->needs_field){
                            //$rel_field_string = $field->name.'()';
                            //dd($rel_field_string);
                            $record->$field_name()->associate($to_attach);
                        } else {
                            if ( is_array($request->$field_name) ){
                                $record->$field_name()->detach(); //detach all and reattach
                                foreach ( $request->$field_name as $field_value){
                                    $to_attach = $model::find($field_value['id']);
                                    if (!$to_attach){
                                        $to_attach = new $model();
                                        $to_attach->{$field->relationship->display_field} = $field_value;
                                        $to_attach->save();
                                    }
                                    $record->$field_name()->attach($to_attach);    
                                }
                            } else {
                                $record->$field_name()->attach($to_attach);
                            }
                        }
                    } else {
                        if ( is_array($request->$field_name) ){
                            $record->$field_name = json_encode($request->$field_name);
                        } else {
                            $record->$field_name = $request->$field_name;
                        }
                    }
                }
            }
            $record->save();
        }

        return Redirect('/admin/'.$slug.'/list');
    }

    public function delete($slug,$id,Request $request){
        $model = "\\App\\".SilScaffold::getScaffoldFromSlug($slug)['model'];
        $item = $model::find($id);
        $item->delete();
        return Redirect('/admin/'.$slug.'/list');
    }
}
