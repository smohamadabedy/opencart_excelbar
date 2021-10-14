<?php
require_once(DIR_SYSTEM.'library/excelbar/dash.php');
class ControllerExcelbarDash extends Controller {

	public function index() {

		$this->load->model('excelbar/dash');

        $this->load->language('excelbar/dash');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addStyle('//cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css');
		$this->document->addScript('//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js');

		$data['form_upload'] = $this->url->link('excelbar/dash/upload', 'user_token=' . $this->session->data['user_token'] , true);
		$data['excel_parsing'] = $this->url->link('excelbar/dash/ep', 'user_token=' . $this->session->data['user_token'] , true);
		$data['delete_item'] = $this->url->link('excelbar/dash/delete', 'user_token=' . $this->session->data['user_token'] , true);

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('excelbar/dash', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['user_token'] = $this->session->data['user_token'];
		if(isset($this->session->data['excel_info'])){
			$data['excel_info'] = $this->session->data['excel_info'];
			$data['excel_info']['stat'] = true;
			unset($this->session->data['excel_info']);
		}else{
			$data['excel_info']['stat'] = false;
		}

		
		$data['ex_files'] = $this->model_excelbar_dash->getAll();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('excelbar/dash', $data));
	}
	public function upload(){

		$this->load->model('excelbar/dash');

		$myfile = $_FILES['excel'];
		$myfile_name = DIR_UPLOAD.time().'_'.$myfile['name'];
		$myfile_format =  pathinfo($myfile['name'], PATHINFO_EXTENSION);
		if($myfile_format != "xlsx"){
			var_dump($myfile_format);
			$message  = ["type" => 'danger' ,"text" => 'file format is not accepted | only xlsx files' ];
		}
		else{
			if (move_uploaded_file($this->request->files['excel']['tmp_name'], $myfile_name)) {
				$re = $this->model_excelbar_dash->add_file(['file' => $myfile_name , 'size' => $myfile['size'] ]);
				if($re){
					$message  = ["type" => 'success' ,"text" => 'file successfuly uploaded' ];
				}
			}else{
				$message  = ["type" => 'danger' ,"text" => 'could not upload the file please try again' ];
			}
		}

		$this->session->data['excel_info'] = $message;
		$this->response->redirect($this->url->link('excelbar/dash', 'user_token=' . $this->session->data['user_token'], true));

		//end of upload
	}
	function render_barcode(){
		$code = $_GET['code'];
		$n = new dash();

	}
	function ep(){
		
        $this->load->language('excelbar/dash');

		$this->document->setTitle($this->language->get('heading_title2'));
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title2'),
			'href' => $this->url->link('excelbar/dash', 'user_token=' . $this->session->data['user_token'], true)
		);


		
		$n = new dash($_GET['address']);
		$code = $n->read_table();
		$data_ex = ($n->read_all());
		$table = "";
		$table .= "<table class='table table-bordered table-hover'><tbody>";
		foreach($data_ex as $key => $val){
			if($key == 0 ){
				$table .= '<thead><tr>';
				foreach($val as $key => $minival){
					$table .= "<th class='text-center'>".substr($minival,0,30)."</th>";

				}
				$table .= '</tr></thead>';

			}else{
				$table .= '<tr>';
				foreach($val as $key => $minival){
					if($key == 2){
						$table .= '<td><img src="data:image/png;base64,'.base64_encode($n->create_barcode($minival)).'"/></td>';

					}else{
						$table .= "<td>".substr($minival,0,30)."</td>";
					}
				}
				$table .= '</tr>';
			}
		}
		$table .= "</tbody></table>";
		$data['table'] = $table; 
	
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('excelbar/form', $data));
		
	}
	function delete(){
		if(isset($_GET['id']) && isset($_GET['address'])){

			$this->load->model('excelbar/dash');

			if(is_file($this->request->get['address'])){
				unlink($this->request->get['address']);
			}
			$re = $this->model_excelbar_dash->delete($this->request->get['id']);
			if($re){
				$message  = ["type" => 'success' ,"text" => 'file deleted completly' ];
				$this->session->data['excel_info'] = $message;
			}else{
				$message  = ["type" => 'danger' ,"text" => 'could not delete file' ];
				$this->session->data['excel_info'] = $message;
			}
			
		}else{
			$message  = ["type" => 'danger' ,"text" => 'Access denied' ];
			$this->session->data['excel_info'] = $message;
		}
		$this->response->redirect($this->url->link('excelbar/dash', 'user_token=' . $this->session->data['user_token'], true));	

	}
}