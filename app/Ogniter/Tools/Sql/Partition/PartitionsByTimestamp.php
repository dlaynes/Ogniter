<?php

namespace App\Ogniter\Tools\Sql\Partition;

class PartitionsByTimestamp {

    protected $partition_list = [];
    protected $remove_partition_list = [];
    protected $tableName = '';

    function setTableName($tableName){
        $this->tableName = $tableName;
    }

    function addYearRange($year, $start_month=1){
        $year = (int) $year;
        $start_month = (int) $start_month;

        for($month = $start_month; $month <= 12; $month++) {
            //$first_second = mktime(0,0,0,$month,1,$year);
            $last_second = mktime(23,59,00,$month+1,0,$year);

            $name = 'p'.$year.'m'.$month;
            //TODO: check if partition exists
            //SHOW CREATE TABLE rankings??
            //https://dev.mysql.com/doc/refman/5.1/en/partitioning-info.html
            $this->partition_list[] = 'PARTITION '.$name.' VALUES LESS THAN ('.($last_second+1).')';
        }
    }

    function addFullYearRange($year){
        $last_second = mktime(23,59,00,12+1,0,$year);
        $name = 'p'.$year;
        $this->partition_list[] = 'PARTITION '.$name.' VALUES LESS THAN ('.($last_second+1).')';
    }

    function removeYearRange($year, $start_month=1){
        $year = (int) $year;
        $start_month = (int) $start_month;

        for($month = $start_month; $month <= 12; $month++) {
            //$first_second = mktime(0,0,0,$month,1,$year);
            $last_second = mktime(23,59,00,$month+1,0,$year);
            //TODO: check if partition exists
            $name = 'p'.$year.'m'.$month;
            $this->remove_partition_list[] = $name;
        }
    }

    function addPartitionsSQL(){
        if(!count($this->partition_list)){
            throw new \Exception("You must indicate at least one partition range");
        }
        if(empty($this->tableName)){
            throw new \Exception("Table name missing when generating the add-partition command");
        }

        $partition_list = implode(', ', $this->partition_list );
        return "ALTER TABLE {$this->tableName} ADD PARTITION ($partition_list)";
    }

    function mergeYearPartitionsSQL($year, $start_month=1){
        $list = [];
        $year = (int) $year;
        $start_month = (int) $start_month;

        for($month = $start_month; $month <= 12; $month++) {
            //$first_second = mktime(0,0,0,$month,1,$year);
            $last_second = mktime(23, 59, 00, $month + 1, 0, $year);
            //TODO: check if the partition exists
            $list[] = 'p' . $year . 'm' . $month;
        }

        $last_second = mktime(23,59,00,12+1,0,$year);
        $partition_list = implode(', ', $list );
        if(empty($this->tableName)){
            throw new \Exception("Table name missing when generating the merge-partition command");
        }
        return "ALTER TABLE {$this->tableName} REORGANIZE PARTITION $partition_list INTO (
            PARTITION p{$year} VALUES LESS THAN (".($last_second+1).")
        );";
    }

    function removePartitionsSQL(){
        if(!count($this->remove_partition_list)){
            throw new \Exception("You must set at least one partition range");
        }
        if(empty($this->tableName)){
            throw new \Exception("Table name missing when generating the remove-partition command");
        }
        return 'ALTER TABLE '.$this->tableName.' DROP PARTITION '.implode(', ', $this->remove_partition_list);
    }

}

