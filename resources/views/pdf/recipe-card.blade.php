<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $recipe->title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            background: #f5efe0;
            color: #3b2a1a;
            padding: 40px;
            font-size: 13px;
            line-height: 1.6;
        }

        /* ── Page border ── */
        .page {
            border: 3px double #c9a84c;
            padding: 30px 35px;
            min-height: 95vh;
            background: #fdf6e3;
            position: relative;
        }

        .page::before {
            content: '';
            position: absolute;
            inset: 6px;
            border: 1px solid #e8d9b5;
            pointer-events: none;
        }

        /* ── Header ── */
        .header {
            text-align: center;
            border-bottom: 2px solid #c9a84c;
            padding-bottom: 16px;
            margin-bottom: 20px;
        }

        .platform-name {
            font-size: 11px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #a08060;
            margin-bottom: 8px;
        }

        .recipe-title {
            font-size: 28px;
            font-weight: bold;
            color: #3b2a1a;
            line-height: 1.2;
            margin-bottom: 8px;
        }

        .recipe-meta {
            font-size: 11px;
            color: #7a6045;
            letter-spacing: 1px;
        }

        .recipe-meta span {
            margin: 0 8px;
        }

        /* ── Author ── */
        .author {
            text-align: center;
            font-size: 12px;
            color: #a08060;
            margin-bottom: 16px;
            font-style: italic;
        }

        /* ── Description ── */
        .description {
            font-style: italic;
            color: #5a4030;
            text-align: center;
            margin-bottom: 20px;
            padding: 12px 20px;
            border-top: 1px solid #e8d9b5;
            border-bottom: 1px solid #e8d9b5;
            font-size: 13px;
        }

        /* ── Two column layout ── */
        .body-columns {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .col-left,
        .col-right {
            display: table-cell;
            vertical-align: top;
        }

        .col-left {
            width: 38%;
            padding-right: 24px;
            border-right: 1px solid #e8d9b5;
        }

        .col-right {
            width: 62%;
            padding-left: 24px;
        }

        /* ── Section headings ── */
        .section-title {
            font-size: 10px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #a08060;
            border-bottom: 1px solid #e8d9b5;
            padding-bottom: 4px;
            margin-bottom: 12px;
        }

        /* ── Time boxes ── */
        .time-boxes {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .time-box {
            display: table-cell;
            text-align: center;
            padding: 8px 4px;
            border: 1px solid #e8d9b5;
            background: #f5efe0;
        }

        .time-box:first-child {
            border-radius: 4px 0 0 4px;
        }

        .time-box:last-child {
            border-radius: 0 4px 4px 0;
            border-left: none;
        }

        .time-box+.time-box {
            border-left: none;
        }

        .time-value {
            font-size: 16px;
            font-weight: bold;
            color: #3b2a1a;
            display: block;
        }

        .time-label {
            font-size: 9px;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #a08060;
        }

        /* ── Ingredients ── */
        .ingredient-list {
            list-style: none;
            margin-bottom: 20px;
        }

        .ingredient-list li {
            padding: 5px 0;
            border-bottom: 1px dotted #e8d9b5;
            font-size: 12px;
            display: flex;
            justify-content: space-between;
        }

        .ingredient-list li:last-child {
            border-bottom: none;
        }

        .ing-name {
            color: #3b2a1a;
        }

        .ing-amount {
            color: #7a6045;
            font-style: italic;
        }

        /* ── Difficulty / Category badges ── */
        .badges {
            margin-bottom: 16px;
        }

        .badge {
            display: inline-block;
            background: #f0e6cc;
            color: #7a6045;
            border: 1px solid #c9a84c;
            border-radius: 20px;
            padding: 3px 10px;
            font-size: 10px;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-right: 6px;
        }

        /* ── Steps ── */
        .steps-list {
            list-style: none;
        }

        .step-item {
            display: flex;
            gap: 12px;
            margin-bottom: 14px;
            font-size: 12px;
        }

        .step-num {
            flex-shrink: 0;
            width: 22px;
            height: 22px;
            background: #c9a84c;
            color: #3b2a1a;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: bold;
            margin-top: 1px;
        }

        .step-text {
            color: #3b2a1a;
            line-height: 1.5;
        }

        /* ── Footer ── */
        .footer {
            text-align: center;
            margin-top: 24px;
            padding-top: 12px;
            border-top: 1px solid #e8d9b5;
            font-size: 10px;
            color: #a08060;
            letter-spacing: 1px;
        }

        .ornament {
            color: #c9a84c;
            font-size: 16px;
            display: block;
            margin-bottom: 4px;
        }
    </style>
</head>

<body>
    <div class="page">

        <!-- Header -->
        <div class="header">
            <div class="platform-name">Shire Kitchen · Recipe Card</div>
            <div class="recipe-title">{{ $recipe->title }}</div>
            <div class="recipe-meta">
                <span>{{ ucfirst($recipe->category) }}</span>
                ·
                <span>{{ ucfirst($recipe->difficulty) }}</span>
                ·
                <span>Prep {{ $recipe->prep_time }} min</span>
                ·
                <span>Cook {{ $recipe->cook_time }} min</span>
            </div>
        </div>

        <div class="author">by {{ $recipe->user->name }}</div>

        <!-- Description -->
        <div class="description">{{ $recipe->description }}</div>

        <!-- Body -->
        <div class="body-columns">

            <!-- Left: ingredients + times -->
            <div class="col-left">

                <div class="section-title">Time</div>
                <div class="time-boxes">
                    <div class="time-box">
                        <span class="time-value">{{ $recipe->prep_time }}</span>
                        <span class="time-label">Prep (min)</span>
                    </div>
                    <div class="time-box">
                        <span class="time-value">{{ $recipe->cook_time }}</span>
                        <span class="time-label">Cook (min)</span>
                    </div>
                    <div class="time-box">
                        <span class="time-value">{{ $recipe->prep_time + $recipe->cook_time }}</span>
                        <span class="time-label">Total (min)</span>
                    </div>
                </div>

                <div class="section-title">Details</div>
                <div class="badges" style="margin-bottom: 20px;">
                    <span class="badge">{{ $recipe->category }}</span>
                    <span class="badge">{{ $recipe->difficulty }}</span>
                </div>

                <div class="section-title">Ingredients</div>
                <ul class="ingredient-list">
                    @foreach($recipe->ingredients as $ingredient)
                        <li>
                            <span class="ing-name">{{ $ingredient['name'] }}</span>
                            <span class="ing-amount">{{ $ingredient['amount'] }}</span>
                        </li>
                    @endforeach
                </ul>

            </div>

            <!-- Right: steps -->
            <div class="col-right">

                <div class="section-title">Method</div>
                <ol class="steps-list">
                    @foreach($recipe->steps as $index => $step)
                        <li class="step-item">
                            <span class="step-num">{{ $index + 1 }}</span>
                            <span class="step-text">{{ $step }}</span>
                        </li>
                    @endforeach
                </ol>

            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <span class="ornament">❧</span>
            recipe-sharing-platform.com · Premium Recipe Card
        </div>

    </div>
</body>

</html>
