<?php
class ControllerExtensionModuleExcelbar extends Controller {
        private $error = array();

        public function install(){
                $this->load->model('extension/module/excelbar');
                $this->model_extension_module_excelbar->install();
        }
        public function uninstall(){
                $this->load->model('extension/module/excelbar');
                $this->model_extension_module_excelbar->uninstall();

        }

}