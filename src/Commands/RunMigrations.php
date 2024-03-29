<?php

namespace Sil\Scaffold\Commands;
use Sil\Scaffold\SilScaffold;
use Sil\Scaffold\SilScaffoldRelationship;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class RunMigrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'silscaffold:migrate {--only=}';

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
        $this->call('silscaffold:makemodels');
        $config = SilScaffold::getScaffolds();
        
        foreach ($config as $scaffold_name=>$data){

            if ( $this->option('only') && $this->option('only') != $scaffold_name ){
                //skip all others that don't match our 'only' option
                continue;
            }


            $scaffold = new SilScaffold($data['slug']);
            
            if ( !Schema::hasTable($scaffold->table) ) {

                Schema::create($scaffold->table,function($table) use ($scaffold){
                    $table->increments('id');
                    foreach ($scaffold->fields as $field=>$field_data){
                        
                        $data_type = 'string';
                        $field_name = $field;

                        if ( substr($field,-3) == '_id' ){
                            $data_type = 'biginteger';
                        }

                        if ( @$field_data['type'] == 'datetime' ){
                            $data_type = 'datetime';
                        }

                        if ( is_array($field_data) ){
                            $text_field_types = [
                                'image','repeater','textarea','wysiwyg'
                            ];
                            if ( in_array($field_data['type'],$text_field_types) ){
                                $data_type = 'text';
                            }
                        }

                        if ( @$scaffold->relationships && array_key_exists($field_name,$scaffold->relationships) ){
                            $relationship = new SilScaffoldRelationship($scaffold->relationships[$field_name]);
                            if ( !$relationship->needs_field ){
                                continue;
                            } else {
                                $data_type = 'integer';
                                $field_name = $field_name.'_id';
                            }
                        }

                        $table->$data_type($field_name)->nullable();
                    }
                    $table->timestamps();
                });

                //build many to many tables
                if ( !@$data['relationships'] ){
                    $data['relationships'] = [];
                }

                $many_to_many_tables_made = [];
                
                foreach ($data['relationships'] as $r_name=>$r){
                    if ( $r[0] == 'belongsToMany' ){
                        
                        $scaffold_item = $config[$r_name];
                        $inverse_table = $scaffold_item['table'];
                    
                        $tables = [\Str::singular($inverse_table),\Str::singular($data['table'])];
                        natsort($tables);
                        $table_name = implode('_',$tables);
                        if ( in_array($table_name,$many_to_many_tables_made) ){
                            continue;
                        }
                        Schema::dropIfExists($table_name);
                        $fields = array_map(function($table){
                            return $table.'_id';
                        },$tables);
                        Schema::create($table_name,function($table) use ($fields){
                            foreach ($fields as $f){
                                $table->integer($f);
                            }
                        });
                        $many_to_many_tables_made[] = $table_name;
                    }

                }

                //add dummy data
                if ( @$data['stub_data'] ){
                    if ( is_callable($data['stub_data'],TRUE,$callable_name) ){
                        $data['stub_data'] = $callable_name();
                    }

                    foreach ($data['stub_data'] as $entry){
                        $values = [];
                        foreach ($entry as $k=>$v){
                            if ( stripos($v,'()') !== FALSE ){ //run this function 
                                eval('$v = '.$v.';');
                            }

                            $values[$k] = $v;
                        }
                        $values['created_at'] = date("Y-m-d H:i:s");
                        $values['updated_at'] = date("Y-m-d H:i:s");
                        \DB::table($data['table'])->insert($values);
                    }

                    
                }
            }
        


        }


    }
}
