<?php

/**
 * Class to handle importing FAQs into the plugin
 */

if ( !defined( 'ABSPATH' ) )
	exit;

if (!class_exists('ComposerAutoloaderInit4618f5c41cf5e27cc7908556f031e4d4')) {require_once EWD_UFAQ_PLUGIN_DIR . '/lib/PHPSpreadsheet/vendor/autoload.php';}
use PhpOffice\PhpSpreadsheet\Spreadsheet;
class ewdufaqImport {

	public $status;
	public $message;

	public function __construct() {
		add_action( 'admin_menu', array($this, 'register_install_screen' ));

		if ( isset( $_POST['ewdufaqImport'] ) ) { add_action( 'admin_init', array($this, 'import_faqs' )); }

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_import_scripts' ) );
	}

	public function register_install_screen() {
		global $ewd_ufaq_controller;
		
		add_submenu_page( 
			'edit.php?post_type=ufaq', 
			'Import Menu', 
			'Import', 
			$ewd_ufaq_controller->settings->get_setting( 'access-role' ), 
			'ewd-ufaq-import', 
			array($this, 'display_import_screen') 
		);
	}

	public function display_import_screen() {
		global $ewd_ufaq_controller;

		$import_permission = $ewd_ufaq_controller->permissions->check_permission( 'import' );
		?>
		<div class='wrap'>
			<h2>Import</h2>
			<?php if ( $import_permission ) { ?> 
				<form method='post' enctype="multipart/form-data">
					
					<?php wp_nonce_field( 'EWD_UFAQ_Import', 'EWD_UFAQ_Import_Nonce' );  ?>

					<p>
						<label for="ewd_ufaq_faqs_spreadsheet"><?php _e( 'Spreadsheet Containing FAQs', 'ultimate-faqs' ) ?></label><br />
						<input name="ewd_ufaq_faqs_spreadsheet" type="file" value=""/>
					</p>
					<input type='submit' name='ewdufaqImport' value='Import FAQs' class='button button-primary' />
				</form>
			<?php } else { ?>
				<div class='ewd-ufaq-premium-locked'>
					<a href="https://www.etoilewebdesign.com/license-payment/?Selected=UFAQ&Quantity=1" target="_blank">Upgrade</a> to the premium version to use this feature
				</div>
			<?php } ?>
		</div>
	<?php }

	public function import_faqs() {
		global $ewd_ufaq_controller;

		if ( ! current_user_can( 'edit_posts' ) ) { return; }

		if ( ! isset( $_POST['EWD_UFAQ_Import_Nonce'] ) ) { return; }

    	if ( ! wp_verify_nonce( $_POST['EWD_UFAQ_Import_Nonce'], 'EWD_UFAQ_Import' ) ) { return; }

		$update = $this->handle_spreadsheet_upload();

    	$fields = ewd_ufaq_decode_infinite_table_setting( $ewd_ufaq_controller->settings->get_setting( 'faq-fields' ) );

		if ( $update['message_type'] != 'Success' ) :
			$this->status = false;
			$this->message =  $update['message'];

			add_action( 'admin_notices', array( $this, 'display_notice' ) );

			return;
		endif;

		$excel_url = EWD_UFAQ_PLUGIN_DIR . '/faq-sheets/' . $update['filename'];

	    // Build the workbook object out of the uploaded spreadsheet
	    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load( $excel_url );
	
	    // Create a worksheet object out of the product sheet in the workbook
	    $sheet = $spreadsheet->getActiveSheet();
	
	    $allowable_custom_fields = array();
	    foreach ( $fields as $field ) { $allowable_custom_fields[] = $field->name; }
	    //List of fields that can be accepted via upload
	    $allowed_fields = array( 'ID', 'Question', 'Answer', 'Categories', 'Tags', 'Post Date' );
	
	    // Get column names
	    $highest_column = $sheet->getHighestColumn();
	    $highest_column_index = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString( $highest_column );
	    for ( $column = 1; $column <= $highest_column_index; $column++ ) {

	    	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'ID' ) { $ID_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Question' ) { $question_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Answer' ) { $answer_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Categories' ) { $categories_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Tags' ) { $tags_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Post Date' ) { $date_column = $column; }
	
	        foreach ( $fields as $field ) {

        	    if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == $field->name ) { $field->column = $column; }
        	}
	    }

	    $ID_column = ! empty( $ID_column ) ? $ID_column : -1;
	    $question_column = ! empty( $question_column ) ? $question_column : -1;
	    $answer_column = ! empty( $answer_column ) ? $answer_column : -1;
	    $categories_column = ! empty( $categories_column ) ? $categories_column : -1;
	    $tags_column = ! empty( $tags_column ) ? $tags_column : -1;
	    $date_column = ! empty( $date_column ) ? $date_column : -1;
	
	    // Put the spreadsheet data into a multi-dimensional array to facilitate processing
	    $highest_row = $sheet->getHighestRow();
	    for ( $row = 2; $row <= $highest_row; $row++ ) {
	        for ( $column = 1; $column <= $highest_column_index; $column++ ) {
	            $data[$row][$column] = $sheet->getCellByColumnAndRow( $column, $row )->getValue();
	        }
	    }
	
	    // Create the query to insert the products one at a time into the database and then run it
	    foreach ( $data as $faq ) {
	        
	        // Create an array of the values that are being inserted for each FAQ
	     	$post = array();
	     	$field_values = array();
	        foreach ( $faq as $col_index => $value ) {

	            if ( $col_index == $ID_column ) { $post['ID'] = esc_sql( $value ); }
            	elseif ( $col_index == $question_column ) { $post['post_title'] = esc_sql( $value ); }
            	elseif ( $col_index == $answer_column ) { $post['post_content'] = esc_sql( $value ); }
            	elseif ( $col_index == $date_column ) { $post['post_date'] = esc_sql( $value ); }
            	elseif ( $col_index == $categories_column ) { $post_categories = explode( ',', esc_sql( $value ) ); }
            	elseif ( $col_index == $tags_column ) { $post_tags = explode( ',', esc_sql( $value ) ); }
            	else {

            		foreach ( $fields as $field ) {

            			if ( $col_index == $field->column ) { $field_values[ $field->name ] = esc_sql( $value ); }
            		}
            	}
	        }

	        $post['post_status'] = 'publish';
        	$post['post_type'] = EWD_UFAQ_FAQ_POST_TYPE;

        	$post_id = wp_insert_post( $post );

        	if ( $post_id != 0 ) {

            	if ( ! empty( $post_categories ) ) {
            	    
            		$category_ids = array();
            	    foreach ( $post_categories as $category ) {
            	        
            	        $term = term_exists( $category, EWD_UFAQ_FAQ_CATEGORY_TAXONOMY );
            	        if ( ! empty( $term ) ) { $category_ids[] = (int) $term['term_id']; }
            	    }
            	}
            	if ( isset( $category_ids ) and is_array( $category_ids ) ) { wp_set_object_terms( $post_id, $category_ids, EWD_UFAQ_FAQ_CATEGORY_TAXONOMY ); }

            	if ( ! empty( $post_tags ) ) {
            	    
            		$tag_ids = array();
            	    foreach ( $post_tags as $tag ) {
            	        
            	        $term = term_exists( $tag, EWD_UFAQ_FAQ_TAG_TAXONOMY );
            	        if ( ! empty( $term ) ) { $tag_ids[] = (int) $term['term_id']; }
            	    }
            	}
            	if ( isset( $tag_ids ) and is_array( $tag_ids ) ) { wp_set_object_terms( $post_id, $tag_ids, EWD_UFAQ_FAQ_TAG_TAXONOMY ); }
				
            	foreach ( $fields as $field ) {

            		if ( empty( $field->column ) ) { continue; }
            	    
            	    if ( $field->type == 'checkbox' ) { update_post_meta( $post_id, "Custom_Field_" . $field->id, explode( ',', $field_values[ $field->name ] ) ); }
            	    else { update_post_meta( $post_id, "Custom_Field_" . $field->id, $field_values[ $field->name ]); }
            	}
			}
	
        	unset( $post_categories );
        	unset( $category_ids );
        	unset( $post_tags );
        	unset( $tag_ids );
	    }

	    $this->status = true;
		$this->message = __( 'FAQs added successfully.', 'ultimate-faqs' );

		add_action( 'admin_notices', array( $this, 'display_notice' ) );
	}

	function handle_spreadsheet_upload() {
		  /* Test if there is an error with the uploaded spreadsheet and return that error if there is */
        if ( ! empty( $_FILES['ewd_ufaq_faqs_spreadsheet']['error'] ) ) {
                
            switch( $_FILES['ewd_ufaq_faqs_spreadsheet']['error'] ) {

                case '1':
                    $error = __( 'The uploaded file exceeds the upload_max_filesize directive in php.ini', 'ultimate-faqs' );
                    break;
                case '2':
                    $error = __( 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form', 'ultimate-faqs' );
                    break;
                case '3':
                    $error = __( 'The uploaded file was only partially uploaded', 'ultimate-faqs' );
                    break;
                case '4':
                    $error = __( 'No file was uploaded.', 'ultimate-faqs' );
                    break;

                case '6':
                    $error = __( 'Missing a temporary folder', 'ultimate-faqs' );
                    break;
                case '7':
                    $error = __( 'Failed to write file to disk', 'ultimate-faqs' );
                    break;
                case '8':
                    $error = __( 'File upload stopped by extension', 'ultimate-faqs' );
                    break;
                case '999':
                    default:
                    $error = __( 'No error code avaiable', 'ultimate-faqs' );
            }
        }
        /* Make sure that the file exists */
        elseif ( empty($_FILES['ewd_ufaq_faqs_spreadsheet']['tmp_name']) || $_FILES['ewd_ufaq_faqs_spreadsheet']['tmp_name'] == 'none' ) {
                $error = __( 'No file was uploaded here..', 'ultimate-faqs' );
        }
        /* Move the file and store the URL to pass it onwards*/
        /* Check that it is a .xls or .xlsx file */ 
        if ( ! isset($_FILES['ewd_ufaq_faqs_spreadsheet']['name'] ) or ( ! preg_match("/\.(xls.?)$/", $_FILES['ewd_ufaq_faqs_spreadsheet']['name'] ) and ! preg_match( "/\.(csv.?)$/", $_FILES['ewd_ufaq_faqs_spreadsheet']['name'] ) ) ) {
            $error = __( 'File must be .csv, .xls or .xlsx', 'ultimate-faqs' );
        }
        else {
            $filename = basename( $_FILES['ewd_ufaq_faqs_spreadsheet']['name'] );
            $filename = mb_ereg_replace( "([^\w\s\d\-_~,;\[\]\(\).])", '', $filename );
            $filename = mb_ereg_replace ("([\.]{2,})", '', $filename );

            //for security reason, we force to remove all uploaded file
            $target_path = EWD_UFAQ_PLUGIN_DIR . "/faq-sheets/";

            $target_path = $target_path . $filename;

            if ( ! move_uploaded_file($_FILES['ewd_ufaq_faqs_spreadsheet']['tmp_name'], $target_path ) ) {
                $error .= "There was an error uploading the file, please try again!";
            }
            else {
                $excel_file_name = $filename;
            }
        }

        /* Pass the data to the appropriate function in Update_Admin_Databases.php to create the products */
        if ( ! isset( $error ) ) {
                $update = array( "message_type" => "Success", "filename" => $excel_file_name );
        }
        else {
                $update = array( "message_type" => "Error", "message" => $error );
        }

        return $update;
	}

	public function enqueue_import_scripts() {

		$screen = get_current_screen();

		if ( $screen->id == 'ufaq_page_ewd-ufaq-import' ) {

			wp_enqueue_style( 'ewd-ufaq-admin-css', EWD_UFAQ_PLUGIN_URL . '/assets/css/ewd-ufaq-admin.css', array(), '2.0.0' );

			wp_register_script( 'ewd-ufaq-admin-js', EWD_UFAQ_PLUGIN_URL . '/assets/js/ewd-ufaq-admin.js', array( 'jquery', 'jquery-ui-sortable' ), EWD_UFAQ_VERSION, true );

			$args = array();
			
			wp_localize_script( 'ewd-ufaq-admin-js', 'ewd_ufaq_php_data', $args );
	
			wp_enqueue_script( 'ewd-ufaq-admin-js' );
		}
	}

	public function display_notice() {

		if ( $this->status ) {

			echo "<div class='updated'><p>" . $this->message . "</p></div>";
		}
		else {

			echo "<div class='error'><p>" . $this->message . "</p></div>";
		}
	}

}


