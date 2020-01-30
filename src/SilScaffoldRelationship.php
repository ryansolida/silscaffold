<?php

namespace Sil\Scaffold;

class SilScaffoldRelationship{
    function __construct($relationship_array){
        $array_keys = [
            'type',
            'model',
            'display_field',
            'field_type'
        ];
        foreach ($relationship_array as $k=>$v){
            $property = $array_keys[$k];
            $this->$property = $v;
        }

        $this->model = "\\App\\".$this->model;


        $multi_field_types = [
            'belongsToMany',
            'hasMany'
        ];
        if ( !property_exists($this,'field_type')){

            if ( in_array($this->type,$multi_field_types ) ){
                $this->field_type = 'many_select';
            } else {
                $this->field_type = 'select';
            }

            foreach ( $this->model::all() as $r){
                $this->options[$r->id] = $r;
            } 
            $this->option_display_field = $this->display_field;
        }

        $this->needs_field = TRUE;
        if ( in_array($this->type,$multi_field_types) ){
            $this->needs_field = FALSE;
        }
    }
}