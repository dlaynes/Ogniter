<?php

namespace App\Ogniter\Api\Remote\Ogame\Helper;

use App\Ogniter\Tools\File\CsvBuilder;
use App\Ogniter\Tools\Sql\Query\LoadData;
use DB;

class DataBuilder {
    protected $_tableName = '';

    protected $_csvBuilder = '';

    protected $_file = '';

    protected $_queryBuilder = \NULL;

    function __construct(CsvBuilder $builder, LoadData $queryBuilder){
        $this->_csvBuilder = $builder;
        $this->_queryBuilder = $queryBuilder;
    }

    public function setFile($file){
        $this->_file = $file;
        $this->_csvBuilder->setFile($file);
        return $this;
    }

    public function setTableName($name){
        $this->_tableName = $name;
        $this->_queryBuilder->setTableName($name);
        return $this;
    }

    public function setFields($fields){
        $this->_queryBuilder->setFields($fields);
        return $this;
    }

    public function setIgnore($ignore){
        $this->_queryBuilder->setIgnore(!!$ignore);
        return $this;
    }

    public function setReplace($replace){
        $this->_queryBuilder->setReplace(!!$replace);
        return $this;
    }

    public function addRow($row){
        $this->_csvBuilder->addRow($row);
    }

    public function save(){
        $this->_csvBuilder->saveBuffer();
        $query = $this->_queryBuilder->buildQuery($this->_file);
        DB::unprepared($query);
        $this->_csvBuilder->close();
        //@unlink($this->_file);
    }

}