<!DOCTYPE html>
<html lang="@{locale|en}">
<head>
    <title>${title}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font: 400 16px 'Inter', sans-serif;
            color: #3C4964;
        }

        .wrapper {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px 0;
        }

        .wrapper::before,
        .wrapper::after,
        .bg::before,
        .bg::after {
            position: absolute;
            left: 0;
            top: 0;
            content: '';
            width: 100%;
            height: 100%;
        }

        .wrapper::before {
            background: linear-gradient(21.09deg, #FFFFFF 2.18%, #E2E2E2 62.87%);
            clip-path: polygon(0 0, 60% 0, 19% 100%, 0 100%);
        }

        .wrapper::after {
            background: linear-gradient(29deg, #FFFFFF 17.28%, #EBEBEB 58.61%);
            clip-path: polygon(60% 0%, 100% 0, 100% 53%, 42% 100%, 19% 100%);
        }

        .bg {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .bg::before {
            background: linear-gradient(293deg, #FFFFFF 31.21%, #BABABA 63.74%);
            clip-path: polygon(66.7% 80%, 63% 100%, 42% 100%);
        }

        .bg::after {
            background: linear-gradient(130.09deg, #FFFFFF -66.33%, #EAEAEA 44.08%);
            clip-path: polygon(66.7% 80%, 85% 100%, 64% 100%);
        }

        .container {
            position: relative;
            z-index: 1;
            padding: 0 15px;
            width: 100%;
            max-width: 949px;
        }

        .error-code-text {
            margin: 0 auto 38px;
            max-width: 635px;
            font-weight: 600;
            font-size: 200px;
            text-align: center;
            color: #3F87D2;
        }

        .main-title {
            margin: 0 auto 38px;
            max-width: 635px;
            font-weight: 900;
            font-size: 36px;
            line-height: 150%;
            text-align: center;
        }

        .main-title span {
            font-weight: 700;
            color: #3F87D2;
        }

        .main-description {
            margin: 0 auto 47px;
            max-width: 803px;
            font-size: 16px;
            line-height: 150%;
            text-align: center;
        }

        .version {
            margin-bottom: 25px;
            font-size: 12px;
            line-height: 150%;
            display: flex;
            justify-content: center;
        }

        .version span {
            margin-left: 15px;
        }

        .logo {
            display: flex;
            justify-content: center;
        }

        @media (max-width: 1600px) {
            .wrapper::after {
                clip-path: polygon(60% 0%, 100% 0, 100% 75%, 42% 100%, 19% 100%);
            }

            .bg::before {
                clip-path: polygon(66.7% 89%, 63% 100%, 42% 100%);
            }

            .bg::after {
                clip-path: polygon(66.7% 89%, 85% 100%, 64% 100%);
            }
        }

        @media (max-width: 1024px) {
            .main-title {
                margin-bottom: 17px;
                font-size: 32px;
                line-height: 122%;
            }

            .error-code-text {
                font-size: 160px;
            }

            .main-description {
                margin-bottom: 34px;
                font-size: 14px;
            }

            .item {
                width: calc(50% - 16px);
            }
        }

        @media (max-width: 768px) {
            .main-title {
                font-size: 26px;
            }

            .error-code-text {
                font-size: 80px;
            }

            .version {
                flex-direction: column;
                text-align: center;
            }

            .box {
                row-gap: 29px;
                padding: 26px 29px;
            }

            .item {
                width: 100%;
            }

            .wrapper::after {
                background: linear-gradient(29deg, #FFFFFF 4.28%, #EBEBEB 58.61%);
                clip-path: polygon(60% 0%, 100% 0, 100% 100%, 42% 100%, 19% 100%);
            }

            .bg {
                display: none;
            }
        }
    </style>
    <block:head>
        <stack:collect name="styles" level="2"/>
    </block:head>
</head>
<body>
<div class="wrapper">
    <div class="container">
        <block:body/>
    </div>
    <div class="bg"></div>
</div>
<stack:collect name="scripts" level="1"/>
</body>
<hidden>${context}</hidden>
</html>
