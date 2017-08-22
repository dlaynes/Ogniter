<?php

namespace App\Ogniter\Tools\File;

class CsvBuilder {
    /*
    How many records we should save right away, in order to keep memory usage low
    */
    protected $chunkSize = 500;

    protected $chunkCount = 0;

    protected $file;

    protected $bufferData = '';

    protected $fieldsTerminatedBy = ',';

    protected $linesTerminatedBy = "\n";

    protected $fp;

    function __construct($file = '', $fieldsTerminatedBy = ',', $linesTerminatedBy="\n"){
        $this->file = $file;

        $this->fieldsTerminatedBy = $fieldsTerminatedBy;
        $this->linesTerminatedBy = $linesTerminatedBy;
    }

    function setFile($file){
        $this->file = $file;
    }

    function initPointer(){
        $this->fp = fopen($this->file,'wa+');
    }

    function setChunkSize($chunkSize){
        $this->chunkSize = (int) $chunkSize;
    }

    function addRow(array $record){
        $record = implode($this->fieldsTerminatedBy, $record).$this->linesTerminatedBy;
        $this->bufferData .= $record;

        $this->chunkCount++;
        if($this->chunkCount > $this->chunkSize){
            $this->saveBuffer();
        }
    }

    function saveBuffer(){
        if(!$this->fp){
            $this->initPointer();
        }

        fwrite($this->fp, $this->bufferData);
        //\file_put_contents($this->file, $this->bufferData, \FILE_APPEND);

        $this->bufferData = '';
        $this->chunkCount = 0;
    }

    function close(){
        if($this->fp){
            fclose($this->fp);
        }
    }

}