<?php

include "admin_auth.php";
include "../includes/database.php";
include "../includes/csrf.php";

$blog_id = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);

if (!$blog_id) {
    header("Location: admindashboard.php?page=blog");
    exit;
}

$sql = "
    SELECT *
    FROM blogs
    WHERE id = :id
    LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->execute([
    ':id' => $blog_id
]);

$blog = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$blog) {
    header("Location: admindashboard.php?page=blog");
    exit;
}

$content_data = [];

if (!empty($blog['content'])) {

    $decoded_content = json_decode(
        $blog['content'],
        true
    );

    if (is_array($decoded_content)) {
        $content_data = $decoded_content;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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

    $status = $_POST['status'] ?? 'false';

    $posted_content = $_POST['content'] ?? '[]';

    $decoded_posted_content = json_decode(
        $posted_content,
        true
    );

    if (is_array($decoded_posted_content)) {
        $content_data = $decoded_posted_content;
    } else {
        $content_data = [];
    }

    $upload_dir = "../images/";

    if (!is_dir($upload_dir)) {
        mkdir(
            $upload_dir,
            0777,
            true
        );
    }

    $main_image = $blog['main_image'];


    if (
        isset($_FILES['main_image']) &&
        $_FILES['main_image']['error'] === UPLOAD_ERR_OK
    ) {

        $allowed_extensions = [
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

        if (in_array($extension, $allowed_extensions, true)) {

            $new_filename =
                uniqid('blog_', true)
                . '.'
                . $extension;

            $destination =
                $upload_dir
                . $new_filename;


            if (
    move_uploaded_file(
        $_FILES['main_image']['tmp_name'],
        $destination
    )
) {

    if (
        !empty($main_image) &&
        file_exists("../images/" . $main_image)
    ) {

        unlink(
            "../images/" . $main_image
        );
    }

    $main_image = $new_filename;
}
        }
    }

    $section_images =
        $_FILES['section_images'] ?? null;


    if (
        $section_images &&
        isset($section_images['name']) &&
        is_array($section_images['name'])
    ) {

        foreach (
            $section_images['name']
            as $uid => $file_name
        ) {

            if (
                empty($file_name) ||
                !isset($section_images['error'][$uid]) ||
                $section_images['error'][$uid] !== UPLOAD_ERR_OK
            ) {
                continue;
            }


            $extension = strtolower(
                pathinfo(
                    $file_name,
                    PATHINFO_EXTENSION
                )
            );


            $allowed_extensions = [
                'jpg',
                'jpeg',
                'png',
                'webp',
                'jfif'
            ];


            if (
                !in_array(
                    $extension,
                    $allowed_extensions,
                    true
                )
            ) {
                continue;
            }


            $new_filename =
                uniqid('section_', true)
                . '.'
                . $extension;


            $destination =
                $upload_dir
                . $new_filename;


            if (
                move_uploaded_file(
                    $section_images['tmp_name'][$uid],
                    $destination
                )
            ) {

                foreach (
                    $content_data
                    as $index => &$block
                ) {

                    if (
                        ($block['type'] ?? '') === 'section' &&
                        ($block['uid'] ?? '') === $uid
                    ) {

                        if (
    !empty($block['image']) &&
    file_exists("../images/" . $block['image'])
) {

    unlink(
        "../images/" . $block['image']
    );
}


                        $block['image'] = $new_filename;

                        break;
                    }
                }

                unset($block);
            }
        }
    }

    foreach (
        $content_data
        as &$block
    ) {

        if (isset($block['uid'])) {
            unset($block['uid']);
        }
    }

    unset($block);

    $update_sql = "
        UPDATE blogs
        SET
            title = :title,
            describe = :describe,
            published_at = :published_at,
            main_image = :main_image,
            content = :content,
            button_text = :button_text,
            button_url = :button_url,
            status = CAST(:status AS BOOLEAN),
            updated_at = CURRENT_TIMESTAMP
        WHERE id = :id
    ";

    $update_stmt = $conn->prepare($update_sql);

    $update_stmt->execute([
        ':title' => $title,
        ':describe' => $describe,
        ':published_at' => $published_at ?: null,
        ':main_image' => $main_image,
        ':content' => json_encode(
            $content_data,
            JSON_UNESCAPED_UNICODE
        ),
        ':button_text' => $button_text,
        ':button_url' => $button_url,
        ':status' => $status,
        ':id' => $blog_id
    ]);

    echo  
        '<script>
            window.location.href = "admindashboard.php?page=blog";
        </script>';
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>

        body {
            background: #f8f9fa;
        }

        .container {
            max-width: 1000px;
        }

        .card {
            border: none;
            border-radius: 15px;
        }

        .card-header {
            border-radius: 15px 15px 0 0 !important;
        }

        .content-block {
            border: 1px solid #dee2e6;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            background: #fff;
        }

        .block-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .block-title {
            font-weight: 600;
            margin: 0;
        }

        .section-image {
            max-width: 250px;
            max-height: 180px;
            object-fit: cover;
            border-radius: 10px;
            margin-top: 10px;
        }

        .paragraph-item,
        .list-item {
            border: 1px solid #e5e5e5;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 12px;
            background: #fafafa;
        }

        .btn-add {
            margin-right: 8px;
            margin-bottom: 8px;
        }

    </style>

</head>
<body>
<div class="container mt-5 mb-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="bi bi-pencil-square"></i>
                Edit Blog
            </h4>
        </div>

        <div class="card-body p-4">
            <form
                method="POST"
                enctype="multipart/form-data"
                id="blogForm"
            >
                  <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars(
            $_SESSION['csrf_token'] ?? '',
            ENT_QUOTES,
            'UTF-8'
        ) ?>"
    >
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        Title
                    </label>
                    <input
                        type="text"
                        name="title"
                        class="form-control"
                        value="<?= htmlspecialchars(
                            $blog['title'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                        required
                    >

                </div>

                <div class="mb-3">

                    <label class="form-label fw-bold">
                        Short Description
                    </label>

                    <input
                        type="text"
                        name="describe"
                        class="form-control"
                        maxlength="150"
                        value="<?= htmlspecialchars(
                            $blog['describe'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                    >

                </div>

                <div class="mb-3">

                    <label class="form-label fw-bold">
                        Published Date
                    </label>

                    <input
                        type="date"
                        name="published_at"
                        class="form-control"
                        value="<?= htmlspecialchars(
                            $blog['published_at'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                    >

                </div>
                <div class="mb-4">

                    <label class="form-label fw-bold">
                        Main Image
                    </label>

                    <?php if (!empty($blog['main_image'])): ?>

                        <div class="mb-2">

                            <img
                                src="../<?= htmlspecialchars(
                                    $blog['main_image'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                                class="section-image"
                                alt="Main Image"
                            >

                        </div>

                    <?php endif; ?>


                    <input
                        type="file"
                        name="main_image"
                        class="form-control"
                        accept=".jpg,.jpeg,.png,.webp,.jfif"
                    >

                    <small class="text-muted">
                        Leave empty if you want to keep the current image.
                    </small>

                </div>

                <div class="mb-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <h5 class="fw-bold mb-0">
                            Blog Content
                        </h5>

                    </div>

                    <div class="mb-4">

                        <button
                            type="button"
                            class="btn btn-outline-primary btn-add"
                            onclick="addParagraph()"
                        >
                            <i class="bi bi-text-paragraph"></i>
                            Add Paragraph
                        </button>


                        <button
                            type="button"
                            class="btn btn-outline-success btn-add"
                            onclick="addParagraphWithList()"
                        >
                            <i class="bi bi-list-ul"></i>
                            Add Paragraph + List
                        </button>


                        <button
                            type="button"
                            class="btn btn-outline-dark btn-add"
                            onclick="addSection()"
                        >
                            <i class="bi bi-layout-text-window-reverse"></i>
                            Add Section
                        </button>
                    </div>

                    <div id="contentContainer"></div>

                    <input
                        type="hidden"
                        name="content"
                        id="contentInput"
                    >

                </div>

                <div class="mb-3">

                    <label class="form-label fw-bold">
                        Bottom Button Text
                    </label>

                    <input
                        type="text"
                        name="button_text"
                        class="form-control"
                        value="<?= htmlspecialchars(
                            $blog['button_text'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                    >

                </div>

                <div class="mb-3">

                    <label class="form-label fw-bold">
                        Bottom Button URL
                    </label>

                    <input
                        type="text"
                        name="button_url"
                        class="form-control"
                        value="<?= htmlspecialchars(
                            $blog['button_url'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                    >

                </div>

                <div class="form-check form-switch mb-4">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="status"
                        id="status"
                        <?= !empty($blog['status'])
                            ? 'checked'
                            : '' ?>
                    >

                    <label
                        class="form-check-label"
                        for="status"
                    >
                        Active
                    </label>

                </div>

                <div class="d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-check-lg"></i>
                        Update Blog
                    </button>


                    <a
                        href="admindashboard.php?page=blog"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>


<script>

    let contentBlocks = <?= json_encode(
        $content_data,
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    ) ?>;

    function generateUid() {

        return 'section_' +
            Date.now() +
            '_' +
            Math.random()
                .toString(36)
                .substring(2, 9);
    }

    contentBlocks = contentBlocks.map(block => {

        if (block.type === 'section') {

            block.uid = generateUid();

            if (!Array.isArray(block.paragraphs)) {
                block.paragraphs = [];
            }

            if (!Array.isArray(block.list)) {
                block.list = [];
            }
        }


        if (block.type === 'paragraph_list') {

            if (!Array.isArray(block.list)) {
                block.list = [];
            }
        }


        if (block.type === 'paragraph') {

            if (typeof block.text !== 'string') {
                block.text = '';
            }
        }


        return block;

    });
    function addParagraph() {
        contentBlocks.push({
            type: 'paragraph',
            text: ''
        });
        renderContent();
    }

    function addParagraphWithList(paragraph = null) {
        contentBlocks.push(
            paragraph || {
                type: 'paragraph_list',
                text: '',
                list: []
            }
        );
        renderContent();
    }

    function addSection(section = null) {
        contentBlocks.push(
            section || {
                type: 'section',

                uid: generateUid(),

                subtitle: '',

                image: '',

                paragraphs: [],

                list: []
            }
        );
        renderContent();
    }

    function removeBlock(index) {
        contentBlocks.splice(
            index,
            1
        );
        renderContent();
    }

    function updateParagraph(
        index,
        value
    ) {
        contentBlocks[index].text =
            value;
        updateHiddenContent();
    }

    function updateParagraphListText(
        index,
        value
    ) {

        contentBlocks[index].text =
            value;

        updateHiddenContent();
    }

    function addParagraphListItem(index) {

        if (!Array.isArray(
            contentBlocks[index].list
        )) {

            contentBlocks[index].list = [];

        }


        contentBlocks[index].list.push({

            text: ''

        });

        renderContent();
    }

    function updateParagraphListItem(
        blockIndex,
        itemIndex,
        value
    ) {

        contentBlocks[
            blockIndex
        ].list[
            itemIndex
        ].text = value;

        updateHiddenContent();
    }

    function removeParagraphListItem(
        blockIndex,
        itemIndex
    ) {

        contentBlocks[
            blockIndex
        ].list.splice(
            itemIndex,
            1
        );

        renderContent();
    }

    function updateSectionTitle(
        index,
        value
    ) {

        contentBlocks[index].subtitle =
            value;

        updateHiddenContent();
    }

    function addSectionParagraph(index) {

        if (!Array.isArray(
            contentBlocks[index].paragraphs
        )) {

            contentBlocks[index].paragraphs = [];

        }


        contentBlocks[index].paragraphs.push('');

        renderContent();
    }

    function updateSectionParagraph(
        blockIndex,
        paragraphIndex,
        value
    ) {

        contentBlocks[
            blockIndex
        ].paragraphs[
            paragraphIndex
        ] = value;

        updateHiddenContent();
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
    function addListItem(index) {

        if (!Array.isArray(
            contentBlocks[index].list
        )) {
            contentBlocks[index].list = [];
        }


        contentBlocks[index].list.push({

            title: '',
            text: ''

        });

        renderContent();
    }

    function updateListTitle(
        blockIndex,
        itemIndex,
        value
    ) {

        contentBlocks[
            blockIndex
        ].list[
            itemIndex
        ].title = value;

        updateHiddenContent();
    }

    function updateListText(
        blockIndex,
        itemIndex,
        value
    ) {

        contentBlocks[
            blockIndex
        ].list[
            itemIndex
        ].text = value;

        updateHiddenContent();
    }

    function removeListItem(
        blockIndex,
        itemIndex
    ) {

        contentBlocks[
            blockIndex
        ].list.splice(
            itemIndex,
            1
        );

        renderContent();
    }

    function removeSectionImage(index) {

        contentBlocks[index].image = '';

        renderContent();
    }

    function renderContent() {

        const container =
            document.getElementById(
                'contentContainer'
            );


        container.innerHTML = '';


        contentBlocks.forEach(
            (block, index) => {
                if (
                    block.type ===
                    'paragraph'
                ) {

                    container.innerHTML += `

                        <div class="content-block">

                            <div class="block-header">

                                <h6 class="block-title">
                                    <i class="bi bi-text-paragraph"></i>
                                    Paragraph
                                </h6>

                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="removeBlock(${index})"
                                >
                                    <i class="bi bi-trash"></i>
                                </button>

                            </div>


                            <textarea
                                class="form-control"
                                rows="5"
                                placeholder="Write your paragraph..."
                                oninput="updateParagraph(${index}, this.value)"
                            >${escapeHtml(
                                block.text || ''
                            )}</textarea>

                        </div>

                    `;
                }

                if (
                    block.type ===
                    'paragraph_list'
                ) {

                    if (!Array.isArray(block.list)) {
                        block.list = [];
                    }


                    let listHtml = '';


                    block.list.forEach(
                        (item, itemIndex) => {

                            listHtml += `

                                <div class="list-item">

                                    <div class="d-flex justify-content-between mb-2">

                                        <strong>
                                            List Item
                                        </strong>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="removeParagraphListItem(${index}, ${itemIndex})"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    </div>


                                    <textarea
                                        class="form-control"
                                        rows="2"
                                        placeholder="List item..."
                                        oninput="updateParagraphListItem(${index}, ${itemIndex}, this.value)"
                                    >${escapeHtml(
                                        item.text || ''
                                    )}</textarea>

                                </div>

                            `;
                        }
                    );


                    container.innerHTML += `

                        <div class="content-block">

                            <div class="block-header">

                                <h6 class="block-title">

                                    <i class="bi bi-list-ul"></i>

                                    Paragraph + List

                                </h6>


                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="removeBlock(${index})"
                                >
                                    <i class="bi bi-trash"></i>
                                </button>

                            </div>


                            <textarea
                                class="form-control mb-3"
                                rows="5"
                                placeholder="Write your paragraph..."
                                oninput="updateParagraphListText(${index}, this.value)"
                            >${escapeHtml(
                                block.text || ''
                            )}</textarea>


                            <div>

                                <label class="form-label fw-bold">
                                    List
                                </label>

                                ${listHtml}


                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-success"
                                    onclick="addParagraphListItem(${index})"
                                >
                                    <i class="bi bi-plus-lg"></i>
                                    Add List Item
                                </button>

                            </div>

                        </div>

                    `;
                }

                if (
                    block.type ===
                    'section'
                ) {

                    if (!Array.isArray(block.paragraphs)) {
                        block.paragraphs = [];
                    }

                    if (!Array.isArray(block.list)) {
                        block.list = [];
                    }


                    let paragraphsHtml = '';


                    block.paragraphs.forEach(
                        (paragraph, paragraphIndex) => {

                            paragraphsHtml += `

                                <div class="paragraph-item">

                                    <div class="d-flex justify-content-between mb-2">

                                        <strong>
                                            Paragraph
                                        </strong>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="removeSectionParagraph(${index}, ${paragraphIndex})"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    </div>


                                    <textarea
                                        class="form-control"
                                        rows="4"
                                        placeholder="Write paragraph..."
                                        oninput="updateSectionParagraph(${index}, ${paragraphIndex}, this.value)"
                                    >${escapeHtml(
                                        paragraph || ''
                                    )}</textarea>

                                </div>

                            `;

                        }
                    );


                    let listHtml = '';


                    block.list.forEach(
                        (item, itemIndex) => {

                            listHtml += `

                                <div class="list-item">

                                    <div class="d-flex justify-content-between mb-2">

                                        <strong>
                                            List Item
                                        </strong>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="removeListItem(${index}, ${itemIndex})"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    </div>


                                    <input
                                        type="text"
                                        class="form-control mb-2"
                                        placeholder="List title"
                                        value="${escapeAttribute(
                                            item.title || ''
                                        )}"
                                        oninput="updateListTitle(${index}, ${itemIndex}, this.value)"
                                    >


                                    <textarea
                                        class="form-control"
                                        rows="3"
                                        placeholder="List text..."
                                        oninput="updateListText(${index}, ${itemIndex}, this.value)"
                                    >${escapeHtml(
                                        item.text || ''
                                    )}</textarea>

                                </div>

                            `;

                        }
                    );


                    let imageHtml = '';


                    if (block.image) {

                        imageHtml = `

                            <div class="mb-3">

                                <img
                                    src="../${escapeAttribute(block.image)}"
                                    class="section-image d-block"
                                    alt="Section Image"
                                >


                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-danger mt-2"
                                    onclick="removeSectionImage(${index})"
                                >
                                    <i class="bi bi-trash"></i>
                                    Remove Image
                                </button>

                            </div>

                        `;

                    }


                    container.innerHTML += `

                        <div class="content-block">

                            <div class="block-header">

                                <h6 class="block-title">

                                    <i class="bi bi-layout-text-window-reverse"></i>

                                    Section

                                </h6>


                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="removeBlock(${index})"
                                >
                                    <i class="bi bi-trash"></i>
                                </button>

                            </div>


                            <!-- SUBTITLE -->

                            <div class="mb-3">

                                <label class="form-label fw-bold">
                                    Subtitle
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    value="${escapeAttribute(
                                        block.subtitle || ''
                                    )}"
                                    placeholder="Section subtitle"
                                    oninput="updateSectionTitle(${index}, this.value)"
                                >

                            </div>


                            <!-- IMAGE -->

                            <div class="mb-3">

                                <label class="form-label fw-bold">
                                    Section Image
                                </label>


                                ${imageHtml}


                                <input
                                    type="file"
                                    name="section_images[${block.uid}]"
                                    class="form-control"
                                    accept=".jpg,.jpeg,.png,.webp,.jfif"
                                >

                            </div>


                            <!-- PARAGRAPHS -->

                            <div class="mb-3">

                                <label class="form-label fw-bold">
                                    Paragraphs
                                </label>


                                ${paragraphsHtml}


                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-primary"
                                    onclick="addSectionParagraph(${index})"
                                >
                                    <i class="bi bi-plus-lg"></i>
                                    Add Paragraph
                                </button>

                            </div>


                            <!-- LIST -->

                            <div>

                                <label class="form-label fw-bold">
                                    List
                                </label>


                                ${listHtml}


                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-success"
                                    onclick="addListItem(${index})"
                                >
                                    <i class="bi bi-plus-lg"></i>
                                    Add List Item
                                </button>

                            </div>

                        </div>

                    `;

                }

            }
        );


        updateHiddenContent();

    }

    function updateHiddenContent() {

        document.getElementById(
            'contentInput'
        ).value = JSON.stringify(
            contentBlocks
        );

    }

    function escapeHtml(value) {

        return String(value)
            .replace(
                /&/g,
                '&amp;'
            )
            .replace(
                /</g,
                '&lt;'
            )
            .replace(
                />/g,
                '&gt;'
            )
            .replace(
                /"/g,
                '&quot;'
            )
            .replace(
                /'/g,
                '&#039;'
            );

    }
    function escapeAttribute(value) {

        return String(value)
            .replace(
                /&/g,
                '&amp;'
            )
            .replace(
                /"/g,
                '&quot;'
            )
            .replace(
                /'/g,
                '&#039;'
            )
            .replace(
                /</g,
                '&lt;'
            )
            .replace(
                />/g,
                '&gt;'
            );

    }


    /*
    |--------------------------------------------------------------------------
    | FORM SUBMIT
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('blogForm')
        .addEventListener(
            'submit',
            function () {

                updateHiddenContent();

            }
        );

    renderContent();

</script>
</body>
</html>