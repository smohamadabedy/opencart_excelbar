<?php
class ModelExtensionModuleExcelbar extends Model {
                        
        public function install(){
                        $db = DB_PREFIX;
                        $sql0 = <<<EOT
                              CREATE TABLE  IF NOT EXISTS `{$db}excelbar` (
  `id` int(11) NOT NULL,
  `address` varchar(500) NOT NULL,
  `date_added` datetime NOT NULL,
  `size` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
EOT;
                       
                         $sql2 = <<<EOT
ALTER TABLE `oc_excelbar`
  ADD PRIMARY KEY (`id`);
EOT;       

                        $sql3 = <<<EOT
ALTER TABLE `oc_excelbar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
EOT;
                        $sql4 = <<<EOT
COMMIT
EOT; 
                        $sql5 = <<<EOT
START TRANSACTION
EOT; 
                   $this->db->query($sql5);  
                        $this->db->query($sql0);  
                        $this->db->query($sql2);  
                        $this->db->query($sql3);  
                        $this->db->query($sql4);  
         }
        public function uninstall(){
                $db = DB_PREFIX;
                $sql = "
                DROP TABLE IF EXISTS {$db}excelbar;
                ";
                $this->db->query($sql);  

        }
                
}