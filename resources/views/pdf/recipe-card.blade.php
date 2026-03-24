<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $recipe->title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            background: #fdf6e3;
            color: #3b2a1a;
            font-size: 12px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        /* Page margins and border */
        @page {
            margin: 32px 40px;
            border: 3px double #c9a84c;
        }

        /* Main container – ensures content stays within page margins */
        .page-content {
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
        }

        /* Header */
        .header {
            text-align: center;
            border-bottom: 2px solid #c9a84c;
            padding-bottom: 12px;
            margin-bottom: 12px;
        }

        .platform-name {
            font-size: 10px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #a08060;
            margin-bottom: 4px;
        }

        .recipe-title {
            font-size: 24px;
            font-weight: bold;
            color: #3b2a1a;
            line-height: 1.2;
            margin-bottom: 4px;
        }

        .recipe-meta {
            font-size: 11px;
            color: #7a6045;
            letter-spacing: 1px;
        }

        .recipe-meta span {
            margin: 0 4px;
        }

        /* Author */
        .author {
            text-align: center;
            font-size: 11px;
            color: #a08060;
            margin-bottom: 12px;
            font-style: italic;
        }

        /* Description */
        .description {
            font-style: italic;
            color: #5a4030;
            text-align: center;
            margin-bottom: 12px;
            padding: 8px 12px;
            border-top: 1px solid #e8d9b5;
            border-bottom: 1px solid #e8d9b5;
            font-size: 11px;
            word-wrap: break-word;
        }

        /* Image – wider but height‑constrained */
        .recipe-image-wrap {
            text-align: center;
            margin-bottom: 16px;
        }

        .recipe-image {
            width: 100%;
            /* fill the container horizontally */
            max-height: 280px;
            /* keep the height you liked */
            object-fit: contain;
            /* preserve aspect ratio, no cropping */
            border: 1px solid #e8d9b5;
            border-radius: 4px;
        }

        /* Two‑column table layout (rock‑solid in DomPDF) */
        .two-columns {
            display: table;
            width: 100%;
            table-layout: fixed;
            /* forces columns to honour fixed widths */
            margin-top: 4px;
        }

        .col-left,
        .col-right {
            display: table-cell;
            vertical-align: top;
            word-wrap: break-word;
        }

        .col-left {
            width: 36%;
            padding-right: 20px;
            border-right: 1px solid #e8d9b5;
        }

        .col-right {
            width: 64%;
            padding-left: 20px;
        }

        /* Section heading */
        .section-title {
            font-size: 10px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #a08060;
            border-bottom: 1px solid #e8d9b5;
            padding-bottom: 4px;
            margin-bottom: 10px;
            margin-top: 12px;
        }

        .col-left .section-title:first-of-type {
            margin-top: 0;
        }

        /* Time boxes */
        .time-boxes {
            display: flex;
            margin-bottom: 16px;
            gap: 1px;
        }

        .time-box {
            flex: 1;
            text-align: center;
            padding: 6px 4px;
            border: 1px solid #e8d9b5;
            background: #f5efe0;
        }

        .time-value {
            font-size: 15px;
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

        /* Badges */
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
            margin-right: 4px;
        }

        /* Ingredient list */
        .ingredient-list {
            list-style: none;
            margin-bottom: 16px;
        }

        .ingredient-list li {
            padding: 4px 0;
            border-bottom: 1px dotted #e8d9b5;
            font-size: 11px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            page-break-inside: avoid;
        }

        .ing-name {
            color: #3b2a1a;
        }

        .ing-amount {
            color: #7a6045;
            font-style: italic;
        }

        /* Steps list */
        .steps-list {
            list-style: none;
        }

        .step-item {
            display: flex;
            gap: 10px;
            margin-bottom: 12px;
            font-size: 12px;
            page-break-inside: avoid;
        }

        .step-num {
            flex-shrink: 0;
            width: 20px;
            height: 20px;
            background: #c9a84c;
            color: #3b2a1a;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            font-weight: bold;
            margin-top: 2px;
        }

        .step-text {
            color: #3b2a1a;
            line-height: 1.5;
            word-wrap: break-word;
            flex: 1;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 24px;
            padding-top: 10px;
            border-top: 1px solid #e8d9b5;
            font-size: 10px;
            color: #a08060;
            letter-spacing: 1px;
        }

        .ornament {
            color: #c9a84c;
            font-size: 14px;
            display: block;
            margin-bottom: 3px;
        }

        /* Page break control */
        .header,
        .author,
        .description,
        .recipe-image-wrap,
        .footer {
            page-break-inside: avoid;
        }

        /* Allow the two‑column block to break across pages if needed */
        .two-columns {
            page-break-inside: auto;
        }

        /* Keep individual rows together */
        .ingredient-list li,
        .step-item {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>
    <div class="page-content">
        <!-- Header -->
        <div class="header">
            <div class="platform-name">Recipe Sharing Platform · Recipe Card</div>
            <div class="recipe-title">{{ $recipe->title }}</div>
            <div class="recipe-meta">
                <span>{{ ucfirst($recipe->category) }}</span> ·
                <span>{{ ucfirst($recipe->difficulty) }}</span> ·
                <span>Prep {{ $recipe->prep_time }} min</span> ·
                <span>Cook {{ $recipe->cook_time }} min</span>
            </div>
        </div>

        <div class="author">by {{ $recipe->user->name }}</div>

        <!-- Description -->
        <div class="description">{{ $recipe->description }}</div>

        <!-- Image (wider, height‑constrained) -->
        @if($imageData)
            <div class="recipe-image-wrap">
                <img src="{{ $imageData }}" class="recipe-image" alt="{{ $recipe->title }}" />
            </div>
        @endif

        <!-- Two‑column table layout -->
        <div class="two-columns">
            <!-- Left column -->
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
                <div class="badges">
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

            <!-- Right column (Method) -->
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
