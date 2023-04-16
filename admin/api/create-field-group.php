<?php

/**
 * Provide the create field group functionality
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
if (empty($_POST['title']) || empty($_POST['fields']) || empty($_POST['location']) || empty($_POST['headers'])) {
    print_r("Missing parameters");
    return;
};

// Get parameters
$group_title = $_POST['title'];
$fields = $_POST['fields'];
$location = $_POST['location'];
$headers = $_POST['headers'];

// Create field group
$group = array(
    'key' => 'group_' . md5($group_title),
    'title' => $group_title,
    'fields' => $fields,
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => $location,
            ),
        ),
    ),
);
$field_group = acf_import_field_group($group);

// Create array with old and new field names, to be used by JS to update the JSON object with the new field names
$names = [];

foreach ($fields as $key => $field) {
    $temp = [];
    $temp['new_name'] = $field['name'];
    $temp['old_name'] = $headers[$key];
    array_push($names, $temp);
}

if ($field_group) {
    echo json_encode($names);
} else {
    print_r("Error creating field group");
}
