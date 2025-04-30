<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Formbuilder extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->ctl="formbuilder";
		$this->pagename="Register Profile";
        $this->load->model('Mdl_forms');

        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'form_validation']);


		$this->load->database();
		$this->load->helper('form');
        $this->load->helper('security');
		$this->load->helper('url');
        $this->load->library('form_validation');
		$this->load->library("Pagination");
		$this->load->library('table');
		$this->load->library('encryption');
		$this->token =$this->input->cookie('token', TRUE);
		$this->event =$this->input->cookie('eventID', TRUE);

		$tokendata= $this->packfunction->decrypt($this->token);
		$event= $this->packfunction->decrypt($this->event);

		
		$this->userID =isset($tokendata['userID'])? $tokendata['userID']:"";
		$this->UserName = isset($tokendata['UserName'])? $tokendata['UserName']:"";
		$this->profile=$this->mdl_packFunction->getCheckUser($this->UserName);
		$this->usergroupID = isset($this->profile['usergroupID'])? $this->profile['usergroupID']:"";
		$this->UserEmail=isset($this->profile['Useremail'])? $this->profile['Useremail']:"";
		$this->userFname=isset($this->profile['userFname'])? $this->profile['userFname']:"";
		$this->userEvent=isset($this->profile['userevent'])? $this->profile['userevent']:"";
		$this->eventID =isset($event['eventID'])? $event['eventID']:"";
		
		if (empty($this->token)) {
			redirect('authen/logout', 'refresh');
		}
		if(count($this->profile)<=0){
			redirect('authen/logout','refresh');
		}
		if (!$tokendata || time() > $tokendata['expiryTime']) {
			redirect('authen/logout', 'refresh');
		}
		if ($this->eventID == "") {
			redirect('home/eventlist');
		}
		$this->event= $this->Mdl_packFunction->getEventDetail($this->eventID);
		if($this->userEvent!='all'){
			if (!in_array($this->event['eventID'],explode(",",$this->userEvent))) {
				redirect('home');
			}
		}

		$nonce = base64_encode(random_bytes(16));
        $this->nonce  = $nonce;
		$listcsp = $this->mdl_packFunction->getlistcsp();
		if(count($listcsp)>0){
		   $datacsp = [];
		   foreach($listcsp as $csp){
			   $listcspby = $this->mdl_packFunction->getlistcspby($csp['type']);
			   if(count($listcspby)>0){
				   $datacspby =[];
				  
				   foreach($listcspby as $cspby){
					   $datamap = [
						   "{{nonce}}" => "'nonce-$nonce'",
						   "{{base_url}}" => base_url()
					   ];
					   $datacspby[]= $datamap[$cspby['data']] ?? $cspby['data'];

				   }
			   }

			   $datacsp[] = $csp['type']." ".implode(' ', $datacspby).";";
		   }
		   $datalistcsp = implode(' ', $datacsp);
		}
		$this->output
            ->set_header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload")
            ->set_header("X-Permitted-Cross-Domain-Policies: none")
            ->set_header("Cache-Control: no-cache, no-store, must-revalidate, max-age=0")
            ->set_header("X-Content-Type-Options: nosniff")
            ->set_header("X-XSS-Protection: 1; mode=block")
            ->set_header("Referrer-Policy: no-referrer")
            ->set_header("Access-Control-Allow-Methods: GET, POST, OPTIONS")
            ->set_header("Access-Control-Allow-Headers: Content-Type, Authorization")
            ->set_header("Access-Control-Allow-Credentials: true")
			->set_header("X-Frame-Options: DENY")
            ->set_header("Content-Security-Policy: {$datalistcsp}");  

        
    }

    // public function index() {
    //     $this->data['viewName']='Register Profile'; 
	// 	// $this->data['subdomain']= $this->event['subdomain']; 
	// 	// $this->data['keyword'] = isset($_GET['keyword']) ? $_GET['keyword'] : '';
    //     $this->data['static_fields'] = $this->get_static_fields();
    //     $this->data['dynamic_fields'] = $this->get_dynamic_fields();
    //     $this->data['field_types'] = $this->get_field_types();
    //     // $this->load->view('form_builder/form_builder', $data);
    //     $this->packfunction->packViewNew($this->data,'form_builder/form_builder');

    // }

    public function index($limit=0){
		$this->data['viewName']='List Register Form'; 
		$this->data['subdomain']= $this->event['subdomain']; 
		$this->data['keyword'] = isset($_GET['keyword']) ? $_GET['keyword'] : '';
		$this->data['limit'] = $limit <> 0 ? $limit : 0;

        // $this->data['static_fields'] = $this->get_static_fields();
        // $this->data['dynamic_fields'] = $this->get_dynamic_fields();
        // $this->data['field_types'] = $this->get_field_types();

		$this->data['registerType'] = isset($_GET['registerType']) ? $_GET['registerType'] : '';
		$this->data['showrow'] = isset($_GET['showrow']) ? $_GET['showrow'] :10;
		$this->data['badgetype'] = isset($_GET['badgetype']) ? $_GET['badgetype'] : '';

		$this->data['getregistertype'] = $this->Mdl_forms->getregistertype(
			$this->data['subdomain']
		);
		
		// $this->data['getbadgetype'] = $this->Mdl_badge->getbadgetype(
		// 	$this->data['subdomain']
		// );

		$config = array();
		$this->data['getlist'] = $this->Mdl_forms->getList(
			$this->data['subdomain'],
			addslashes($this->data['keyword']),
			$this->data['limit'],
			$this->data['showrow'],
			$this->data['registerType'],
			$this->data['badgetype']
		);

		$total = $this->Mdl_forms->getList($this->data['subdomain'],
			addslashes($this->data['keyword']),
			'',
			$this->data['showrow'],
			$this->data['registerType'],
			$this->data['badgetype']);

		$num_rows = count($total);
		$total_rows = $num_rows;
		$this->data['total_rows'] = $num_rows;
		$base_url = base_url() . $this->ctl.'/index/' ;
		$per_page = $this->data['showrow'];
		$uri_segment = 3;
		$config = $this->pagination_configuration($base_url, $total_rows, $per_page, $uri_segment);
		$this->pagination->initialize($config);
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$this->data['pagination'] = $this->pagination->create_links(); // เลขหน้า
		$this->packfunction->packViewNew($this->data,'formbuilder/form_builderList');
	}

    private function pagination_configuration($base_url='', $total_rows='', $per_page='', $uri_segment='')
	{
		$config = array();
		$config['use_page_numbers'] = false;  // แก้ไขเป็น true เพื่อใช้ page numbers
		$config["base_url"] = $base_url;
		$config["total_rows"] = $total_rows;
		$config["per_page"] = $per_page;
		$config["uri_segment"] = $uri_segment;  // segment ที่บอกว่าขณะนี้อยู่หน้าไหน
		$config['reuse_query_string'] = true;
	
		$config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
	
		$config['full_tag_open'] = '<ul class="pagination justify-content-center">';
		$config['full_tag_close'] = '</ul>';
	
		$config['first_link'] = 'First';
		$config['first_tag_open'] = '<li class="page-item">';
		$config['first_tag_close'] = '</li>';
	
		$config['prev_link'] = 'Prev';
		$config['prev_tag_open'] = '<li class="page-item">';
		$config['prev_tag_close'] = '</li>';
	
		$config['next_link'] = 'Next';
		$config['next_tag_open'] = '<li class="page-item">';
		$config['next_tag_close'] = '</li>';
	
		$config['last_link'] = 'Last';
		$config['last_tag_open'] = '<li class="page-item">';
		$config['last_tag_close'] = '</li>';
	
		$config['cur_tag_open'] = '<li class="page-item active" aria-current="page"><span class="page-link">';
		$config['cur_tag_close'] = '</span></li>';
	
		$config['num_tag_open'] = '<li class="page-item">';
		$config['num_tag_close'] = '</li>';
	
		$config['attributes'] = array('class' => 'page-link');
	
		return $config;
	}

    public function form_builderEdit($form_no=''){

		$this->data['viewName']='Edit Register Form'; 
		$this->data['eventID'] =  $this->event['eventID']; 
		$this->data['subdomain']= $this->event['subdomain']; 
		// $this->data['getpaper']= $this->Mdl_badge->getdetail($this->data['subdomain'],$paperID);
		// if(count($this->data['getpaper'])>0){
		// 	$this->data['getonpaper']= $this->Mdl_badge->getonpaperdetail($this->data['subdomain'],Md5($this->data['getpaper']['paperID']));
		// 	$this->data['registerType']=array('visitor','exhibitor','vip','press','guest');

		// 	$this->packfunction->packViewNew($this->data,'formbuilder/form_builderEdit');
		// }else{
		// 	redirect('formbuilder');
		// }
        $this->packfunction->packViewNew($this->data,'formbuilder/form_builderEdit');


	}

    public function form_buildercreate(){

		$this->data['viewName']='Create Register Form'; 
        $this->data['eventID'] =  $this->event['eventID']; 
		$this->data['subdomain']= $this->event['subdomain']; 
		// $this->data['getpaper']= $this->Mdl_badge->getdetail($this->data['subdomain'],$paperID);
		// if(count($this->data['getpaper'])>0){
		// 	$this->data['getonpaper']= $this->Mdl_badge->getonpaperdetail($this->data['subdomain'],Md5($this->data['getpaper']['paperID']));
		// 	$this->data['registerType']=array('visitor','exhibitor','vip','press','guest');

		// 	$this->packfunction->packViewNew($this->data,'formbuilder/form_builderCreate');
		// }else{
		// 	redirect('formbuilder');
		// }
        $this->packfunction->packViewNew($this->data,'formbuilder/form_builderCreate');

	}


    public function updatestatus()
    {
        // Get and sanitize inputs
        $form_id = trim($this->input->post('form_id'));
        $subdomain = trim($this->input->post('subdomain'));
		$status = trim($this->input->post('status'));
	
        if (empty($form_id)) {
            echo json_encode(['status' => 'error', 'message' => 'formID cannot be empty']);
            return;
        }
        if (empty($status)) {
            echo json_encode(['status' => 'error', 'message' => 'status cannot be empty']);
            return;
        }

        // Sanitize the subdomain to prevent XSS
        $escaped_form_id = $this->security->xss_clean($form_id);
        $escaped_status = $this->security->xss_clean($status);

        // Check the code using the model
        try {
			$dataupdate= array(
				"status" => $escaped_status,
				"updateDT" => $this->packfunction->dtYMDnow(),
				"updateBY" => $this->UserName
			);
			$dataupdatexss = $this->security->xss_clean($dataupdate);
			$this->Mdl_forms->updateData($dataupdatexss, 'tc_config_form_new','MD5(form_no)',$escaped_form_id);
            echo json_encode(['status' => 'success', 'message' => 'success']);
            
        } catch (Exception $e) {
            // Log error (if applicable) and return a generic error message
            log_message('error', 'Error checking code: ' . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'An error occurred while processing your request']);
        }
    }

public function save_form() {
    // Sanitize and prepare form data
    $data = array(
        'form_code' => $this->input->post('form_code'),
        'form_name' => $this->input->post('form_name'),
        'subdomain' => $this->event['subdomain'],
        'eventID' => $this->input->post('eventID'),
        'registerType' => $this->input->post('registerType'),
        'isPreWalk' => $this->input->post('isPreWalk'),
        'form_label_lang' => $this->input->post('form_label_lang'),
        'form_register_lang' => $this->input->post('form_register_lang'),
        'form_limit' => $this->input->post('form_limit'),
        'form_start_date' => $this->input->post('form_start_date'),
        'form_end_date' => $this->input->post('form_end_date'),
        'form_html_detail' => $this->input->post('form_html_detail'),
        'form_badge_image_harder' => $this->input->post('form_badge_image_harder'),
        'form_badge_image_footer' => $this->input->post('form_badge_image_footer'),
        'form_file_pdpa' => $this->input->post('form_file_pdpa'),
        
        // Form flags
        'form_questionnaire_main' => $this->input->post('form_questionnaire_main') ? 'YES' : 'NO',
        'form_questionnaire_sub' => $this->input->post('form_questionnaire_sub') ? 'YES' : 'NO',
        'form_is_private' => $this->input->post('form_is_private') ? 'YES' : 'NO',
        'form_is_group' => $this->input->post('form_is_group') ? 'YES' : 'NO',
        'form_ispdpa' => $this->input->post('form_ispdpa') ? 'YES' : 'NO',
        'form_login' => $this->input->post('form_login') ? 'YES' : 'NO',
        'form_is_html_header' => $this->input->post('form_is_html_header') ? 'YES' : 'NO',
        'form_html_header' => $this->input->post('form_html_header'),
        'form_sync_cms' => $this->input->post('form_sync_cms') ? 'YES' : 'NO',
        
        // Group settings
        'form_limit_person' => $this->input->post('form_limit_person') ? 'YES' : 'NO',
        'form_topic_person' => $this->input->post('form_topic_person'),
        'form_limit_min' => $this->input->post('form_limit_min'),
        'form_limit_max' => $this->input->post('form_limit_max'),
        'form_copy_information_register' => $this->input->post('form_copy_information_register') ? 'YES' : 'NO',
        'form_copy_information_qa' => $this->input->post('form_copy_information_qa') ? 'YES' : 'NO',
        'form_copy_address' => $this->input->post('form_copy_address') ? 'YES' : 'NO',
        'form_copy_tocenter' => $this->input->post('form_copy_tocenter') ? 'YES' : 'NO',
        'form_copy_mainperson' => $this->input->post('form_copy_mainperson') ? 'YES' : 'NO',
        'form_copy_mainfield' => $this->input->post('form_copy_mainfield'),
        
        // Serial settings
        'form_is_custom_serial' => $this->input->post('form_is_custom_serial') ? 'YES' : 'NO',
        'form_serial_text' => $this->input->post('form_serial_text'),
        'form_serial_date' => $this->input->post('form_serial_date') ? 'YES' : 'NO',
        'form_date_format' => $this->input->post('form_date_format'),
        'form_serial_digits' => $this->input->post('form_serial_digits'),
        
        // PDPA settings
        'form_type_pdpa' => $this->input->post('form_type_pdpa'),
        'form_html_pdpa' => $this->input->post('form_html_pdpa'),
        
        // Sync CMS settings
        'sync_ticketID' => $this->input->post('sync_ticketID'),
        'sync_eventID' => $this->input->post('sync_eventID'),
        'sync_registerFormConfigID' => $this->input->post('sync_registerFormConfigID'),
        'sync_registerConfigID' => $this->input->post('sync_registerConfigID'),
        'form_sync_subregister' => $this->input->post('form_sync_subregister') ? 'YES' : 'NO',
        'form_sync_bmm' => $this->input->post('form_sync_bmm') ? 'YES' : 'NO',
        
        // Additional settings
        'form_html_complete' => $this->input->post('form_html_complete'),
        'form_html_mail' => $this->input->post('form_html_mail'),
        'form_is_send_templatemail' => $this->input->post('form_is_send_templatemail') ? 'YES' : 'NO',
        'form_templateID' => $this->input->post('form_templateID'),
        
        'form_page_register' => 'RegisterResV3',
        'form_page_question' => 'QuestionnaireV3',
        'form_page_complete' => 'CompleteV3',
        'form_mail_complete' => 'HtmlmailBlank',
        
        'createDT' => date('Y-m-d H:i:s'),
        'createBY' => $this->UserName,
        'status' => 'ON'
    );

    // // Handle file uploads for PDPA and badge images
    // if (!empty($_FILES['form_file_pdpa']['name'])) {
    //     $pdpa_file = $this->handle_file_upload('form_file_pdpa', 'pdpa_files');
    //     if ($pdpa_file['status']) {
    //         $data['form_file_pdpa'] = $pdpa_file['filename'];
    //     }
    // }

    // if (!empty($_FILES['form_badge_image_harder']['name'])) {
    //     $header_image = $this->handle_file_upload('form_badge_image_harder', 'badge_images');
    //     if ($header_image['status']) {
    //         $data['form_badge_image_harder'] = $header_image['filename'];
    //     }
    // }

    // if (!empty($_FILES['form_badge_image_footer']['name'])) {
    //     $footer_image = $this->handle_file_upload('form_badge_image_footer', 'badge_images');
    //     if ($footer_image['status']) {
    //         $data['form_badge_image_footer'] = $footer_image['filename'];
    //     }
    // }

    try {
        $result = $this->Mdl_forms->insert_form($this->security->xss_clean($data));
        if ($result) {
            echo json_encode(['status' => true, 'message' => 'Form saved successfully']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Failed to save form']);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}

public function update_form() {
    $form_no = $this->input->post('form_no');

    // Prepare form data
    $data = array(
        'form_code' => $this->input->post('form_code'),
        'form_name' => $this->input->post('form_name'),
        'registerType' => $this->input->post('registerType'),
        'isPreWalk' => $this->input->post('isPreWalk'),
        'form_label_lang' => $this->input->post('form_label_lang'),
        'form_register_lang' => $this->input->post('form_register_lang'),
        'form_limit' => $this->input->post('form_limit'),
        'form_start_date' => $this->input->post('form_start_date'),
        'form_end_date' => $this->input->post('form_end_date'),
        'form_html_detail' => $this->input->post('form_html_detail'),
        'form_badge_image_harder' => $this->input->post('form_badge_image_harder'),
        'form_badge_image_footer' => $this->input->post('form_badge_image_footer'),
        'form_file_pdpa' => $this->input->post('form_file_pdpa'),
        
        'form_questionnaire_main' => $this->input->post('form_questionnaire_main') ? 'YES' : 'NO',
        'form_questionnaire_sub' => $this->input->post('form_questionnaire_sub') ? 'YES' : 'NO',
        'form_is_private' => $this->input->post('form_is_private') ? 'YES' : 'NO',
        'form_is_group' => $this->input->post('form_is_group') ? 'YES' : 'NO',
        'form_ispdpa' => $this->input->post('form_ispdpa') ? 'YES' : 'NO',
        'form_login' => $this->input->post('form_login') ? 'YES' : 'NO',
        'form_is_html_header' => $this->input->post('form_is_html_header') ? 'YES' : 'NO',
        'form_html_header' => $this->input->post('form_html_header'),
        'form_sync_cms' => $this->input->post('form_sync_cms') ? 'YES' : 'NO',
        
        'form_limit_person' => $this->input->post('form_limit_person') ? 'YES' : 'NO',
        'form_topic_person' => $this->input->post('form_topic_person'),
        'form_limit_min' => $this->input->post('form_limit_min'),
        'form_limit_max' => $this->input->post('form_limit_max'),
        'form_copy_information_register' => $this->input->post('form_copy_information_register') ? 'YES' : 'NO',
        'form_copy_information_qa' => $this->input->post('form_copy_information_qa') ? 'YES' : 'NO',
        'form_copy_address' => $this->input->post('form_copy_address') ? 'YES' : 'NO',
        'form_copy_tocenter' => $this->input->post('form_copy_tocenter') ? 'YES' : 'NO',
        'form_copy_mainperson' => $this->input->post('form_copy_mainperson') ? 'YES' : 'NO',
        'form_copy_mainfield' => $this->input->post('form_copy_mainfield'),
        
        'form_is_custom_serial' => $this->input->post('form_is_custom_serial') ? 'YES' : 'NO',
        'form_serial_text' => $this->input->post('form_serial_text'),
        'form_serial_date' => $this->input->post('form_serial_date') ? 'YES' : 'NO',
        'form_date_format' => $this->input->post('form_date_format'),
        'form_serial_digits' => $this->input->post('form_serial_digits'),
        
        'form_type_pdpa' => $this->input->post('form_type_pdpa'),
        'form_html_pdpa' => $this->input->post('form_html_pdpa'),
        
        'sync_ticketID' => $this->input->post('sync_ticketID'),
        'sync_eventID' => $this->input->post('sync_eventID'),
        'sync_registerFormConfigID' => $this->input->post('sync_registerFormConfigID'),
        'sync_registerConfigID' => $this->input->post('sync_registerConfigID'),
        'form_sync_subregister' => $this->input->post('form_sync_subregister') ? 'YES' : 'NO',
        'form_sync_bmm' => $this->input->post('form_sync_bmm') ? 'YES' : 'NO',
        
        'form_html_complete' => $this->input->post('form_html_complete'),
        'form_html_mail' => $this->input->post('form_html_mail'),
        'form_is_send_templatemail' => $this->input->post('form_is_send_templatemail') ? 'YES' : 'NO',
        'form_templateID' => $this->input->post('form_templateID'),
        'form_template_ebadge' => $this->input->post('form_template_ebadge'),
        
        'updateDT' => date('Y-m-d H:i:s'),
        'updateBY' => $this->UserName
    );

    // // Handle file uploads
    // if (!empty($_FILES['form_file_pdpa']['name'])) {
    //     $pdpa_file = $this->handle_file_upload('form_file_pdpa', 'pdpa_files');
    //     if ($pdpa_file['status']) {
    //         $data['form_file_pdpa'] = $pdpa_file['filename'];
    //     }
    // }

    // if (!empty($_FILES['form_badge_image_harder']['name'])) {
    //     $header_image = $this->handle_file_upload('form_badge_image_harder', 'badge_images');
    //     if ($header_image['status']) {
    //         $data['form_badge_image_harder'] = $header_image['filename'];
    //     }
    // }

    // if (!empty($_FILES['form_badge_image_footer']['name'])) {
    //     $footer_image = $this->handle_file_upload('form_badge_image_footer', 'badge_images');
    //     if ($footer_image['status']) {
    //         $data['form_badge_image_footer'] = $footer_image['filename'];
    //     }
    // }

    try {
        // Clean the data
        $clean_data = $this->security->xss_clean($data);
        
        // Update the form
        $result = $this->Mdl_forms->updateData($clean_data, 'tc_config_form_new', 'form_no', $form_no);
        
        if ($result) {
            echo json_encode(['status' => true, 'message' => 'Form updated successfully']);
        } else {
            echo json_encode(['status' => false, 'message' => 'No changes made to the form']);
        }
    } catch (Exception $e) {
        log_message('error', 'Form update error: ' . $e->getMessage());
        echo json_encode(['status' => false, 'message' => 'Error updating form: ' . $e->getMessage()]);
    }
}

private function handle_file_upload($file_field, $upload_path) {
    $config['upload_path'] = FCPATH . 'uploads/' . $upload_path;
    $config['allowed_types'] = 'gif|jpg|png|pdf';
    $config['max_size'] = 2048; // 2MB
    $config['encrypt_name'] = TRUE;

    if (!is_dir($config['upload_path'])) {
        mkdir($config['upload_path'], 0777, true);
    }

    $this->load->library('upload', $config);

    if ($this->upload->do_upload($file_field)) {
        $upload_data = $this->upload->data();
        return [
            'status' => true,
            'filename' => $upload_data['file_name']
        ];
    }

    return [
        'status' => false,
        'error' => $this->upload->display_errors()
    ];
}

    public function get_form($form_id) {
        $form = $this->Mdl_forms->get_form($form_id);
        
        if ($form) {
            echo json_encode(['status' => true, 'form' => $form]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Form not found']);
        }
    }
    
    public function render_form($form_id) {
        $form = $this->Mdl_forms->get_form($form_id);
        
        if (!$form) {
            show_404();
            return;
        }
        
        $data['form'] = $form;
        $this->load->view('form_builder/render_form', $data);
    }
    
    public function submit_form() {
        $form_id = $this->input->post('form_id');
        $form_data = $this->input->post('form_data');
        
        // Process file uploads if any
        $uploaded_files = [];
        if (!empty($_FILES)) {
            foreach ($_FILES as $field_name => $file_info) {
                if ($file_info['error'] == 0) {
                    $config['upload_path'] = './uploads/';
                    $config['allowed_types'] = 'gif|jpg|png|pdf|doc|docx';
                    $config['max_size'] = 2048;
                    $config['encrypt_name'] = TRUE;
                    
                    $this->load->library('upload', $config);
                    
                    if ($this->upload->do_upload($field_name)) {
                        $uploaded_files[$field_name] = $this->upload->data('file_name');
                    }
                }
            }
        }
        
        // Merge uploaded files with form data
        if (!empty($uploaded_files)) {
            foreach ($uploaded_files as $field => $filename) {
                $form_data[$field] = $filename;
            }
        }
        
        $submission_id = $this->Mdl_forms->save_submission($form_id, $form_data);
        
        if ($submission_id) {
            $this->session->set_flashdata('success', 'Form submitted successfully');
            redirect('form_builder/thank_you');
        } else {
            $this->session->set_flashdata('error', 'Failed to submit form');
            redirect('form_builder/render_form/' . $form_id);
        }
    }
    
    public function thank_you() {
        $this->load->view('form_builder/thank_you');
    }
    
    public function export_submissions($form_id) {
        $form = $this->Mdl_forms->get_form($form_id);
        $submissions = $this->Mdl_forms->get_submissions($form_id);
        
        if (!$form || !$submissions) {
            show_404();
            return;
        }
        
        // Process form fields to get exportable fields
        $form_fields = json_decode($form->form_data, true);
        $export_fields = [];
        
        foreach ($form_fields as $field) {
            if (isset($field['export']) && $field['export'] == true) {
                $export_fields[] = $field;
            }
        }
        
        // Generate CSV file
        $filename = 'form_submissions_' . $form_id . '_' . date('Ymd') . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // CSV header row
        $header = array_map(function($field) {
            return $field['label'];
        }, $export_fields);
        
        fputcsv($output, $header);
        
        // CSV data rows
        foreach ($submissions as $submission) {
            $submission_data = json_decode($submission->submission_data, true);
            $row = [];
            
            foreach ($export_fields as $field) {
                $field_id = $field['id'];
                $row[] = isset($submission_data[$field_id]) ? $submission_data[$field_id] : '';
            }
            
            fputcsv($output, $row);
        }
        
        fclose($output);
        exit;
    }
    
    

    public function update($formID) {
        $this->data['viewName'] = 'Edit Register Form'; 
        $this->data['eventID'] = $this->event['eventID']; 
        $this->data['subdomain'] = $this->event['subdomain']; 
        
        $form = $this->Mdl_forms->get_forms($formID);        
        if (!$form) {
            show_404();
            return;
        }
        // Add form data to the view data
        $this->data['form'] = $form;
        $this->data['title'] = 'Update Form';
        
        // Debug - check what data we're sending to view
        // log_message('debug', 'Form data: ' . print_r($form, true));
        
        $this->packfunction->packViewNew($this->data, 'formbuilder/form_builderUpdate');
    }

    public function check_form_code() {
        $code = trim($this->input->post('code'));
        $subdomain = $this->event['subdomain'];
        
        // Validate input
        if (empty($code) || strlen($code) < 3) {
            echo json_encode(['valid' => false, 'message' => 'Form code must be at least 3 characters']);
            return;
        }

        if (preg_match('/\s/', $code)) {
            echo json_encode(['valid' => false, 'message' => 'Whitespace is not allowed']);
            return;
        }

        // Check for duplicates
        $is_duplicate = $this->Mdl_forms->check_duplicate_form_code($code, $subdomain);
        
        echo json_encode([
            'valid' => !$is_duplicate,
            'message' => $is_duplicate ? 'This form code already exists' : 'Form code is available'
        ]);
    }

    public function check_form_code_update() {
        $code = trim($this->input->post('code'));
        $form_no = trim($this->input->post('form_no')); // Get current form number
        $subdomain = $this->event['subdomain'];
        
        // Validate input
        if (empty($code) || strlen($code) < 3) {
            echo json_encode(['valid' => false, 'message' => 'Form code must be at least 3 characters']);
            return;
        }

        if (preg_match('/\s/', $code)) {
            echo json_encode(['valid' => false, 'message' => 'Whitespace is not allowed']);
            return;
        }

        // Check for duplicates excluding current form
        $is_duplicate = $this->Mdl_forms->check_duplicate_form_code_update($code, $subdomain, $form_no);
        
        echo json_encode([
            'valid' => !$is_duplicate,
            'message' => $is_duplicate ? 'This form code already exists' : 'Form code is available'
        ]);
    }

    public function generate_code() {
        $subdomain = $this->session->userdata('subdomain');
        $this->load->model('mdl_forms');
        
        $code = $this->mdl_forms->generate_unique_code($subdomain);
        
        if ($code) {
            echo json_encode(['status' => true, 'code' => $code]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Could not generate unique code']);
        }
    }

    public function deleteform() {
        if ($this->input->is_ajax_request()) {
            $form_id = $this->input->post('form_id');
            
            $data = array(
                'status' => 'DELETE',
                'updateDT' => $this->packfunction->dtYMDnow(),
                'updateBY' => $this->UserName
            );

            try {
                $clean_data = $this->security->xss_clean($data);
                $result = $this->Mdl_forms->updateData($clean_data, 'tc_config_form_new', 'MD5(form_no)', $form_id);
                
                if ($result) {
                    echo json_encode(['status' => 'success']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to delete form']);
                }
            } catch (Exception $e) {
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }
    }





    public function form_edit($form_no=''){

		$this->data['viewName']='Edit Register Form'; 
		$this->data['eventID'] =  $this->event['eventID']; 
		$this->data['subdomain']= $this->event['subdomain']; 
		// $this->data['getpaper']= $this->Mdl_badge->getdetail($this->data['subdomain'],$paperID);
		// if(count($this->data['getpaper'])>0){
		// 	$this->data['getonpaper']= $this->Mdl_badge->getonpaperdetail($this->data['subdomain'],Md5($this->data['getpaper']['paperID']));
		// 	$this->data['registerType']=array('visitor','exhibitor','vip','press','guest');

		// 	$this->packfunction->packViewNew($this->data,'formbuilder/form_builderEdit');
		// }else{
		// 	redirect('formbuilder');
		// }
        $this->packfunction->packViewNew($this->data,'formbuilder/form_edit');


	}



    private function get_static_fields() {
        // Get country and nationality lists
        $countries = $this->Mdl_forms->get_countries();
        $nationalities = $this->Mdl_forms->get_nationalities();

        return [
            [
                'type' => 'text',
                'label' => 'Title',
                'label_th' => 'คำนำหน้า',
                'placeholder' => 'Enter title',
                'required' => false,
                'name' => 'userTitle',
                'OriginalName' => 'Title'

            ],
            [
                'type' => 'text',
                'label' => 'First Name',
                'label_th' => 'ชื่อ',
                'placeholder' => 'Enter first name',
                'required' => true,
                'name' => 'userFname',
                'OriginalName' => 'First Name'
            ],
            [
                'type' => 'text',
                'label' => 'Middle Name',
                'label_th' => 'ชื่อกลาง',
                'placeholder' => 'Enter middle name',
                'required' => false,
                'name' => 'userMname',
                'OriginalName' => 'Middle Name'
            ],
            [
                'type' => 'text',
                'label' => 'Last Name',
                'label_th' => 'นามสกุล', 
                'placeholder' => 'Enter last name',
                'required' => true,
                'name' => 'userLname',
                'OriginalName' => 'Last Name'
            ],
            [
                'type' => 'email',
                'label' => 'Email',
                'label_th' => 'อีเมล',
                'placeholder' => 'Enter email address',
                'required' => true,
                'name' => 'userEmail',
                'OriginalName' => 'Email'
            ],
            [
                'type' => 'tel',
                'label' => 'Mobile No.',
                'label_th' => 'เบอร์โทรศัพท์มือถือ',
                'placeholder' => 'Enter mobile number',
                'required' => false,
                'name' => 'mobile',
                'OriginalName' => 'Mobile No.'
            ],

            [
                'type' => 'tel',
                'label' => 'Telephone',
                'label_th' => 'เบอร์โทรศัพท์',
                'placeholder' => 'Enter telephone',
                'required' => false,
                'name' => 'telephone',
                'OriginalName' => 'Telephone'
            ],
            [
                'type' => 'text',
                'label' => 'Company',
                'label_th' => 'บริษัท',
                'placeholder' => 'Enter company name',
                'required' => false,
                'name' => 'companyName',
                'OriginalName' => 'Company'
            ],
            [
                'type' => 'text',
                'label' => 'Position',
                'label_th' => 'ตำแหน่ง',
                'placeholder' => 'Enter position',
                'required' => false,
                'name' => 'position',
                'OriginalName' => 'Position'
            ],
            [
                'type' => 'text',
                'label' => 'Employee ID/Passport',
                'label_th' => 'รหัสพนักงาน/หนังสือเดินทาง',
                'placeholder' => 'Enter ID or passport number',
                'required' => false,
                'name' => 'idPassportNo',
                'OriginalName' => 'Employee ID/Passport'
            ],
            [
                'type' => 'text',
                'label' => 'State/Province',
                'label_th' => 'รัฐ/จังหวัด',
                'placeholder' => 'Enter State/Province',
                'required' => false,
                'name' => 'state',
                'OriginalName' => 'State/Province'
            ],
            [
                'type' => 'text',
                'label' => 'City',
                'label_th' => 'เมือง',
                'placeholder' => 'Enter city',
                'required' => false,
                'name' => 'city',
                'OriginalName' => 'City'
            ],
            [
                'type' => 'text',
                'label' => 'Sub District',
                'label_th' => 'แขวง/ตำบล',
                'placeholder' => 'Enter sub district',
                'required' => false,
                'name' => 'subdistrict',
                'OriginalName' => 'Sub District'
            ],
            [
                'type' => 'text',
                'label' => 'District',
                'label_th' => 'เขต/อำเภอ',
                'placeholder' => 'Enter district',
                'required' => false,
                'name' => 'district',
                'OriginalName' => 'District'
            ],
            [
                'type' => 'textarea',
                'label' => 'Address',
                'label_th' => 'ที่อยู่',
                'placeholder' => 'Enter address',
                'required' => false,
                'name' => 'address',
                'OriginalName' => 'Address'
            ],
            [
                'type' => 'text',
                'label' => 'Zip/Postal Code',
                'label_th' => 'รหัสไปรษณีย์',
                'placeholder' => 'Enter zip/postal code',
                'required' => false,
                'name' => 'postcode',
                'OriginalName' => 'Zip/Postal Code' 
                
            ],
            [
                // 'type' => 'country',
                'type' => 'select',
                'label' => 'Country',
                'label_th' => 'ประเทศ',
                'placeholder' => 'Select country',
                'options' => $countries,
                'required' => false,
                'special_type' => 'country', // Add this identifier
                'name' => 'countryID',
                'OriginalName' => 'Country'
                
            ],
            [
                // 'type' => 'nationality',
                'type' => 'select',
                'label' => 'Nationality',
                'label_th' => 'สัญชาติ',
                'placeholder' => 'Select nationality',
                'options' => $nationalities,
                'required' => false,
                'special_type' => 'nationality', // Add this identifier
                'name' => 'nationality', // Add this identifier
                'OriginalName' => 'Nationality'
            ],
            [
                'type' => 'file',
                'label' => 'Upload File',
                'label_th' => 'อัปโหลดไฟล์',
                'placeholder' => 'Upload a file',
                'required' => false,
                'accept' => 'image/*,application/pdf',
                'name' => 'brochure',
                'OriginalName' => 'Upload File'
            ],
            [
                'type' => 'file',
                'label' => 'Upload Profile',
                'label_th' => 'อัปโหลดโปรไฟล์',
                'placeholder' => 'Upload profile picture',
                'accept' => 'image/*',
                'required' => false,
                'name' => 'imgProfile',
                'OriginalName' => 'Upload Profile'
            ],
            [
                'type' => 'text',
                'label' => 'Username',
                'label_th' => 'ชื่อผู้ใช้',
                'placeholder' => 'Enter username',
                'required' => false,
                'name' => 'username',
                'OriginalName' => 'Username'
            ],
            [
                'type' => 'password',
                'label' => 'Password',
                'label_th' => 'รหัสผ่าน',
                'placeholder' => 'Enter password',
                'required' => false,
                'name' => 'password',
                'OriginalName' => 'Password'
            ],
            [
                'type' => 'text',
                'label' => 'Group Name',
                'label_th' => 'กลุ่ม',
                'placeholder' => 'Enter groupName',
                'required' => false,
                'name' => 'groupName',
                'OriginalName' => 'Group Name'
            ],
            [
                'type' => 'text',
                'label' => 'Website',
                'label_th' => 'เว็บไซต์',
                'placeholder' => 'Enter website',
                'required' => false,
                'name' => 'website',
                'OriginalName' => 'Website'
            ],
            [
                'type' => 'text',
                'label' => 'Facebook',
                'label_th' => 'เฟสบุ๊ค',
                'placeholder' => 'Enter Facebook link',
                'required' => false,
                'name' => 'facebook',
                'OriginalName' => 'Facebook'
            ],
            [
                'type' => 'text',
                'label' => 'Line ID',
                'label_th' => 'Line ID',
                'placeholder' => 'Enter Line ID',
                'required' => false,
                'name' => 'lineID',
                'OriginalName' => 'Line ID'
            ],
            [
                'type' => 'text',
                'label' => 'Flight No.',
                'label_th' => 'หมายเลขเที่ยวบิน',
                'placeholder' => 'Enter flight number',
                'required' => false,
                'name' => 'flightno',
                'OriginalName' => 'Flight No.'
            ],
            [
                'type' => 'text',
                'label' => 'Arrival Date',
                'label_th' => 'วันที่มาถึง',
                'placeholder' => 'Enter arrival date',
                'required' => false,
                'name' => 'arrivalDate',
                'OriginalName' => 'Arrival Date'
            ],
            [
                'type' => 'text',
                'label' => 'Hotel Name',
                'label_th' => 'ชื่อโรงแรม',
                'placeholder' => 'Enter hotel name',
                'required' => false,
                'name' => 'hotelName',
                'OriginalName' => 'Hotel Name'
            ],
            [
                'type' => 'text',
                'label' => 'Check-in Date',
                'label_th' => 'วันที่เช็คอิน',
                'placeholder' => 'Enter check-in date',
                'required' => false,
                'name' => 'checkin_date',
                'OriginalName' => 'Check-in Date'
            ],
            [
                'type' => 'text',
                'label' => 'Check-out Date',
                'label_th' => 'วันที่เช็คเอาท์',
                'placeholder' => 'Enter check-out date',
                'required' => false,
                'name' => 'checkout_date',
                'OriginalName' => 'Check-out Date'
            ]

        ];
    }
    
    private function get_dynamic_fields() {
        return [
            [
                'type' => 'text',
                'label' => 'Short Text',
                'placeholder' => 'Enter short text',
                'icon' => 'fa-font',
                'OriginalName' => 'Short Text'
            ],
            [
                'type' => 'textarea',
                'label' => 'Long Text',
                'placeholder' => 'Enter long text',
                'icon' => 'fa-align-left',
                'OriginalName' => 'Long Text'
            ],
            [
                'type' => 'static',
                'label' => 'Static Text',
                'icon' => 'fa-paragraph',
                'OriginalName' => 'Static Text'
            ],
            [
                'type' => 'select',
                'label' => 'Dropdown',
                'placeholder' => 'Select option',
                'icon' => 'fa-caret-down',
                'OriginalName' => 'Dropdown'
            ],
            [
                'type' => 'checkbox',
                'label' => 'Checkbox Group',
                'icon' => 'fa-check-square',
                'OriginalName' => 'Checkbox Group'
            ],
            [
                'type' => 'single-checkbox',
                'label' => 'Single Checkbox',
                'icon' => 'fa-check',
                'OriginalName' => 'Single Checkbox'
            ],
            [
                'type' => 'radio',
                'label' => 'Radio Buttons',
                'icon' => 'fa-circle',
                'OriginalName' => 'Radio Buttons'
            ],
            [
                'type' => 'date',
                'label' => 'Date Picker',
                'icon' => 'fa-calendar',
                'OriginalName' => 'Date Picker'
            ],
            [
                'type' => 'time',
                'label' => 'Time Picker',
                'icon' => 'fa-clock',
                'OriginalName' => 'Time Picker'
            ],
            [
                'type' => 'file',
                'label' => 'File Upload',
                'icon' => 'fa-file',
                'OriginalName' => 'File Upload'
            ],
            [
                'type' => 'image',
                'label' => 'Image Upload',
                'accept' => 'image/*',
                'icon' => 'fa-image',
                'OriginalName' => 'Image Upload'
            ],
            [
                'type' => 'video',
                'label' => 'Video Upload',
                'accept' => 'video/*',
                'icon' => 'fa-video',
                'OriginalName' => 'Video Upload'
                
            ],
            [
                'type' => 'daterange',
                'label' => 'Date Range Picker',
                'icon' => 'fa-calendar-range',
                'OriginalName' => 'Date Range Picker'
            ]
        ];
    }

    private function get_field_types() {
        return [
            'text' => 'Text Input',
            'textarea' => 'Text Area',
            'number' => 'Number',
            'email' => 'Email',
            'password' => 'Password',
            'tel' => 'Telephone',
            'select' => 'Dropdown',
            'radio' => 'Radio Buttons',
            'checkbox' => 'Checkbox Group',
            'date' => 'Date Picker',
            'time' => 'Time Picker',
            'datetime-local' => 'Date and Time',
            'file' => 'File Upload',
            'hidden' => 'Hidden Field'
        ];
    }

    public function form_create($formID = '') {
        $this->data['viewName'] = 'Field Form Builder';
        $this->data['eventID'] = $this->event['eventID'];
        $this->data['subdomain'] = $this->event['subdomain'];

        // Get form data
        $form = null;
        $form_fields = [];
        
        if ($formID) {
            $form = $this->Mdl_forms->get_forms($formID);
            if (!$form) {
                show_404();
                return;
            }
            // Get existing fields
            $form_fields = $this->Mdl_forms->get_form_fields($form['form_no']); 
        }

        // Add data to view
        $this->data['form'] = $form;
        $this->data['form_fields'] = json_encode($this->format_form_fields($form_fields));
        $this->data['title'] = $formID ? 'Update Form' : 'Create Form';
        
        $this->data['static_fields'] = $this->get_static_fields();
        $this->data['dynamic_fields'] = $this->get_dynamic_fields();
        $this->data['field_types'] = $this->get_field_types();
        
        $this->packfunction->packViewNew($this->data, 'formbuilder/form_create');
    }

    private function format_form_fields($fields) {
        $formatted = [];
        foreach ($fields as $field) {
            $options = [];
            
            // ตรวจสอบและใช้ค่า conf_field_list_choice จาก database
            if ($field->conf_field_is_choice === 'YES' && !empty($field->conf_field_list_choice)) {
                try {
                    $options = json_decode($field->conf_field_list_choice, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        throw new Exception('Invalid JSON format');
                    }
                } catch (Exception $e) {
                    log_message('error', 'Error parsing field options: ' . $e->getMessage());
                    $options = [];
                }
            }
            
            // Determine special type based on field name
            $special_type = $this->determine_special_type($field->conf_field_name);
            
            $formatted[] = [
                'id' => $field->conf_field_id,
                'name' => $field->conf_field_name,
                'conf_field_original_name' => $field->conf_field_original_name,
                'OriginalName' => $field->conf_field_original_name,
                'label' => $field->conf_field_label_en,
                'label_th' => $field->conf_field_label_th,
                'type' => $field->conf_field_type,
                'special_type' => $special_type,
                'required' => $field->conf_field_require === 'YES',
                'required_value' => $field->conf_field_require_value,
                'fullWidth' => $field->conf_field_column === '12',
                'placeholder' => $field->conf_field_placeholder,
                'options' => $options,
                'conf_field_list_choice' => $field->conf_field_list_choice,
                'is_choice' => $field->conf_field_is_choice === 'YES',
                'showInput' => $field->conf_field_is_choice_other === 'YES',
                'input_format' => $field->conf_field_keyupcase,
                'onsite' => $field->status === 'ON',
                'export' => $field->conf_include_export === 'YES',
                'unique' => $field->conf_field_checkdata === 'YES',
                'display' => $field->conf_display_on ? explode(',', $field->conf_display_on) : [],
                'description' => $field->conf_field_describe,
                'default' => $field->conf_field_default_value,
                'order' => $field->conf_field_order_by
            ];
        }
        return $formatted;
    }

    private function determine_special_type($field_name) {
        if ($field_name === 'countryID') {
            return 'country';
        } else if ($field_name === 'nationality') {
            return 'nationality';
        }
        return null;
    }

    public function save_fields_form() {
        try {

            
            $form_id = $this->input->post('form_id');
            if (!$form_id) {
                throw new Exception('Form ID is required');
            }

            $form = $this->Mdl_forms->get_forms(md5($form_id));
            if (!$form) {
                throw new Exception('Form not found');
            }

            $form_data = $this->input->post('form_data');
            $existing_fields = $this->Mdl_forms->get_form_fields($form_id);

            foreach ($form_data as $index => $field) {
                // Format options for country/nationality
                $is_choice = 'NO';
                $list_choice = null;

                if (isset($field['special_type']) && 
                    ($field['special_type'] === 'country' || $field['special_type'] === 'nationality')) {
                    
                    $is_choice = 'YES';
                    if (isset($field['options']) && is_array($field['options'])) {
                        // Ensure each option has correct structure
                        $options = array_map(function($option) use ($index) {
                            return [
                                'id' => isset($option['id']) ? $option['id'] : ($index + 1),
                                'value' => $option['value'],
                                // แปลงค่า show เป็น boolean อย่างชัดเจน
                                'show' => isset($option['show']) ? 
                                    filter_var($option['show'], FILTER_VALIDATE_BOOLEAN) : true,
                                'orderby' => isset($option['orderby']) ? intval($option['orderby']) : ($index + 1)
                            ];
                        }, $field['options']);

                        // Sort by orderby before saving
                        usort($options, function($a, $b) {
                            return $a['orderby'] - $b['orderby'];
                        });

                        $list_choice = json_encode($options);
                    }
                }

                // Handle regular choice fields (select, radio, checkbox)
                if (in_array($field['type'], ['select', 'radio', 'checkbox']) && isset($field['options'])) {
                    $is_choice = 'YES';
                    $list_choice = is_array($field['options']) ? json_encode($field['options']) : null;
                }

                $fieldData = array(
                    'form_id' => $form_id,
                    'subdomain' => $form['subdomain'],
                    'eventID' => $form['eventID'],
                    'registerType' => $form['registerType'],
                    'isPreWalk' => $form['isPreWalk'],
                    'conf_field_id' => $index + 1,
                    'conf_field_name' => isset($field['name']) ? $field['name'] : $field['label'],
                    'conf_field_original_name' => isset($field['OriginalName']) ? $field['OriginalName'] : 
                        (isset($field['conf_field_original_name']) ? $field['conf_field_original_name'] : 
                        (isset($field['name']) ? $field['name'] : $field['label'])),
                    'conf_field_label_en' => $field['label'],
                    'conf_field_label_th' => $field['label_th'],
                    'conf_field_type' => $field['type'],
                    'conf_field_group' => 'main',
                    'conf_field_require' => $field['required'] ? 'YES' : 'NO',
                    'conf_field_require_value' => $field['required_value'] ?? '',
                    'conf_field_column' => $field['fullWidth'] ? '12' : '6',
                    'conf_field_placeholder' => $field['placeholder'] ?? '',
                    'conf_field_is_choice' => $is_choice,
                    'conf_field_list_choice' => $list_choice,
                    'conf_field_order_by' => $index + 1,
                    'status' => isset($field['onsite']) && $field['onsite'] ? 'ON' : 'OFF',
                    'conf_include_export' => isset($field['export']) && $field['export'] ? 'YES' : 'NO',
                    'conf_field_checkdata' => isset($field['unique']) && $field['unique'] ? 'YES' : 'NO',
                    'createDT' => date('Y-m-d H:i:s'),
                    'createBY' => $this->UserName
                );

                // Clean data
                $fieldData = $this->security->xss_clean($fieldData);

                // Insert field
                $this->Mdl_forms->insert_field($fieldData);
            }

            echo json_encode(['status' => true, 'message' => 'Fields saved successfully']);

        } catch (Exception $e) {
            log_message('error', 'Save fields error: ' . $e->getMessage());
            echo json_encode(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function delete_field() {
        try {
            $form_id = $this->input->post('form_id');
            $field_name = $this->input->post('field_name');
            
            if (!$form_id || !$field_name) {
                throw new Exception('Missing required data');
            }

            // Delete field
            $this->db->where('form_id', $form_id);
            $this->db->where('conf_field_name', $field_name);
            $result = $this->db->delete('tc_config_field_new');

            if ($result) {
                // Reorder remaining fields
                $this->db->where('form_id', $form_id);
                $this->db->order_by('conf_field_order_by', 'ASC');
                $remaining_fields = $this->db->get('tc_config_field_new')->result();

                foreach ($remaining_fields as $index => $field) {
                    $this->db->where('form_id', $form_id);
                    $this->db->where('conf_field_id', $field->conf_field_id);
                    $this->db->update('tc_config_field_new', ['conf_field_order_by' => $index + 1]);
                }

                echo json_encode(['status' => true, 'message' => 'Field deleted successfully']);
            } else {
                throw new Exception('Failed to delete field');
            }
        } catch (Exception $e) {
            log_message('error', 'Delete field error: ' . $e->getMessage());
            echo json_encode(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function duplicate_form() {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        try {
            $form_id = $this->input->post('form_id');
            
            // Get original form
            $original_form = $this->Mdl_forms->get_forms($form_id);
            if (!$original_form) {
                throw new Exception('Original form not found');
            }

            // Generate unique code
            $new_code = $this->Mdl_forms->generate_unique_code($original_form['subdomain']);
            if (!$new_code) {
                throw new Exception('Could not generate unique form code');
            }

            // Create new form data
            $new_form = $original_form;
            $original_form_no = $new_form['form_no']; // Store original form_no
            unset($new_form['form_no']); // Remove primary key
            $new_form['form_code'] = $new_code;
            $new_form['form_name'] = $original_form['form_name'] . ' (Copy)';
            $new_form['createDT'] = date('Y-m-d H:i:s');
            $new_form['createBY'] = $this->UserName;
            $new_form['updateDT'] = null;
            $new_form['updateBY'] = null;

            // Insert new form and get the new form_no
            $new_form_no = $this->Mdl_forms->insert_form($new_form);
            if (!$new_form_no) {
                throw new Exception('Failed to create new form');
            }

            // Get and duplicate form fields using original form_no
            $original_fields = $this->Mdl_forms->get_form_fields($original_form_no);
            foreach ($original_fields as $field) {
                $new_field = (array)$field;
                unset($new_field['id']); // Remove primary key if exists
                $new_field['form_id'] = $new_form_no; // Use the new form_no
                $new_field['createDT'] = date('Y-m-d H:i:s');
                $new_field['createBY'] = $this->UserName;
                $new_field['updateDT'] = null;
                $new_field['updateBY'] = null;

                $this->Mdl_forms->insert_field($new_field);
            }

            echo json_encode([
                'status' => 'success',
                'message' => 'Form duplicated successfully',
                'new_form_no' => $new_form_no // Return new form_no for reference
            ]);

        } catch (Exception $e) {
            log_message('error', 'Form duplication error: ' . $e->getMessage());
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}