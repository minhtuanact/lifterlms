var rendered_messages = {};
var wplc_running_chat = {};
var wplc_poll_chat_delay = 5000;

jQuery(function () {
    if (localization_data.enable_files) {
        wplc_setup_file_picker();
    }
    if (localization_data.channel === 'mcu') {
        wplc_setup_mcu_channel();
    }
    wplc_setup_typing();
    wplc_change_chat_status(false);

    jQuery("body").on("openChat", function (e, cid) {
        jQuery("#wplc_agent_chat_input").val('');
        wplc_load_chat_info(cid);
    });

    jQuery("body").on("openCompletedChat",function(e,cid){
        wplc_get_chat_messages_call(active_chat).then(function (response) {
            if (!response.ErrorFound) {
                response.Data.forEach((msg) => {
                    wplc_render_message(msg, "server_message", wplc_running_chat);
                });
                wplc_render_message({
                    added_at: new Date(),
                    code: "NONE",
                    id: "-1",
                    is_file: false,
                    msg: localization_data.chat_closed,
                    originates: "1"
                }, "server_message", wplc_running_chat);
                jQuery("#chat_list_body").removeClass("chat_loading");
            }
        });
    })

    jQuery("body").on("joinChat", function (e, cid) {
        wplc_init_chat(cid);
    });

    jQuery("body").on("wplc-chat-removed", function (e, cid) {
        if (cid === parseInt(wplc_running_chat.id) && (jQuery("#active_chat_box").is(":visible") || jQuery("#inactive_chat_box").is(":hidden"))) {
            jQuery("#active_chat_box").fadeOut();
            jQuery("#inactive_chat_box").fadeIn();
        }
    });

});

function wplc_load_chat_info(cid) {
    rendered_messages[cid] = {};
    wplc_get_chat_info_call(cid).then(function (chat) {
            wplc_running_chat = chat.Data;
            wplc_enable_chat(wplc_running_chat);
        },
        function (error) {
            console.log(error);
        });
}

function wplc_init_chat(cid) {
    if (!!localization_data.agent_accepts_data) {
        wplc_get_chat_agent_call(cid).then(
            function (response) {
                if (!response.ErrorFound) {
                    wplc_chatbox_activate(cid);
                    my_chats.push(cid);
                    wplc_get_chat_messages_call(cid).then(function (response) {
                        if (!response.ErrorFound) {
                            response.Data.forEach((msg) => {
                                wplc_render_message(msg, "server_message", wplc_running_chat);
                            });
                            wplc_change_chat_status(true);

                            if (localization_data.channel === 'mcu') {
                                wplc_join_chat_socket_session(wplc_running_chat);
                            }
                            jQuery("#wplc_admin_close_chat").show();
                            jQuery("#chat_list_body").removeClass("chat_loading");
                        }
                    })
                } else {
                    wplc_change_chat_status(false);
                    jQuery("#chat_list_body").removeClass("chat_loading");
                    alert(response.ErrorMessage);
                }
            },
            function (error) {
                console.log(error);
            }
        );
    }
}

function wplc_unload_chat(chat) {
    rendered_messages[chat.id] = {};
    wplc_running_chat = {};
    wplc_change_chat_status(false);
}

function wplc_setup_file_picker() {
    jQuery("#file_picker").on("click", function () {
            jQuery("#file_input").click();
        }
    );

    jQuery("#file_input").on("change", function () {
        if (jQuery("#file_input").val() === '') {
            return;
        }
        if (!wplc_checkFile(jQuery(this)[0].files[0].name)) {
            return;
        }
        let sending_id = wplc_render_sending_message("File Uploading", wplc_running_chat);
        var formdata = new FormData();
        formdata.append('security', localization_data.nonce);
        formdata.append('name', jQuery(this)[0].files[0].name);
        formdata.append('action', 'wplc_admin_upload_file');
        formdata.append('cid', wplc_running_chat.id);
        formdata.append('file', jQuery(this)[0].files[0]);

        jQuery.ajax({
            url: localization_data.ajaxurl,
            data: formdata,
            type: "POST",
            data: formdata,
            contentType: false,
            processData: false,
            success: function (response) {
                jQuery("#file_input").val('');
                if (!response.ErrorFound) {
                    wplc_remove_message(wplc_running_chat.id, -1, sending_id, "local_message");
                    wplc_render_message({
                        id: response.Data.id,
                        msg: response.Data.fileLink,
                        added_at: response.Data.added_at,
                        originates: response.Data.originates,
                        is_file: true,
                    }, "server_message", wplc_running_chat)
                    if (localization_data.channel === 'mcu') {
                        jQuery('body').trigger('mcu-send-file', {
                            sessionID: wplc_running_chat.session,
                            agentID: localization_data.user_id,
                            url: response.Data.fileLink,
                            name: response.Data.fileName,
                            size: response.Data.fileSize
                        });
                    }
                }
            },
        });

    });
}

function wplc_checkFile(fileName) {
    let result = false;
    const allowedFileExtensions = ['png', 'pjp', 'jpeg', 'pjpeg', 'pjpeg', 'jpg', 'jfif'];
    const lastDot = fileName.lastIndexOf('.');
    const ext = fileName.substring(lastDot + 1);
    if (allowedFileExtensions.indexOf(ext.toLowerCase()) >= 0) {
        result = true;
    }
    return result;
}

function wplc_setup_typing() {
    jQuery("#wplc_agent_chat_input").on('input', function (e) {
        var inputField = jQuery(this);
        if (!inputField.data("typing")) {
            inputField.data("typing", true);
            setTimeout(function () {
                inputField.data("typing", false);
            }, 250);
            jQuery("body").trigger("mcu-send-typing", {
                sessionID: wplc_running_chat.session,
                agentID: localization_data.user_id
            });
        }
    });
}

function wplc_get_chat_agent_call(chatID) {
    let data = {
        action: 'wplc_set_agent_chat',
        cid: chatID,
        security: localization_data.nonce,
    };
    return jQuery.ajax({
        url: localization_data.ajaxurl,
        data: data,
        type: "POST"
    });
}

function wplc_get_chat_info_call(cid) {
    let data = {
        action: 'wplc_get_chat_info',
        cid: cid,
        security: localization_data.nonce,
    };
    return jQuery.ajax({
        url: localization_data.ajaxurl,
        data: data,
        type: "POST"
    });
}

function wplc_get_chat_messages_call(chatID) {
    let data = {
        action: 'wplc_get_chat_messages',
        cid: chatID,
        security: localization_data.nonce,
    };
    return jQuery.ajax({
        url: localization_data.ajaxurl,
        data: data,
        type: "POST"
    });
}

function wplc_create_message_html(message_data, type, chat) {

    // console.log('msg', message_data);

    let templateIncoming = generate_incoming_message_template();
    let templateOutgoing = generate_outgoing_message_template();

    let sender = parseInt(message_data.originates) === 2 ? chat.name : 'admin';
    let senderType = parseInt(message_data.originates) === 2 ? "user" : "admin";
    let template = senderType == "user" ? templateIncoming : templateOutgoing;

    let avatarName = wplc_isDoubleByte(sender) ? 'Visitor' : sender;
    let avatarEmail = senderType === "user" ? md5(chat.email) : md5(localization_data.agent_email);

    let data = {
        sender: sender,
        mid: message_data.id,
        elementId: type + "_" + message_data.id,
        senderType: senderType,
        message: wplc_decodeHtml(message_data.msg),
        gravatarSource: "//www.gravatar.com/avatar/" + avatarEmail + "?s=64&d=" + encodeURIComponent(localization_data.wplc_protocol + "://ui-avatars.com/api//" + avatarName + "/64/" + wplc_stringToColor(sender) + "/fff")
    };

    if (parseInt(message_data.originates) === -1) {
        data.submessage = 'sending....';
    } else {
        if (parseInt(message_data.originates) === 1) {
            data.submessage = generate_submessage_text(message_data.added_at, 'admin')
        } else {
            data.submessage = generate_submessage_text(message_data.added_at, chat.name);
        }
    }

    for (let propertyName in data) {
        let regx = new RegExp("{{" + propertyName + "}}", 'g');
        template = template.replace(regx, data[propertyName]);
    }

    var messageElement = jQuery(template);
    messageElement.find(".wplc_msg_container").text(data.message);

    return wplc_linkify_message(TCXemojione.convertTextToEmoji(messageElement[0].outerHTML, localization_data.images_url + "/emojis/32/"), message_data.is_file);
}

function generate_submessage_text(date, name) {

    var date_added = new Date(date);
    var timezoneOffset = date_added.getTimezoneOffset();
    var result = '';
    if (localization_data.show_date && localization_data.show_time) {
        result = new Date((date_added.getTime() - (timezoneOffset * 60 * 1000))).toLocaleString();
    } else if (localization_data.show_date) {
        result = new Date((date_added.getTime() - (timezoneOffset * 60 * 1000))).toLocaleDateString();
    } else if (localization_data.show_time) {
        result = new Date((date_added.getTime() - (timezoneOffset * 60 * 1000))).toLocaleTimeString();
    }


    if (localization_data.show_name) {
        result = result + ' - ' + name;
    }


    return result;
}

function generate_incoming_message_template() {
    let templateIncoming = `<div id="{{elementId}}" class="incoming_msg">`

    if (localization_data.show_avatar) {
        templateIncoming += `  <div class="incoming_msg_img">
                                       <img src="{{gravatarSource}}" alt="{{sender}} avatar">
                                   </div>`
    }

    templateIncoming += `    <div class="received_msg">
                                    <div class="received_withd_msg">
                                        <div class="wplc_msg_container">
                                        </div>
                                        <span class="time_date">{{submessage}}</span>
                                    </div>
                                </div>
                            </div>`
    return templateIncoming;
}

function generate_outgoing_message_template() {
    let templateOutgoing = `<div id="{{elementId}}" class="outgoing_msg">
                                <div class="sent_msg">
                                    <p class="wplc_msg_container"></p>
                                    <span class="time_date">{{submessage}}</span>
                                 </div>`

    if (localization_data.show_avatar) {
        templateOutgoing += `  <div class="incoming_msg_img">
                                       <img src="{{gravatarSource}}" alt="{{sender}} avatar">
                                   </div>`
    }

    templateOutgoing += ` 
                            </div>`

    return templateOutgoing;

}

function wplc_render_sending_message(message, chat) {
    let temp_id = Math.floor(Math.random() * (100001)) - 200000;
    message_data = {
        id: temp_id,
        msg: message,
        added_at: new Date(),
        originates: -1,
    }
    wplc_render_message(message_data, "local_message", chat);
    return temp_id;
}

function wplc_render_message(message_data, type, chat) {
    if (typeof rendered_messages[chat.id] != 'undefined') {
        if (!rendered_messages[chat.id].hasOwnProperty(message_data.originates)) {
            rendered_messages[chat.id][message_data.originates] = [];
        }
        if (rendered_messages[chat.id][message_data.originates].indexOf(message_data.id) < 0) {
            let wplc_chat_box_element = "#wplc_chat_messages";
            let new_message = jQuery(wplc_create_message_html(message_data, type, chat));
            jQuery(wplc_chat_box_element).append(new_message);
            rendered_messages[chat.id][message_data.originates].push(message_data.id);
            wplc_scroll_to_bottom();
        }
    }
}

function wplc_remove_message(chatID, originates, message_id, type) {
    let messageIndex = rendered_messages[chatID][originates].indexOf(message_id);
    if (messageIndex >= 0) {
        let message_element = "#" + type + "_" + message_id;
        jQuery(message_element).remove();
        rendered_messages[chatID][originates].splice(messageIndex, 1);
        wplc_scroll_to_bottom();
    }
}

function wplc_scroll_to_bottom() {

    let height = jQuery('#wplc_chat_messages')[0].scrollHeight;
    jQuery('#wplc_chat_messages').scrollTop(height);
}

function wplc_chatbox_activate(chatID) {
    jQuery(".typing_indicator").hide();

    jQuery("#wplc_admin_close_chat").unbind("click");

    jQuery("#wplc_admin_close_chat").on("click", function () {
        let data = wplc_end_chat_call(chatID);

        jQuery.ajax({
            url: localization_data.ajaxurl,
            data: data,
            type: "POST",
            success: function (plain_response) {
                wplc_change_chat_status(false);
                if (localization_data.channel === 'mcu') {
                    jQuery("body").trigger("mcu-chat-close", {
                        sessionID: wplc_running_chat.session,
                        agentID: localization_data.user_id,
                        statusID: plain_response.Status
                    });
                }
            },
            error: function (jqXHR, exception) {
                wplc_render_error('Error faced during the chat ending process please try again');
            },
            complete: function (response) {
            }
        });
    });

    jQuery("#wplc_agent_chat_input").keyup(function (event) {
        if (event.keyCode == 13) {
            jQuery("#wplc_admin_send_msg").click();
        }
    });

    jQuery("#wplc_admin_send_msg").on("click", wplc_send_message);

}

function wplc_send_message() {
    var message = jQuery("#wplc_agent_chat_input").val();
    jQuery("#wplc_agent_chat_input").val('');

    if (message !== "") {
        var data = {
            action: 'wplc_admin_send_msg',
            security: localization_data.nonce,
            cid: wplc_running_chat.id,
            msg: message
        };
        let sending_id = wplc_render_sending_message(message, wplc_running_chat);
        jQuery.ajax({
            url: localization_data.ajaxurl,
            data: data,
            type: "POST",
            success: function (messageResponse) {
                if (!messageResponse.ErrorFound) {
                    jQuery("body").trigger("mcu-send-message", {
                        sessionID: wplc_running_chat.session,
                        agentID: localization_data.user_id,
                        message: message
                    });
                    wplc_remove_message(wplc_running_chat.id, -1, sending_id, "local_message");
                    wplc_render_message({
                        id: messageResponse.Data.id,
                        msg: messageResponse.Data.msg,
                        added_at: messageResponse.Data.added_at,
                        originates: messageResponse.Data.originates,
                    }, "server_message", wplc_running_chat)
                }
            },
            error: function (jqXHR, exception) {
                wplc_render_error('Error faced during the send message process please try again');
            },
            complete: function (response) {
            }
        });
    }
}

function wplc_enable_chat(chat) {
    jQuery("#wplc_chat_name").html(chat.name);

    jQuery("#wplc_chat_email").html(chat.email);

    var avatarName = wplc_isDoubleByte(chat.name) ? 'Visitor' : chat.name;
    var gravatarSource = "//www.gravatar.com/avatar/" + md5(chat.email) + "?s=48&d=" + encodeURIComponent(localization_data.wplc_protocol + "://ui-avatars.com/api//" + avatarName + "/48/" + wplc_stringToColor(chat.name) + "/fff")
    jQuery("#wplc_avatar_user").attr("src", gravatarSource);

    if (chat.hasOwnProperty("other") && chat.other.hasOwnProperty('custom_fields')
        && typeof chat.other.custom_fields !== 'undefined' && chat.other.custom_fields != null) {
        var custom_fields_html = '';
        chat.other.custom_fields.forEach(function (cf) {
            custom_fields_html += `<div class="wplc_sidebar_info_row">
                <div id='wplc_cf_name'><span class="wplc_sidebar_element_label">${cf.name}</span></div>
                <div id='wplc_cf_value' class='wplc_sidebar_element_value'>${cf.value}</div>
                </div>`;
        });
        jQuery("#chat_custom_fields_info").html(custom_fields_html);
    }
    jQuery("#wplc_info_visitor_name_value").html(chat.name);
    if (chat.email !== '' && chat.email !== undefined) {
        if (chat.email !== 'no email set') {
            jQuery("#wplc_info_visitor_email_value").html("<a href='mailto:" + chat.email + "'>" + chat.email + "</a>");

        } else {
            jQuery("#wplc_info_visitor_email_value").html("<span>" + chat.email + "</span>");
        }
        jQuery("#wplc_info_visitor_email_value").show();
    } else {
        jQuery("#wplc_info_visitor_email").hide();
    }

    jQuery("#wplc_chat_info_menu").unbind("click");

    jQuery("#wplc_chat_info_menu").on("click", function () {
        var infoBar = jQuery("#wplc_sidebar");
        if (infoBar.is(":visible")) {
            infoBar.css('min-width', '0px');
            infoBar.animate({width: "0px"}, 500, function () {
                jQuery(this).hide()
            });

        } else {
            infoBar.css('display', 'flex');
            infoBar.css('max-width', '250px').animate({width: "250px"}, 500, function () {
                jQuery(this).css('min-width', '250px');
            });
        }
    });

    wplc_change_chat_status(true);
    /*  */
}

function wplc_change_chat_status(enabled) {
    let chatInputElement = jQuery("#wplc_agent_chat_input");

    if (!enabled) {
        jQuery("#wplc_admin_close_chat").hide();
        wplc_render_message({
            id: -1,
            msg: "Chat session ended.",
            added_at: new Date()
        }, "server_message", wplc_running_chat)
        /* jQuery("#active_chat_box").fadeOut();
        jQuery("#inactive_chat_box").fadeIn(); */

    } else if (enabled && (jQuery("#active_chat_box").is(":hidden") || jQuery("#inactive_chat_box").is(":visible"))) {
        jQuery("#inactive_chat_box").hide();

        jQuery("#active_chat_box")
            .css("display", "flex")
            .hide()
            .fadeIn();
    }

    jQuery("#file_picker").attr("disabled", !enabled);
    jQuery("#wplc_admin_send_msg").attr("disabled", !enabled);
    jQuery("#quick_resp_btn").attr("disabled", !enabled);
    chatInputElement.attr("disabled", !enabled);
    chatInputElement.attr('placeholder', enabled ? "Type a message" : "Chat session ended.");

}

function wplc_render_error(error, chatID) {
    if (window.console) {
        console.log(error);
    }

    jQuery("#admin_chat_box_area_" + chatID).append("<small>" + error + "</small><br>");
    wplc_scroll_to_bottom();
}

function wplc_ring_new_chat_message() {
    if (!!localization_data.enable_ring) {
        new Audio(localization_data.ring_file).play()
    }
}

function wplc_linkify_message(message, is_file) {

    var displayText = is_file ? "Download / Open File" : "";
    return anchorme(message, {
        attributes: [
            {
                name: 'target',
                value: '_blank'
            },
            {
                name: 'rel',
                value: 'noopener noreferrer'
            }
        ],
        displayText: displayText,
        customTlds: ["local"]
    })

}

function wplc_add_quick_response(response) {
    jQuery("#wplc_agent_chat_input").val(response);
    jQuery("#wplc_agent_chat_input").focus();
}

function wplc_setup_mcu_channel() {
    jQuery("body").unbind('mcu-socket-connected');
    jQuery("body").on('mcu-socket-connected', function (e) {
        jQuery("#wplc_connecting_loader").hide();
        if (jQuery("#wplc_chat_panel").is(":hidden")) {
            jQuery("#wplc_chat_panel").show();
        }
        wplc_set_chat_panel_ui();
    });

    jQuery("body").unbind('mcu-socket-connecting');
    jQuery("body").on('mcu-socket-connecting', function (e) {
        jQuery("#wplc_connecting_loader").show();
        wplc_unload_chat(wplc_running_chat);
        jQuery("#chat_list_body").empty();
        jQuery("#wplc_chat_panel").hide();

    });

    jQuery("body").unbind('mcu-new-message');
    jQuery("body").on('mcu-new-message', function (e, message) {
        if (message.sessionId === wplc_running_chat.session) {
            wplc_render_message(message, 'server_message', wplc_running_chat);
            wplc_ring_new_chat_message();
        } else {
            update_badge_counter(message.sessionId);
        }
    });

    jQuery("body").unbind('mcu-chat-ended');
    jQuery("body").on('mcu-chat-ended', function (e, sessionID) {
        if (sessionID === wplc_running_chat.session) {
            wplc_change_chat_status(false);
        }
    });


    jQuery("body").unbind('mcu-chat-close', chat_close_event);
    jQuery("body").on('mcu-chat-close', chat_close_event);

    /* if (!!localization_data.agent_accepts_data) {
         jQuery("body").trigger('mcu-setup-socket');
     }*/

}

function chat_close_event(e, data) {
    if (data.sessionID === wplc_running_chat.session) {
        wplc_change_chat_status(false);
    }
}

function wplc_join_chat_socket_session(chat) {
    jQuery("body").trigger('mcu-socket-join', {sessionID: chat.session, agentID: localization_data.user_id});
}

function wplc_escape_html(s, forAttribute) {
    return s.replace(forAttribute ? /[&<>'"]/g : /[&<>]/g, function (c) {
        var esc_map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        };
        return esc_map[c];
    });
}

function update_badge_counter(sessionID) {
    var badge_container = jQuery(".chat_list.wplc_p_cul[data-sid='" + sessionID + "'] .wplc_message_count");
    var badge = badge_container.find(".badge");
    var counter = badge.text();
    counter = counter == '' ? 1 : parseInt(counter) + 1;
    badge.html(counter);
    badge_container.show();
}

function wplc_end_chat_call(chatID, chatStatus = -1) {
    let data = {
        action: 'wplc_admin_close_chat',
        security: localization_data.nonce,
        cid: chatID
    };

    if (chatStatus !== -1) {
        data.status = chatStatus;
    }
    return data;
}
