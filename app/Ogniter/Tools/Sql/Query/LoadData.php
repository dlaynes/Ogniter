<?php

namespace App\Ogniter\Tools\Sql\Query;

class LoadData {

    protected $table;

    protected $fields = [];

    protected $fieldsTerminatedBy = ',';

    protected $fieldsOptionallyEnclosedBy = '"';

    protected $fieldsEscapedBy = '"'; //not implemented

    protected $linesTerminatedBy = '\\n';

    protected $ignoreLines = NULL; //not implemented

    protected $replace = FALSE;

    protected $ignore = FALSE;

    protected $local = FALSE;

    function __construct($table = '', $fieldsTerminatedBy = ',', $fieldsOptionallyEnclosedBy='"', $linesTerminatedBy='\n'){
        $this->table = $table;

        $this->fieldsTerminatedBy = $fieldsTerminatedBy;
        $this->fieldsOptionallyEnclosedBy = $fieldsOptionallyEnclosedBy;
        $this->linesTerminatedBy = $linesTerminatedBy;
        $this->setLocal(env('APP_LOAD_CSV_DATA_LOCAL', \TRUE)); //Yeah...
    }

    public function setTableName($name){
        $this->table = $name;
    }

    function setFields(array $fields){
        $this->fields = $fields;
        return $this;
    }

    function setReplace($replace){
        $this->replace = !!$replace;
        return $this;
    }

    function setIgnore($ignore){
        $this->ignore = !!$ignore;
        return $this;
    }

    function setLocal($local){
        $this->local = !!$local;
        return $this;
    }

    function buildQuery($the_file){

        if(!is_file($the_file)){
            throw new \Exception('File '.htmlspecialchars($the_file).' does not exist!');
        }

        $insert_mode = $this->replace ? 'REPLACE' : '';
        if(!$insert_mode){
            $insert_mode = $this->ignore ? 'IGNORE' : '';
        }

        $local_mode = $this->local ? 'LOCAL' : '';

        $fields = count($this->fields)? '('.implode(',', $this->fields).')': '';

        $query = sprintf("LOAD DATA {$local_mode} INFILE '%s'
            $insert_mode
            INTO TABLE {$this->table}
            FIELDS TERMINATED BY '{$this->fieldsTerminatedBy}'
            OPTIONALLY ENCLOSED BY '{$this->fieldsOptionallyEnclosedBy}'
            LINES TERMINATED BY '{$this->linesTerminatedBy}' IGNORE 0 LINES $fields", addslashes( $the_file));
        return $query;
    }
}