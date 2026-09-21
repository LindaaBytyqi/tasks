<?php

include "includes/header.php";
include "includes/database.php";

$sql = "
    SELECT
        id,
        title,
        published_at,
        main_image
    FROM blogs
    WHERE status = TRUE
    ORDER BY published_at ASC
";

$stmt = $conn->prepare($sql);
$stmt->execute();

$blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<style>

.blogs-page {
    max-width: 1600px;
    margin: 192px auto 150px;
    padding: 0 40px;
}

.blogs-header {
    text-align: center;
    margin-bottom: 55px;
}
.blogs-subtitle {
    display: block;
    color: #e06d88;
    font-size: 15px;
    font-weight: 600;
    letter-spacing: 3px;
    text-transform: uppercase;
    margin-bottom: 12px;
}

.blogs-title {
    margin: 0;
    color: #222;
    font-size: 38px;
    font-weight: 600;
    letter-spacing: 1px;
}

.blogs-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 35px;
}

.blog-card {
    min-height: 500px;
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    cursor: pointer;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.07);
    transition:
        transform 0.3s ease,
        box-shadow 0.3s ease;
}

.blog-card:hover {
    transform: translateY(-7px);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
}
.blog-card-image {
    width: 100%;
    height: 400px;
    overflow: hidden;
    background: #f5f5f5;
}

.blog-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.5s ease;
}

.blog-card:hover .blog-card-image img {
    transform: scale(1.04);
}
.blog-card-body {
    padding: 25px 27px 28px;
}
.blog-card-category {
    color: #e06d88;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 12px;
}

.blog-card-title {
    margin: 0 0 20px;
    color: #222;
    font-size: 21px;
    font-weight: 600;
    line-height: 1.4;
}

.blog-card-date {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #888;
    font-size: 15px;
}

.blog-card-date i {
    font-size: 17px;
    color: #e06d88;
}

.no-blogs {
    text-align: center;
    padding: 70px 20px;
    color: #777;
    font-size: 17px;
}


@media (max-width: 1000px) {

    .blogs-page {
        margin-top: 160px;
        padding: 0 25px;
    }
    .blogs-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 25px;
    }

}

@media (max-width: 650px) {

    .blogs-page {
        margin-top: 130px;
        margin-bottom: 70px;
        padding: 0 18px;
    }

    .blogs-header {
        margin-bottom: 35px;
    }

    .blogs-title {
        font-size: 28px;
    }

    .blogs-subtitle {
        font-size: 11px;
        letter-spacing: 2px;
    }

    .blogs-grid {
        grid-template-columns: 1fr;
        gap: 25px;
    }

    .blog-card-image {
        height: 260px;
    }

    .blog-card-body {
        padding: 22px;
    }

    .blog-card-title {
        font-size: 19px;
    }

}

</style>

<section class="blogs-page">

    <div class="blogs-header">

        <span class="blogs-subtitle">
            BEAUTY &amp; SKINCARE
        </span>

        <h1 class="blogs-title">
            EXPERT TIPS AND INSPIRATION
        </h1>

    </div>


    <?php if (!empty($blogs)): ?>

        <div class="blogs-grid">

            <?php foreach ($blogs as $blog): ?>

                <article
                    class="blog-card"
                    onclick="window.location.href='blogdetails.php?id=<?= (int)$blog['id']; ?>'"
                >

                    <div class="blog-card-image">

                        <?php if (!empty($blog['main_image'])): ?>

                            <img
                                src="images/<?= htmlspecialchars(
                                    $blog['main_image'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"
                                alt="<?= htmlspecialchars(
                                    $blog['title'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"
                            >

                        <?php else: ?>

                            <div
                                style="
                                    width:100%;
                                    height:100%;
                                    display:flex;
                                    align-items:center;
                                    justify-content:center;
                                    color:#999;
                                    font-size:14px;
                                "
                            >
                                No Image
                            </div>

                        <?php endif; ?>

                    </div>


                    <div class="blog-card-body">

                        <div class="blog-card-category">
                            CARE
                        </div>

                        <h2 class="blog-card-title">

                            <?= htmlspecialchars(
                                $blog['title'],
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>

                        </h2>


                        <div class="blog-card-date">
                            <i class="far fa-clock"></i>

                            <?php if (!empty($blog['published_at'])): ?>

                                <?= date(
                                    'M d, Y',
                                    strtotime($blog['published_at'])
                                ); ?>

                            <?php endif; ?>
                        </div>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>

    <?php else: ?>

        <div class="no-blogs">
            No blog posts available at the moment.
        </div>

    <?php endif; ?>

</section>


<?php include "includes/footer.php"; ?>

