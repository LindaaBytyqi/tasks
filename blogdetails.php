<?php

include "includes/header.php";
include "includes/database.php";

$blog_id = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);

if (!$blog_id) {

    echo '<script>
        window.location.href = "index.php";
    </script>';

    exit;
}

$sql = "
    SELECT
        id,
        title,
        describe,
        published_at,
        main_image,
        content,
        button_text,
        button_url
    FROM blogs
    WHERE id = :id
      AND status = TRUE
    LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->execute([
    ':id' => $blog_id
]);

$blog = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$blog) {

    echo '<script>
        window.location.href = "index.php";
    </script>';

    exit;
}

$content = json_decode(
    $blog['content'] ?? '[]',
    true
);

if (!is_array($content)) {
    $content = [];
}

function getBlogImagePath($image)
{
    $image = trim((string)$image);

    if ($image === '') {
        return '';
    }

    $image = preg_replace(
        '#^images/#',
        '',
        $image
    );

    $image = preg_replace(
        '#^blog/#',
        '',
        $image
    );

    return 'images/' . $image;
}

function isParagraphHeading($text)
{
    $text = trim((string)$text);

    return preg_match(
        '/^\d+\.\s+.+$/',
        $text
    );
}

function formatListItem($text)
{
    $text = trim((string)$text);

    if ($text === '') {
        return '';
    }

    $colonPosition = strpos($text, ':');

    if ($colonPosition === false) {

        return htmlspecialchars(
            $text,
            ENT_QUOTES,
            'UTF-8'
        );
    }

    $title = trim(
        substr(
            $text,
            0,
            $colonPosition
        )
    );

    $description = trim(
        substr(
            $text,
            $colonPosition + 1
        )
    );

    $html = '';

    if ($title !== '') {

        $html .= '<strong>'
            . htmlspecialchars(
                $title,
                ENT_QUOTES,
                'UTF-8'
            )
            . ':</strong>';
    }

    if ($description !== '') {

        $html .= ' '
            . htmlspecialchars(
                $description,
                ENT_QUOTES,
                'UTF-8'
            );
    }

    return $html;
}

?>

<style>

.blog-page {
    max-width: 1400px !important;
    margin: 80px auto;
    padding: 0 25px;
    font-family: Arial, sans-serif;
}

.blog-category {
    text-align: center;
    color: #e06d88;
    font-size: 25px;
    font-weight: 600;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 15px;
}

.blog-title {
    text-align: center;
    font-size: 42px;
    line-height: 1.2;
    color: #222;
    margin-bottom: 20px;
}

.blog-meta {
    text-align: center;
    color: #777;
    font-size: 15px;
    margin-bottom: 40px;
}

.blog-image {
    width: 100%;
    height: 500px;
    overflow: hidden;
    margin-bottom: 45px;
}

.blog-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.blog-content {
    max-width: 1100px;
    margin: 0 auto;
    color: #444;
    font-size: 17px;
    line-height: 1.8;
}

.blog-paragraph-heading {
    display: block;
    color: #222;
    font-size: 24px;
    font-weight: 700;
    line-height: 1.4;
    margin-top: 35px;
    margin-bottom: 15px;
}

.blog-content p {
    margin-bottom: 22px;
    font-size: 22px;
}

.blog-content ul {
    padding-left: 25px;
    margin-bottom: 25px;
}

.blog-content li {
    margin-bottom: 12px;
    font-size: 18px;
}

.blog-content strong {
    color: #222;
    font-weight: 700;
}

.blog-section-image {
    width: 100%;
    max-height: 450px;
    object-fit: cover;
    margin: 25px 0 30px;
}

.paragraph-list {
    margin-top: 10px;
    margin-bottom: 35px;
}

.paragraph-list p {
    margin-bottom: 15px;
}

.paragraph-list ul {
    margin-top: 10px;
}

.paragraph-list li {
    line-height: 1.7;
}

.back-button {
    display: block;
    width: fit-content;
    margin: 50px auto 20px;
    padding: 12px 28px;
    background: #e06d88;
    color: white;
    text-decoration: none;
    font-size: 15px;
    letter-spacing: 1px;
    transition: 0.3s;
}

.back-button:hover {
    background: #d45c78;
    color: white;
}

.blog-button {
    display: block;
    width: fit-content;
    margin: 40px auto 20px;
    padding: 13px 30px;
    background: #e06d88;
    color: white;
    text-decoration: none;
    font-size: 18px;
    letter-spacing: 1px;
    transition: 0.3s;
}

.blog-button:hover {
    background: #d45c78;
    color: white;
}

@media (max-width: 768px) {

    .blog-page {
        margin: 50px auto;
    }

    .blog-title {
        font-size: 30px;
    }

    .blog-image {
        height: 300px;
    }

    .blog-content {
        font-size: 16px;
    }

    .blog-paragraph-heading {
        font-size: 21px;
    }

    .blog-content p {
        font-size: 18px;
    }

    .blog-content li {
        font-size: 16px;
    }

}

</style>

<section class="blog-page">

    <div class="blog-category">
        SKINCARE
    </div>

    <h1 class="blog-title">

        <?= htmlspecialchars(
            $blog['title'],
            ENT_QUOTES,
            'UTF-8'
        ); ?>

    </h1>

    <div class="blog-meta">

        <?php if (!empty($blog['published_at'])): ?>

            <?= date(
                'M d, Y',
                strtotime($blog['published_at'])
            ); ?>

        <?php endif; ?>

    </div>

    <?php if (!empty($blog['main_image'])): ?>

        <div class="blog-image">

            <img
                src="<?= htmlspecialchars(
                    getBlogImagePath($blog['main_image']),
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>"
                alt="<?= htmlspecialchars(
                    $blog['title'],
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>"
            >

        </div>

    <?php endif; ?>

    <div class="blog-content">

        <?php foreach ($content as $block): ?>

            <?php

            if (!is_array($block)) {
                continue;
            }

            $type = $block['type'] ?? '';

            ?>

            <?php if ($type === 'paragraph'): ?>

    <?php

    $paragraphText = trim(
        (string)($block['text'] ?? '')
    );

    $lines = preg_split(
        "/\r\n|\r|\n/",
        $paragraphText
    );

    ?>

    <?php foreach ($lines as $line): ?>

        <?php

        $line = trim($line);

        if ($line === '') {
            continue;
        }

        ?>

        <?php if (isParagraphHeading($line)): ?>

            <div class="blog-paragraph-heading">
                <?= htmlspecialchars(
                    $line,
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>
            </div>

        <?php else: ?>

            <p>
                <?= htmlspecialchars(
                    $line,
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>
            </p>

        <?php endif; ?>

    <?php endforeach; ?>









            <?php elseif ($type === 'paragraph_list'): ?>

    <div class="paragraph-list">

        <?php

        $paragraphText = trim(
            (string)($block['text'] ?? '')
        );

        $lines = preg_split(
            "/\r\n|\r|\n/",
            $paragraphText
        );

        ?>

        <?php foreach ($lines as $line): ?>

            <?php

            $line = trim($line);

            if ($line === '') {
                continue;
            }

            ?>

            <?php if (isParagraphHeading($line)): ?>

                <div class="blog-paragraph-heading">
                    <?= htmlspecialchars(
                        $line,
                        ENT_QUOTES,
                        'UTF-8'
                    ); ?>
                </div>

            <?php else: ?>

                <p>
                    <?= htmlspecialchars(
                        $line,
                        ENT_QUOTES,
                        'UTF-8'
                    ); ?>
                </p>

            <?php endif; ?>

        <?php endforeach; ?>

        <?php if (
            !empty($block['list']) &&
            is_array($block['list'])
        ): ?>

            <ul>

                <?php foreach (
                    $block['list'] as $item
                ): ?>

                    <?php

                    if (!is_array($item)) {
                        continue;
                    }

                    $itemText = trim(
                        (string)($item['text'] ?? '')
                    );

                    ?>

                    <?php if ($itemText !== ''): ?>

                        <li>
                            <?= formatListItem(
                                $itemText
                            ); ?>
                        </li>

                    <?php endif; ?>

                <?php endforeach; ?>

            </ul>

        <?php endif; ?>

    </div>






            <?php elseif ($type === 'section'): ?>

                <?php if (!empty($block['subtitle'])): ?>

                    <div class="blog-paragraph-heading">

                        <?= htmlspecialchars(
                            $block['subtitle'],
                            ENT_QUOTES,
                            'UTF-8'
                        ); ?>

                    </div>

                <?php endif; ?>

                <?php if (!empty($block['image'])): ?>

                    <img
                        src="<?= htmlspecialchars(
                            getBlogImagePath($block['image']),
                            ENT_QUOTES,
                            'UTF-8'
                        ); ?>"
                        alt="<?= htmlspecialchars(
                            $block['subtitle'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ); ?>"
                        class="blog-section-image"
                    >

                <?php endif; ?>

                <?php if (
                    !empty($block['paragraphs']) &&
                    is_array($block['paragraphs'])
                ): ?>

                    <?php foreach (
                        $block['paragraphs'] as $paragraph
                    ): ?>

                        <?php if (
                            is_string($paragraph) &&
                            trim($paragraph) !== ''
                        ): ?>

                            <p>

                                <?= nl2br(
                                    htmlspecialchars(
                                        $paragraph,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    )
                                ); ?>

                            </p>

                        <?php endif; ?>

                    <?php endforeach; ?>

                <?php endif; ?>

                <?php if (
                    !empty($block['list']) &&
                    is_array($block['list'])
                ): ?>

                    <ul>

                        <?php foreach (
                            $block['list'] as $item
                        ): ?>

                            <?php

                            if (!is_array($item)) {
                                continue;
                            }

                            $itemTitle = trim(
                                (string)($item['title'] ?? '')
                            );

                            $itemText = trim(
                                (string)($item['text'] ?? '')
                            );

                            ?>

                            <?php if (
                                $itemTitle !== '' ||
                                $itemText !== ''
                            ): ?>

                                <li>

                                    <?php if ($itemTitle !== ''): ?>

                                        <strong>

                                            <?= htmlspecialchars(
                                                $itemTitle,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ); ?>:

                                        </strong>

                                    <?php endif; ?>

                                    <?php if ($itemText !== ''): ?>

                                        <?= htmlspecialchars(
                                            $itemText,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>

                                    <?php endif; ?>

                                </li>

                            <?php endif; ?>

                        <?php endforeach; ?>

                    </ul>

                <?php endif; ?>

            <?php endif; ?>

        <?php endforeach; ?>

        <?php if (
            !empty($blog['button_text']) &&
            !empty($blog['button_url'])
        ): ?>

            <a
                href="<?= htmlspecialchars(
                    $blog['button_url'],
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>"
                class="blog-button"
            >

                <?= htmlspecialchars(
                    $blog['button_text'],
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>

            </a>

        <?php endif; ?>

    </div>

</section>


<?php include "includes/footer.php"; ?>