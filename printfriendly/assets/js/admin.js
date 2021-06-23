// Throughout the admin I chose to use slow animations to make it clear that stuff is being hidden or shown depending on settings.
(function ($, config) {
    $(document).ready(function() {
        initAll();
    });

    $(window).load(function() {
        // load the tab from where the settings was saved.
        $('#pf-tabs .pf-bu-tabs li[data-id="' + $('#current-tab').val() + '"]').trigger('click');
    });

    function initAll() {
        $('#pf-tabs').tabs({
            active: $('#current-tab').val(),
            activate: function( event, ui ) {
                $(ui.oldTab).removeClass('pf-bu-is-active');
                $(ui.newTab).addClass('pf-bu-is-active');

                // set the current tab so that when the page is reloaded after save
                // we know which tab to activate
                $('#current-tab').val($(ui.newTab).attr('data-id'));
            }
        });

        var clipboard1 = new ClipboardJS('.pf-clipboard');
        clipboard1.on('success', function(e) {
            // no message needs to be shown.
        });

        initCustomButton();
        initMisc();

        initMediaUpload();

        initCategories();
    }

    function initCategories() {
        $('.pf-select2').select2();
    }

    function initMediaUpload() {
        $('body').on('click', '.pf_upload_image_button', function(e){
            var that = $(this);
            var hidden = $(that).attr('data-pf-element');
            e.preventDefault();

            var button = $(this),
            pf_uploader = wp.media({
                title: config.i10n.upload_window_title,
                library : {
                    uploadedTo : wp.media.view.settings.post.id,
                    type : 'image'
                },
                button: {
                    text: config.i10n.upload_window_button_title
                },
                multiple: false
            }).on('select', function() {
                var attachment = pf_uploader.state().get('selection').first().toJSON();
                $(hidden).val(attachment.url).trigger('change');
                $(hidden + '_label').html($('<img src="' + attachment.url + '">'));
            })
            .open();
        });
    }

    function initCustomButton() {
        if(! $('#custom-btn').prop('checked')){
            $('div.custom-btn').addClass('disabled');
        }
        $('body').on('click', function(e) {
            if($('#custom-btn').prop('checked')){
                $('div.custom-btn').removeClass('disabled');
            }else{
                $('div.custom-btn').addClass('disabled');
            }
        });
    }

    function initMisc() {
        $('.show_list').change(function() {
            if ($('.show_list:checked').length == 0) {
              $('.content_placement').hide('slow');
              $('.content_placement input').prop('disabled', true);
              $('.show_template').prop('checked', true);
            } else {
              $('.show_template').prop('checked', false);
              $('.content_placement').show('slow');
              $('.content_placement input').prop('disabled', false);
            }

            var postName = 'printfriendly_option[show_on_posts]';
            var homeName = 'printfriendly_option[show_on_homepage]';

            var optionName = $(this).attr('name');
            if (optionName == homeName || optionName == postName){
              if($(this).is(':checked')) {
                $('#pf-categories').show('slow');
              } else if(!$('input[name="' + homeName + '"]').is(':checked') && !$('input[name="' + postName + '"]').is(':checked')) {
                $('#pf-categories').hide('slow');
              }
            }
          }).change();

          $('.show_template').change(function() {
            if($(this).is(':checked')) {
              $('.show_list').prop('checked', false);
              $('.show_list').prop('disabled', true);
              $('.content_placement').hide('slow');
              $('.content_placement input').prop('disabled',true);
              $('#pf-categories-metabox').hide('slow');
              $('#pf-snippet').show('slow');
            } else {
              $('.show_list').prop('disabled', false);
              $('.content_placement').show('slow');
              $('.content_placement input').prop('disabled', false);
              $('#pf-snippet').hide('slow');
            }
          }).change();

          $('#toggle-categories').click(function() {
            if($('#pf-categories-metabox').is(':visible')) {
              $('#pf-categories-metabox').hide('slow');
            } else {
              $('#pf-categories-metabox').show('slow');
            }
          });

          $(document).mouseup(function (e) {
            var container = $("#pf-categories");

            if (container.has(e.target).length === 0) {
              $('#pf-categories-metabox').hide('slow');
            }
          });

          $('.pf-color-picker').wpColorPicker({
            change: function (event, ui) {
              var hex = ui.color.toString();
              $('#text_color').val('#' + hex);
              $('#printfriendly-text2').css('color','#' + hex);
            }
          });

          $('#text_size').change(function(){
            size = $('#text_size').val();
            $('#printfriendly-text2').css('font-size',parseInt(size));
          }).change();

          $('#css input').change(function() {
            if($(this).is(':checked')) {
              $(this).val('off');
              $('#margin, #txt-color, #txt-size').hide('slow');
              pf_reset_style();
            } else {
              $(this).val('on');
              $('#margin, #txt-color, #txt-size').show('slow');
              pf_apply_style();
            }
          }).change();

          $('#custom_text').on('change keyup', function(){
            pf_custom_text_change();
          });

          $("[name='printfriendly_option[custom_button_text]']").change(function(){
            pf_custom_text_change();
          });

          function pf_custom_text_change(){
            $('#buttongroup3 span:not(.printandpdf)').text( $('#custom_text').val() );
            var newText = $('#custom-text-no').prop('checked') ? '' : $('#custom_text').val();
            $('#printfriendly-text2').text( newText );
            $('#printfriendly-text2').css('color','#' + $('#text_color').val());
          }

          function pf_initialize_preview(urlInputSelector, previewSelector) {
            var el = $(urlInputSelector);
            var imgUrl = $.trim(el.val());
            var preview = $(previewSelector + '-preview');
            var error = $(previewSelector + '-error');

            el.on('input paste change keyup', function() {
              setTimeout(function() {
                // ie shows error if we try to merge the two below into a single statement
                var img = $('<img/>');
                var customButtonIcon = $("[name='printfriendly_option[custom_button_icon]']:checked").val();

                if (customButtonIcon === 'custom-image') {
                  imgUrl = $('#custom_image').val();
                } else if (customButtonIcon === 'no-image') {
                  imgUrl = '';
                } else {
                  imgUrl = customButtonIcon;
                }

                preview.html('');
                error.html('');
                if(imgUrl != '') {
                  img.on('load', function(){
                    preview.html('').append(img);
                  });
                  img.on('error', function(){
                    error.html('<div class="error settings-error"><p><strong>' + config.i10n.invalid_image_url + '</strong></p></div>');
                  });
                  img.attr('src',imgUrl);
                }
              }, 100);
            });
          }

          pf_initialize_preview('#custom_image', '#pf-custom-button');
          pf_initialize_preview("[name='printfriendly_option[custom_button_icon]']", '#pf-custom-button');
          pf_initialize_preview('#upload-an-image', '#pf-image');
          //$('#custom_image, #upload-an-image').change();
          $('#custom_image').on('focus', function() {
            $('#custom-image').prop('checked', true);
            $('#pf-custom-button-error').show();
          });
          $('#button-style input.radio').on('change', function() {
            if($('#custom-image').is(':checked')) {
              $('#pf-custom-button-error').show();
            } else {
              $('#pf-custom-button-error').hide();
            }
          }).change();

          $('#pf-logo').on('change', function() {
            if($(this).val() == 'favicon') {
              $('.custom-logo, #image-preview').hide();
            } else {
              $('.custom-logo').css('display', 'inline-block');
              $('#image-preview').show();
            }
          }).change();

          $('#website_protocol').on('change', function() {
            if($(this).val() == 'https') {
              $('#https-beta-registration').show('slow');
            } else {
              $('#https-beta-registration').hide('slow');
            }
          }).change();

          function pf_reset_style() {
            $('#printfriendly-text2').css('font-size',14);
            $('#printfriendly-text2').css('color','#000000');
          }

          function pf_apply_style() {
            $('#printfriendly-text2').css('color', '#' + $('#text_color').val() );
            size = $('#text_size').val();
            $('#printfriendly-text2').css('font-size',parseInt(size));
          }

          // postboxes setup
          $('.if-js-closed').removeClass('if-js-closed').addClass('closed');

          // categories checkboxes
          var category_ids = $('#category_ids').val();

          if( typeof category_ids !== 'undefined' ) {
            category_ids = category_ids.split(',');

            if(category_ids[0] == 'all') {
              var ids = [];
              $('#categorydiv :checkbox').each(function() {
                $(this).prop('checked', true);
              });
              $('#category-all :checkbox').each(function() {
                ids.push($(this).val());
              });
              // for older wp versions we do not have per category settings so
              // ids array will be empty and in that case we shouldn't replace 'all'
              if(ids.length != 0) {
                $('#category_ids').val(ids.join(','));
              }
            } else {

              $('#categorydiv :checkbox').each(function() {
                if($.inArray($(this).val(), category_ids) != -1) {
                  $(this).prop('checked', true);
                }
              });
            }
          }

          $('#categorydiv :checkbox').click(function() {
            var values = $('#category_ids').val();
            var ids = [];
            if(values != '')
              ids = values.split(',');

            var id = $(this).val();

            if($(this).is(':checked'))
              ids.push(id);
            else {
              ids = $.grep(ids, function(value) {
                return value != id;
              });
            }

            $('#category_ids').val(ids.join(','));
          });

          $('#custom_image').click(function() {
            $("#custom-image-rb").prop('checked', true);
            $("#custom-image-rb").trigger("change");
          });

          $('#custom_text').click(function() {
            $("#custom-text-rb").prop('checked', true);
            $("#custom-text-rb").trigger("change");
          });
    }

})(jQuery, config);
