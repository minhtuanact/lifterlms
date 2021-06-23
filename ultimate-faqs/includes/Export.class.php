<?php

/**
 * Class to export reviews created by the plugin
 */

if ( !defined( 'ABSPATH' ) )
	exit;

if (!class_exists('ComposerAutoloaderInit4618f5c41cf5e27cc7908556f031e4d4')) { require_once EWD_UFAQ_PLUGIN_DIR . '/lib/PHPSpreadsheet/vendor/autoload.php'; }
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
class ewdufaqExport {

	public function __construct() {
		add_action( 'admin_menu', array($this, 'register_install_screen' ));

		if ( isset( $_POST['ewd_ufaq_export'] ) ) { add_action( 'admin_menu', array($this, 'export_faqs' )); }
		if ( isset( $_POST['ewd_ufaq_export_pdf'] ) ) { add_action( 'admin_menu', array($this, 'export_faqs_pdf' )); }

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_export_scripts' ) );
	}

	public function register_install_screen() {
		global $ewd_ufaq_controller;
		
		add_submenu_page( 
			'edit.php?post_type=ufaq', 
			'Export Menu', 
			'Export', 
			$ewd_ufaq_controller->settings->get_setting( 'access-role' ), 
			'ewd-ufaq-export', 
			array($this, 'display_export_screen') 
		);
	}

	public function display_export_screen() {
		global $ewd_ufaq_controller;

		$export_permission = $ewd_ufaq_controller->permissions->check_permission( 'export' );

		?>
		<div class='wrap'>
			<h2>Export</h2>
			<?php if ( $export_permission ) { ?> 
				<form method='post'>
					<?php wp_nonce_field( 'EWD_UFAQ_Export_PDF', 'EWD_UFAQ_Export_PDF_Nonce' );  ?>
					<input type='submit' name='ewd_ufaq_export' value='Export to Spreadsheet' class='button button-primary' />
				</form>
				<form method='post'>
					<?php wp_nonce_field( 'EWD_UFAQ_Export_PDF', 'EWD_UFAQ_Export_PDF_Nonce' );  ?>
					<input type='submit' name='ewd_ufaq_export_pdf' value='Export to PDF' class='button button-primary' />
				</form>
			<?php } else { ?>
				<div class='ewd-ufaq-premium-locked'>
					<a href="https://www.etoilewebdesign.com/license-payment/?Selected=UFAQ&Quantity=1" target="_blank">Upgrade</a> to the premium version to use this feature
				</div>
			<?php } ?>
		</div>
	<?php }

	public function export_faqs() {
		global $ewd_ufaq_controller;

		if ( ! isset( $_POST['EWD_UFAQ_Export_PDF_Nonce'] ) ) { return; }

    	if ( ! wp_verify_nonce( $_POST['EWD_UFAQ_Export_PDF_Nonce'], 'EWD_UFAQ_Export_PDF' ) ) { return; }

		$faq_fields = ewd_ufaq_decode_infinite_table_setting( $ewd_ufaq_controller->settings->get_setting( 'faq-fields' ) );

		// Instantiate a new PHPExcel object
		$spreadsheet = new Spreadsheet();
		// Set the active Excel worksheet to sheet 0
		$spreadsheet->setActiveSheetIndex(0);

		// Print out the regular review field labels
		$spreadsheet->getActiveSheet()->setCellValue( 'A1', 'ID' );
		$spreadsheet->getActiveSheet()->setCellValue( 'B1', 'Question' );
		$spreadsheet->getActiveSheet()->setCellValue( 'C1', 'Answer' );
		$spreadsheet->getActiveSheet()->setCellValue( 'D1', 'Categories' );
		$spreadsheet->getActiveSheet()->setCellValue( 'E1', 'Tags' );
		$spreadsheet->getActiveSheet()->setCellValue( 'F1', 'Post Date' );

		$column = 'G';
		foreach ( $faq_fields as $faq_field ) {

			$spreadsheet->getActiveSheet()->setCellValue( $column . '1', $faq_field->name );
    		$column++;
		}

		//start while loop to get data
		$row_count = 2;

		$params = array(
			'posts_per_page' => -1,
			'post_type' => EWD_UFAQ_FAQ_POST_TYPE
		);

		$faqs = get_posts( $params );

		foreach ( $faqs as $faq ) {

    	 	$categories = strip_tags( get_the_term_list( $faq->ID, EWD_UFAQ_FAQ_CATEGORY_TAXONOMY, '', ',' ) );
    	 	$tags = strip_tags( get_the_term_list( $faq->ID, EWD_UFAQ_FAQ_TAG_TAXONOMY, '', ',' ) );

    	 	$spreadsheet->getActiveSheet()->setCellValue( 'A' . $row_count, $faq->ID );
			$spreadsheet->getActiveSheet()->setCellValue( 'B' . $row_count, $faq->post_title );
			$spreadsheet->getActiveSheet()->setCellValue( 'C' . $row_count, $faq->post_content );
			$spreadsheet->getActiveSheet()->setCellValue( 'D' . $row_count, $categories );
			$spreadsheet->getActiveSheet()->setCellValue( 'E' . $row_count, $tags );
			$spreadsheet->getActiveSheet()->setCellValue( 'F' . $row_count, $faq->post_date );

			$column = 'G';
			foreach ( $faq_fields as $faq_field ) {

				$field_value = get_post_meta( $faq->ID, "Custom_Field_" . $faq_field->id, true );
				$field_value = is_array( $field_value ) ? implode( ',', $field_value ) : $field_value;
				
				$spreadsheet->getActiveSheet()->setCellValue( $column . $row_count, $field_value );
   				$column++;
			}
			
    		$row_count++;
		}

		// Redirect output to a client’s web browser (Excel5)
		if (!isset($format_type) == "csv") {

			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="faqs_export.csv"');
			header('Cache-Control: max-age=0');
			$objWriter = new Csv($spreadsheet);
			$objWriter->save('php://output');
			die();
		}
		else {

			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="faqs_export.xls"');
			header('Cache-Control: max-age=0');
			$objWriter = new Xls($spreadsheet);
			$objWriter->save('php://output');
			die();
		}
	}

	public function export_faqs_pdf() {
		global $ewd_ufaq_controller;

		if ( ! isset( $_POST['EWD_UFAQ_Export_PDF_Nonce'] ) ) { return; }

    	if ( ! wp_verify_nonce( $_POST['EWD_UFAQ_Export_PDF_Nonce'], 'EWD_UFAQ_Export_PDF' ) ) { return; }

		$faq_fields = ewd_ufaq_decode_infinite_table_setting( $ewd_ufaq_controller->settings->get_setting( 'faq-fields' ) );

		require_once EWD_UFAQ_PLUGIN_DIR . '/lib/FPDF/fpdf.php' ;

		$params = array(
			'posts_per_page' => -1,
			'post_type' => EWD_UFAQ_FAQ_POST_TYPE
		);

		$faqs = get_posts( $params );

		$pdf_passes = array(
			'first_page_run', 
			'second_page_run', 
			'final'
		);

		$table_of_contents = array();

		foreach ( $pdf_passes as $pdf_pass ) {

			$pdf = new FPDF();
			$pdf->AddPage();

			if ( $pdf_pass == 'second_page_run' or $pdf_pass == 'final' ) {
				
				$pdf->SetFont( 'Arial', 'B', 14 );
				$pdf->Cell( 20, 10, 'Page #' );
				$pdf->Cell( 20, 10, 'Article Title' );
				$pdf->Ln();
				$pdf->SetFont( 'Arial', '', 12 );

				foreach ( $table_of_contents as $entry ) {
					$pdf->Cell(20, 5, "  " . $entry['page']);
					$pdf->MultiCell(0, 5, $entry['title']);
					$pdf->Ln();
				}

				unset( $table_of_contents );
			}

			foreach ($faqs as $faq) {

				$question = utf8_decode( strip_tags( html_entity_decode( $faq->post_title ) ) );

				$answer = utf8_decode( strip_tags( html_entity_decode( $faq->post_content ) ) );
				$answer = str_replace( '&#91;', '[', $answer );
				$answer = str_replace( '&#93;', ']', $answer );

				$pdf->AddPage();

				$entry = array(
					'page' => $pdf->GetPage(),
					'title' => $question
				);

				$pdf->SetFont( 'Arial', 'B', 15 );
				$pdf->MultiCell( 0, 10, $question );
				$pdf->Ln();
				$pdf->SetFont( 'Arial', '', 12 );
				$pdf->MultiCell( 0, 10, $answer );

				$table_of_contents[] = $entry;
			}

			if ( $pdf_pass == 'first_page_run' or $pdf_pass == 'second_page_run' ) {

				$pdf->Close();
			}

			if ( $pdf_pass == 'final' ) {

		 		$pdf->Output( 'Ultimate-FAQ-Manual.pdf', 'D' );
			}
		}
	}

	public function enqueue_export_scripts() {

		$screen = get_current_screen();

		if ( $screen->id == 'ufaq_page_ewd-ufaq-export' ) {

			wp_enqueue_style( 'ewd-ufaq-admin-css', EWD_UFAQ_PLUGIN_URL . '/assets/css/ewd-ufaq-admin.css', array(), EWD_UFAQ_VERSION );

			wp_register_script( 'ewd-ufaq-admin-js', EWD_UFAQ_PLUGIN_URL . '/assets/js/ewd-ufaq-admin.js', array( 'jquery', 'jquery-ui-sortable' ), EWD_UFAQ_VERSION, true );

			$args = array();
			
			wp_localize_script( 'ewd-ufaq-admin-js', 'ewd_ufaq_php_data', $args );
	
			wp_enqueue_script( 'ewd-ufaq-admin-js' );
		}
	}

}


