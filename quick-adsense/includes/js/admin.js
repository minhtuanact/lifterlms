jQuery(document).ready(function() {
	jQuery('#quick_adsense_settings_reset_to_default').click(quick_adsense_settings_reset_to_default);	
	
	jQuery('#quick_adsense_settings_enable_position_beginning_of_post').click(quick_adsense_settings_enable_position_beginning_of_post);
	jQuery('#quick_adsense_settings_enable_position_middle_of_post').click(quick_adsense_settings_enable_position_middle_of_post);
	jQuery('#quick_adsense_settings_enable_position_end_of_post').click(quick_adsense_settings_enable_position_end_of_post);	
	jQuery('#quick_adsense_settings_enable_position_after_more_tag').click(quick_adsense_settings_enable_position_after_more_tag);	
	jQuery('#quick_adsense_settings_enable_position_before_last_para').click(quick_adsense_settings_enable_position_before_last_para);	
	jQuery('#quick_adsense_settings_enable_position_after_para_option_1').click(quick_adsense_settings_enable_position_after_para_option_1);	
	jQuery('#quick_adsense_settings_enable_position_after_para_option_2').click(quick_adsense_settings_enable_position_after_para_option_2);	
	jQuery('#quick_adsense_settings_enable_position_after_para_option_3').click(quick_adsense_settings_enable_position_after_para_option_3);	
	jQuery('#quick_adsense_settings_enable_position_after_image_option_1').click(quick_adsense_settings_enable_position_after_image_option_1);	
	jQuery('#quick_adsense_settings_enable_on_posts').click(quick_adsense_settings_enable_on_posts);
	jQuery('#quick_adsense_settings_enable_on_pages').click(quick_adsense_settings_enable_on_pages);	
	jQuery('#quick_adsense_settings_enable_on_homepage').click(quick_adsense_settings_enable_on_homepage);
	jQuery('#quick_adsense_settings_enable_on_categories').click(quick_adsense_settings_enable_on_categories);
	jQuery('#quick_adsense_settings_enable_on_archives').click(quick_adsense_settings_enable_on_archives);
	jQuery('#quick_adsense_settings_enable_on_tags').click(quick_adsense_settings_enable_on_tags);
	jQuery('#quick_adsense_settings_enable_all_possible_ads').click(quick_adsense_settings_enable_all_possible_ads);	
	jQuery('#quick_adsense_settings_disable_widgets_on_homepage').click(quick_adsense_settings_disable_widgets_on_homepage);	
	jQuery('#quick_adsense_settings_disable_for_loggedin_users').click(quick_adsense_settings_disable_for_loggedin_users);	
	jQuery('#quick_adsense_settings_enable_quicktag_buttons').click(quick_adsense_settings_enable_quicktag_buttons);	
	jQuery('#quick_adsense_settings_disable_randomads_quicktag_button').click(quick_adsense_settings_disable_randomads_quicktag_button);	
	jQuery('#quick_adsense_settings_disable_disablead_quicktag_buttons').click(quick_adsense_settings_disable_disablead_quicktag_buttons);	
	jQuery('#quick_adsense_settings_disable_positionad_quicktag_buttons').click(quick_adsense_settings_disable_positionad_quicktag_buttons);
	
	jQuery('#quick_adsense_settings_onpost_enable_global_style').click(quick_adsense_settings_onpost_enable_global_style);	
	jQuery('#quick_adsense_settings_onpost_global_alignment').click(quick_adsense_settings_onpost_enable_global_style);
	jQuery('#quick_adsense_settings_onpost_global_margin').click(quick_adsense_settings_onpost_enable_global_style);
	
	jQuery('#quick_adsense_settings_ad_beginning_of_post').change(function() {
		quick_adsense_vi_check_status(this);
		quick_adsense_settings_handle_vi_single_selection();
	});

	jQuery('#quick_adsense_settings_ad_middle_of_post').change(function() {
		quick_adsense_vi_check_status(this);
		quick_adsense_settings_handle_vi_single_selection();
	});
	
	quick_adsense_settings_enable_position_beginning_of_post();
	quick_adsense_settings_enable_position_middle_of_post();
	quick_adsense_settings_enable_position_end_of_post();
	quick_adsense_settings_enable_position_after_more_tag();
	quick_adsense_settings_enable_position_before_last_para();
	quick_adsense_settings_enable_position_after_para_option_1();
	quick_adsense_settings_enable_position_after_para_option_2();
	quick_adsense_settings_enable_position_after_para_option_3();
	quick_adsense_settings_enable_position_after_image_option_1();
	quick_adsense_settings_enable_on_posts();
	quick_adsense_settings_enable_on_pages();
	quick_adsense_settings_enable_on_homepage();
	quick_adsense_settings_enable_on_categories();
	quick_adsense_settings_enable_on_archives();
	quick_adsense_settings_enable_on_tags();
	quick_adsense_settings_enable_all_possible_ads();
	quick_adsense_settings_disable_widgets_on_homepage();
	quick_adsense_settings_disable_for_loggedin_users();
	quick_adsense_settings_enable_quicktag_buttons();
	quick_adsense_settings_disable_randomads_quicktag_button();
	quick_adsense_settings_disable_disablead_quicktag_buttons();
	quick_adsense_settings_disable_positionad_quicktag_buttons();
	quick_adsense_settings_onpost_enable_global_style();
	quick_adsense_settings_handle_vi_single_selection();
	
	jQuery('#quick_adsense_onpost_content_adunits_showall_button').click(function() {
		if(jQuery('#quick_adsense_onpost_content_adunits_showall_button').text() == ' Show All') {
			jQuery('#quick_adsense_onpost_content_adunits_all_wrapper').slideDown();
			jQuery('#quick_adsense_onpost_content_adunits_showall_button').html('<span class="dashicons dashicons-arrow-up"></span> <b>Show Less</b>');
		} else {
			jQuery('#quick_adsense_onpost_content_adunits_all_wrapper').slideUp();
			jQuery('#quick_adsense_onpost_content_adunits_showall_button').html('<span class="dashicons dashicons-arrow-down"></span> <b>Show All</b>');
		}
	});
	
	jQuery('#quick_adsense_widget_adunits_showall_button').click(function() {
		if(jQuery('#quick_adsense_widget_adunits_showall_button').text() == ' Show All') {
			jQuery('#quick_adsense_widget_adunits_all_wrapper').slideDown();
			jQuery('#quick_adsense_widget_adunits_showall_button').html('<span class="dashicons dashicons-arrow-up"></span> <b>Show Less</b>');
		} else {
			jQuery('#quick_adsense_widget_adunits_all_wrapper').slideUp();
			jQuery('#quick_adsense_widget_adunits_showall_button').html('<span class="dashicons dashicons-arrow-down"></span> <b>Show All</b>');
		}
	});

	jQuery('#quick_adsense_settings_form').submit(function() {
		jQuery('#quick_adsense_settings_form select').each(function() {
			if(jQuery(this).prop('disabled') == true) {
				jQuery(this).prop('disabled', false);
			}
		});
	});

	jQuery('#quick_adsense_settings_form').fadeIn();
	if(window.location.href.indexOf('#quick_adsense_adstxt_adsense_auto_update') > -1) {
		quick_adsense_adstxt_adsense_auto_update();
	}
	
	jQuery('.quick_adsense_onpost_ad_reset_stats').click(function() {
		jQuery(this).prop('disabled', true);
		jQuery.post(
			jQuery('#quick_adsense_admin_ajax').val(), {
				'action': 'quick_adsense_onpost_ad_reset_stats',
				'index': jQuery(this).attr('data-index'),
				'quick_adsense_nonce': jQuery('#quick_adsense_nonce').val()
			}, function(response) {
				if(response.indexOf('###SUCCESS###') !== -1) {
					jQuery('.quick_adsense_onpost_ad_reset_stats').each(function() {
						jQuery(this).prop('disabled', false);
					});
				}
			}
		);
	});
	
	jQuery('.quick_adsense_onpost_ad_show_stats').click(function() {
		jQuery('<div id="quick_adsense_onpost_ad_show_stats_dialog" data-index="'+jQuery(this).attr('data-index')+'"></div>').html('<div class="quick_adsense_ajaxloader"></div>').dialog({
			'modal': true,
			'resizable': false,
			'width': jQuery("body").width() * 0.5,
			'maxWidth': jQuery("body").width() * 0.5,
			'maxHeight': jQuery("body").height() * 0.9,
			'title': 'Ad'+jQuery(this).attr('data-index')+' Performance Stats (30 Days)',
			position: { my: 'center', at: 'center', of: window },
			open: function (event, ui) {
				jQuery('.ui-dialog').css({'z-index': 999999, 'max-width': '90%'});
				jQuery('.ui-widget-overlay').css({'z-index': 999998, 'opacity': 0.8, 'background': '#000000'});
				jQuery.post(
					jQuery('#quick_adsense_admin_ajax').val(), {
						'action': 'quick_adsense_onpost_ad_get_stats_chart',
						'index': jQuery('#quick_adsense_onpost_ad_show_stats_dialog').attr('data-index'),
						'quick_adsense_nonce': jQuery('#quick_adsense_nonce').val()
					}, function(response) {
						jQuery('.quick_adsense_ajaxloader').hide();
						jQuery('.ui-dialog-content').html(response.replace('###SUCCESS###', ''));
						jQuery('.ui-accordion .ui-accordion-content').css('max-height', (jQuery("body").height() * 0.45));
						jQuery('.ui-dialog').css({'position': 'fixed'});
						jQuery('#quick_adsense_onpost_ad_show_stats_dialog').delay(500).dialog({position: { my: 'center', at: 'center', of: window }});
						
						jQuery('#quick_adsense_ad_stats_chart_wrapper canvas').attr('width', jQuery('#quick_adsense_ad_stats_chart_wrapper').width()+'px');
						jQuery('#quick_adsense_ad_stats_chart_wrapper canvas').attr('height', jQuery('#quick_adsense_ad_stats_chart_wrapper').height()+'px');
						if(jQuery('#quick_adsense_ad_stats_chart_data').length) {
							var quick_adsense_ad_stats_chart = new Chart(jQuery('#quick_adsense_ad_stats_chart'), {
								type: 'line',
								responsive: false,
								data: {
									datasets: [{
										data: JSON.parse(jQuery('#quick_adsense_ad_stats_chart_data').val()),
										backgroundColor: '#EDF5FB',
										borderColor: '#186EAE',/*E8EBEF*/
										borderWidth: 1
									}]
								},
								options: {
									title: {
										display: false,
										backgroundColor: '#EDF5FB'
									},
									legend: {
										display: false,
									},
									scales: {
										xAxes: [{
											type: "time",
											display: true,
											scaleLabel: {
												display: true
											},
											gridLines: {
												display: true,
												drawTicks: true
											},
											ticks: {
												display: true
											}
										}],
										yAxes: [{
											display: true,
											scaleLabel: {
												display: true
											},
											gridLines: {
												display: true,
												drawTicks: true
											},
											ticks: {
												display: true
											}
										}]
									},
									tooltips: {
										displayColors: false,
										callbacks: {
											label: function(tooltipItem, data) {
												return ['Impressions: '+parseInt(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]['y'],  10), 'Clicks: '+parseInt(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]['y1'],  10)];
											},
											title: function(tooltipItem, data) {
												var monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
												var dateParts = tooltipItem[0].xLabel.split('/');
												var date = new Date(dateParts[2], dateParts[0]-1, dateParts[1]);
												return monthNames[date.getMonth()]+' '+date.getDate();
											}
										}
									}
								}
							});
						}
					}
				);
			},
			buttons: { },
			close: function() {
				jQuery(this).dialog('destroy');
			}
		})
	});
});

function quick_adsense_adstxt_adsense_auto_update() {
	jQuery.post(
		jQuery('#quick_adsense_adstxt_adsense_admin_notice_ajax').val(), {
			'action': 'quick_adsense_adstxt_adsense_auto_update',
			'quick_adsense_adstxt_adsense_admin_notice_nonce': jQuery('#quick_adsense_adstxt_adsense_admin_notice_nonce').val(),
		}, function(response) {
			if(response != '###SUCCESS###') {
				jQuery(response).dialog({
					'modal': true,
					'resizable': false,
					'title': 'Ads.txt Auto Updation Failed',
					'width': jQuery("body").width() * 0.5,
					'maxWidth': jQuery("body").width() * 0.5,
					'maxHeight': jQuery("body").height() * 0.9,
					position: { my: 'center', at: 'center', of: window },
					open: function (event, ui) {
						jQuery('.ui-dialog').css({'z-index': 999999, 'max-width': '90%'});
						jQuery('.ui-widget-overlay').css({'z-index': 999998, 'opacity': 0.8, 'background': '#000000'});
					},
					buttons : {
						'Cancel': function() {
							jQuery(this).dialog("close");
						}
					},
					close: function() {
						jQuery(this).dialog('destroy');
					}
				});
			} else {
				jQuery('.quick_adsense_adstxt_adsense_notice').hide();
			}
		}
	);
}

function quick_adsense_settings_reset_to_default() {
	jQuery('#quick_adsense_settings_max_ads_per_page').val('3');
	
	jQuery('#quick_adsense_settings_enable_position_beginning_of_post').prop('checked', true);		
	jQuery('#quick_adsense_settings_ad_beginning_of_post').val('1');
	jQuery('#quick_adsense_settings_enable_position_middle_of_post').prop('checked', false);
	jQuery('#quick_adsense_settings_ad_middle_of_post').val('0');
	jQuery('#quick_adsense_settings_enable_position_end_of_post').prop('checked', true);
	jQuery('#quick_adsense_settings_ad_end_of_post').val('0');
	
	jQuery('#quick_adsense_settings_enable_position_after_more_tag').prop('checked', false);
	jQuery('#quick_adsense_settings_ad_after_more_tag').val('0');
	jQuery('#quick_adsense_settings_enable_position_before_last_para').prop('checked', false);
	jQuery('#quick_adsense_settings_ad_before_last_para').val('0');
	
	for(var i = 1; i <= 3; i++) {
		jQuery('#quick_adsense_settings_enable_position_after_para_option_'+i).prop('checked', false);
		jQuery('#quick_adsense_settings_ad_after_para_option_'+i).val('0');
		jQuery('#quick_adsense_settings_position_after_para_option_'+i).val('1');
		jQuery('#quick_adsense_settings_enable_jump_position_after_para_option_'+i).prop('checked', false);
	}
	
	for(var i = 1; i <= 1; i++) {
		jQuery('#quick_adsense_settings_enable_position_after_image_option_'+i).prop('checked', false);
		jQuery('#quick_adsense_settings_ad_after_image_option_'+i).val('0');
		jQuery('#quick_adsense_settings_position_after_image_option_'+i).val('1');
		jQuery('#quick_adsense_settings_enable_jump_position_after_image_option_'+i).prop('checked', false);
	}
	
	jQuery('#quick_adsense_settings_enable_on_posts').prop('checked', true);
	jQuery('#quick_adsense_settings_enable_on_pages').prop('checked', true);
	
	jQuery('#quick_adsense_settings_enable_on_homepage').prop('checked', false);
	jQuery('#quick_adsense_settings_enable_on_categories').prop('checked', false);
	jQuery('#quick_adsense_settings_enable_on_archives').prop('checked', false);
	jQuery('#quick_adsense_settings_enable_on_tags').prop('checked', false);
	jQuery('#quick_adsense_settings_enable_all_possible_ads').prop('checked', false);
	
	jQuery('#quick_adsense_settings_disable_widgets_on_homepage').prop('checked', false);
	
	jQuery('#quick_adsense_settings_disable_for_loggedin_users').prop('checked', false);
	
	jQuery('#quick_adsense_settings_enable_quicktag_buttons').prop('checked', true);
	jQuery('#quick_adsense_settings_disable_randomads_quicktag_button').prop('checked', false);
	jQuery('#quick_adsense_settings_disable_disablead_quicktag_buttons').prop('checked', false);
	jQuery('#quick_adsense_settings_disable_positionad_quicktag_buttons').prop('checked', false);
	
	jQuery('#quick_adsense_settings_onpost_enable_global_style').prop('checked', false);
	jQuery('#quick_adsense_settings_onpost_global_alignment').val('2');
	jQuery('#quick_adsense_settings_onpost_global_margin').val('10');
		
	for(var i = 1; i <= 10; i++) {
		jQuery('#quick_adsense_settings_onpost_ad_'+i+'_content').val('');
		jQuery('#quick_adsense_settings_onpost_ad_'+i+'_alignment').val('2');
		jQuery('#quick_adsense_settings_onpost_ad_'+i+'_margin').val('10');
		
		jQuery('#quick_adsense_settings_widget_ad_'+i+'_content').val('');
	}
	
	quick_adsense_settings_enable_position_beginning_of_post();
	quick_adsense_settings_enable_position_middle_of_post();
	quick_adsense_settings_enable_position_end_of_post();
	quick_adsense_settings_enable_position_after_more_tag();
	quick_adsense_settings_enable_position_before_last_para();
	quick_adsense_settings_enable_position_after_para_option_1();
	quick_adsense_settings_enable_position_after_para_option_2();
	quick_adsense_settings_enable_position_after_para_option_3();
	quick_adsense_settings_enable_position_after_image_option_1();
	quick_adsense_settings_enable_on_posts();
	quick_adsense_settings_enable_on_pages();
	quick_adsense_settings_enable_on_homepage();
	quick_adsense_settings_enable_on_categories();
	quick_adsense_settings_enable_on_archives();
	quick_adsense_settings_enable_on_tags();
	quick_adsense_settings_enable_all_possible_ads();
	quick_adsense_settings_disable_widgets_on_homepage();
	quick_adsense_settings_disable_for_loggedin_users();
	quick_adsense_settings_enable_quicktag_buttons();
	quick_adsense_settings_disable_randomads_quicktag_button();
	quick_adsense_settings_disable_disablead_quicktag_buttons();
	quick_adsense_settings_disable_positionad_quicktag_buttons();
	quick_adsense_settings_onpost_enable_global_style();
	quick_adsense_settings_handle_vi_single_selection();
}

function quick_adsense_settings_enable_position_beginning_of_post() {
	if(jQuery('#quick_adsense_settings_enable_position_beginning_of_post').prop('checked') == true) {
		jQuery('#quick_adsense_settings_ad_beginning_of_post').prop('disabled', false);
		jQuery('#quick_adsense_settings_ad_beginning_of_post').parent().removeClass('disabled');
	} else {
		jQuery('#quick_adsense_settings_ad_beginning_of_post').prop('disabled', true);
		jQuery('#quick_adsense_settings_ad_beginning_of_post').parent().addClass('disabled');
	}
	quick_adsense_settings_handle_vi_single_selection();
}

function quick_adsense_settings_enable_position_middle_of_post() {
	if(jQuery('#quick_adsense_settings_enable_position_middle_of_post').prop('checked') == true) {
		jQuery('#quick_adsense_settings_ad_middle_of_post').prop('disabled', false);
		jQuery('#quick_adsense_settings_ad_middle_of_post').parent().removeClass('disabled');
	} else {
		jQuery('#quick_adsense_settings_ad_middle_of_post').prop('disabled', true);
		jQuery('#quick_adsense_settings_ad_middle_of_post').parent().addClass('disabled');
	}
	quick_adsense_settings_handle_vi_single_selection();
}

function quick_adsense_settings_enable_position_end_of_post() {
	if(jQuery('#quick_adsense_settings_enable_position_end_of_post').prop('checked') == true) {
		jQuery('#quick_adsense_settings_ad_end_of_post').prop('disabled', false);
		jQuery('#quick_adsense_settings_ad_end_of_post').parent().removeClass('disabled');
	} else {
		jQuery('#quick_adsense_settings_ad_end_of_post').prop('disabled', true);
		jQuery('#quick_adsense_settings_ad_end_of_post').parent().addClass('disabled');
	}
}

function quick_adsense_settings_enable_position_after_more_tag() {
	if(jQuery('#quick_adsense_settings_enable_position_after_more_tag').prop('checked') == true) {
		jQuery('#quick_adsense_settings_ad_after_more_tag').prop('disabled', false);
		jQuery('#quick_adsense_settings_ad_after_more_tag').parent().removeClass('disabled');
	} else {
		jQuery('#quick_adsense_settings_ad_after_more_tag').prop('disabled', true);
		jQuery('#quick_adsense_settings_ad_after_more_tag').parent().addClass('disabled');
	}
}
	
function quick_adsense_settings_enable_position_before_last_para() {
	if(jQuery('#quick_adsense_settings_enable_position_before_last_para').prop('checked') == true) {
		jQuery('#quick_adsense_settings_ad_before_last_para').prop('disabled', false);
		jQuery('#quick_adsense_settings_ad_before_last_para').parent().removeClass('disabled');
	} else {
		jQuery('#quick_adsense_settings_ad_before_last_para').prop('disabled', true);
		jQuery('#quick_adsense_settings_ad_before_last_para').parent().addClass('disabled');
	}
}

function quick_adsense_settings_enable_position_after_para_option_1() {
	if(jQuery('#quick_adsense_settings_enable_position_after_para_option_1').prop('checked') == true) {
		jQuery('#quick_adsense_settings_ad_after_para_option_1').prop('disabled', false);
		jQuery('#quick_adsense_settings_position_after_para_option_1').prop('disabled', false);
		jQuery('#quick_adsense_settings_enable_jump_position_after_para_option_1').prop('disabled', false);
		jQuery('#quick_adsense_settings_ad_after_para_option_1').parent().removeClass('disabled');
	} else {
		jQuery('#quick_adsense_settings_ad_after_para_option_1').prop('disabled', true);
		jQuery('#quick_adsense_settings_position_after_para_option_1').prop('disabled', true);
		jQuery('#quick_adsense_settings_enable_jump_position_after_para_option_1').prop('disabled', true);
		jQuery('#quick_adsense_settings_ad_after_para_option_1').parent().addClass('disabled');
	}
}

function quick_adsense_settings_enable_position_after_para_option_2() {
	if(jQuery('#quick_adsense_settings_enable_position_after_para_option_2').prop('checked') == true) {
		jQuery('#quick_adsense_settings_ad_after_para_option_2').prop('disabled', false);
		jQuery('#quick_adsense_settings_position_after_para_option_2').prop('disabled', false);
		jQuery('#quick_adsense_settings_enable_jump_position_after_para_option_2').prop('disabled', false);
		jQuery('#quick_adsense_settings_ad_after_para_option_2').parent().removeClass('disabled');
	} else {
		jQuery('#quick_adsense_settings_ad_after_para_option_2').prop('disabled', true);
		jQuery('#quick_adsense_settings_position_after_para_option_2').prop('disabled', true);
		jQuery('#quick_adsense_settings_enable_jump_position_after_para_option_2').prop('disabled', true);
		jQuery('#quick_adsense_settings_ad_after_para_option_2').parent().addClass('disabled');
	}
}

function quick_adsense_settings_enable_position_after_para_option_3() {
	if(jQuery('#quick_adsense_settings_enable_position_after_para_option_3').prop('checked') == true) {
		jQuery('#quick_adsense_settings_ad_after_para_option_3').prop('disabled', false);
		jQuery('#quick_adsense_settings_position_after_para_option_3').prop('disabled', false);
		jQuery('#quick_adsense_settings_enable_jump_position_after_para_option_3').prop('disabled', false);
		jQuery('#quick_adsense_settings_ad_after_para_option_3').parent().removeClass('disabled');
	} else {
		jQuery('#quick_adsense_settings_ad_after_para_option_3').prop('disabled', true);
		jQuery('#quick_adsense_settings_position_after_para_option_3').prop('disabled', true);
		jQuery('#quick_adsense_settings_enable_jump_position_after_para_option_3').prop('disabled', true);
		jQuery('#quick_adsense_settings_ad_after_para_option_3').parent().addClass('disabled');
	}
}

function quick_adsense_settings_enable_position_after_image_option_1() {
	if(jQuery('#quick_adsense_settings_enable_position_after_image_option_1').prop('checked') == true) {
		jQuery('#quick_adsense_settings_ad_after_image_option_1').prop('disabled', false);
		jQuery('#quick_adsense_settings_position_after_image_option_1').prop('disabled', false);
		jQuery('#quick_adsense_settings_enable_jump_position_after_image_option_1').prop('disabled', false);
		jQuery('#quick_adsense_settings_ad_after_image_option_1').parent().removeClass('disabled');
	} else {
		jQuery('#quick_adsense_settings_ad_after_image_option_1').prop('disabled', true);
		jQuery('#quick_adsense_settings_position_after_image_option_1').prop('disabled', true);
		jQuery('#quick_adsense_settings_enable_jump_position_after_image_option_1').prop('disabled', true);
		jQuery('#quick_adsense_settings_ad_after_image_option_1').parent().addClass('disabled');
	}
}

function quick_adsense_settings_enable_on_posts() {
	if(jQuery('#quick_adsense_settings_enable_on_posts').prop('checked') == true) {
		jQuery('#quick_adsense_settings_enable_on_posts').parent().removeClass('disabled');
	} else {
		jQuery('#quick_adsense_settings_enable_on_posts').parent().addClass('disabled');
	}
}

function quick_adsense_settings_enable_on_pages() {
	if(jQuery('#quick_adsense_settings_enable_on_pages').prop('checked') == true) {
		jQuery('#quick_adsense_settings_enable_on_pages').parent().removeClass('disabled');
	} else {
		jQuery('#quick_adsense_settings_enable_on_pages').parent().addClass('disabled');
	}
}

function quick_adsense_settings_enable_on_homepage() {
	if(jQuery('#quick_adsense_settings_enable_on_homepage').prop('checked') == true) {
		jQuery('#quick_adsense_settings_enable_on_homepage').parent().removeClass('disabled');
	} else {
		jQuery('#quick_adsense_settings_enable_on_homepage').parent().addClass('disabled');
	}
}

function quick_adsense_settings_enable_on_categories() {
	if(jQuery('#quick_adsense_settings_enable_on_categories').prop('checked') == true) {
		jQuery('#quick_adsense_settings_enable_on_categories').parent().removeClass('disabled');
	} else {
		jQuery('#quick_adsense_settings_enable_on_categories').parent().addClass('disabled');
	}
}

function quick_adsense_settings_enable_on_archives() {
	if(jQuery('#quick_adsense_settings_enable_on_archives').prop('checked') == true) {
		jQuery('#quick_adsense_settings_enable_on_archives').parent().removeClass('disabled');
	} else {
		jQuery('#quick_adsense_settings_enable_on_archives').parent().addClass('disabled');
	}
}

function quick_adsense_settings_enable_on_tags() {
	if(jQuery('#quick_adsense_settings_enable_on_tags').prop('checked') == true) {
		jQuery('#quick_adsense_settings_enable_on_tags').parent().removeClass('disabled');
	} else {
		jQuery('#quick_adsense_settings_enable_on_tags').parent().addClass('disabled');
	}
}

function quick_adsense_settings_enable_all_possible_ads() {
	if(jQuery('#quick_adsense_settings_enable_all_possible_ads').prop('checked') == true) {
		jQuery('#quick_adsense_settings_enable_all_possible_ads').parent().removeClass('disabled');
	} else {
		jQuery('#quick_adsense_settings_enable_all_possible_ads').parent().addClass('disabled');
	}
}

function quick_adsense_settings_disable_widgets_on_homepage() {
	if(jQuery('#quick_adsense_settings_disable_widgets_on_homepage').prop('checked') == true) {
		jQuery('#quick_adsense_settings_disable_widgets_on_homepage').parent().removeClass('disabled');
	} else {
		jQuery('#quick_adsense_settings_disable_widgets_on_homepage').parent().addClass('disabled');
	}
}

function quick_adsense_settings_disable_for_loggedin_users() {
	if(jQuery('#quick_adsense_settings_disable_for_loggedin_users').prop('checked') == true) {
		jQuery('#quick_adsense_settings_disable_for_loggedin_users').parent().removeClass('disabled');
	} else {
		jQuery('#quick_adsense_settings_disable_for_loggedin_users').parent().addClass('disabled');
	}
}

function quick_adsense_settings_enable_quicktag_buttons() {
	if(jQuery('#quick_adsense_settings_enable_quicktag_buttons').prop('checked') == true) {
		jQuery('#quick_adsense_settings_enable_quicktag_buttons').parent().removeClass('disabled');
	} else {
		jQuery('#quick_adsense_settings_enable_quicktag_buttons').parent().addClass('disabled');
	}
}

function quick_adsense_settings_disable_randomads_quicktag_button() {
	if(jQuery('#quick_adsense_settings_disable_randomads_quicktag_button').prop('checked') == true) {
		jQuery('#quick_adsense_settings_disable_randomads_quicktag_button').parent().removeClass('disabled');
	} else {
		jQuery('#quick_adsense_settings_disable_randomads_quicktag_button').parent().addClass('disabled');
	}
}

function quick_adsense_settings_disable_disablead_quicktag_buttons() {
	if(jQuery('#quick_adsense_settings_disable_disablead_quicktag_buttons').prop('checked') == true) {
		jQuery('#quick_adsense_settings_disable_disablead_quicktag_buttons').parent().removeClass('disabled');
	} else {
		jQuery('#quick_adsense_settings_disable_disablead_quicktag_buttons').parent().addClass('disabled');
	}
}

function quick_adsense_settings_disable_positionad_quicktag_buttons() {
	if(jQuery('#quick_adsense_settings_disable_positionad_quicktag_buttons').prop('checked') == true) {
		jQuery('#quick_adsense_settings_disable_positionad_quicktag_buttons').parent().removeClass('disabled');
	} else {
		jQuery('#quick_adsense_settings_disable_positionad_quicktag_buttons').parent().addClass('disabled');
	}
}

function quick_adsense_settings_onpost_enable_global_style() {
	if(jQuery('#quick_adsense_settings_onpost_enable_global_style').prop('checked') == true) {
		jQuery('#quick_adsense_settings_onpost_enable_global_style').parent().removeClass('disabled');
		jQuery('#quick_adsense_settings_onpost_global_alignment').prop('disabled', false);
		jQuery('#quick_adsense_settings_onpost_global_margin').prop('disabled', false);
		for(var i = 1; i <= 10; i++) {
			jQuery('#quick_adsense_settings_onpost_ad_'+i+'_alignment').val(jQuery('#quick_adsense_settings_onpost_global_alignment').val());
			jQuery('#quick_adsense_settings_onpost_ad_'+i+'_margin').val(jQuery('#quick_adsense_settings_onpost_global_margin').val());
			jQuery('#quick_adsense_settings_onpost_ad_'+i+'_alignment').prop('disabled', true);
			jQuery('#quick_adsense_settings_onpost_ad_'+i+'_margin').prop('disabled', true);
			jQuery('#quick_adsense_settings_onpost_ad_'+i+'_alignment').parent().addClass('disabled');
		}
	} else {
		jQuery('#quick_adsense_settings_onpost_enable_global_style').parent().addClass('disabled');
		jQuery('#quick_adsense_settings_onpost_global_alignment').prop('disabled', true);
		jQuery('#quick_adsense_settings_onpost_global_margin').prop('disabled', true);
		for(var i = 1; i <= 10; i++) {
			jQuery('#quick_adsense_settings_onpost_ad_'+i+'_alignment').prop('disabled', false);
			jQuery('#quick_adsense_settings_onpost_ad_'+i+'_margin').prop('disabled', false);
			jQuery('#quick_adsense_settings_onpost_ad_'+i+'_alignment').parent().removeClass('disabled');
		}
	}
}

function quick_adsense_settings_handle_vi_single_selection() {
	/*	
	Logic Table for vi Single Selection
									Beginning Enabled, Other		Beginning Enabled, vi		Beginning Disabled, Other		Beginning Disabled, vi
									
	Middle Enabled, Other			B1, M1							B1, M0						B0, M1							B0, M1, Random - B

	Middle Enabled, vi				B0, M1							------						B0, M1							B0, M1, Random - B
		
	Middle Disabled, Other			B1, M0							B1, M0						B0,	M0							B0, M0, Random B

	Middle Disabled, vi				B1, M0, Random - M				B1, M0, Random - M			B0,	M0, Random M				B0, M0, Random B, Random M
	*/
	if(jQuery('#quick_adsense_settings_enable_position_middle_of_post').prop('checked') == true) {
		if(jQuery('#quick_adsense_settings_ad_middle_of_post').val() != '100') {
			if(jQuery('#quick_adsense_settings_enable_position_beginning_of_post').prop('checked') == true) {
				if(jQuery('#quick_adsense_settings_ad_beginning_of_post').val() != '100') { //Middle Enabled, Other + Beginning Enabled, Other = B1, M1
					jQuery('#quick_adsense_settings_ad_beginning_of_post').children('option[value="100"]').prop('disabled', false);
					jQuery('#quick_adsense_settings_ad_middle_of_post').children('option[value="100"]').prop('disabled', false);
				} else { //Middle Enabled, Other + Beginning Enabled, vi = B1, M0
					jQuery('#quick_adsense_settings_ad_beginning_of_post').children('option[value="100"]').prop('disabled', false);
					jQuery('#quick_adsense_settings_ad_middle_of_post').children('option[value="100"]').prop('disabled', true);
				}
			} else {
				if(jQuery('#quick_adsense_settings_ad_beginning_of_post').val() != '100') { //Middle Enabled, Other + Beginning Disabled, Other = B0, M1
					jQuery('#quick_adsense_settings_ad_beginning_of_post').children('option[value="100"]').prop('disabled', true);
					jQuery('#quick_adsense_settings_ad_middle_of_post').children('option[value="100"]').prop('disabled', false);
				} else { //Middle Enabled, Other + Beginning Disabled, vi = B0, M1, Random - B
					jQuery('#quick_adsense_settings_ad_beginning_of_post').children('option[value="100"]').prop('disabled', true);
					jQuery('#quick_adsense_settings_ad_middle_of_post').children('option[value="100"]').prop('disabled', false);
					jQuery('#quick_adsense_settings_ad_beginning_of_post').val('0');
				}
			}
		} else {
			if(jQuery('#quick_adsense_settings_enable_position_beginning_of_post').prop('checked') == true) {
				if(jQuery('#quick_adsense_settings_ad_beginning_of_post').val() != '100') { //Middle Enabled, vi + Beginning Enabled, Other = B0, M1
					jQuery('#quick_adsense_settings_ad_beginning_of_post').children('option[value="100"]').prop('disabled', true);
					jQuery('#quick_adsense_settings_ad_middle_of_post').children('option[value="100"]').prop('disabled', false);
				} else { //Middle Enabled, vi + Beginning Enabled, vi = ------
					// This state should never be reached - Error
					alert('Error');
				}
			} else {
				if(jQuery('#quick_adsense_settings_ad_beginning_of_post').val() != '100') { //Middle Enabled, vi + Beginning Disabled, Other = B0, M1
					jQuery('#quick_adsense_settings_ad_beginning_of_post').children('option[value="100"]').prop('disabled', true);
					jQuery('#quick_adsense_settings_ad_middle_of_post').children('option[value="100"]').prop('disabled', false);
				} else { //Middle Enabled, vi + Beginning Disabled, vi = B0, M1, Random - B
					jQuery('#quick_adsense_settings_ad_beginning_of_post').children('option[value="100"]').prop('disabled', true);
					jQuery('#quick_adsense_settings_ad_middle_of_post').children('option[value="100"]').prop('disabled', false);
					jQuery('#quick_adsense_settings_ad_beginning_of_post').val('0');
				}
			}
		}
	} else {
		if(jQuery('#quick_adsense_settings_ad_middle_of_post').val() != '100') {
			if(jQuery('#quick_adsense_settings_enable_position_beginning_of_post').prop('checked') == true) {
				if(jQuery('#quick_adsense_settings_ad_beginning_of_post').val() != '100') { //Middle Disabled, Other + Beginning Enabled, Other = B1, M0
					jQuery('#quick_adsense_settings_ad_beginning_of_post').children('option[value="100"]').prop('disabled', false);
					jQuery('#quick_adsense_settings_ad_middle_of_post').children('option[value="100"]').prop('disabled', true);
				} else { //Middle Disabled, Other + Beginning Enabled, vi = B1, M0
					jQuery('#quick_adsense_settings_ad_beginning_of_post').children('option[value="100"]').prop('disabled', false);
					jQuery('#quick_adsense_settings_ad_middle_of_post').children('option[value="100"]').prop('disabled', true);
				}
			} else {
				if(jQuery('#quick_adsense_settings_ad_beginning_of_post').val() != '100') { //Middle Disabled, Other + Beginning Disabled, Other = B0,	M0
					jQuery('#quick_adsense_settings_ad_beginning_of_post').children('option[value="100"]').prop('disabled', true);
					jQuery('#quick_adsense_settings_ad_middle_of_post').children('option[value="100"]').prop('disabled', true);
				} else { //Middle Disabled, Other + Beginning Disabled, vi = B0, M0, Random B
					jQuery('#quick_adsense_settings_ad_beginning_of_post').children('option[value="100"]').prop('disabled', true);
					jQuery('#quick_adsense_settings_ad_middle_of_post').children('option[value="100"]').prop('disabled', true);
					jQuery('#quick_adsense_settings_ad_beginning_of_post').val('0');
				}
			}
		} else {
			if(jQuery('#quick_adsense_settings_enable_position_beginning_of_post').prop('checked') == true) {
				if(jQuery('#quick_adsense_settings_ad_beginning_of_post').val() != '100') { //Middle Enabled, vi + Beginning Enabled, Other = B1, M0, Random - M
					jQuery('#quick_adsense_settings_ad_beginning_of_post').children('option[value="100"]').prop('disabled', false);
					jQuery('#quick_adsense_settings_ad_middle_of_post').children('option[value="100"]').prop('disabled', true);
					jQuery('#quick_adsense_settings_ad_middle_of_post').val('0');
				} else { //Middle Enabled, vi + Beginning Enabled, vi = B1, M0, Random - M
					jQuery('#quick_adsense_settings_ad_beginning_of_post').children('option[value="100"]').prop('disabled', false);
					jQuery('#quick_adsense_settings_ad_middle_of_post').children('option[value="100"]').prop('disabled', true);
					jQuery('#quick_adsense_settings_ad_middle_of_post').val('0');
				}
			} else {
				if(jQuery('#quick_adsense_settings_ad_beginning_of_post').val() != '100') { //Middle Enabled, vi + Beginning Disabled, Other = B0,	M0, Random M
					jQuery('#quick_adsense_settings_ad_beginning_of_post').children('option[value="100"]').prop('disabled', true);
					jQuery('#quick_adsense_settings_ad_middle_of_post').children('option[value="100"]').prop('disabled', true);
					jQuery('#quick_adsense_settings_ad_middle_of_post').val('0');
				} else { //Middle Enabled, vi + Beginning Disabled, vi = B0, M0, Random B, Random M
					jQuery('#quick_adsense_settings_ad_beginning_of_post').children('option[value="100"]').prop('disabled', true);
					jQuery('#quick_adsense_settings_ad_middle_of_post').children('option[value="100"]').prop('disabled', true);
					jQuery('#quick_adsense_settings_ad_middle_of_post').val('0');
				}
			}
		}
	}
}