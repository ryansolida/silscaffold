<?php
namespace Sil\Scaffold;
class SilScaffold {

    function __construct($slug){
        $scaffold = $this->getScaffoldFromSlug($slug);
        
        foreach ($scaffold as $k=>$v){
            $this->$k = $v;
        }
    }

    public static function getScaffolds(){
        $scaffolds = config('scaffolds');
        foreach ($scaffolds as $key=>$scaffold){
            if ( !@$scaffold['slug'] ){
                $scaffolds[$key]['slug'] = $key; //default to the slug being the key
            }

            if ( !@$scaffold['table'] ){
                $scaffolds[$key]['table'] = $key; //default to the table being the key
            }

            if ( !@$scaffold['model'] ){
                $scaffolds[$key]['model'] = str_replace(' ','',\Str::singular(ucwords(str_replace('-',' ',$key))));
            }

            if ( !@$scaffold['display_name'] ){
                $scaffolds[$key]['display_name'] = ucwords(str_replace('-',' ',$key));
            }

            if ( !@$scaffold['display_name_plural'] ){
                $scaffolds[$key]['display_name_plural'] = ucwords(str_replace('-',' ',$key));
            }
        }
        return $scaffolds;
    }

    public static function getScaffoldFromSlug($slug){
        $scaffolds = self::getScaffolds();
        foreach ( self::getScaffolds() as $model_name=>$data){
            if ( $data['slug'] == $slug ){
                return $data;
            }
        }
    }

    public static function getModelPathFromSlug($slug){
        $scaffold = self::getScaffoldFromSlug($slug);
        return '\\App\\'.$scaffold['model'];
    }

    public static function getScaffoldFromModelName($model_name){
        $model_name = str_replace("App\\",'',$model_name);
        foreach ( self::getScaffolds() as $data){
            if ( $data['model'] == $model_name ){
                return $data;
            }
        }
    }

    public static function getScaffoldRelationships($slug){
        $scaffold = SilScaffold::getScaffoldFromSlug($slug);
        $return_relationships = [];
        if ( @!$scaffold['relationships'] ){
            return [];
        }
        foreach ($scaffold['relationships'] as $name=>$relationship){
            $return_relationships[$name] = [
                'type'=>$relationship[0],
                'entity'=>"App\\".$relationship[1]
            ];
        }
        return $return_relationships;
    }

    public static function getScaffoldImages($slug){
        $scaffold = SilScaffold::getScaffoldFromSlug($slug);
        $images = [];
        foreach ($scaffold['fields'] as $field_name=>$field_data){
            if ( !is_array($field_data) ){continue;}
            if(@$field_data['type'] == 'image' ){
                $images[] = $field_name;
            }
        }
        return $images;
    }

    public static function getRepeaterFields($slug){
        $scaffold = SilScaffold::getScaffoldFromSlug($slug);
        $repeaters = [];
        foreach ($scaffold['fields'] as $field_name=>$field_data){
            if ( !is_array($field_data) ){continue;}
            if(@$field_data['repeats'] || @$field_data['type'] == 'repeater' ){
                $repeaters[] = $field_name;
            }
        }
        return $repeaters;
    }

    public static function getFields($slug){
        $scaffold = new SilScaffold($slug);
        $fields = [];

        foreach ($scaffold->fields as $field_name=>$field_data){
            
            $field_data_prepped = $scaffold->prepFieldData($field_name,$field_data,$scaffold);
            $field = new SilScaffoldField($field_data_prepped);
            $field->label = $field_data;
            $field->type = 'text';
            //$field->repeats = FALSE;

            if ( is_array($field_data) ){
                if ( @$field_data['label'] ){
                    $field->label = $field_data['label'];
                } else {
                    $field->label = ucwords(str_replace('_',' ',$field_name));
                }

                if ( @$field_data['type'] ){
                    $field->type = $field_data['type'];
                }
                
                //if ( @$field_data['repeats'] ){
                    //$field->repeats = TRUE;
                //}
            }
            
            if ( @$field_data['type'] == 'repeater' ){
                //$fields[$field_name]['fields'] = $field_data['fields'];
                $subfields = [];
                foreach ($field_data['fields'] as $fname=>$fdata){
                    $prepped_fdata = $scaffold->prepFieldData($fname,$fdata,$scaffold);
                    $subfields[] = new SilScaffoldField($prepped_fdata);
                }
                $field->fields = $subfields;
            }
            
            $fields[$field_name] = $field;

            
        }

        /*
        if ( property_exists($scaffold,'relationships') ){
            foreach (@$scaffold->relationships as $field_name=>$rel_config){
                if ( count($rel_config) >= 4 && array_key_exists($rel_config[3],$scaffold->fields)){
                    $relationship_model = "\\App\\".$rel_config[1];
                    $relationship_options_raw = $relationship_model::all();
                    $relationship_options = [];
                    foreach ($relationship_options_raw as $r){
                        $relationship_display_field_name = $rel_config[2];
                        $relationship_options[$r->id] = $r->$relationship_display_field_name;
                    }
                    
                    $field = new SilScaffoldField([
                        'name'=>$rel_config[3],
                        'type'=>'select'
                    ]);
                    
                    $field->options = $relationship_options;
                    $field->label = $rel_config[1];
                  
                    $fields[$field->name] = $field;
                }
            }
        }
        */

        $fields = array_values($fields);
        return $fields;
    }

    function prepFieldData($field_name,$field_data,$scaffold){
        if ( is_array($field_data) ){
            if ( !array_key_exists('type',$field_data) ){
                $field_data['type'] = 'text';
            }
            $field_data = array_merge(['name'=>$field_name],$field_data );
        } else {
            $field_data = [
                'label'=>$field_data,
                'name'=>$field_name,
                'type'=>'text'
            ];
        }


        
        $relationship = FALSE;
        if ( @$scaffold->relationships && array_key_exists($field_name,$scaffold->relationships) ){
            $relationship = new SilScaffoldRelationship($scaffold->relationships[$field_name]);
        }
        /*
        $relationship = FALSE;
        $relationships = SilScaffold::getScaffoldRelationships($scaffold->slug);
        if ( array_key_exists($field_name,$relationships) ){
            $relationship = $relationships[$field_name];
            dd($relationship);
            $records = $relationship['entity']::all();
            $relationship['records'] = $records;
        }
        */
        $field_data['relationship'] = $relationship;

        return $field_data;
    }
}