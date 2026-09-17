
<?php

include "admin_auth.php";
include "../includes/csrf.php";
include "../includes/database.php";


if (isset($_POST['add_blog'])) {

    verifyCsrfToken();


    $title = trim(
        $_POST['title'] ?? ''
    );

    $describe = trim(
        $_POST['describe'] ?? ''
    );

    $published_at = trim(
        $_POST['published_at'] ?? ''
    ); 

    $button_text = trim(
        $_POST['button_text'] ?? ''
    );

    $button_url = trim(
        $_POST['button_url'] ?? ''
    );

    $status = isset($_POST['status']);

    if ($title === '') {
        die("Title is required.");
    }

    if ($describe === '') {
        die("Description is required.");
    }

    if ($published_at === '') {
        die("Published date is required.");
    }

    $uploadDirectory = "../images/";

    if (!is_dir($uploadDirectory)) {

        mkdir(
            $uploadDirectory,
            0777,
            true
        );

    }


    $mainImage = '';


    if (
        isset($_FILES['main_image']) &&
        $_FILES['main_image']['error'] === UPLOAD_ERR_OK
    ) {

        $allowedExtensions = [
            'jpg',
            'jpeg',
            'png',
            'webp',
            'jfif'
        ];


        $extension = strtolower(
            pathinfo(
                $_FILES['main_image']['name'],
                PATHINFO_EXTENSION
            )
        );


        if (
            !in_array(
                $extension,
                $allowedExtensions,
                true
            )
        ) {

            die("Invalid main image format.");

        }


        $mainImage =
            uniqid(
                'blog_',
                true
            )
            . '.'
            . $extension;


        if (
            !move_uploaded_file(
                $_FILES['main_image']['tmp_name'],
                $uploadDirectory . $mainImage
            )
        ) {

            die("Main image upload failed.");

        }

    }

    $content = [];


    if (!empty($_POST['content'])) {

        $decodedContent = json_decode(
            $_POST['content'],
            true
        );


        if (
            json_last_error() === JSON_ERROR_NONE &&
            is_array($decodedContent)
        ) {


            foreach ($decodedContent as $block) {

                if (
                    !isset($block['type']) ||
                    !in_array(
                        $block['type'],
                        [
                            'paragraph',
                            'paragraph_list',
                            'section'
                        ],
                        true
                    )
                ) {
                    continue;
                }

                if (
                    $block['type'] === 'paragraph'
                ) {

                    $text = trim(
                        $block['text'] ?? ''
                    );


                    if ($text !== '') {

                        $content[] = [

                            'type' =>
                                'paragraph',

                            'text' =>
                                $text

                        ];

                    }

                }

                if (
                    $block['type'] === 'paragraph_list'
                ) {

                    $text = trim(
                        $block['text'] ?? ''
                    );


                    $list = [];


                    if (
                        isset($block['list']) &&
                        is_array($block['list'])
                    ) {

                        foreach (
                            $block['list']
                            as $item
                        ) {

                            $itemText = trim(
                                $item['text'] ?? ''
                            );


                            if ($itemText === '') {

                                continue;

                            }


                            $list[] = [

                                'text' =>
                                    $itemText

                            ];

                        }

                    }

                    if (
                        $text !== '' ||
                        !empty($list)
                    ) {

                        $content[] = [

                            'type' =>
                                'paragraph_list',

                            'text' =>
                                $text,

                            'list' =>
                                $list

                        ];

                    }

                }
                if (
                    $block['type'] === 'section'
                ) {

                    $uid = $block['uid'] ?? '';
                    $subtitle = trim(
                        $block['subtitle'] ?? ''
                    );

                    $paragraphs = [];


                    if (
                        isset($block['paragraphs']) &&
                        is_array($block['paragraphs'])
                    ) {

                        foreach (
                            $block['paragraphs']
                            as $paragraph
                        ) {

                            $paragraph = trim(
                                $paragraph
                            );


                            if ($paragraph !== '') {

                                $paragraphs[] =
                                    $paragraph;

                            }

                        }

                    }


                    $list = [];


                    if (
                        isset($block['list']) &&
                        is_array($block['list'])
                    ) {

                        foreach (
                            $block['list']
                            as $item
                        ) {

                            $itemTitle = trim(
                                $item['title'] ?? ''
                            );


                            $itemText = trim(
                                $item['text'] ?? ''
                            );


                            if (
                                $itemTitle === '' &&
                                $itemText === ''
                            ) {

                                continue;

                            }


                            $list[] = [

                                'title' =>
                                    $itemTitle,

                                'text' =>
                                    $itemText

                            ];

                        }

                    }

                    $sectionImage = '';


                    if (
                        $uid !== '' &&
                        isset(
                            $_FILES['section_images']['error'][$uid]
                        ) &&
                        $_FILES['section_images']['error'][$uid]
                        === UPLOAD_ERR_OK
                    ) {


                        $extension = strtolower(
                            pathinfo(
                                $_FILES['section_images']['name'][$uid],
                                PATHINFO_EXTENSION
                            )
                        );


                        $allowedExtensions = [

                            'jpg',
                            'jpeg',
                            'png',
                            'webp',
                            'jfif'

                        ];


                        if (
                            !in_array(
                                $extension,
                                $allowedExtensions,
                                true
                            )
                        ) {

                            die(
                                "Invalid section image format."
                            );

                        }


                        $sectionImage =
                            uniqid(
                                'section_',
                                true
                            )
                            . '.'
                            . $extension;


                        if (
                            !move_uploaded_file(
                                $_FILES['section_images']['tmp_name'][$uid],
                                $uploadDirectory . $sectionImage
                            )
                        ) {

                            die(
                                "Section image upload failed."
                            );

                        }

                    }

                    $sectionData = [

                        'type' =>
                            'section',

                        'subtitle' =>
                            $subtitle,

                        'image' =>
                            $sectionImage,

                        'paragraphs' =>
                            $paragraphs,

                        'list' =>
                            $list

                    ];


                    $content[] =
                        $sectionData;

                }

            }

        }

    }

    $sql = "

        INSERT INTO blogs (

            title,
            describe,
            published_at,
            main_image,
            content,
            button_text,
            button_url,
            status

        )

        VALUES (

            :title,
            :describe,
            :published_at,
            :main_image,
            CAST(:content AS JSONB),
            :button_text,
            :button_url,
            :status

        )

    ";


    $stmt = $conn->prepare($sql);


    $stmt->execute([

        'title' =>
            $title,

        'describe' =>
            $describe,

        'published_at' =>
            $published_at,

        'main_image' =>
            $mainImage,

        'content' =>
            json_encode(
                $content,
                JSON_UNESCAPED_UNICODE
            ),

        'button_text' =>
            $button_text,

        'button_url' =>
            $button_url,

        'status' =>
            $status

    ]);
echo '<script>
    window.location.href = "admindashboard.php?page=blog";
</script>';
exit;
}

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Add Blog
    </title>


    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>


<body class="bg-light">


<div class="container mt-5 mb-5">

    <div class="row justify-content-center">

        <div class="col-md-10 col-lg-9">

            <div class="card shadow">

                <div class="card-header bg-primary text-white">

                    <h3 class="mb-0">
                        Add New Article
                    </h3>

                </div>


                <div class="card-body">


                    <form
                        method="POST"
                        enctype="multipart/form-data"
                        id="blogForm"
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

                        <h5 class="mb-3">
                            Article Information
                        </h5>


                        <div class="mb-3">

                            <label class="form-label">
                                Title
                            </label>

                            <input
                                type="text"
                                name="title"
                                class="form-control"
                                maxlength="255"
                                required
                            >

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea
                                name="describe"
                                class="form-control"
                                rows="3"
                                maxlength="150"
                                required
                            ></textarea>

                            <small class="text-muted">
                                Maximum 150 characters.
                            </small>

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Published Date
                            </label>

                            <input
                                type="date"
                                name="published_at"
                                class="form-control"
                                value="<?= date('Y-m-d'); ?>"
                                required
                            >

                        </div>


                        <div class="mb-4">

                            <label class="form-label">
                                Main Image
                            </label>

                            <input
                                type="file"
                                name="main_image"
                                class="form-control"
                                accept=".jpg,.jpeg,.png,.webp,.jfif"
                            >

                            <small class="text-muted">
                                JPG, JPEG, PNG, WEBP or JFIF
                            </small>

                        </div>

                        <hr class="my-4">

                        <h5 class="mb-2">
                            Article Content
                        </h5>


                        <p class="text-muted">
                            Add paragraphs and sections to your article.
                        </p>


                        <div
                            id="contentBlocks"
                        ></div>


                        <div
                            id="emptyMessage"
                            class="text-muted border rounded p-4 text-center mb-3"
                        >

                            No content added yet.

                        </div>

                        <div class="d-flex gap-2 mb-4 flex-wrap">


                            <button
                                type="button"
                                class="btn btn-outline-primary"
                                onclick="addParagraph()"
                            >

                                + Add Paragraph

                            </button>


                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                onclick="addParagraphList()"
                            >

                                + Add Paragraph + List

                            </button>


                            <button
                                type="button"
                                class="btn btn-outline-primary"
                                onclick="addSection()"
                            >

                                + Add Section

                            </button>


                        </div>


                        <input
                            type="hidden"
                            name="content"
                            id="contentInput"
                        >


                        <hr class="my-4">

                        <h5 class="mb-3">
                            Bottom Button
                        </h5>


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
                                        Button URL
                                    </label>

                                    <input
                                        type="text"
                                        name="button_url"
                                        class="form-control"
                                    >

                                </div>

                            </div>


                        </div>


                        <!-- Status -->

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

                                Active Article

                            </label>

                        </div>


                        <!-- Buttons -->

                        <button
                            type="submit"
                            name="add_blog"
                            class="btn btn-success"
                        >

                            Save Article

                        </button>


                        <a
                            href="admindashboard.php?page=blog"
                            class="btn btn-secondary"
                        >

                            Cancel

                        </a>


                    </form>

                </div>

            </div>

        </div>

    </div>

</div>
<script>

let contentBlocks = [];
function generateUid() {

    return (
        'section_' +
        Date.now() +
        '_' +
        Math.random()
            .toString(36)
            .substring(2, 9)
    );

}

function addParagraph() {

    contentBlocks.push({

        type: "paragraph",

        text: ""

    });


    renderContent();

}

function addParagraphList() {

    contentBlocks.push({

        type: "paragraph_list",

        text: "",

        list: []

    });


    renderContent();

}

function addSection() {

    contentBlocks.push({

        type: "section",

        uid: generateUid(),

        subtitle: "",

        paragraphs: [],

        list: []

    });


    renderContent();

}

function renderContent() {

    const container =
        document.getElementById(
            "contentBlocks"
        );


    const emptyMessage =
        document.getElementById(
            "emptyMessage"
        );


    container.innerHTML = "";


    if (
        contentBlocks.length === 0
    ) {

        emptyMessage.style.display =
            "block";

        return;

    }


    emptyMessage.style.display =
        "none";


    contentBlocks.forEach(
        (block, blockIndex) => {
            if (
                block.type === "paragraph"
            ) {

                renderParagraph(
                    block,
                    blockIndex
                );
            }

            if (
                block.type === "paragraph_list"
            ) {

                renderParagraphList(
                    block,
                    blockIndex
                );

            }

            if (
                block.type === "section"
            ) {

                renderSection(
                    block,
                    blockIndex
                );

            }

        }
    );

}

function renderParagraph(
    block,
    blockIndex
) {

    const container =
        document.getElementById(
            "contentBlocks"
        );


    const div =
        document.createElement(
            "div"
        );


    div.className =
        "card mb-3";


    div.innerHTML = `

        <div class="card-header
                    d-flex
                    justify-content-between
                    align-items-center">

            <strong>
                Paragraph
            </strong>

            <button
                type="button"
                class="btn btn-danger btn-sm"
                onclick="removeBlock(${blockIndex})"
            >
                Remove
            </button>

        </div>


        <div class="card-body">

            <textarea
                class="form-control"
                rows="4"
                placeholder="Write your paragraph..."
            >${escapeHtml(block.text || '')}</textarea>

        </div>

    `;


    const textarea =
        div.querySelector(
            "textarea"
        );


    textarea.addEventListener(
        "input",
        function () {

            contentBlocks[
                blockIndex
            ].text = this.value;

        }
    );


    container.appendChild(div);

}

function renderParagraphList(
    block,
    blockIndex
) {

    const container =
        document.getElementById(
            "contentBlocks"
        );


    const div =
        document.createElement(
            "div"
        );


    div.className =
        "card mb-3 border-secondary";


    let listHTML = "";


    block.list.forEach(
        (item, listIndex) => {

            listHTML += `

                <div class="input-group mb-2">

                    <input
                        type="text"
                        class="form-control paragraph-list-item"
                        data-list="${listIndex}"
                        value="${escapeHtml(
                            item.text || ''
                        )}"
                        placeholder="Write list item..."
                    >

                    <button
                        type="button"
                        class="btn btn-danger"
                        onclick="
                            removeParagraphListItem(
                                ${blockIndex},
                                ${listIndex}
                            )
                        "
                    >

                        ×

                    </button>

                </div>

            `;

        }
    );


    div.innerHTML = `

        <div class="card-header
                    d-flex
                    justify-content-between
                    align-items-center">

            <strong>
                Paragraph + Unordered List
            </strong>

            <button
                type="button"
                class="btn btn-danger btn-sm"
                onclick="removeBlock(${blockIndex})"
            >
                Remove
            </button>

        </div>


        <div class="card-body">


            <!-- Paragraph -->

            <div class="mb-3">

                <label class="form-label">

                    Paragraph

                </label>


                <textarea
                    class="form-control paragraph-list-text"
                    rows="4"
                    placeholder="Write your paragraph..."
                >${escapeHtml(
                    block.text || ''
                )}</textarea>

            </div>


            <!-- Unordered List -->

            <div>

                <div class="d-flex
                            justify-content-between
                            align-items-center
                            mb-2">

                    <label class="form-label mb-0">

                        Unordered List

                    </label>


                    <button
                        type="button"
                        class="btn btn-outline-primary btn-sm"
                        onclick="
                            addParagraphListItem(
                                ${blockIndex}
                            )
                        "
                    >

                        + Add List Item

                    </button>

                </div>


                <div>

                    ${
                        listHTML ||
                        '<p class="text-muted mb-0">No list items added.</p>'
                    }

                </div>

            </div>


        </div>

    `;

    const paragraphTextarea =
        div.querySelector(
            ".paragraph-list-text"
        );


    paragraphTextarea.addEventListener(
        "input",
        function () {

            contentBlocks[
                blockIndex
            ].text = this.value;

        }
    );

    div
        .querySelectorAll(
            ".paragraph-list-item"
        )
        .forEach(
            input => {

                input.addEventListener(
                    "input",
                    function () {

                        const index =
                            parseInt(
                                this.dataset.list
                            );


                        contentBlocks[
                            blockIndex
                        ].list[index].text =
                            this.value;

                    }
                );

            }
        );


    container.appendChild(div);

}

function addParagraphListItem(
    blockIndex
) {

    contentBlocks[
        blockIndex
    ].list.push({

        text: ""

    });


    renderContent();

}
function removeParagraphListItem(
    blockIndex,
    listIndex
) {

    contentBlocks[
        blockIndex
    ].list.splice(
        listIndex,
        1
    );


    renderContent();

}
function renderSection(
    block,
    blockIndex
) {

    const container =
        document.getElementById(
            "contentBlocks"
        );


    const section =
        document.createElement(
            "div"
        );


    section.className =
        "card mb-4 border-primary";

    let paragraphsHTML = "";


    block.paragraphs.forEach(
        (paragraph, paragraphIndex) => {

            paragraphsHTML += `

                <div class="input-group mb-2">

                    <textarea
                        class="form-control section-paragraph"
                        data-paragraph="${paragraphIndex}"
                        rows="3"
                        placeholder="Section paragraph..."
                    >${escapeHtml(
                        paragraph || ''
                    )}</textarea>


                    <button
                        type="button"
                        class="btn btn-danger"
                        onclick="
                            removeSectionParagraph(
                                ${blockIndex},
                                ${paragraphIndex}
                            )
                        "
                    >

                        ×

                    </button>

                </div>

            `;

        }
    );

    let listHTML = "";

    block.list.forEach(
        (item, listIndex) => {

            listHTML += `

                <div class="card mb-2">

                    <div class="card-body">


                        <div class="mb-2">

                            <label class="form-label">

                                List Item Title

                            </label>


                            <input
                                type="text"
                                class="form-control list-title"
                                data-list="${listIndex}"
                                value="${escapeHtml(
                                    item.title || ''
                                )}"
                                placeholder="Oil Cleanser"
                            >

                        </div>


                        <div class="mb-2">

                            <label class="form-label">

                                List Item Text

                            </label>


                            <textarea
                                class="form-control list-text"
                                data-list="${listIndex}"
                                rows="2"
                                placeholder="Helps dissolve makeup and sunscreen..."
                            >${escapeHtml(
                                item.text || ''
                            )}</textarea>

                        </div>


                        <button
                            type="button"
                            class="btn btn-danger btn-sm"
                            onclick="
                                removeListItem(
                                    ${blockIndex},
                                    ${listIndex}
                                )
                            "
                        >

                            Remove List Item

                        </button>


                    </div>

                </div>

            `;

        }
    );

    section.innerHTML = `

        <div class="card-header
                    bg-primary
                    text-white
                    d-flex
                    justify-content-between
                    align-items-center">

            <strong>

                Section ${getSectionNumber(
                    blockIndex
                )}

            </strong>


            <button
                type="button"
                class="btn btn-light btn-sm"
                onclick="removeBlock(${blockIndex})"
            >

                Remove Section

            </button>

        </div>


        <div class="card-body">


            <!-- Subtitle -->

            <div class="mb-3">

                <label class="form-label">

                    Section Subtitle

                </label>


                <input
                    type="text"
                    class="form-control"
                    id="subtitle_${block.uid}"
                    value="${escapeHtml(
                        block.subtitle || ''
                    )}"
                >

            </div>


            <!-- Image -->

            <div class="mb-4">

                <label class="form-label">

                    Section Image

                </label>


                <input
                    type="file"
                    class="form-control section-image"
                    name="section_images[${block.uid}]"
                    accept=".jpg,.jpeg,.png,.webp,.jfif"
                >


                <small class="text-muted">

                    JPG, JPEG, PNG, WEBP or JFIF

                </small>

            </div>


            <hr>


            <!-- Section Paragraphs -->

            <div class="mb-3">

                <div class="d-flex
                            justify-content-between
                            align-items-center
                            mb-2">

                    <label class="form-label mb-0">

                        Paragraphs

                    </label>


                    <button
                        type="button"
                        class="btn btn-outline-primary btn-sm"
                        onclick="
                            addSectionParagraph(
                                ${blockIndex}
                            )
                        "
                    >

                        + Add Paragraph

                    </button>

                </div>


                <div>

                    ${
                        paragraphsHTML ||
                        '<p class="text-muted">No paragraphs added.</p>'
                    }

                </div>

            </div>


            <hr>


            <!-- Section List -->

            <div>

                <div class="d-flex
                            justify-content-between
                            align-items-center
                            mb-2">

                    <label class="form-label mb-0">

                        List

                    </label>


                    <button
                        type="button"
                        class="btn btn-outline-primary btn-sm"
                        onclick="
                            addListItem(
                                ${blockIndex}
                            )
                        "
                    >

                        + Add List Item

                    </button>

                </div>


                <div>

                    ${
                        listHTML ||
                        '<p class="text-muted">No list items added.</p>'
                    }

                </div>

            </div>


        </div>

    `;


    container.appendChild(section);
    const subtitleInput =
        document.getElementById(
            "subtitle_" +
            block.uid
        );


    subtitleInput.addEventListener(
        "input",
        function () {

            contentBlocks[
                blockIndex
            ].subtitle =
                this.value;

        }
    );

    section
        .querySelectorAll(
            ".section-paragraph"
        )
        .forEach(
            textarea => {

                textarea.addEventListener(
                    "input",
                    function () {

                        const index =
                            parseInt(
                                this.dataset.paragraph
                            );


                        contentBlocks[
                            blockIndex
                        ].paragraphs[index] =
                            this.value;

                    }
                );

            }
        );
    section
        .querySelectorAll(
            ".list-title"
        )
        .forEach(
            input => {

                input.addEventListener(
                    "input",
                    function () {

                        const index =
                            parseInt(
                                this.dataset.list
                            );


                        contentBlocks[
                            blockIndex
                        ].list[index].title =
                            this.value;

                    }
                );

            }
        );

    section
        .querySelectorAll(
            ".list-text"
        )
        .forEach(
            textarea => {

                textarea.addEventListener(
                    "input",
                    function () {

                        const index =
                            parseInt(
                                this.dataset.list
                            );


                        contentBlocks[
                            blockIndex
                        ].list[index].text =
                            this.value;

                    }
                );

            }
        );

}

function addSectionParagraph(
    blockIndex
) {

    contentBlocks[
        blockIndex
    ].paragraphs.push("");


    renderContent();

}


function removeSectionParagraph(
    blockIndex,
    paragraphIndex
) {

    contentBlocks[
        blockIndex
    ].paragraphs.splice(
        paragraphIndex,
        1
    );


    renderContent();

}

function addListItem(
    blockIndex
) {

    contentBlocks[
        blockIndex
    ].list.push({

        title: "",

        text: ""

    });


    renderContent();

}

function removeListItem(
    blockIndex,
    listIndex
) {

    contentBlocks[
        blockIndex
    ].list.splice(
        listIndex,
        1
    );

    renderContent();
}


function removeBlock(
    blockIndex
) {

    contentBlocks.splice(
        blockIndex,
        1
    );


    renderContent();

}

function getSectionNumber(
    blockIndex
) {

    let number = 0;


    for (
        let i = 0;
        i <= blockIndex;
        i++
    ) {

        if (
            contentBlocks[i].type ===
            "section"
        ) {

            number++;

        }

    }


    return number;

}
function escapeHtml(
    text
) {

    return String(text)

        .replace(
            /&/g,
            "&amp;"
        )

        .replace(
            /</g,
            "&lt;"
        )

        .replace(
            />/g,
            "&gt;"
        )

        .replace(
            /"/g,
            "&quot;"
        )

        .replace(
            /'/g,
            "&#039;"
        );

}
document
    .getElementById("blogForm")
    .addEventListener(
        "submit",
        function () {
            document
                .getElementById(
                    "contentInput"
                )
                .value =
                JSON.stringify(
                    contentBlocks
                );
        }
    );
</script>
</body>
</html>

