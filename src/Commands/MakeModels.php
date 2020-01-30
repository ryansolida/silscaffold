<?php

namespace Sil\Scaffold\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Sil\Scaffold\SilScaffold;

class MakeModels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'silscaffold:makemodels';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $config = config('scaffolds');
        
        foreach ($config as $model_name=>$data){
            $model_template_data = file_get_contents(__DIR__.'/../ModelTemplate.php');
            $model_template_data = str_replace('CLASSNAME',$data['model'],$model_template_data);

            $relationships = SilScaffold::getScaffoldRelationships($data['slug']);
            $relationships_str = '';
            if (count($relationships) > 0 ){
                foreach ($relationships as $name=>$rel){
                    $relationships_str .= "\tpublic function ".$name."(){\n"
                        ."\t\treturn \$this->".$rel['type']."('".$rel['entity']."');\n"
                        ."\t}\n\n";
                }
            }
            $model_template_data = str_replace("RELATIONSHIPS",$relationships_str,$model_template_data);


            //CUSTOM GETTERS
            $image_fields = SilScaffold::getScaffoldImages($data['slug']);
            $getters_str = '';
            if ( count($image_fields) > 0 ){
                foreach ($image_fields as $i){
                    $method_name = \Str::camel('get_'.$i.'_attribute');
                    $getter_str = "\tpublic function {$method_name}(){\n"
                        ."\t\treturn \$this->attributes['{$i}']==''?[]:json_decode(\$this->attributes['{$i}']);\n"
                        ."\t}\n\n";
                    $getters_str .= $getter_str;
                }
            }

            $repeating_fields = SilScaffold::getRepeaterFields($data['slug']);
            if ( count($repeating_fields) > 0 ){
                foreach ($repeating_fields as $i){
                    $method_name = \Str::camel('get_'.$i.'_attribute');
                    $getter_str = "\tpublic function {$method_name}(){\n"
                        //."\t\treturn json_decode(\$this->attributes['".$i."']);\n"
                        ."\t\treturn \$this->attributes['{$i}']==''?[]:json_decode(\$this->attributes['{$i}']);\n"
                        ."\t}\n\n";
                    $getters_str .= $getter_str;
                }
            }


            $model_template_data = str_replace("GETTERS",$getters_str,$model_template_data);
            

            $model_file = app_path().'/'.$data['model'].'.php';
            $custom_methods_content = '';
            if ( file_exists($model_file) ){
                $current_file_contents = file_get_contents($model_file);
                $start_str = '//CUSTOM METHODS START -- DO NOT REMOVE THIS LINE';
                $end_str = '//CUSTOM METHODS END -- DO NOT REMOVE THIS LINE';
                $custom_methods_content = substr($current_file_contents,strpos($current_file_contents,$start_str)+strlen($start_str));
                $custom_methods_content = substr($custom_methods_content,0,strpos($custom_methods_content,$end_str));
                $custom_methods_content = trim($custom_methods_content);
            }

            $model_template_data_after_custom_methods = str_replace('CUSTOMMETHODS',$custom_methods_content,$model_template_data);

            
            file_put_contents($model_file,$model_template_data_after_custom_methods);
        
        }


    }
}
