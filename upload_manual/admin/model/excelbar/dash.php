<?php

class ModelExcelbarDash extends Model {
    function test(){
        echo 'test';
    }
    function add_file($data){
		$date_modified = date('Y-m-d H:i:s'); 
        $sql =  "INSERT INTO `". DB_PREFIX ."excelbar` (`id`, `address`, `date_added`, `size`) VALUES (NULL, '{$data['file']}', '$date_modified', '{$data['size']}')";
        return $this->db->query($sql);
    }
    function getAll(){
        $sql =  "SELECT * from `". DB_PREFIX ."excelbar` where 1 order by id ASC";
        return $this->db->query($sql)->rows;
    }
    function delete($id){
        $sql = "DELETE from `". DB_PREFIX ."excelbar` where `id` = $id";
        return $this->db->query($sql);
    }

}