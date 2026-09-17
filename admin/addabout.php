<?php

include "admin_auth.php";
include "../includes/database.php";
include "../includes/csrf.php";


if (isset($_POST['save_section'])) {

    verifyCsrfToken();


    $section_type = trim(
        $_POST['section_type'] ?? ''
    );


    $sort_order = filter_input(
        INPUT_POST,
        'sort_order',
        FILTER_VALIDATE_INT
    );

    $sort_order =
        $sort_order !== false
        ? $sort_order
        : 0;


    $status = isset($_POST['status']) ? true : false;


    $data = [];

    if ($section_type === 'hero') {

        $data = [

            'eyebrow' =>
                trim($_POST['eyebrow'] ?? ''),

            'title' =>
                trim($_POST['title'] ?? ''),

            'highlight' =>
                trim($_POST['highlight'] ?? ''),

            'description' =>
                trim($_POST['description'] ?? ''),

            'button_text' =>
                trim($_POST['button_text'] ?? ''),

            'button_link' =>
                trim($_POST['button_link'] ?? ''),

            'image' => ''

        ];


        if (
            isset($_FILES['image']) &&
            $_FILES['image']['error'] === UPLOAD_ERR_OK
        ) {

            $allowedExtensions = [
                'jpg',
                'jpeg',
                'png',
                'webp'
            ];


            $extension = strtolower(
                pathinfo(
                    $_FILES['image']['name'],
                    PATHINFO_EXTENSION
                )
            );


            if (!in_array(
                $extension,
                $allowedExtensions,
                true
            )) {

                die("Invalid image format.");

            }


            $imageName =
                uniqid('', true)
                . '.'
                . $extension;


            $uploadPath =
                "../images/" . $imageName;


            if (!move_uploaded_file(
                $_FILES['image']['tmp_name'],
                $uploadPath
            )) {

                die("Image upload failed.");

            }


            $data['image'] = $imageName;

        }

    }


    elseif ($section_type === 'story') {

        $paragraphs =
            $_POST['paragraphs']
            ?? [];


        $paragraphs = array_values(
            array_filter(
                array_map(
                    'trim',
                    $paragraphs
                )
            )
        );


        $stats = [];


        $statValues =
            $_POST['stat_value']
            ?? [];


        $statLabels =
            $_POST['stat_label']
            ?? [];


        foreach ($statValues as $index => $value) {

            $value = trim($value);

            $label =
                trim(
                    $statLabels[$index]
                    ?? ''
                );


            if (
                $value === '' &&
                $label === ''
            ) {

                continue;

            }


            $stats[] = [

                'value' => $value,

                'label' => $label

            ];

        }


        $data = [

            'eyebrow' =>
                trim($_POST['eyebrow'] ?? ''),

            'title' =>
                trim($_POST['title'] ?? ''),

            'highlight' =>
                trim($_POST['highlight'] ?? ''),

            'paragraphs' =>
                $paragraphs,

            'image' => '',

            'stats' =>
                $stats

        ];


        if (
            isset($_FILES['image']) &&
            $_FILES['image']['error'] === UPLOAD_ERR_OK
        ) {

            $allowedExtensions = [
                'jpg',
                'jpeg',
                'png',
                'webp'
            ];


            $extension = strtolower(
                pathinfo(
                    $_FILES['image']['name'],
                    PATHINFO_EXTENSION
                )
            );


            if (!in_array(
                $extension,
                $allowedExtensions,
                true
            )) {

                die("Invalid image format.");

            }


            $imageName =
                uniqid('', true)
                . '.'
                . $extension;


            if (!move_uploaded_file(
                $_FILES['image']['tmp_name'],
                "../images/" . $imageName
            )) {

                die("Image upload failed.");

            }


            $data['image'] = $imageName;

        }

    }

    elseif ($section_type === 'team') {

        $members = [];


        $roles =
            $_POST['member_role']
            ?? [];


        $names =
            $_POST['member_name']
            ?? [];


        $descriptions =
            $_POST['member_description']
            ?? [];


        foreach ($roles as $index => $role) {

            $role =
                trim($role);

            $name =
                trim(
                    $names[$index]
                    ?? ''
                );

            $description =
                trim(
                    $descriptions[$index]
                    ?? ''
                );


            $imageName = '';


            if (
                isset($_FILES['member_image']['error'][$index]) &&
                $_FILES['member_image']['error'][$index] === UPLOAD_ERR_OK
            ) {

                $extension = strtolower(
                    pathinfo(
                        $_FILES['member_image']['name'][$index],
                        PATHINFO_EXTENSION
                    )
                );


                $allowedExtensions = [
                    'jpg',
                    'jpeg',
                    'png',
                    'webp'
                ];


                if (!in_array(
                    $extension,
                    $allowedExtensions,
                    true
                )) {

                    die("Invalid team image format.");

                }


                $imageName =
                    uniqid('', true)
                    . '.'
                    . $extension;


                if (!move_uploaded_file(
                    $_FILES['member_image']['tmp_name'][$index],
                    "../images/" . $imageName
                )) {
                    die("Team image upload failed.");
                }

            }


            $members[] = [
                'role' =>
                    $role,

                'name' =>
                    $name,

                'description' =>
                    $description,

                'image' =>
                    $imageName

            ];

        }


        $data = [

            'eyebrow' =>
                trim($_POST['eyebrow'] ?? ''),

            'title' =>
                trim($_POST['title'] ?? ''),

            'highlight' =>
                trim($_POST['highlight'] ?? ''),

            'members' =>
                $members

        ];

    }


    elseif ($section_type === 'values') {

        $values = [];


        $icons =
            $_POST['value_icon']
            ?? [];


        $titles =
            $_POST['value_title']
            ?? [];


        $descriptions =
            $_POST['value_description']
            ?? [];


        foreach ($icons as $index => $icon) {

            $icon =
                trim($icon);

            $title =
                trim(
                    $titles[$index]
                    ?? ''
                );

            $description =
                trim(
                    $descriptions[$index]
                    ?? ''
                );


            $values[] = [

                'icon' =>
                    $icon,

                'title' =>
                    $title,

                'description' =>
                    $description

            ];

        }


        $data = [

            'eyebrow' =>
                trim($_POST['eyebrow'] ?? ''),

            'title' =>
                trim($_POST['title'] ?? ''),

            'highlight' =>
                trim($_POST['highlight'] ?? ''),

            'description' =>
                trim($_POST['description'] ?? ''),

            'values' =>
                $values

        ];

    }
    elseif ($section_type === 'quote') {

        $data = [

            'eyebrow' =>
                trim($_POST['eyebrow'] ?? ''),

            'title' =>
                trim($_POST['title'] ?? ''),

            'highlight' =>
                trim($_POST['highlight'] ?? ''),

            'description' =>
                trim($_POST['description'] ?? ''),

            'button_text' =>
                trim($_POST['button_text'] ?? ''),

            'button_link' =>
                trim($_POST['button_link'] ?? ''),

            'image' => ''

        ];


        if (
            isset($_FILES['image']) &&
            $_FILES['image']['error'] === UPLOAD_ERR_OK
        ) {

            $extension = strtolower(
                pathinfo(
                    $_FILES['image']['name'],
                    PATHINFO_EXTENSION
                )
            );


            $allowedExtensions = [
                'jpg',
                'jpeg',
                'png',
                'webp'
            ];


            if (!in_array(
                $extension,
                $allowedExtensions,
                true
            )) {

                die("Invalid image format.");

            }


            $imageName =
                uniqid('', true)
                . '.'
                . $extension;


            if (!move_uploaded_file(
                $_FILES['image']['tmp_name'],
                "../images/" . $imageName
            )) {

                die("Image upload failed.");

            }


            $data['image'] =
                $imageName;

        }

    }

    elseif ($section_type === 'text' || $section_type === 'promise') {

        $data = [

            'eyebrow' =>
                trim($_POST['eyebrow'] ?? ''),

            'title' =>
                trim($_POST['title'] ?? ''),

            'highlight' =>
                trim($_POST['highlight'] ?? ''),

            'description' =>
                trim($_POST['description'] ?? '')

        ];
    }


    else {
        die("Invalid section type.");
    }
    $stmt = $conn->prepare("
        INSERT INTO about_sections
        (
            section_type,
            data,
            sort_order,
            status
        )
        VALUES
        (
            :section_type,
            CAST(:data AS JSONB),
            :sort_order,
            :status
        )
    ");


    $stmt->execute([

        'section_type' =>
            $section_type,

        'data' =>
            json_encode(
                $data,
                JSON_UNESCAPED_UNICODE
            ),

        'sort_order' =>
            $sort_order,

        'status' =>
            $status

    ]);


  echo  
    '<script>
        window.location.href = "admindashboard.php?page=aboutus";
    </script>';
exit;
}

?>


<div class="container mt-4">

    <div class="card shadow">

        <div class="card-header">

            <h3 class="mb-0">
                Add About Section
            </h3>

        </div>


        <div class="card-body">

            <form
                method="POST"
                enctype="multipart/form-data"
                id="aboutSectionForm"
            >

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars(
                        generateCsrfToken(),
                        ENT_QUOTES,
                        'UTF-8'
                    ); ?>"
                >


                <div class="mb-4">

                    <label class="form-label">
                        Section Type
                    </label>

                    <select
                        name="section_type"
                        id="sectionType"
                        class="form-select"
                        required
                    >

                        <option value="">
                            Select Section
                        </option>

                        <option value="hero">
                            Hero
                        </option>

                        <option value="story">
                            Story
                        </option>

                        <option value="team">
                            Team
                        </option>

                        <option value="values">
                            Values
                        </option>

                        <option value="quote">
                            Quote
                        </option>

                        <option value="text">
                            Text
                        </option>

                        <option value="promise">
                            Promise
                        </option>

                    </select>

                </div>


                <div id="sectionFields"></div>


                <div class="mb-3">

                    <label class="form-label">
                        Sort Order
                    </label>

                    <input
                        type="number"
                        name="sort_order"
                        class="form-control"
                        value="1"
                        min="0"
                    >

                </div>


                <div class="form-check mb-4">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="status"
                        id="status"
                        checked
                    >

                    <label
                        class="form-check-label"
                        for="status"
                    >
                        Active
                    </label>

                </div>


                <button
                    type="submit"
                    name="save_section"
                    class="btn btn-success"
                >
                    Save Section
                </button>


                <a
                    href="admindashboard.php?page=aboutus"
                    class="btn btn-secondary"
                >
                    Cancel
                </a>

            </form>

        </div>

    </div>

</div>


<script>

const sectionType =
    document.getElementById("sectionType");

const sectionFields =
    document.getElementById("sectionFields");


sectionType.addEventListener(
    "change",
    function () {

        renderFields(
            this.value
        );

    }
);


function renderFields(type) {

    sectionFields.innerHTML = "";


    if (type === "hero") {

        sectionFields.innerHTML = `

            ${basicFields()}

            ${imageField()}

            ${buttonFields()}

        `;

    }


    if (type === "story") {

        sectionFields.innerHTML = `

            ${basicFields()}

            <div class="mb-3">

                <label class="form-label">
                    Paragraphs
                </label>

                <div id="paragraphs">

                    ${paragraphField()}

                </div>

                <button
                    type="button"
                    class="btn btn-outline-primary btn-sm"
                    onclick="addParagraph()"
                >
                    + Add Paragraph
                </button>

            </div>


            ${imageField()}


            <hr>

            <h5>
                Statistics
            </h5>

            <div id="stats">

                ${statField()}

            </div>

            <button
                type="button"
                class="btn btn-outline-primary btn-sm"
                onclick="addStat()"
            >
                + Add Statistic
            </button>

        `;

    }


    if (type === "team") {

        sectionFields.innerHTML = `

            ${basicFields()}

            <hr>

            <h5>
                Team Members
            </h5>

            <div id="teamMembers">

                ${teamMemberField()}

            </div>

            <button
                type="button"
                class="btn btn-outline-primary btn-sm"
                onclick="addTeamMember()"
            >
                + Add Team Member
            </button>

        `;

    }


    if (type === "values") {

        sectionFields.innerHTML = `

            ${basicFields()}

            <div class="mb-3">

                <label class="form-label">
                    Section Description
                </label>

                <textarea
                    name="description"
                    class="form-control"
                    rows="3"
                ></textarea>

            </div>

            <hr>

            <h5>
                Values
            </h5>

            <div id="values">

                ${valueField()}

            </div>

            <button
                type="button"
                class="btn btn-outline-primary btn-sm"
                onclick="addValue()"
            >
                + Add Value
            </button>

        `;

    }


    if (type === "quote") {

        sectionFields.innerHTML = `

            ${basicFields()}

            <div class="mb-3">

                <label class="form-label">
                    Description
                </label>

                <textarea
                    name="description"
                    class="form-control"
                    rows="3"
                ></textarea>

            </div>

            ${imageField()}

            ${buttonFields()}

        `;

    }


    if (
        type === "text" ||
        type === "promise"
    ) {

        sectionFields.innerHTML = `

            ${basicFields()}

            <div class="mb-3">

                <label class="form-label">
                    Description
                </label>

                <textarea
                    name="description"
                    class="form-control"
                    rows="5"
                ></textarea>

            </div>

        `;

    }

}


function basicFields() {

    return `

        <div class="mb-3">

            <label class="form-label">
                Eyebrow
            </label>

            <input
                type="text"
                name="eyebrow"
                class="form-control"
            >

        </div>


        <div class="mb-3">

            <label class="form-label">
                Title
            </label>

            <input
                type="text"
                name="title"
                class="form-control"
            >

        </div>


        <div class="mb-3">

            <label class="form-label">
                Highlight
            </label>

            <input
                type="text"
                name="highlight"
                class="form-control"
            >

        </div>

    `;

}


function imageField() {

    return `

        <div class="mb-3">

            <label class="form-label">
                Image
            </label>

            <input
                type="file"
                name="image"
                class="form-control"
                accept=".jpg,.jpeg,.png,.webp"
            >

        </div>

    `;

}


function buttonFields() {

    return `

        <div class="row">

            <div class="col-md-6">

                <div class="mb-3">

                    <label class="form-label">
                        Button Text
                    </label>

                    <input
                        type="text"
                        name="button_text"
                        class="form-control"
                    >

                </div>

            </div>


            <div class="col-md-6">

                <div class="mb-3">

                    <label class="form-label">
                        Button Link
                    </label>

                    <input
                        type="text"
                        name="button_link"
                        class="form-control"
                    >

                </div>

            </div>

        </div>

    `;

}


function paragraphField() {

    return `

        <div class="input-group mb-2 paragraph-item">

            <textarea
                name="paragraphs[]"
                class="form-control"
                rows="3"
                placeholder="Paragraph"
            ></textarea>

            <button
                type="button"
                class="btn btn-danger"
                onclick="this.parentElement.remove()"
            >
                ×
            </button>

        </div>

    `;

}


function addParagraph() {

    document
        .getElementById("paragraphs")
        .insertAdjacentHTML(
            "beforeend",
            paragraphField()
        );

}


function statField() {

    return `

        <div class="row mb-2 stat-item">

            <div class="col-md-5">

                <input
                    type="text"
                    name="stat_value[]"
                    class="form-control"
                    placeholder="Value"
                >

            </div>


            <div class="col-md-5">

                <input
                    type="text"
                    name="stat_label[]"
                    class="form-control"
                    placeholder="Label"
                >

            </div>


            <div class="col-md-2">

                <button
                    type="button"
                    class="btn btn-danger w-100"
                    onclick="this.closest('.stat-item').remove()"
                >
                    ×
                </button>

            </div>

        </div>

    `;

}


function addStat() {

    document
        .getElementById("stats")
        .insertAdjacentHTML(
            "beforeend",
            statField()
        );

}


function teamMemberField() {

    return `

        <div class="card mb-3 team-member-item">

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6">

                        <label class="form-label">
                            Role
                        </label>

                        <input
                            type="text"
                            name="member_role[]"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Name
                        </label>

                        <input
                            type="text"
                            name="member_name[]"
                            class="form-control"
                        >

                    </div>

                </div>


                <div class="mb-3 mt-3">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea
                        name="member_description[]"
                        class="form-control"
                        rows="3"
                    ></textarea>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Image
                    </label>

                    <input
                        type="file"
                        name="member_image[]"
                        class="form-control"
                        accept=".jpg,.jpeg,.png,.webp"
                    >

                </div>


                <button
                    type="button"
                    class="btn btn-danger"
                    onclick="this.closest('.team-member-item').remove()"
                >
                    Remove Member
                </button>

            </div>

        </div>

    `;

}


function addTeamMember() {

    document
        .getElementById("teamMembers")
        .insertAdjacentHTML(
            "beforeend",
            teamMemberField()
        );

}


function valueField() {

    return `

        <div class="card mb-3 value-item">

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">

                        <label class="form-label">
                            Bootstrap Icon
                        </label>

                        <input
                            type="text"
                            name="value_icon[]"
                            class="form-control"
                            placeholder="bi-gem"
                        >

                    </div>


                    <div class="col-md-8">

                        <label class="form-label">
                            Title
                        </label>

                        <input
                            type="text"
                            name="value_title[]"
                            class="form-control"
                        >

                    </div>

                </div>


                <div class="mt-3">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea
                        name="value_description[]"
                        class="form-control"
                        rows="3"
                    ></textarea>

                </div>


                <button
                    type="button"
                    class="btn btn-danger mt-3"
                    onclick="this.closest('.value-item').remove()"
                >
                    Remove Value
                </button>

            </div>

        </div>

    `;

}


function addValue() {

    document
        .getElementById("values")
        .insertAdjacentHTML(
            "beforeend",
            valueField()
        );

}

</script>