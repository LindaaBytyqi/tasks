<?php

include "admin_auth.php";
include "../includes/database.php";
include "../includes/csrf.php";


$id = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);


if (!$id) {

    header(
        "Location: admindashboard.php?page=aboutus"
    );

    exit;
}


$stmt = $conn->prepare("
    SELECT *
    FROM about_sections
    WHERE id = :id
");

$stmt->execute([
    'id' => $id
]);


$section = $stmt->fetch(
    PDO::FETCH_ASSOC
);


if (!$section) {

    header(
        "Location: admindashboard.php?page=aboutus"
    );

    exit;
}


$data = json_decode(
    $section['data'],
    true
);


if (!is_array($data)) {

    $data = [];

}


if (isset($_POST['update_section'])) {

    verifyCsrfToken();


    $section_type =
        trim(
            $_POST['section_type'] ?? ''
        );


    $sort_order =
        filter_input(
            INPUT_POST,
            'sort_order',
            FILTER_VALIDATE_INT
        );


    $sort_order =
        $sort_order !== false
        ? $sort_order
        : 0;


    $status =
        isset($_POST['status'])
        ? true
        : false;


    $newData = [];


    if ($section_type === 'hero') {

        $newData = [

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

            'image' =>
                $data['image'] ?? ''

        ];


        $newData['image'] =
            uploadReplacementImage(
                'image',
                $data['image'] ?? ''
            );

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

            $value =
                trim($value);

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


        $newData = [

            'eyebrow' =>
                trim($_POST['eyebrow'] ?? ''),

            'title' =>
                trim($_POST['title'] ?? ''),

            'highlight' =>
                trim($_POST['highlight'] ?? ''),

            'paragraphs' =>
                $paragraphs,

            'stats' =>
                $stats,

            'image' =>
                $data['image'] ?? ''

        ];


        $newData['image'] =
            uploadReplacementImage(
                'image',
                $data['image'] ?? ''
            );

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


        $oldMembers =
            $data['members']
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


            $oldImage =
                $oldMembers[$index]['image']
                ?? '';


            $imageName =
                uploadMemberImage(
                    $index,
                    $oldImage
                );


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


        $newData = [

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

            $values[] = [

                'icon' =>
                    trim($icon),

                'title' =>
                    trim(
                        $titles[$index]
                        ?? ''
                    ),

                'description' =>
                    trim(
                        $descriptions[$index]
                        ?? ''
                    )

            ];

        }


        $newData = [

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

        $newData = [

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

            'image' =>
                $data['image'] ?? ''

        ];


        $newData['image'] =
            uploadReplacementImage(
                'image',
                $data['image'] ?? ''
            );

    }


    elseif (
        $section_type === 'text' ||
        $section_type === 'promise'
    ) {

        $newData = [

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
        UPDATE about_sections
        SET
            section_type = :section_type,
            data = CAST(:data AS JSONB),
            sort_order = :sort_order,
            status = :status,
            updated_at = CURRENT_TIMESTAMP
        WHERE id = :id
    ");


    $stmt->execute([

        'section_type' =>
            $section_type,

        'data' =>
            json_encode(
                $newData,
                JSON_UNESCAPED_UNICODE
            ),

        'sort_order' =>
            $sort_order,

        'status' =>
            $status,

        'id' =>
            $id

    ]);


    header(
        "Location: admindashboard.php?page=aboutus"
    );

    exit;
}


function uploadReplacementImage(
    $field,
    $oldImage
) {

    if (
        !isset($_FILES[$field]) ||
        $_FILES[$field]['error'] !== UPLOAD_ERR_OK
    ) {

        return $oldImage;

    }


    $allowedExtensions = [
        'jpg',
        'jpeg',
        'png',
        'webp'
    ];


    $extension = strtolower(
        pathinfo(
            $_FILES[$field]['name'],
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


    $newImage =
        uniqid('', true)
        . '.'
        . $extension;


    if (!move_uploaded_file(
        $_FILES[$field]['tmp_name'],
        "../images/" . $newImage
    )) {

        die("Image upload failed.");

    }


    if (
        !empty($oldImage) &&
        file_exists(
            "../images/" . $oldImage
        )
    ) {

        unlink(
            "../images/" . $oldImage
        );

    }


    return $newImage;
}


function uploadMemberImage(
    $index,
    $oldImage
) {

    if (
        !isset($_FILES['member_image']['error'][$index]) ||
        $_FILES['member_image']['error'][$index] !== UPLOAD_ERR_OK
    ) {

        return $oldImage;

    }


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

        die("Invalid member image format.");

    }


    $newImage =
        uniqid('', true)
        . '.'
        . $extension;


    if (!move_uploaded_file(
        $_FILES['member_image']['tmp_name'][$index],
        "../images/" . $newImage
    )) {

        die("Member image upload failed.");

    }


    if (
        !empty($oldImage) &&
        file_exists(
            "../images/" . $oldImage
        )
    ) {

        unlink(
            "../images/" . $oldImage
        );

    }


    return $newImage;
}

?>


<div class="container mt-4">

    <div class="card shadow">

        <div class="card-header">

            <h3 class="mb-0">
                Edit About Section
            </h3>

        </div>


        <div class="card-body">

            <form
                action="editabout.php?id=<?= (int)$section['id']; ?>"
                method="POST"
                enctype="multipart/form-data"
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
                    >

                        <?php

                        $types = [
                            'hero' => 'Hero',
                            'story' => 'Story',
                            'team' => 'Team',
                            'values' => 'Values',
                            'quote' => 'Quote',
                            'text' => 'Text',
                            'promise' => 'Promise'
                        ];

                        ?>

                        <?php foreach ($types as $value => $label): ?>

                            <option
                                value="<?= $value; ?>"
                                <?= $section['section_type'] === $value ? 'selected' : ''; ?>
                            >
                                <?= $label; ?>
                            </option>

                        <?php endforeach; ?>

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
                        value="<?= (int)$section['sort_order']; ?>"
                        min="0"
                    >

                </div>


                <div class="form-check mb-4">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="status"
                        id="status"
                        <?= $section['status'] ? 'checked' : ''; ?>
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
                    name="update_section"
                    class="btn btn-success"
                >
                    Update Section
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

const currentData = <?= json_encode(
    $data,
    JSON_UNESCAPED_UNICODE
); ?>;


const sectionType =
    document.getElementById("sectionType");

const sectionFields =
    document.getElementById("sectionFields");


sectionType.addEventListener(
    "change",
    function () {

        renderFields(
            this.value,
            {}
        );

    }
);


function renderFields(type, data) {

    sectionFields.innerHTML = "";


    if (type === "hero") {

        sectionFields.innerHTML = `

            ${basicFields(data)}

            <div class="mb-3">

                <label class="form-label">
                    Description
                </label>

                <textarea
                    name="description"
                    class="form-control"
                    rows="4"
                >${escapeHtml(data.description || "")}</textarea>

            </div>

            ${editImageField(data.image || "")}

            ${buttonFields(data)}

        `;

    }


    if (type === "story") {

        sectionFields.innerHTML = `

            ${basicFields(data)}

            <div class="mb-3">

                <label class="form-label">
                    Paragraphs
                </label>

                <div id="paragraphs">

                    ${(data.paragraphs || [])
                        .map(paragraphField)
                        .join("")}

                </div>

                <button
                    type="button"
                    class="btn btn-outline-primary btn-sm"
                    onclick="addParagraph()"
                >
                    + Add Paragraph
                </button>

            </div>


            ${editImageField(data.image || "")}


            <hr>

            <h5>
                Statistics
            </h5>

            <div id="stats">

                ${(data.stats || [])
                    .map(statField)
                    .join("")}

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

            ${basicFields(data)}

            <hr>

            <h5>
                Team Members
            </h5>

            <div id="teamMembers">

                ${(data.members || [])
                    .map(teamMemberField)
                    .join("")}

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

            ${basicFields(data)}

            <div class="mb-3">

                <label class="form-label">
                    Section Description
                </label>

                <textarea
                    name="description"
                    class="form-control"
                    rows="3"
                >${escapeHtml(data.description || "")}</textarea>

            </div>

            <hr>

            <h5>
                Values
            </h5>

            <div id="values">

                ${(data.values || [])
                    .map(valueField)
                    .join("")}

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

            ${basicFields(data)}

            <div class="mb-3">

                <label class="form-label">
                    Description
                </label>

                <textarea
                    name="description"
                    class="form-control"
                    rows="3"
                >${escapeHtml(data.description || "")}</textarea>

            </div>

            ${editImageField(data.image || "")}

            ${buttonFields(data)}

        `;

    }


    if (
        type === "text" ||
        type === "promise"
    ) {

        sectionFields.innerHTML = `

            ${basicFields(data)}

            <div class="mb-3">

                <label class="form-label">
                    Description
                </label>

                <textarea
                    name="description"
                    class="form-control"
                    rows="5"
                >${escapeHtml(data.description || "")}</textarea>

            </div>

        `;

    }

}


function basicFields(data) {

    return `

        <div class="mb-3">

            <label class="form-label">
                Eyebrow
            </label>

            <input
                type="text"
                name="eyebrow"
                class="form-control"
                value="${escapeHtml(data.eyebrow || "")}"
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
                value="${escapeHtml(data.title || "")}"
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
                value="${escapeHtml(data.highlight || "")}"
            >

        </div>

    `;

}


function editImageField(image) {

    return `

        <div class="mb-3">

            <label class="form-label">
                Current Image
            </label>

            <br>

            ${
                image
                ? `<img
                        src="../images/${escapeHtml(image)}"
                        style="width:220px;height:120px;object-fit:cover;border-radius:10px;"
                   >`
                : `<p class="text-muted">
                        No image
                   </p>`
            }

        </div>


        <div class="mb-3">

            <label class="form-label">
                Change Image
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


function buttonFields(data) {

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
                        value="${escapeHtml(data.button_text || "")}"
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
                        value="${escapeHtml(data.button_link || "")}"
                    >

                </div>

            </div>

        </div>

    `;

}


function paragraphField(
    paragraph = ""
) {

    return `

        <div class="input-group mb-2 paragraph-item">

            <textarea
                name="paragraphs[]"
                class="form-control"
                rows="3"
            >${escapeHtml(paragraph)}</textarea>

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
            paragraphField("")
        );

}


function statField(
    stat = {}
) {

    return `

        <div class="row mb-2 stat-item">

            <div class="col-md-5">

                <input
                    type="text"
                    name="stat_value[]"
                    class="form-control"
                    placeholder="Value"
                    value="${escapeHtml(stat.value || "")}"
                >

            </div>


            <div class="col-md-5">

                <input
                    type="text"
                    name="stat_label[]"
                    class="form-control"
                    placeholder="Label"
                    value="${escapeHtml(stat.label || "")}"
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
            statField({})
        );

}


function teamMemberField(
    member = {},
    index
) {

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
                            value="${escapeHtml(member.role || "")}"
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
                            value="${escapeHtml(member.name || "")}"
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
                    >${escapeHtml(member.description || "")}</textarea>

                </div>


                ${
                    member.image
                    ? `<div class="mb-3">
                            <img
                                src="../images/${escapeHtml(member.image)}"
                                style="width:150px;height:100px;object-fit:cover;border-radius:10px;"
                            >
                       </div>`
                    : ""
                }


                <div class="mb-3">

                    <label class="form-label">
                        Change Image
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
            teamMemberField({}, null)
        );

}


function valueField(
    value = {}
) {

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
                            value="${escapeHtml(value.icon || "")}"
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
                            value="${escapeHtml(value.title || "")}"
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
                    >${escapeHtml(value.description || "")}</textarea>

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
            valueField({})
        );

}


function escapeHtml(value) {

    return String(value ?? "")
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");

}


renderFields(
    sectionType.value,
    currentData
);

</script>