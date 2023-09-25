<?php
    if (defined('URL')) {
        // Initialize
        $old_domain_json = array();
        $cached_domain = DIR_SYSTEM . '/domain_hop.json';

        // Get from config.php
        $remove_text = array(
            'index.php',
            '/admin',
            'www.'
        );
        
        //If first visit is from Admin then remove Admin
        $current_domain = str_replace($remove_text, '', URL);
        $current_domain = trim($current_domain, "/");

        // Initialize Cached Domain
        if(!is_file($cached_domain)){
            fopen($cached_domain, 'w'); // Create file if don't exist
        }
        else{
            $old_domain_json = file_get_contents($cached_domain);
            $old_domain_json = json_decode($old_domain_json, true);

            if(!$old_domain_json) $old_domain_json = array();
        }

        if( $current_domain && !in_array($current_domain, $old_domain_json)){
            
            $old_domain_json[] = $current_domain;

            $old_domain_json = json_encode($old_domain_json);
            $domain_json_file = fopen($cached_domain, "w");
            fwrite($domain_json_file, $old_domain_json);
            fclose($domain_json_file);
        }
    }

    