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
if (!class_exists("ACF")) {
    print_r("ACF is not installed");
    return;
};

// Check if all required parameters are present
if (empty($_POST['data']) || empty($_POST['publish']) || empty($_POST['post_type'])) {
    print_r("Missing parameters");
    return;
};

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
        'post_title' => $row[array_key_first($row)],
        'post_content' => '',
        'post_status' => $publish == "publish" ? 'publish' : 'draft',
        'post_type' => $post_type,
    );
    $post_id = wp_insert_post($post);

    // Add ACF fields
    if ($post_id) {
        foreach ($row as $field => $value) {

            // Skip empty fields
            if ($value == '') {
                continue;
            } elseif (acf_get_field($field)['type'] == 'link') {
                // Create link
                $site = array(
                    'title' => 'Link',
                    'url' => $value,
                    'target' => '',
                );
                update_field($field, $site, $post_id);
            } elseif (acf_get_field($field)['type'] == 'image') {

                // Sets upload directory and sets file type
                $uploaddir = wp_upload_dir();
                $uploadurl = $uploaddir['url'] . '/' . basename($value);
                $uploadfile = $uploaddir['path'] . '/' . basename($value);
                $wp_filetype = wp_check_filetype(basename(basename($value)), null);
                $filetype = $wp_filetype['type'];

                // Add .jpg extension if no extension is present
                if (pathinfo(basename($value), PATHINFO_EXTENSION) == "") {
                    $uploadfile .= ".jpg";
                    $uploadurl .= ".jpg";
                    $filetype = 'image/jpeg';
                };

                // Download image
                $image = file_get_contents($value);
                file_put_contents($uploadfile, $image);

                // Add image to media library
                $attachment = array(
                    'post_mime_type' => $filetype,
                    'post_title' => basename($value),
                    'post_content' => '',
                    'post_status' => 'inherit',
                    'guid' => $uploadurl,
                );
                $attach_id = wp_insert_attachment($attachment, $uploadfile);

                // Add image to ACF field
                if ($attach_id > 0 && !is_wp_error($attach_id)) {
                    update_field($field, $attach_id, $post_id);
                }
            } else {
                // Add other fields
                update_field($field, $value, $post_id);
            }
        }
    }
}
