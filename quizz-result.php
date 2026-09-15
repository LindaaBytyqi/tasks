<?php

include "includes/database.php";

$category = $_POST['category'] ?? '';

$skin_type = $_POST['skin_type'] ?? '';
$skin_concern = $_POST['skin_concern'] ?? '';

$makeup_area = $_POST['makeup_area'] ?? '';
$makeup_look = $_POST['makeup_look'] ?? '';

$hair_type = $_POST['hair_type'] ?? '';
$hair_concern = $_POST['hair_concern'] ?? '';

$body_need = $_POST['body_need'] ?? '';
$body_product = $_POST['body_product'] ?? 'any';

$allowed_categories = [
    'face',
    'makeup',
    'hair',
    'body'
];

if (!in_array($category, $allowed_categories, true)) {

    header("Location: beauty-quiz.php");
    exit;
}

$categoryIds = [

    'makeup' => 7,

    'face' => 8,

    'body' => 10,

    'hair' => 15

];

$skinTypes = [

    'dry' => [
        'dry skin',
        'dry',
        'dehydrated skin',
        'dehydrated'
    ],

    'oily' => [
        'oily skin',
        'oily',
        'excess oil',
        'excess oils'
    ],

    'combination' => [
        'combination skin',
        'combination'
    ],

    'normal' => [
        'normal skin',
        'normal'
    ],

    'sensitive' => [
        'sensitive skin',
        'sensitive',
        'reactive skin',
        'reactive',
        'delicate skin'
    ]

];


$skinConcerns = [

    'hydration' => [
        'hydration',
        'hydrating',
        'hydrate',
        'dehydrated',
        'moisture',
        'moisturize',
        'moisturizing',
        'moisturized'
    ],

    'acne' => [
        'acne',
        'acne-prone',
        'blemish',
        'blemishes',
        'breakout',
        'breakouts'
    ],

    'hyperpigmentation' => [
        'hyperpigmentation',
        'pigmentation',
        'dark spot',
        'dark spots',
        'uneven skin tone',
        'post-acne marks'
    ],

    'dullness' => [
        'dull',
        'dullness',
        'dull skin',
        'radiance',
        'radiant',
        'brightening',
        'brighter',
        'glow'
    ],

    'aging' => [
        'aging',
        'ageing',
        'fine line',
        'fine lines',
        'wrinkle',
        'wrinkles',
        'elasticity',
        'youthful'
    ],

    'barrier' => [
        'skin barrier',
        'barrier support',
        'protective barrier',
        'barrier',
        'ceramides'
    ],

    'redness' => [
        'redness',
        'redness-prone',
        'irritation',
        'irritated',
        'soothing',
        'soothe',
        'calming',
        'calm',
        'reactive'
    ],

    'pores' => [
        'pores',
        'enlarged pores',
        'clogged pores',
        'refined pores'
    ]

];


$skinProductTypes = [

    'cleanser' => [
        'cleanser',
        'face wash',
        'facial wash',
        'wash'
    ],

    'serum' => [
        'serum'
    ],

    'moisturizer' => [
        'moisturizer',
        'moisturiser',
        'facial cream',
        'face cream',
        'moisturizing cream',
        'gel moisturizer',
        'gel cream'
    ],

    'mask' => [
        'mask',
        'wrapping mask'
    ],

    'spf' => [
        'spf',
        'sunscreen',
        'sun protection'
    ]

];



$hairTypes = [

    'dry' => [
        'dry hair',
        'dry'
    ],

    'normal' => [
        'normal hair',
        'normal'
    ],

    'damaged' => [
        'damaged hair',
        'damaged'
    ],

    'fine' => [
        'fine hair',
        'fine'
    ],

    'thick' => [
        'thick hair',
        'thick'
    ]

];


$hairConcerns = [

    'frizz' => [
        'frizz',
        'frizzy',
        'tame frizz'
    ],

    'dryness' => [
        'dryness',
        'dry hair',
        'dehydrated hair'
    ],

    'damage' => [
        'damage',
        'damaged',
        'repair',
        'repairing',
        'strengthen',
        'strengthening'
    ],

    'shine' => [
        'shine',
        'shiny',
        'radiance'
    ],

    'weakness' => [
        'weakness',
        'weak hair',
        'strength'
    ]

];
$bodyConcerns = [

    'hydration' => [
        'hydration',
        'hydrating',
        'moisturize',
        'moisturizing',
        'moisture',
        'dry skin',
        'nourishing'
    ],

    'exfoliation' => [
        'exfoliation',
        'exfoliate',
        'exfoliating',
        'dead skin cells',
        'scrub'
    ],

    'smoothness' => [
        'smoothness',
        'smooth',
        'smoother',
        'soft',
        'softer'
    ],

    'glow' => [
        'glow',
        'glowing',
        'radiant',
        'radiance',
        'bright'
    ]

];

$bodyProductTypes = [

    'scrub' => [
        'scrub',
        'sugar scrub'
    ],

    'butter' => [
        'body butter',
        'butter'
    ],

    'any' => []

];

$makeupAreas = [

    'face' => [
        'powder',
        'foundation',
        'bronzer',
        'blush',
        'complexion'
    ],

    'eyes' => [
        'eyes',
        'eyeshadow',
        'mascara',
        'lashes',
        'lash'
    ],

    'lips' => [
        'lipstick',
        'lip gloss',
        'lip oil',
        'lip glow',
        'lip plumper',
        'lip',
        'lips'
    ],

    'glow' => [
        'highlighter',
        'highlight',
        'glow',
        'radiant'
    ]

];

$makeupLooks = [

    'natural' => [
        'natural',
        'soft',
        'fresh'
    ],

    'everyday' => [
        'everyday',
        'daily',
        'everyday makeup'
    ],

    'soft_glam' => [
        'soft glam',
        'soft-glam',
        'subtle glam'
    ],

    'full_glam' => [
        'full glam',
        'full-glam',
        'dramatic',
        'bold',
        'intense'
    ]

];


function containsKeyword($text, $keywords)
{
    foreach ($keywords as $keyword) {

        $keyword = strtolower(trim($keyword));

        if (
            $keyword !== '' &&
            strpos($text, $keyword) !== false
        ) {

            return true;

        }

    }

    return false;
}


$sql = "
    SELECT *
    FROM products
    WHERE status IN ('1', 'active')
";

$stmt = $conn->prepare($sql);
$stmt->execute();

$productsFromDatabase = $stmt->fetchAll(PDO::FETCH_ASSOC);


$scores = [];


foreach ($productsFromDatabase as $product) {

    $productCategoryId = (int)$product['category_id'];

    $name = strtolower(
        trim($product['name'] ?? '')
    );

    $description = strtolower(
        trim($product['description'] ?? '')
    );


    $text = $name . ' ' . $description;
    $score = 0;


    if (
        $category === 'face' &&
        $productCategoryId === $categoryIds['face']
    ) {

        if ($skin_type === 'not_sure') {


            $score += 1;

        } elseif (
            isset($skinTypes[$skin_type]) &&
            containsKeyword(
                $text,
                $skinTypes[$skin_type]
            )
        ) {

            $score += 4;

        }


        if (
            isset($skinConcerns[$skin_concern]) &&
            containsKeyword(
                $text,
                $skinConcerns[$skin_concern]
            )
        ) {

            $score += 6;

        }

        foreach (
            $skinProductTypes as $productType => $keywords
        ) {

            if (
                containsKeyword(
                    $text,
                    $keywords
                )
            ) {

                $score += 1;

                break;

            }

        }

    }

    if (
        $category === 'makeup' &&
        $productCategoryId === $categoryIds['makeup']
    ) {


        if (
            isset($makeupAreas[$makeup_area]) &&
            containsKeyword(
                $text,
                $makeupAreas[$makeup_area]
            )
        ) {

            $score += 6;

        }

        if (
            isset($makeupLooks[$makeup_look]) &&
            containsKeyword(
                $text,
                $makeupLooks[$makeup_look]
            )
        ) {

            $score += 4;

        }

    }

    if (
        $category === 'hair' &&
        $productCategoryId === $categoryIds['hair']
    ) {


        if ($hair_type === 'not_sure') {

            $score += 1;

        } elseif (
            isset($hairTypes[$hair_type]) &&
            containsKeyword(
                $text,
                $hairTypes[$hair_type]
            )
        ) {

            $score += 4;

        }


        if (
            isset($hairConcerns[$hair_concern]) &&
            containsKeyword(
                $text,
                $hairConcerns[$hair_concern]
            )
        ) {

            $score += 6;

        }

    }


    if (
        $category === 'body' &&
        $productCategoryId === $categoryIds['body']
    ) {



        if (
            isset($bodyConcerns[$body_need]) &&
            containsKeyword(
                $text,
                $bodyConcerns[$body_need]
            )
        ) {

            $score += 6;

        }


        if (
            $body_product !== 'any' &&
            isset($bodyProductTypes[$body_product]) &&
            containsKeyword(
                $text,
                $bodyProductTypes[$body_product]
            )
        ) {

            $score += 4;

        }

    }


    if ($score > 0) {

        $scores[
            (int)$product['id']
        ] = $score;

    }

}

arsort($scores);

$recommended_ids = array_slice(
    array_keys($scores),
    0,
    3
);

$products = [];


foreach ($recommended_ids as $recommendedId) {

    foreach ($productsFromDatabase as $product) {

        if (
            (int)$product['id'] === (int)$recommendedId
        ) {

            $products[] = $product;

            break;

        }

    }

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

    <title>Your Beauty Recommendations</title>


    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >


    <style>

        body {

            font-family: 'Plus Jakarta Sans', sans-serif;

            background: #fafafa;

            color: #222;

        }


        .quiz-results-page {

            padding: 150px 20px 100px;

            min-height: 100vh;

        }


        .results-container {

            max-width: 1200px;

            margin: auto;

        }


        .results-header {

            text-align: center;

            margin-bottom: 60px;

        }


        .results-header span {

            color: #eb3f81;

            font-size: 12px;

            font-weight: 700;

            letter-spacing: 2px;

        }


        .results-header h1 {

            font-size: 42px;

            margin: 15px 0;

        }


        .results-header h1 strong {

            color: #eb3f81;

        }


        .results-header p {

            color: #777;

            max-width: 600px;

            margin: auto;

            line-height: 1.7;

        }


        .results-grid {

            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 25px;

        }


        .result-card {

            background: #fff;

            border-radius: 22px;

            overflow: hidden;

            border: 1px solid #eee;

            transition: .25s ease;

        }


        .result-card:hover {

            transform: translateY(-5px);

            box-shadow:
                0 15px 40px rgba(0,0,0,.07);

        }


        .result-image {

            height: 300px;

            background: #f7f7f7;

            display: flex;

            align-items: center;

            justify-content: center;

            position: relative;

        }


        .result-image img {

            width: 100%;

            height: 100%;

            object-fit: contain;

            padding: 25px;

        }


        .result-content {

            padding: 25px;

        }


        .result-content h3 {

            font-size: 17px;

            margin-bottom: 12px;

        }


        .result-price {

            font-size: 16px;

            font-weight: 700;

            margin-bottom: 20px;

        }


        .old-price {

            color: #aaa;

            text-decoration: line-through;

            font-size: 13px;

            margin-right: 7px;

        }


        .sale-price {

            color: #eb3f81;

        }


        .result-button {

            display: inline-flex;

            align-items: center;

            gap: 8px;

            background: #eb3f81;

            color: white;

            text-decoration: none;

            padding: 11px 20px;

            border-radius: 50px;

            font-size: 11px;

            font-weight: 700;

            letter-spacing: .5px;

            transition: .2s ease;

        }


        .result-button:hover {

            background: #d92f70;

            color: white;

        }


        .no-results {

            text-align: center;

            background: white;

            padding: 60px 30px;

            border-radius: 25px;

        }


        .no-results i {

            font-size: 45px;

            color: #eb3f81;

        }


        .retake-button {

            display: inline-block;

            margin-top: 20px;

            background: #eb3f81;

            color: white;

            text-decoration: none;

            padding: 13px 25px;

            border-radius: 50px;

            font-size: 12px;

            font-weight: 700;

        }


        @media (max-width: 768px) {

            .results-grid {

                grid-template-columns: 1fr;

            }


            .results-header h1 {

                font-size: 32px;

            }

        }

    </style>

</head>


<body>


<div class="quiz-results-page">

    <div class="results-container">

        <div class="results-header">

            <span>YOUR PERSONALIZED PICKS</span>

            <h1>
                Products <strong>Picked For You</strong>
            </h1>

            <p>
                Based on your answers, we've selected
                products that best match your needs.
            </p>

        </div>



        <?php if (!empty($products)): ?>


            <div class="results-grid">


                <?php foreach ($products as $product): ?>


                    <?php

                    $price = (float)$product['price'];

                    $sale_price = $product['sale_price'];

                    $has_sale =
                        $sale_price !== null &&
                        (float)$sale_price < $price;

                    ?>


                    <div class="result-card">


                        <div class="result-image">

                            <img
                                src="images/<?php echo htmlspecialchars($product['image']); ?>"
                                alt="<?php echo htmlspecialchars($product['name']); ?>"
                            >

                        </div>


                        <div class="result-content">


                            <h3>
                                <?php
                                echo htmlspecialchars(
                                    $product['name']
                                );
                                ?>
                            </h3>


                            <div class="result-price">

                                <?php if ($has_sale): ?>

                                    <span class="old-price">
                                        €<?php echo number_format($price, 2); ?>
                                    </span>

                                    <span class="sale-price">
                                        €<?php
                                        echo number_format(
                                            (float)$sale_price,
                                            2
                                        );
                                        ?>
                                    </span>

                                <?php else: ?>

                                    €<?php
                                    echo number_format(
                                        $price,
                                        2
                                    );
                                    ?>

                                <?php endif; ?>

                            </div>


                            <a
                                href="productdetails.php?id=<?php echo (int)$product['id']; ?>"
                                class="result-button"
                            >

                                VIEW PRODUCT

                                <i class="bi bi-arrow-right"></i>

                            </a>


                        </div>

                    </div>


                <?php endforeach; ?>


            </div>


        <?php else: ?>


            <div class="no-results">

                <i class="bi bi-stars"></i>

                <h2 class="mt-3">
                    We couldn't find a perfect match.
                </h2>

                <p class="text-muted">
                    Try taking the quiz again with different answers.
                </p>

                <a
                    href="beauty-quiz.php"
                    class="retake-button"
                >
                </a>

            </div>


        <?php endif; ?>


    </div>

</div>


</body>

</html>