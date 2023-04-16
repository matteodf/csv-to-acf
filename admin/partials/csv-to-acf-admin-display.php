<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://matteodefilippis.com
 * @copyright  Copyright (c)2023 Matteo De Filippis
 * @since      0.1.0
 *
 * @package    Csv_To_Acf
 * @subpackage Csv_To_Acf/admin/partials
 */
?>

<div class="csv-to-acf">
    <div class="headings">
        <h1><?php esc_html_e('CSV to ACF Importer', 'csv-to-acf') ?></h1>
        <div class="subheadings">
            <p class="version">
                <?php esc_html_e('Version', 'csv-to-acf') ?> <?php echo esc_html_e($this->version) ?>
            </p>
            <p class="author">by <a href="https://matteodefilippis.com" target="_blank">Matteo De Filippis</a></p>
        </div>
    </div>
    <div id="introduction">
        <p>
            <?php esc_html_e('This plugin allows you to import data from a CSV file into a new post with properly configurated ACF fields.', 'csv-to-acf') ?>
        </p>
        <p>
            <?php esc_html_e('To use the plugin, you must follow these rules:', 'csv-to-acf') ?>
        </p>
        <ul>
            <li>
                <?php esc_html_e('The first row must contain the field names, or at least an indication of the content of each column.', 'csv-to-acf') ?>
            </li>
            <li>
                <?php esc_html_e('Each other row must contain the data to be imported.', 'csv-to-acf') ?>
            </li>
            <li>
                <?php esc_html_e('The first column must contain the post title.', 'csv-to-acf') ?>
            </li>
            <li>
                <?php esc_html_e('Image fields must contain the image URL. The URL must be linked to the image file, not to the page where the image is displayed.', 'csv-to-acf') ?>
            </li>
            <li>
                <?php esc_html_e('When the image link does not provide the extension of the image, the plugin sets it to .jpg by default.', 'csv-to-acf') ?>
            </li>
        </ul>
        <p>
            <strong>
                <?php esc_html_e('Important: ', 'csv-to-acf') ?>
            </strong>
            <?php esc_html_e('the plugin will not import data into existing posts.', 'csv-to-acf') ?>
        </p>
        <p>
            <strong>
                <?php esc_html_e('Warning: ', 'csv-to-acf') ?>
            </strong>
            <?php esc_html_e('the plugin currently supports only the following field types: text, image, link, oembed', 'csv-to-acf') ?>
        </p>
        <button class="main-cta" id="start">
            <?php esc_html_e('Start importing', 'csv-to-acf') ?>
        </button>
    </div>

    <div id="step-one" class="display-none">
        <h2>
            <?php esc_html_e('First step: select the CSV file to be imported', 'csv-to-acf') ?>
        </h2>
        <input type="file" id="csv_file" name="csv_file" accept=".csv">
        <br />
        <div id="preview_file" class="display-none"></div>
        <button class="secondary-cta" id="step-one-back">
            <?php esc_html_e('Go back', 'csv-to-acf') ?>
        </button>
        <button class="main-cta display-none" id="step-one-button">
            <?php esc_html_e('Continue', 'csv-to-acf') ?>
        </button>
    </div>
    <div id="step-two" class="display-none">
        <div class="description">
            <h2>
                <?php esc_html_e('Second step: ACF Group settings', 'csv-to-acf') ?>
            </h2>
            <p>
                <?php esc_html_e('Here you can set the ACF group settings. You can select the post type where the fields will be displayed and the title of the group.', 'csv-to-acf') ?>
            </p>
            <p>
                <strong>
                    <?php esc_html_e('Important: ', 'csv-to-acf') ?>
                </strong>
                <?php esc_html_e('the title of the group must be unique.', 'csv-to-acf') ?>
            </p>
        </div>
        <div class="fields">
            <label for="field_group_location">
                <?php esc_html_e('Post type', 'csv-to-acf') ?>
            </label>
            <select name="field_group_location" id="field_group_location">
                <?php
                $args = array(
                    'public'   => true,
                );
                $output = 'names';
                $operator = 'and';
                $types = get_post_types($args, $output, $operator);
                foreach ($types as $type) {
                    echo "<option value='$type'>" . ucfirst($type) . "</option>";
                }
                ?>
            </select>
            <br />
            <br />
            <label for="field_group_title">
                <?php esc_html_e('ACF Group title', 'csv-to-acf') ?>
            </label>
            <input type="text" name="field_group_title" id="field_group_title" placeholder="Field group title">
        </div>
        <button class="secondary-cta" id="step-two-back">
            <?php esc_html_e('Go back', 'csv-to-acf') ?>
        </button>
        <button class="main-cta" id="step-two-button">
            <?php esc_html_e('Continue', 'csv-to-acf') ?>
        </button>
    </div>
    <div id="step-three" class="display-none">
        <h2>
            <?php esc_html_e('Third step: ACF fields settings', 'csv-to-acf') ?>
        </h2>
        <p>
            <?php esc_html_e('Here you can set the ACF fields settings. You can select the type of field, the label, the key and the name of the field.', 'csv-to-acf') ?>
        </p>
        <p>
            <?php esc_html_e('You can click on the "Autofill" button to automatically fill the fields with the data from the CSV file.', 'csv-to-acf') ?>
        </p>
        <p>
            <strong>
                <?php esc_html_e('Important: ', 'csv-to-acf') ?>
            </strong>
            <?php esc_html_e('the key of the field must be unique.', 'csv-to-acf') ?>
        </p>
        <button class="main-cta" id="autofill_button">
            <?php esc_html_e('Autofill', 'csv-to-acf') ?>
        </button>
        <div id="acf_fields"></div>
        <button class="secondary-cta" id="step-three-back">
            <?php esc_html_e('Go back', 'csv-to-acf') ?>
        </button>
        <button class="main-cta" id="create_field_group_button">
            <?php esc_html_e('Create fields', 'csv-to-acf') ?>
        </button>
    </div>
    <div id="step-four" class="display-none">
        <h2>
            <?php esc_html_e('Fourth step: import data', 'csv-to-acf') ?>
        </h2>
        <p>
            <?php esc_html_e('The fields have been created. Now you can import the data from the CSV file and create the posts.', 'csv-to-acf') ?>
        </p>
        <p>
            <?php esc_html_e('You can select if you want to publish or draft the posts.', 'csv-to-acf') ?>
        </p>
        <p>
            <strong>
                <?php esc_html_e('Important: ', 'csv-to-acf') ?>
            </strong>
            <?php esc_html_e('the plugin will not import data into existing posts. It will only create new posts.', 'csv-to-acf') ?>
        </p>
        <label for="publish_page">
            <?php esc_html_e('Publish or draft the posts', 'csv-to-acf') ?>
        </label>
        <select id="publish_page" name="publish_page">
            <option value="publish">
                <?php esc_html_e('Publish', 'csv-to-acf') ?>
            </option>
            <option value="draft">
                <?php esc_html_e('Draft', 'csv-to-acf') ?>
            </option>
        </select>
        <br />
        <button class="secondary-cta" id="step-four-back">
            <?php esc_html_e('Go back', 'csv-to-acf') ?>
        </button>
        <button class="main-cta" id="import_data_button">
            <?php esc_html_e('Import data', 'csv-to-acf') ?>
        </button>
    </div>
    <div class="display-none" id="waiting">
        <h2>
            <?php esc_html_e('Importing data...', 'csv-to-acf') ?>
        </h2>
        <p>
            <?php esc_html_e('Please wait while the data is being imported. This may take a while depending on the size of the CSV file.', 'csv-to-acf') ?>
        </p>
    </div>
    <div class="display-none" id="conclusion">
        <h2>
            <?php esc_html_e('Import completed!', 'csv-to-acf') ?>
        </h2>
        <p>
            <?php esc_html_e('The data has been imported successfully.', 'csv-to-acf') ?>
        </p>
        <p>
            <?php esc_html_e('You can now go to the posts page and check the imported data.', 'csv-to-acf') ?>
        </p>
        <p>
            <?php esc_html_e('If you liked this plugin, please consider leaving a star on ', 'csv-to-acf') ?>
            <a href="" target="_blank">GitHub</a>.
        </p>
        <p>
            <?php esc_html_e('If you have any questions or suggestions, please contact me on ', 'csv-to-acf') ?>
            <a href="https://matteodefilippis.com" target="_blank">
                <?php esc_html_e('my website', 'csv-to-acf') ?>
            </a>.
        </p>
        <p>
            <strong>
                <?php esc_html_e('Important: ', 'csv-to-acf') ?>
            </strong>
            <?php esc_html_e('This plugin is still in beta. If you find any bugs or have any suggestions, feel free to open an issue or a pull request on ', 'csv-to-acf') ?><a href="" target="_blank">GitHub</a>.
        </p>
        <p>
            <?php esc_html_e('Thank you for using this plugin!', 'csv-to-acf') ?>
        </p>
        <button class="main-cta" id="start-over">
            <?php esc_html_e('Start over', 'csv-to-acf') ?>
        </button>
    </div>
</div>