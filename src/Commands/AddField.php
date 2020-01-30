<?php

namespace Sil\Scaffold\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Sil\Scaffold\SilScaffold;

class AddField extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'silscaffold:addfield {--scaffold=} {--field_name=} {--type=}';

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
        
        if ( is_null($this->option('scaffold')) || is_null($this->option('field_name')) ){
            dd("--scaffold and --field_name are required");
        }

        $scaffold = config('scaffolds.'.$this->option('scaffold'));
        if ( is_null($scaffold) ){
            dd("'".$this->option('scaffold')."' is not a valid scaffold");
        }

        $table_name = $scaffold['table'];
        $field_type = 'text';
        if ( $this->option('type') ){
            $field_type = $this->option('type');
        }

        $table_structure = \DB::select("DESCRIBE $table_name");
        $last_field_before_created_at = FALSE;
        foreach ($table_structure as $k=>$v){
            if ( $v->Field == 'created_at' ){
                $last_field_before_created_at = $table_structure[$k-1]->Field;
            }
        }

        Schema::table($table_name,function($table) use ($field_type,$last_field_before_created_at){
            $table->$field_type($this->option('field_name'))->nullable()->after($last_field_before_created_at);
        });

        dd("'".$this->option('field_name') ."' successfully added to table: ".$table_name);

    }
}
