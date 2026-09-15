<?php
include "includes/header.php";
?>

<style>
.beauty-quiz-page {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: #fafafa;
    min-height: 100vh;
    padding: 160px 20px 100px;
}

.quiz-container {
    max-width: 900px;
    margin: 0 auto;
}

.quiz-header {
    text-align: center;
    margin-bottom: 45px;
}

.quiz-label {
    display: inline-block;
    color: #eb3f81;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 2px;
    margin-bottom: 15px;
}

.quiz-header h1 {
    font-size: 42px;
    font-weight: 700;
    margin-bottom: 15px;
    color: #222;
}

.quiz-header h1 span {
    color: #eb3f81;
}

.quiz-header p {
    color: #777;
    font-size: 16px;
    max-width: 600px;
    margin: auto;
    line-height: 1.7;
}

/* Progress */

.quiz-progress {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 45px;
}

.progress-step {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: #eee;
    color: #999;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 700;
    transition: .3s;
}

.progress-step.active {
    background: #eb3f81;
    color: #fff;
}

.progress-line {
    width: 70px;
    height: 2px;
    background: #eee;
}

.progress-line.active {
    background: #eb3f81;
}

/* Quiz box */

.quiz-box {
    background: #fff;
    border-radius: 25px;
    padding: 45px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, .05);
}

.quiz-step {
    display: none;
}

.quiz-step.active {
    display: block;
}

.quiz-step-title {
    text-align: center;
    margin-bottom: 35px;
}

.quiz-step-title h2 {
    font-size: 25px;
    font-weight: 700;
    color: #222;
    margin-bottom: 8px;
}

.quiz-step-title p {
    color: #888;
    font-size: 14px;
}

/* Options */

.quiz-options {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.quiz-option {
    position: relative;
}

.quiz-option input {
    position: absolute;
    opacity: 0;
}

.quiz-option label {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 70px;
    border: 1px solid #e7e7e7;
    border-radius: 14px;
    padding: 15px 20px;
    text-align: center;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    color: #444;
    transition: .3s;
    background: #fff;
}

.quiz-option label:hover {
    border-color: #eb3f81;
    color: #eb3f81;
}

.quiz-option input:checked + label {
    border-color: #eb3f81;
    background: #fff5f9;
    color: #eb3f81;
}

/* Buttons */

.quiz-buttons {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 35px;
}

.quiz-back,
.quiz-next,
.quiz-submit {
    border: none;
    border-radius: 30px;
    padding: 13px 27px;
    font-family: inherit;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: .3s;
}

.quiz-back {
    background: #f1f1f1;
    color: #555;
}

.quiz-back:hover {
    background: #e7e7e7;
}

.quiz-next,
.quiz-submit {
    background: #eb3f81;
    color: #fff;
}

.quiz-next:hover,
.quiz-submit:hover {
    background: #d92f70;
    transform: translateY(-1px);
}

.quiz-submit {
    margin-left: auto;
}


.category-option label {
    min-height: 130px;
    flex-direction: column;
    gap: 10px;
}

.category-option i {
    font-size: 28px;
    color: #eb3f81;
}

.category-option span {
    font-size: 14px;
}

.category-questions {
    display: none;
}
.category-questions.active {
    display: block;
}

@media (max-width: 768px) {

    .beauty-quiz-page {
        padding: 130px 15px 70px;
    }

    .quiz-header h1 {
        font-size: 32px;
    }

    .quiz-box {
        padding: 28px 20px;
    }

    .quiz-options {
        grid-template-columns: 1fr;
    }

    .progress-line {
        width: 40px;
    }

    .category-option label {
        min-height: 100px;
    }
}
</style>


<div class="beauty-quiz-page">

    <div class="quiz-container">

        <div class="quiz-header">

            <span class="quiz-label">
                FIND YOUR BEAUTY MATCH
            </span>

            <h1>
                Discover your <span>perfect routine.</span>
            </h1>

            <p>
                Answer a few simple questions and we'll help you
                discover products that fit your beauty needs.
            </p>

        </div>


        <!-- PROGRESS -->

        <div class="quiz-progress">

            <div class="progress-step active" data-step="1">
                1
            </div>

            <div class="progress-line"></div>

            <div class="progress-step" data-step="2">
                2
            </div>

            <div class="progress-line"></div>

            <div class="progress-step" data-step="3">
                3
            </div>

        </div>


        <form action="quizz-result.php" method="POST" id="beautyQuiz">


            <div class="quiz-box">

                <div class="quiz-step active" data-step="1">

                    <div class="quiz-step-title">

                        <h2>
                            What are you shopping for?
                        </h2>

                        <p>
                            Choose the category you'd like help with.
                        </p>

                    </div>


                    <div class="quiz-options">

                        <div class="quiz-option category-option">

                            <input
                                type="radio"
                                name="category"
                                id="category-face"
                                value="face"
                            >

                            <label for="category-face">

                                <i class="bi bi-droplet"></i>

                                <span>
                                    Face / Skincare
                                </span>

                            </label>

                        </div>


                        <div class="quiz-option category-option">

                            <input
                                type="radio"
                                name="category"
                                id="category-makeup"
                                value="makeup"
                            >

                            <label for="category-makeup">

                                <i class="bi bi-palette"></i>

                                <span>
                                    Makeup
                                </span>

                            </label>

                        </div>


                        <div class="quiz-option category-option">

                            <input
                                type="radio"
                                name="category"
                                id="category-hair"
                                value="hair"
                            >

                            <label for="category-hair">

                                <i class="bi bi-scissors"></i>

                                <span>
                                    Hair Care
                                </span>

                            </label>

                        </div>


                        <div class="quiz-option category-option">

                            <input
                                type="radio"
                                name="category"
                                id="category-body"
                                value="body"
                            >

                            <label for="category-body">

                                <i class="bi bi-heart"></i>

                                <span>
                                    Body Care
                                </span>

                            </label>

                        </div>

                    </div>


                    <div class="quiz-buttons">

                        <span></span>

                        <button
                            type="button"
                            class="quiz-next"
                        >
                            NEXT
                            <i class="bi bi-arrow-right"></i>
                        </button>

                    </div>

                </div>

                <div
                    class="category-questions"
                    id="faceQuestions"
                >

                    <div
                        class="quiz-step"
                        data-step="2"
                    >

                        <div class="quiz-step-title">

                            <h2>
                                What's your skin type?
                            </h2>

                            <p>
                                Choose the option that describes your skin best.
                            </p>

                        </div>


                        <div class="quiz-options">

                            <div class="quiz-option">
                                <input type="radio" name="skin_type" id="dry" value="dry">
                                <label for="dry">Dry</label>
                            </div>

                            <div class="quiz-option">
                                <input type="radio" name="skin_type" id="oily" value="oily">
                                <label for="oily">Oily</label>
                            </div>

                            <div class="quiz-option">
                                <input type="radio" name="skin_type" id="combination" value="combination">
                                <label for="combination">Combination</label>
                            </div>

                            <div class="quiz-option">
                                <input type="radio" name="skin_type" id="normal" value="normal">
                                <label for="normal">Normal</label>
                            </div>

                            <div class="quiz-option">
                                <input type="radio" name="skin_type" id="sensitive" value="sensitive">
                                <label for="sensitive">Sensitive</label>
                            </div>

                            <div class="quiz-option">
                                <input type="radio" name="skin_type" id="skin-not-sure" value="not_sure">
                                <label for="skin-not-sure">Not Sure</label>
                            </div>

                        </div>


                        <div class="quiz-buttons">

                            <button
                                type="button"
                                class="quiz-back"
                            >
                                <i class="bi bi-arrow-left"></i>
                                BACK
                            </button>

                            <button
                                type="button"
                                class="quiz-next"
                            >
                                NEXT
                                <i class="bi bi-arrow-right"></i>
                            </button>

                        </div>

                    </div>



                    <div
                        class="quiz-step"
                        data-step="3"
                    >

                        <div class="quiz-step-title">

                            <h2>
                                What would you like to improve?
                            </h2>

                            <p>
                                Tell us what your skin needs most.
                            </p>

                        </div>


                        <div class="quiz-options">

                            <div class="quiz-option">
                                <input type="radio" name="skin_concern" id="hydration" value="hydration">
                                <label for="hydration">Hydration</label>
                            </div>

                            <div class="quiz-option">
                                <input type="radio" name="skin_concern" id="acne" value="acne">
                                <label for="acne">Acne & Breakouts</label>
                            </div>

                            <div class="quiz-option">
                                <input type="radio" name="skin_concern" id="hyperpigmentation" value="hyperpigmentation">
                                <label for="hyperpigmentation">Hyperpigmentation</label>
                            </div>

                            <div class="quiz-option">
                                <input type="radio" name="skin_concern" id="dullness" value="dullness">
                                <label for="dullness">Dullness & Glow</label>
                            </div>

                            <div class="quiz-option">
                                <input type="radio" name="skin_concern" id="aging" value="aging">
                                <label for="aging">Fine Lines & Aging</label>
                            </div>

                            <div class="quiz-option">
                                <input type="radio" name="skin_concern" id="barrier" value="barrier">
                                <label for="barrier">Skin Barrier</label>
                            </div>

                        </div>


                        <div class="quiz-buttons">

                            <button
                                type="button"
                                class="quiz-back"
                            >
                                <i class="bi bi-arrow-left"></i>
                                BACK
                            </button>

                            <button
                                type="submit"
                                class="quiz-submit"
                            >
                                FIND MY PRODUCTS
                                <i class="bi bi-stars"></i>
                            </button>

                        </div>

                    </div>

                </div>


                <div
                    class="category-questions"
                    id="makeupQuestions"
                >

                    <div
                        class="quiz-step"
                        data-step="2"
                    >

                        <div class="quiz-step-title">

                            <h2>
                                What are you looking for?
                            </h2>

                            <p>
                                Choose the type of makeup you'd like to discover.
                            </p>

                        </div>


                        <div class="quiz-options">

                            <div class="quiz-option">
                                <input type="radio" name="makeup_area" id="makeup-face" value="face">
                                <label for="makeup-face">Face</label>
                            </div>

                            <div class="quiz-option">
                                <input type="radio" name="makeup_area" id="makeup-eyes" value="eyes">
                                <label for="makeup-eyes">Eyes</label>
                            </div>

                            <div class="quiz-option">
                                <input type="radio" name="makeup_area" id="makeup-lips" value="lips">
                                <label for="makeup-lips">Lips</label>
                            </div>

                            <div class="quiz-option">
                                <input type="radio" name="makeup_area" id="makeup-glow" value="glow">
                                <label for="makeup-glow">Glow</label>
                            </div>

                        </div>


                        <div class="quiz-buttons">

                            <button type="button" class="quiz-back">
                                <i class="bi bi-arrow-left"></i>
                                BACK
                            </button>

                            <button type="button" class="quiz-next">
                                NEXT
                                <i class="bi bi-arrow-right"></i>
                            </button>

                        </div>

                    </div>


                    <div
                        class="quiz-step"
                        data-step="3"
                    >

                        <div class="quiz-step-title">

                            <h2>
                                What kind of look do you prefer?
                            </h2>

                            <p>
                                Choose the style that fits you best.
                            </p>

                        </div>


                        <div class="quiz-options">

                            <div class="quiz-option">
                                <input type="radio" name="makeup_look" id="natural" value="natural">
                                <label for="natural">Natural</label>
                            </div>

                            <div class="quiz-option">
                                <input type="radio" name="makeup_look" id="soft-glam" value="soft_glam">
                                <label for="soft-glam">Soft Glam</label>
                            </div>

                            <div class="quiz-option">
                                <input type="radio" name="makeup_look" id="full-glam" value="full_glam">
                                <label for="full-glam">Full Glam</label>
                            </div>

                            <div class="quiz-option">
                                <input type="radio" name="makeup_look" id="everyday" value="everyday">
                                <label for="everyday">Everyday</label>
                            </div>

                        </div>


                        <div class="quiz-buttons">

                            <button type="button" class="quiz-back">
                                <i class="bi bi-arrow-left"></i>
                                BACK
                            </button>

                            <button type="submit" class="quiz-submit">
                                FIND MY PRODUCTS
                                <i class="bi bi-stars"></i>
                            </button>

                        </div>

                    </div>

                </div>

                <div
                    class="category-questions"
                    id="hairQuestions"
                >

                    <div
                        class="quiz-step"
                        data-step="2"
                    >

                        <div class="quiz-step-title">

                            <h2>
                                What's your hair type?
                            </h2>

                            <p>
                                Tell us a little about your hair.
                            </p>

                        </div>


                        <div class="quiz-options">

                            <div class="quiz-option">
                                <input type="radio" name="hair_type" id="hair-dry" value="dry">
                                <label for="hair-dry">Dry</label>
                            </div>

                            <div class="quiz-option">
                                <input type="radio" name="hair_type" id="hair-normal" value="normal">
                                <label for="hair-normal">Normal</label>
                            </div>

                            <div class="quiz-option">
                                <input type="radio" name="hair_type" id="hair-damaged" value="damaged">
                                <label for="hair-damaged">Damaged</label>
                            </div>

                            <div class="quiz-option">
                                <input type="radio" name="hair_type" id="hair-fine" value="fine">
                                <label for="hair-fine">Fine</label>
                            </div>

                            <div class="quiz-option">
                                <input type="radio" name="hair_type" id="hair-thick" value="thick">
                                <label for="hair-thick">Thick</label>
                            </div>

                            <div class="quiz-option">
                                <input type="radio" name="hair_type" id="hair-not-sure" value="not_sure">
                                <label for="hair-not-sure">Not Sure</label>
                            </div>

                        </div>


                        <div class="quiz-buttons">

                            <button type="button" class="quiz-back">
                                <i class="bi bi-arrow-left"></i>
                                BACK
                            </button>

                            <button type="button" class="quiz-next">
                                NEXT
                                <i class="bi bi-arrow-right"></i>
                            </button>

                        </div>

                    </div>


                    <div
                        class="quiz-step"
                        data-step="3"
                    >

                        <div class="quiz-step-title">

                            <h2>
                                What's your main hair concern?
                            </h2>

                            <p>
                                Choose what you'd like to improve.
                            </p>

                        </div>


                        <div class="quiz-options">

                            <div class="quiz-option">
                                <input type="radio" name="hair_concern" id="frizz" value="frizz">
                                <label for="frizz">Frizz</label>
                            </div>

                            <div class="quiz-option">
                                <input type="radio" name="hair_concern" id="hair-dryness" value="dryness">
                                <label for="hair-dryness">Dryness</label>
                            </div>

                            <div class="quiz-option">
                                <input type="radio" name="hair_concern" id="hair-damage" value="damage">
                                <label for="hair-damage">Damage</label>
                            </div>

                            <div class="quiz-option">
                                <input type="radio" name="hair_concern" id="hair-shine" value="shine">
                                <label for="hair-shine">Lack of Shine</label>
                            </div>

                            <div class="quiz-option">
                                <input type="radio" name="hair_concern" id="weak-hair" value="weakness">
                                <label for="weak-hair">Weak Hair</label>
                            </div>

                        </div>


                        <div class="quiz-buttons">

                            <button type="button" class="quiz-back">
                                <i class="bi bi-arrow-left"></i>
                                BACK
                            </button>

                            <button type="submit" class="quiz-submit">
                                FIND MY PRODUCTS
                                <i class="bi bi-stars"></i>
                            </button>

                        </div>

                    </div>

                </div>


                <div
                    class="category-questions"
                    id="bodyQuestions"
                >

                    <div
                        class="quiz-step"
                        data-step="2"
                    >

                        <div class="quiz-step-title">

                            <h2>
                                What does your body skin need?
                            </h2>

                            <p>
                                Choose what you'd like to focus on.
                            </p>

                        </div>


                        <div class="quiz-options">

                            <div class="quiz-option">
                                <input type="radio" name="body_need" id="body-hydration" value="hydration">
                                <label for="body-hydration">Hydration</label>
                            </div>

                            <div class="quiz-option">
                                <input type="radio" name="body_need" id="body-exfoliation" value="exfoliation">
                                <label for="body-exfoliation">Exfoliation</label>
                            </div>

                            <div class="quiz-option">
                                <input type="radio" name="body_need" id="body-smoothness" value="smoothness">
                                <label for="body-smoothness">Smoothness</label>
                            </div>

                            <div class="quiz-option">
                                <input type="radio" name="body_need" id="body-glow" value="glow">
                                <label for="body-glow">Glow</label>
                            </div>

                        </div>


                        <div class="quiz-buttons">

                            <button type="button" class="quiz-back">
                                <i class="bi bi-arrow-left"></i>
                                BACK
                            </button>

                            <button type="button" class="quiz-next">
                                NEXT
                                <i class="bi bi-arrow-right"></i>
                            </button>

                        </div>

                    </div>


                    <div
                        class="quiz-step"
                        data-step="3"
                    >

                        <div class="quiz-step-title">

                            <h2>
                                What kind of product do you want?
                            </h2>

                            <p>
                                Choose your preferred body-care product.
                            </p>

                        </div>


                        <div class="quiz-options">

                            <div class="quiz-option">
                                <input type="radio" name="body_product" id="body-scrub" value="scrub">
                                <label for="body-scrub">Body Scrub</label>
                            </div>

                            <div class="quiz-option">
                                <input type="radio" name="body_product" id="body-butter" value="butter">
                                <label for="body-butter">Body Butter</label>
                            </div>

                            <div class="quiz-option">
                                <input type="radio" name="body_product" id="body-any" value="any">
                                <label for="body-any">Anything</label>
                            </div>

                        </div>


                        <div class="quiz-buttons">

                            <button type="button" class="quiz-back">
                                <i class="bi bi-arrow-left"></i>
                                BACK
                            </button>

                            <button type="submit" class="quiz-submit">
                                FIND MY PRODUCTS
                                <i class="bi bi-stars"></i>
                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>


<script>

const form = document.getElementById("beautyQuiz");

const categoryInputs = document.querySelectorAll(
    'input[name="category"]'
);

const categorySections = document.querySelectorAll(
    ".category-questions"
);

const progressSteps = document.querySelectorAll(
    ".progress-step"
);

let selectedCategory = "";
let currentStep = 1;



categoryInputs.forEach(input => {

    input.addEventListener("change", function() {

        selectedCategory = this.value;

        categorySections.forEach(section => {
            section.classList.remove("active");
        });

        const selectedSection =
            document.getElementById(
                this.value + "Questions"
            );

        if (selectedSection) {
            selectedSection.classList.add("active");
        }

    });

});


function showStep(step) {

    document
        .querySelectorAll(".quiz-step")
        .forEach(stepElement => {

            stepElement.classList.remove("active");

        });


    const activeSection =
        document.querySelector(
            ".category-questions.active"
        );


    if (step === 1) {

        document
            .querySelector(
                '.quiz-step[data-step="1"]'
            )
            .classList.add("active");

    } else if (activeSection) {

        const target =
            activeSection.querySelector(
                `.quiz-step[data-step="${step}"]`
            );

        if (target) {
            target.classList.add("active");
        }

    }


    progressSteps.forEach(progress => {

        const number =
            Number(progress.dataset.step);

        progress.classList.toggle(
            "active",
            number <= step
        );

    });


    currentStep = step;

}


document
    .querySelectorAll(".quiz-next")
    .forEach(button => {

        button.addEventListener("click", function() {

            if (currentStep === 1) {

                const category =
                    document.querySelector(
                        'input[name="category"]:checked'
                    );

                if (!category) {

                    alert(
                        "Please select a category."
                    );

                    return;
                }

                selectedCategory =
                    category.value;

                showStep(2);

                return;
            }

            const activeSection =
                document.querySelector(
                    ".category-questions.active"
                );

            if (!activeSection) {
                return;
            }


            const current =
                activeSection.querySelector(
                    `.quiz-step[data-step="${currentStep}"]`
                );


            if (!current) {
                return;
            }


            const selected =
                current.querySelector(
                    'input[type="radio"]:checked'
                );


            if (!selected) {

                alert(
                    "Please select an option."
                );

                return;
            }


            if (currentStep < 3) {
                showStep(currentStep + 1);
            }

        });

    });


document
    .querySelectorAll(".quiz-back")
    .forEach(button => {

        button.addEventListener("click", function() {

            if (currentStep === 2) {

                showStep(1);

                return;
            }


            if (currentStep > 1) {

                showStep(
                    currentStep - 1
                );

            }

        });

    });

form.addEventListener("submit", function(event) {

    if (!selectedCategory) {

        event.preventDefault();

        alert(
            "Please select a category."
        );

        return;
    }


    const activeSection =
        document.querySelector(
            ".category-questions.active"
        );


    if (!activeSection) {

        event.preventDefault();

        return;
    }


    const current =
        activeSection.querySelector(
            `.quiz-step[data-step="${currentStep}"]`
        );


    if (current) {

        const selected =
            current.querySelector(
                'input[type="radio"]:checked'
            );

        if (!selected) {

            event.preventDefault();

            alert(
                "Please select an option."
            );

        }

    }

});

</script>

<?php
include "includes/footer.php";
?>