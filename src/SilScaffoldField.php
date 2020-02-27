<?php
namespace Sil\Scaffold;
class SilScaffoldField {
    function __construct($data=null){
        if ( is_null($data) ){
            throw new \Exception("String (field name) or Array required to create a new field");
        }
        
        if ( is_string($data) ){
            $this->name = $data;
            $this->type = 'text';
        }


        if ( is_array($data) ){
            foreach ($data as $k=>$v){
                $this->$k = $v;
            }
        }


        if ( @$this->type == 'datetime' ){
            $this->type = 'text';
        }

        if ( !property_exists($this,'name') || !property_exists($this,'type') ){
            throw new \Exception("`name` and `type` required to create field. Passed: ".json_encode($data));
        }

        if ( !property_exists($this,'label') ){
            $this->label = ucwords(str_replace('_',' ',$this->name));
        }

        $this->field_name = $this->name;

        if ( $this->relationship ){
            $this->type = $this->relationship->field_type;
            if ( property_exists($this->relationship,'options') ){
                $this->options = $this->relationship->options;
            }
            
            if ( $this->relationship->needs_field ){
                $this->field_name = $this->name.'_id';
            }
        }

        $this->json = FALSE;
        if ( $this->type == 'repeater' || $this->type == 'image'){
            $this->json = TRUE;
        }
        
        $this->data = $data;
        
        $this->component_name = ucfirst(\Str::camel($this->type)).'Field';

    }
    
}