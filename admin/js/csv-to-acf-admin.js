/**
 * @link       https://matteodefilippis.com
 * @copyright  Copyright (c)2023 Matteo De Filippis
 * @since      0.1.0
 *
 * @package    Csv_To_Acf
 * @subpackage Csv_To_Acf/admin/js
 */

let data = [];
let headers = [];

function switchSection(previous, next) {
    previous.classList.add("display-none");
    next.classList.remove("display-none");
}
const acfFieldTypes = ["text", "image", "oembed", "link"];

// Initiate the import process when the button is clicked
const introduction = document.querySelector("#introduction");
const startImport = document.querySelector("#start");

startImport.addEventListener("click", function () {
    switchSection(introduction, stepOne);
});

/**
 * STEP 1 - Upload the CSV file
 */
const stepOne = document.querySelector("#step-one");
const csvUpload = document.querySelector("#csv_file");
const stepOneButton = document.querySelector("#step-one-button");
const stepOneBack = document.querySelector("#step-one-back");
const acfFieldConfigurator = document.querySelector("#acf_fields");

// Parse the CSV file and display the preview of the resulting JSON object
// Also create the fields for the user to configure the ACF fields
csvUpload.addEventListener("change", function (e) {
    const file = e.target.files[0];
    const reader = new FileReader();

    // Reset the fields section
    acfFieldConfigurator.innerHTML = "";

    reader.onload = function (e) {
        // Parse the file
        const text = e.target.result;
        const lines = [];
        let quote = false;
        for (let row = 0, col = 0, c = 0; c < text.length; c++) {
            const cc = text[c],
                nc = text[c + 1];
            lines[row] = lines[row] || [];
            lines[row][col] = lines[row][col] || "";
            if (cc == '"' && quote && nc == '"') {
                lines[row][col] += cc;
                ++c;
                continue;
            }
            if (cc == '"') {
                quote = !quote;
                continue;
            }
            if (cc == "," && !quote) {
                ++col;
                continue;
            }
            if (cc == "\r" && nc == "\n" && !quote) {
                ++row;
                col = 0;
                ++c;
                continue;
            }
            if (cc == "\n" && !quote) {
                ++row;
                col = 0;
                continue;
            }
            if (cc == "\r" && !quote) {
                ++row;
                col = 0;
                continue;
            }
            lines[row][col] += cc;
        }

        const result = [];
        headers = lines[0];

        for (let i = 1; i < lines.length; i++) {
            const obj = {};

            for (let j = 0; j < headers.length; j++) {
                obj[headers[j]] = lines[i][j];
            }

            result.push(obj);
        }

        // Display the preview and the button to go to the next step
        data = result;
        document.querySelector("#preview_file").innerHTML = "";
        document
            .querySelector("#preview_file")
            .appendChild(
                document.createTextNode(JSON.stringify(result, null, 4))
            );
        document
            .querySelector("#preview_file")
            .classList.remove("display-none");
        document
            .querySelector("#step-one-button")
            .classList.remove("display-none");

        // Create the fields for the user to configure the ACF fields
        headers.forEach((item, key) => {
            // Create the field options container
            const fieldContainer = document.createElement("div");
            fieldContainer.classList.add("field_" + key);

            // Create the field title
            const fieldContainerTitle = document.createElement("h3");
            fieldContainerTitle.innerHTML = item;
            fieldContainer.appendChild(fieldContainerTitle);

            // Create the field label input
            const fieldContainerLabel = document.createElement("input");
            fieldContainerLabel.setAttribute("type", "text");
            fieldContainerLabel.setAttribute("name", "field_" + key + "_label");
            fieldContainerLabel.setAttribute("placeholder", "Label");
            fieldContainer.appendChild(fieldContainerLabel);

            // Create the field name input
            const fieldContainerName = document.createElement("input");
            fieldContainerName.setAttribute("type", "text");
            fieldContainerName.setAttribute("name", "field_" + key + "_name");
            fieldContainerName.setAttribute("placeholder", "Name");
            fieldContainer.appendChild(fieldContainerName);

            // Create the field type select
            const fieldContainerType = document.createElement("select");
            fieldContainerType.setAttribute("name", "field_" + key + "_type");
            const placeholder = document.createElement("option");
            placeholder.setAttribute("value", "Select a type");
            placeholder.innerHTML = "Select a type";
            fieldContainerType.appendChild(placeholder);
            acfFieldTypes.forEach((item) => {
                const option = document.createElement("option");
                option.setAttribute("value", item);
                option.innerHTML = item[0].toUpperCase() + item.slice(1);
                fieldContainerType.appendChild(option);
            });
            fieldContainer.appendChild(fieldContainerType);

            // Append the field options container to the proper section
            acfFieldConfigurator.appendChild(fieldContainer);
        });
    };

    reader.readAsText(file);
});

// Switch between the different sections
stepOneButton.addEventListener("click", function () {
    switchSection(stepOne, stepTwo);
});
stepOneBack.addEventListener("click", function () {
    switchSection(stepOne, introduction);
});

/**
 * STEP 2 - Select the field group title and location
 */
const stepTwo = document.querySelector("#step-two");
const stepTwoButton = document.querySelector("#step-two-button");
const stepTwoBack = document.querySelector("#step-two-back");

// Switch between the different sections
stepTwoButton.addEventListener("click", function () {
    if (document.querySelector("#field_group_title").value === "") {
        alert("Please select a field group title");
        return;
    } else if (document.querySelector("#field_group_location").value === "") {
        alert("Please select a field group location");
        return;
    }
    switchSection(stepTwo, stepThree);
});
stepTwoBack.addEventListener("click", function () {
    switchSection(stepTwo, stepOne);
});

/**
 * STEP 3 - Configure the fields
 */
const stepThree = document.querySelector("#step-three");
const autoFillButton = document.querySelector("#autofill_button");
const createFieldGroupButton = document.querySelector(
    "#create_field_group_button"
);
const stepThreeBack = document.querySelector("#step-three-back");

// Auto fill the fields. It will use the field title as the label and name (lowercase, no spaces) and set the type to "text"
autoFillButton.addEventListener("click", function (e) {
    e.preventDefault();
    const fields = document.querySelectorAll("#acf_fields > div");
    fields.forEach((item) => {
        item.querySelector("input[name*='label']").value =
            item.querySelector("h3").innerHTML;
        item.querySelector("input[name*='name']").value = item
            .querySelector("h3")
            .innerHTML.toLowerCase()
            .replaceAll(" ", "_")
            .replaceAll("\n", "_");
        item.querySelector("select[name*='type']").value = "text";
    });
});

// Create the field group and save it to the database.
// Then modify the JSON object substituting the field names with the ACF field keys.
// If all goes well, the next step section will be displayed.

createFieldGroupButton.addEventListener("click", function (e) {
    e.preventDefault();
    const fields = document.querySelectorAll("#acf_fields > div");
    const fieldGroup = {};
    fieldGroup.title = document.querySelector("#field_group_title").value;
    fieldGroup.location = document.querySelector("#field_group_location").value;
    fieldGroup.fields = [];
    fields.forEach((item) => {
        const field = {};
        field.label = item.querySelector("input[name*='label']").value;
        field.name = item.querySelector("input[name*='name']").value;
        field.type = item.querySelector("select[name*='type']").value;
        field.key = field.name;
        fieldGroup.fields.push(field);
    });
    fieldGroup.headers = headers;
    jQuery.post(
        "./admin-post.php?action=create_field_group",
        fieldGroup,
        function (response) {
            if (response == "ACF is not installed") {
                alert("ACF is not installed");
            } else if (response == "Missing parameters") {
                alert("Missing parameters");
            } else if (response == "Error creating field group") {
                alert("Error creating field group");
            } else {
                let result = JSON.parse(response);
                result.forEach((item) => {
                    const newName = item.new_name;
                    const oldName = item.old_name;
                    data.forEach((item) => {
                        item[newName] = item[oldName];
                        delete item[oldName];
                    });
                });
                switchSection(stepThree, stepFour);
            }
        }
    );
});

// Switch between the different sections
stepThreeBack.addEventListener("click", function () {
    switchSection(stepThree, stepTwo);
});

/**
 * STEP 4 - Import the data
 */
const stepFour = document.querySelector("#step-four");
const importDataButton = document.querySelector("#import_data_button");
const stepFourBack = document.querySelector("#step-four-back");
const waiting = document.querySelector("#waiting");
const conclusion = document.querySelector("#conclusion");

// Import the data deciding if it should be published or not
importDataButton.addEventListener("click", function (e) {
    e.preventDefault();
    const publish = document.querySelector("#publish_page").value;
    const post_type = document.querySelector("#field_group_location").value;
    switchSection(stepFour, waiting);

    jQuery.post(
        "./admin-post.php?action=import_data",
        {
            publish: publish,
            post_type: post_type,
            data: JSON.stringify(data),
        },
        function (response) {
            if (response == "ACF is not installed") {
                alert("ACF is not installed");
            } else if (response == "Missing parameters") {
                alert("Missing parameters");
            } else {
                switchSection(waiting, conclusion);
            }
        }
    );
});

// Switch between the different sections
stepFourBack.addEventListener("click", function () {
    switchSection(stepFour, stepThree);
});

// Start over button
const startOverButton = document.querySelector("#start-over");

startOverButton.addEventListener("click", function () {
    location.reload();
});
