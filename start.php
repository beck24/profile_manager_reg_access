<?php

function profile_manager_reg_access_init() {
    elgg_extend_view('css/elgg', 'profile_manager_reg_access/css');
    elgg_extend_view('css/walled_garden', 'profile_manager_reg_access/css');
    
    elgg_register_plugin_hook_handler('action', 'register', 'profile_manager_reg_access_register', 0);
    elgg_register_event_handler('create', 'metadata', 'profile_manager_reg_access_md_create');
}


function profile_manager_reg_access_register($hook, $type, $return, $params) {
    // set a flag for us to know that metadata being saved is coming from this action
    elgg_set_config('profile_manager_reg_access_registration', true);
}


function profile_manager_reg_access_md_create($event, $type, $metadata) {
    // determine if we are saving registration information
    if (!elgg_get_config('profile_manager_reg_access_registration')) {
        return true;
    }
    
    // see if we have any supplied access info
    $access = get_input('profile_manager_reg_' . $metadata->name . '_access', false);
    if ($access === false) {
        return true;
    }
    
    // we have an access setting, lets use it
    $metadata->access_id = $access;
    $metadata->save();
    
    return true;
}

elgg_register_event_handler('init', 'system', 'profile_manager_reg_access_init');