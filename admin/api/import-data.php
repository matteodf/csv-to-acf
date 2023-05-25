<?php

/**
 * Provide the import data functionality
 * 
 * @link       https://matteodefilippis.com
 * @copyright  Copyright (c)2023 Matteo De Filippis
 * @since      0.1.0
 *
 * @package    Csv_To_Acf
 * @subpackage Csv_To_Acf/admin/api
 */

// Check if ACF is installed
if (!function_exists('acf_get_field') || !function_exists('update_field')) {
    print_r("ACF is not properly installed");
    return;
};

// Check if all required parameters are present
if (!isset($_POST['data'], $_POST['publish'], $_POST['post_type'])) {
    print_r("Missing parameters");
    return;
}

// Get parameters
$data = $_POST['data'];
$publish = $_POST['publish'];
$post_type = $_POST['post_type'];

// Decode JSON string
$tmp = substr($data, 1, -1);
$tmp = preg_split('/(?<=\})(.*?)(?=\{)/', $tmp);
foreach ($tmp as $key => $value) {
    $value = html_entity_decode(stripslashes($value));
    $tmp[$key] = json_decode($value, true);
}

// Create posts
foreach ($tmp as $key => $row) {

    // Skip empty rows
    if ($row[array_key_first($row)] == '') {
        continue;
    }

    // Create post
    $post = array(
        'post_title' => sanitize_text_field($row[array_key_first($row)]),
        'post_content' => '',
        'post_status' => $publish == "publish" ? 'publish' : 'draft',
        'post_type' => sanitize_text_field($post_type),
    );
    $post_id = wp_insert_post($post);

    // Add ACF fields
    if (!$post_id) {
        continue;
    }
    foreach ($row as $field => $value) {

        // Skip empty fields
        if ($value == '') {
            continue;
        };

        $field = acf_get_field($field)['type'];

        switch ($field) {
            case 'link':
                // Create link
                $site = array(
                    'title' => 'Link',
                    'url' => esc_url_raw($value),
                    'target' => '',
                );
                update_field($field, $site, $post_id);
                break;
            case 'image':
                $response = wp_remote_head($value);
                $headers = wp_remote_retrieve_headers($response);

                // Extract image name from Content-Disposition header
                if (isset($headers['content-disposition'])) {
                    if (preg_match('/filename="(.*?)"/', $headers['content-disposition'], $matches)) {
                        $real_filename = $matches[1];
                    } else {
                        $real_filename = basename(strtok($value));
                    }
                } else {
                    $real_filename = basename(strtok($value));
                }

                // Sets upload directory and sets file type
                $uploaddir = wp_upload_dir();
                $uploadurl = $uploaddir['url'] . '/' . $real_filename;
                $uploadfile = $uploaddir['path'] . '/' . $real_filename;

                // Download image
                $image = file_get_contents($value);
                file_put_contents($uploadfile, $image);


                // Add image to media library
                $attachment = array(
                    'post_mime_type' => $headers['content-type'],
                    'post_title' => $real_filename,
                    'post_content' => '',
                    'post_status' => 'inherit',
                    'guid' => $uploadurl,
                );
                $attach_id = wp_insert_attachment($attachment, $uploadfile);

                // Generate and update image metadata
                $metadata = wp_generate_attachment_metadata($attach_id, $uploadfile);
                $update_attachment = wp_update_attachment_metadata($attach_id, $metadata);

                // Add image to ACF field
                if ($attach_id > 0 && !is_wp_error($attach_id)) {
                    update_field($field, $attach_id, $post_id);
                }
                break;
            default:
                update_field($field, $value, $post_id);
                break;
        }
    }
}
