<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InvoiceApp — Invoice smarter, get paid faster</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Cabinet+Grotesk:wght@300;400;500;700;800&display=swap"
        rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0
        }

        :root {
            --bg: #080b14;
            --bg2: #0d1120;
            --surface: #111827;
            --surface2: #1a2234;
            --border: rgba(255, 255, 255, 0.07);
            --border2: rgba(255, 255, 255, 0.12);
            --text: #f0f2f8;
            --text2: #8b95b0;
            --text3: #4a5568;
            --accent: #3b82f6;
            --accent2: #60a5fa;
            --accent-dim: rgba(59, 130, 246, 0.12);
            --green: #10b981;
            --green-dim: rgba(16, 185, 129, 0.12);
            --gold: #f59e0b;
        }

        html {
            scroll-behavior: smooth
        }

        body {
            font-family: 'Cabinet Grotesk', sans-serif;
            background: var(--bg);
            color: var(--text);
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* ── Noise texture overlay ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
            opacity: .4;
        }

        /* ── Nav ── */
        nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 3rem;
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(8, 11, 20, 0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
        }

        .nav-brand {
            font-family: 'Cabinet Grotesk', sans-serif;
            font-weight: 800;
            font-size: 1.15rem;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .brand-icon {
            width: 32px;
            height: 32px;
            background: var(--accent);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-icon svg {
            width: 18px;
            height: 18px;
            stroke: #fff;
            fill: none;
            stroke-width: 2
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none
        }

        .nav-links a {
            font-size: 14px;
            color: var(--text2);
            text-decoration: none;
            font-weight: 400;
            transition: color .2s
        }

        .nav-links a:hover {
            color: var(--text)
        }

        .nav-right {
            display: flex;
            gap: 10px;
            align-items: center
        }

        .btn-nav-ghost {
            padding: 9px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            background: transparent;
            border: 1px solid var(--border2);
            color: var(--text2);
            cursor: pointer;
            font-family: 'Cabinet Grotesk', sans-serif;
            transition: all .2s;
        }

        .btn-nav-ghost:hover {
            border-color: var(--accent);
            color: var(--text)
        }

        .btn-nav-primary {
            padding: 9px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            background: var(--accent);
            border: none;
            color: #fff;
            cursor: pointer;
            font-family: 'Cabinet Grotesk', sans-serif;
            transition: all .2s;
        }

        .btn-nav-primary:hover {
            background: #2563eb;
            transform: translateY(-1px)
        }

        /* ── Hero ── */
        .hero {
            position: relative;
            padding: 7rem 3rem 5rem;
            text-align: center;
            overflow: hidden;
        }

        .hero-glow {
            position: absolute;
            top: -100px;
            left: 50%;
            transform: translateX(-50%);
            width: 800px;
            height: 600px;
            background: radial-gradient(ellipse at center, rgba(59, 130, 246, 0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero-glow2 {
            position: absolute;
            top: 200px;
            left: 10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(ellipse at center, rgba(16, 185, 129, 0.08) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--accent-dim);
            border: 1px solid rgba(59, 130, 246, 0.25);
            border-radius: 100px;
            padding: 6px 16px 6px 10px;
            font-size: 12px;
            font-weight: 600;
            color: var(--accent2);
            margin-bottom: 2rem;
            animation: fadeUp .6s ease both;
        }

        .badge-pulse {
            width: 6px;
            height: 6px;
            background: var(--green);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1)
            }

            50% {
                opacity: .5;
                transform: scale(1.2)
            }
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0
            }

            to {
                opacity: 1
            }
        }

        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: 5rem;
            font-weight: 900;
            line-height: 1.0;
            letter-spacing: -2px;
            color: var(--text);
            max-width: 800px;
            margin: 0 auto 1.5rem;
            animation: fadeUp .7s .1s ease both;
        }

        .hero h1 em {
            font-style: italic;
            background: linear-gradient(135deg, var(--accent2), var(--green));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-sub {
            font-size: 1.15rem;
            color: var(--text2);
            font-weight: 300;
            max-width: 520px;
            margin: 0 auto 2.5rem;
            line-height: 1.7;
            animation: fadeUp .7s .2s ease both;
        }

        .hero-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            align-items: center;
            margin-bottom: 3.5rem;
            animation: fadeUp .7s .3s ease both;
        }

        .btn-hero-primary {
            padding: 15px 36px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            background: var(--accent);
            border: none;
            color: #fff;
            cursor: pointer;
            font-family: 'Cabinet Grotesk', sans-serif;
            transition: all .2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-hero-primary:hover {
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 20px 40px rgba(59, 130, 246, 0.3)
        }

        .btn-hero-ghost {
            padding: 14px 32px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 500;
            background: transparent;
            border: 1px solid var(--border2);
            color: var(--text2);
            cursor: pointer;
            font-family: 'Cabinet Grotesk', sans-serif;
            transition: all .2s;
        }

        .btn-hero-ghost:hover {
            border-color: var(--text2);
            color: var(--text)
        }

        .hero-trust {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1.5rem;
            font-size: 13px;
            color: var(--text3);
            animation: fadeUp .7s .4s ease both;
        }

        .trust-dot {
            width: 4px;
            height: 4px;
            background: var(--text3);
            border-radius: 50%
        }

        .trust-item {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--text2)
        }

        .trust-item svg {
            width: 14px;
            height: 14px;
            stroke: var(--green);
            fill: none;
            stroke-width: 2.5
        }

        /* ── Dashboard Preview ── */
        .preview-wrap {
            position: relative;
            max-width: 1000px;
            margin: 4rem auto 0;
            animation: fadeUp .8s .5s ease both;
        }

        .preview-frame {
            background: var(--surface);
            border: 1px solid var(--border2);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(255, 255, 255, 0.05);
        }

        .frame-bar {
            background: var(--surface2);
            padding: .6rem 1rem;
            display: flex;
            align-items: center;
            gap: .5rem;
            border-bottom: 1px solid var(--border);
        }

        .frame-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%
        }

        .fd1 {
            background: #ff5f57
        }

        .fd2 {
            background: #febc2e
        }

        .fd3 {
            background: #28c840
        }

        .frame-url {
            flex: 1;
            text-align: center;
            background: var(--bg);
            border-radius: 6px;
            padding: 4px 12px;
            font-size: 11px;
            color: var(--text3);
            margin: 0 1rem;
        }

        .frame-content {
            display: flex;
            height: 420px
        }

        .frame-sidebar {
            width: 180px;
            background: var(--bg);
            border-right: 1px solid var(--border);
            padding: 1rem .75rem;
            flex-shrink: 0;
        }

        .fs-brand {
            font-weight: 800;
            font-size: .9rem;
            color: var(--text);
            margin-bottom: 1.5rem;
            padding-left: .5rem;
            display: flex;
            align-items: center;
            gap: 6px
        }

        .fs-dot {
            width: 6px;
            height: 6px;
            background: var(--accent);
            border-radius: 50%
        }

        .fs-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: .45rem .6rem;
            border-radius: 6px;
            font-size: 12px;
            color: var(--text3);
            margin-bottom: 2px;
        }

        .fs-item.active {
            background: rgba(59, 130, 246, 0.15);
            color: var(--accent2)
        }

        .fs-item svg {
            width: 13px;
            height: 13px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            flex-shrink: 0
        }

        .frame-main {
            flex: 1;
            padding: 1.25rem;
            overflow: hidden
        }

        .fm-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 1rem
        }

        .fm-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: .6rem;
            margin-bottom: 1rem
        }

        .fm-stat {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: .6rem .8rem
        }

        .fm-stat-label {
            font-size: 10px;
            color: var(--text3);
            margin-bottom: 3px
        }

        .fm-stat-val {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text)
        }

        .fm-stat-val.green {
            color: var(--green)
        }

        .fm-stat-val.blue {
            color: var(--accent2)
        }

        .fm-table-wrap {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 8px;
            overflow: hidden
        }

        .fm-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px
        }

        .fm-table th {
            background: rgba(255, 255, 255, 0.03);
            color: var(--text3);
            padding: .4rem .6rem;
            text-align: left;
            font-weight: 500;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .5px;
            border-bottom: 1px solid var(--border)
        }

        .fm-table td {
            padding: .45rem .6rem;
            border-bottom: 1px solid var(--border);
            color: var(--text2)
        }

        .fm-table tr:last-child td {
            border-bottom: none
        }

        .fm-badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 100px;
            font-size: 9px;
            font-weight: 600
        }

        .fb-paid {
            background: rgba(16, 185, 129, .15);
            color: #34d399
        }

        .fb-draft {
            background: rgba(255, 255, 255, .06);
            color: var(--text3)
        }

        /* floating cards */
        .fc-left {
            position: absolute;
            left: -60px;
            top: 80px;
            background: var(--surface2);
            border: 1px solid var(--border2);
            border-radius: 14px;
            padding: 1rem 1.25rem;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
            min-width: 160px;
        }

        .fc-right {
            position: absolute;
            right: -60px;
            bottom: 80px;
            background: var(--surface2);
            border: 1px solid var(--border2);
            border-radius: 14px;
            padding: 1rem 1.25rem;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
            min-width: 160px;
        }

        .fc-label {
            font-size: 10px;
            color: var(--text3);
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: .5px
        }

        .fc-val {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--green)
        }

        .fc-sub {
            font-size: 11px;
            color: var(--text3);
            margin-top: 2px
        }

        .fc-right .fc-val {
            color: var(--accent2)
        }

        /* ── Stats bar ── */
        .stats-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            max-width: 800px;
            margin: 5rem auto;
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
        }

        .stat-item {
            flex: 1;
            text-align: center;
            padding: 2rem 1rem;
            border-right: 1px solid var(--border);
        }

        .stat-item:last-child {
            border-right: none
        }

        .stat-num {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--text), var(--text2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-label {
            font-size: 13px;
            color: var(--text3);
            margin-top: 4px;
            font-weight: 400
        }

        /* ── Features ── */
        .features {
            padding: 5rem 3rem;
            max-width: 1100px;
            margin: 0 auto
        }

        .section-eyebrow {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: var(--accent);
            margin-bottom: 1rem;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            font-weight: 700;
            letter-spacing: -1px;
            color: var(--text);
            margin-bottom: 1rem;
            line-height: 1.1;
        }

        .section-sub {
            font-size: 1rem;
            color: var(--text2);
            font-weight: 300;
            max-width: 480px;
            line-height: 1.7
        }

        .features-header {
            margin-bottom: 3.5rem
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1px;
            background: var(--border);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden
        }

        .feat-card {
            background: var(--bg);
            padding: 2rem;
            transition: background .25s;
            cursor: default;
        }

        .feat-card:hover {
            background: var(--surface)
        }

        .feat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
            border: 1px solid var(--border2);
        }

        .feat-card h3 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: .5rem
        }

        .feat-card p {
            font-size: 13px;
            color: var(--text2);
            line-height: 1.65;
            font-weight: 300
        }

        /* ── Invoice Preview ── */
        .invoice-section {
            padding: 5rem 3rem;
            background: var(--surface);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        .invoice-inner {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5rem;
            align-items: center
        }

        .invoice-preview {
            background: var(--bg);
            border: 1px solid var(--border2);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.5);
        }

        .inv-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem
        }

        .inv-company {
            font-weight: 800;
            font-size: 1rem;
            color: var(--text)
        }

        .inv-company-sub {
            font-size: 11px;
            color: var(--text3);
            margin-top: 3px
        }

        .inv-title-block {
            text-align: right
        }

        .inv-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--accent2)
        }

        .inv-num {
            font-size: 11px;
            color: var(--text3);
            margin-top: 2px
        }

        .inv-badge {
            display: inline-block;
            background: rgba(16, 185, 129, .15);
            color: #34d399;
            font-size: 9px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 100px;
            margin-top: 5px;
            text-transform: uppercase;
            letter-spacing: .5px
        }

        .inv-divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 1.25rem 0
        }

        .inv-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1.25rem;
            font-size: 12px
        }

        .inv-meta-label {
            color: var(--text3);
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 3px
        }

        .inv-meta-val {
            color: var(--text);
            font-weight: 500
        }

        .inv-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 1rem
        }

        .inv-table thead tr {
            background: var(--accent);
            color: #fff
        }

        .inv-table th {
            padding: 8px 10px;
            text-align: left;
            font-weight: 600;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .5px
        }

        .inv-table td {
            padding: 9px 10px;
            border-bottom: 1px solid var(--border);
            color: var(--text2)
        }

        .inv-table tr:last-child td {
            border-bottom: none
        }

        .inv-total-row {
            background: rgba(59, 130, 246, .1)
        }

        .inv-total-row td {
            color: var(--accent2);
            font-weight: 700
        }

        .inv-footer-text {
            font-size: 10px;
            color: var(--text3);
            text-align: center;
            margin-top: 1rem
        }

        /* right side text */
        .inv-text-side .section-eyebrow {
            margin-bottom: 1rem
        }

        .inv-text-side .section-title {
            font-size: 2.4rem
        }

        .inv-text-side .section-sub {
            margin-bottom: 2rem
        }

        .inv-features {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: .75rem
        }

        .inv-feat-item {
            display: flex;
            align-items: flex-start;
            gap: .75rem
        }

        .inv-feat-check {
            width: 20px;
            height: 20px;
            background: var(--green-dim);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 1px
        }

        .inv-feat-check svg {
            width: 11px;
            height: 11px;
            stroke: var(--green);
            fill: none;
            stroke-width: 2.5
        }

        .inv-feat-text {
            font-size: 14px;
            color: var(--text2);
            font-weight: 400
        }

        /* ── How it works ── */
        .how-section {
            padding: 5rem 3rem;
            max-width: 1100px;
            margin: 0 auto
        }

        .how-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-top: 3.5rem;
            position: relative
        }

        .how-line {
            position: absolute;
            top: 28px;
            left: calc(12.5% + 10px);
            right: calc(12.5% + 10px);
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border2), var(--border2), transparent);
        }

        .how-card {
            position: relative;
            z-index: 1
        }

        .how-num {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: var(--surface2);
            border: 1px solid var(--border2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 1.25rem;
            transition: all .3s;
        }

        .how-card:hover .how-num {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff
        }

        .how-card h4 {
            font-size: .95rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: .4rem
        }

        .how-card p {
            font-size: 13px;
            color: var(--text2);
            line-height: 1.6;
            font-weight: 300
        }

        /* ── Testimonial ── */
        .testimonial-section {
            padding: 5rem 3rem;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            background: var(--surface);
        }

        .test-inner {
            max-width: 900px;
            margin: 0 auto;
            text-align: center
        }

        .test-quote {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--text);
            line-height: 1.3;
            margin-bottom: 1.5rem;
        }

        .test-quote em {
            font-style: italic;
            color: var(--accent2)
        }

        .test-author {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .75rem
        }

        .test-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--green));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            color: #fff
        }

        .test-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--text);
            text-align: left
        }

        .test-role {
            font-size: 12px;
            color: var(--text3)
        }

        /* ── CTA ── */
        .cta-section {
            padding: 7rem 3rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta-glow {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 600px;
            height: 400px;
            background: radial-gradient(ellipse, rgba(59, 130, 246, 0.2) 0%, transparent 70%);
            pointer-events: none;
        }

        .cta-section h2 {
            font-family: 'Playfair Display', serif;
            font-size: 4rem;
            font-weight: 700;
            letter-spacing: -1.5px;
            color: var(--text);
            margin-bottom: 1rem;
            line-height: 1.05;
        }

        .cta-section h2 em {
            font-style: italic;
            color: var(--accent2)
        }

        .cta-section p {
            font-size: 1.05rem;
            color: var(--text2);
            font-weight: 300;
            margin-bottom: 2.5rem
        }

        .cta-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            align-items: center;
            margin-bottom: 1.5rem
        }

        .btn-cta {
            padding: 16px 40px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            background: var(--accent);
            border: none;
            color: #fff;
            cursor: pointer;
            font-family: 'Cabinet Grotesk', sans-serif;
            transition: all .25s;
        }

        .btn-cta:hover {
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 20px 50px rgba(59, 130, 246, 0.35)
        }

        .cta-note {
            font-size: 13px;
            color: var(--text3)
        }

        /* ── Footer ── */
        footer {
            border-top: 1px solid var(--border);
            padding: 2.5rem 3rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .footer-brand {
            font-weight: 800;
            font-size: 1rem;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 8px
        }

        .footer-links {
            display: flex;
            gap: 2rem
        }

        .footer-links a {
            font-size: 13px;
            color: var(--text3);
            text-decoration: none;
            transition: color .2s
        }

        .footer-links a:hover {
            color: var(--text2)
        }

        .footer-copy {
            font-size: 12px;
            color: var(--text3)
        }

        /* ── Animations ── */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity .7s ease, transform .7s ease
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0)
        }
    </style>
</head>

<body>

    <!-- Nav -->
    <nav>
        <a href="#" class="nav-brand">
            <div class="brand-icon">
                <svg viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                    <polyline points="14 2 14 8 20 8" />
                    <line x1="16" y1="13" x2="8" y2="13" />
                    <line x1="16" y1="17" x2="8" y2="17" />
                </svg>
            </div>
            InvoiceApp
        </a>
        <ul class="nav-links">
            <li><a href="#features">Features</a></li>
            <li><a href="#how">How it works</a></li>
            <li><a href="#invoice">PDF Invoices</a></li>
        </ul>
        <div class="nav-right">
            <button class="btn-nav-ghost">Sign in</button>
            <button class="btn-nav-primary">Get started free</button>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero">
        <div class="hero-glow"></div>
        <div class="hero-glow2"></div>
        <div class="hero-badge">
            <div class="badge-pulse"></div>
            Built with Laravel · Open source · Self-hostable
        </div>
        <h1>Invoice smarter,<br>get paid <em>faster.</em></h1>
        <p class="hero-sub">The cleanest invoicing tool for freelancers and small teams. Create, send, and track
            invoices — all in one beautiful workspace.</p>
        <div class="hero-actions">
            <button class="btn-hero-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="13 17 18 12 13 7" />
                    <polyline points="6 17 11 12 6 7" />
                </svg>
                Start for free
            </button>
            <button class="btn-hero-ghost">Watch demo</button>
        </div>
        <div class="hero-trust">
            <div class="trust-item">
                <svg viewBox="0 0 24 24">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                No credit card
            </div>
            <div class="trust-dot"></div>
            <div class="trust-item">
                <svg viewBox="0 0 24 24">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                Free forever plan
            </div>
            <div class="trust-dot"></div>
            <div class="trust-item">
                <svg viewBox="0 0 24 24">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                Setup in 2 minutes
            </div>
        </div>

        <!-- Dashboard mockup -->
        <div class="preview-wrap">
            <div class="fc-left">
                <div class="fc-label">Monthly revenue</div>
                <div class="fc-val">$12,480</div>
                <div class="fc-sub">↑ 24% vs last month</div>
            </div>
            <div class="preview-frame">
                <div class="frame-bar">
                    <div class="frame-dot fd1"></div>
                    <div class="frame-dot fd2"></div>
                    <div class="frame-dot fd3"></div>
                    <div class="frame-url">app.invoiceapp.com/dashboard</div>
                </div>
                <div class="frame-content">
                    <div class="frame-sidebar">
                        <div class="fs-brand">
                            <div class="fs-dot"></div>InvoiceApp
                        </div>
                        <div class="fs-item active">
                            <svg viewBox="0 0 24 24">
                                <rect x="3" y="3" width="7" height="7" />
                                <rect x="14" y="3" width="7" height="7" />
                                <rect x="14" y="14" width="7" height="7" />
                                <rect x="3" y="14" width="7" height="7" />
                            </svg>
                            Dashboard
                        </div>
                        <div class="fs-item">
                            <svg viewBox="0 0 24 24">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                            </svg>
                            Customers
                        </div>
                        <div class="fs-item">
                            <svg viewBox="0 0 24 24">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                            </svg>
                            Invoices
                        </div>
                        <div class="fs-item">
                            <svg viewBox="0 0 24 24">
                                <rect x="3" y="3" width="18" height="18" rx="2" />
                                <path d="M3 9h18M9 21V9" />
                            </svg>
                            Reports
                        </div>
                        <div class="fs-item">
                            <svg viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="3" />
                                <path d="M19.07 4.93a10 10 0 0 1 1.41 14.14M4.93 4.93A10 10 0 0 0 6.34 19.07" />
                            </svg>
                            Settings
                        </div>
                    </div>
                    <div class="frame-main">
                        <div class="fm-title">Dashboard</div>
                        <div class="fm-stats">
                            <div class="fm-stat">
                                <div class="fm-stat-label">Total Revenue</div>
                                <div class="fm-stat-val green">$48,290</div>
                            </div>
                            <div class="fm-stat">
                                <div class="fm-stat-label">Invoices</div>
                                <div class="fm-stat-val">124</div>
                            </div>
                            <div class="fm-stat">
                                <div class="fm-stat-label">Pending</div>
                                <div class="fm-stat-val blue">$6,400</div>
                            </div>
                        </div>
                        <div class="fm-table-wrap">
                            <table class="fm-table">
                                <thead>
                                    <tr>
                                        <th>Invoice</th>
                                        <th>Client</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>INV-0024</td>
                                        <td>Acme Corp</td>
                                        <td>$2,800</td>
                                        <td><span class="fm-badge fb-paid">Paid</span></td>
                                    </tr>
                                    <tr>
                                        <td>INV-0023</td>
                                        <td>Nova Studio</td>
                                        <td>$1,200</td>
                                        <td><span class="fm-badge fb-draft">Draft</span></td>
                                    </tr>
                                    <tr>
                                        <td>INV-0022</td>
                                        <td>Pixel Works</td>
                                        <td>$3,500</td>
                                        <td><span class="fm-badge fb-paid">Paid</span></td>
                                    </tr>
                                    <tr>
                                        <td>INV-0021</td>
                                        <td>Bright Media</td>
                                        <td>$900</td>
                                        <td><span class="fm-badge fb-paid">Paid</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="fc-right">
                <div class="fc-label">New invoice created</div>
                <div class="fc-val">INV-0025</div>
                <div class="fc-sub">Just now · $4,200</div>
            </div>
        </div>
    </section>

    <!-- Stats bar -->
    <div class="stats-bar reveal">
        <div class="stat-item">
            <div class="stat-num">2min</div>
            <div class="stat-label">Average invoice creation time</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">100%</div>
            <div class="stat-label">Data isolated per account</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">PDF</div>
            <div class="stat-label">One-click professional export</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">∞</div>
            <div class="stat-label">Invoices on free plan</div>
        </div>
    </div>

    <!-- Features -->
    <section class="features reveal" id="features">
        <div class="features-header">
            <div class="section-eyebrow">Features</div>
            <h2 class="section-title">Everything you need.<br>Nothing you don't.</h2>
            <p class="section-sub">A focused toolkit for freelancers who want to look professional without drowning in
                complexity.</p>
        </div>
        <div class="features-grid">
            <div class="feat-card">
                <div class="feat-icon" style="background:rgba(59,130,246,0.1)">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" y1="13" x2="8" y2="13" />
                        <line x1="16" y1="17" x2="8" y2="17" />
                    </svg>
                </div>
                <h3>Beautiful PDF invoices</h3>
                <p>Pixel-perfect PDFs with your logo, brand color, and footer. Generated in one click using DomPDF.</p>
            </div>
            <div class="feat-card">
                <div class="feat-icon" style="background:rgba(16,185,129,0.1)">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#34d399" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </div>
                <h3>Client management</h3>
                <p>Store all your clients. Create invoices with pre-filled details and track everything per client.</p>
            </div>
            <div class="feat-card">
                <div class="feat-icon" style="background:rgba(245,158,11,0.1)">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="2">
                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
                    </svg>
                </div>
                <h3>Public share links</h3>
                <p>Share a secure token link. Clients view and download their invoice — no login required, ever.</p>
            </div>
            <div class="feat-card">
                <div class="feat-icon" style="background:rgba(139,92,246,0.1)">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" />
                        <path d="M3 9h18M9 21V9" />
                    </svg>
                </div>
                <h3>Analytics & reports</h3>
                <p>Monthly revenue charts, top clients by volume, and Excel export. Know your numbers instantly.</p>
            </div>
            <div class="feat-card">
                <div class="feat-icon" style="background:rgba(236,72,153,0.1)">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#f472b6" stroke-width="2">
                        <circle cx="12" cy="8" r="4" />
                        <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" />
                    </svg>
                </div>
                <h3>Brand customization</h3>
                <p>Upload logo, pick your color, set invoice prefix. Every PDF is uniquely yours and on-brand.</p>
            </div>
            <div class="feat-card">
                <div class="feat-icon" style="background:rgba(20,184,166,0.1)">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#2dd4bf" stroke-width="2">
                        <polyline points="16 3 21 3 21 8" />
                        <line x1="4" y1="20" x2="21" y2="3" />
                        <polyline points="21 16 21 21 16 21" />
                        <line x1="15" y1="15" x2="21" y2="21" />
                    </svg>
                </div>
                <h3>Duplicate invoices</h3>
                <p>Clone any invoice with one click. New number, today's date — all items copied instantly.</p>
            </div>
        </div>
    </section>

    <!-- Invoice section -->
    <section class="invoice-section reveal" id="invoice">
        <div class="invoice-inner">
            <div class="invoice-preview">
                <div class="inv-header">
                    <div>
                        <div class="inv-company">Acme Studio</div>
                        <div class="inv-company-sub">acme@studio.com · +1 555 000 111</div>
                    </div>
                    <div class="inv-title-block">
                        <div class="inv-title">INVOICE</div>
                        <div class="inv-num">INV-0024</div>
                        <div><span class="inv-badge">Paid</span></div>
                    </div>
                </div>
                <hr class="inv-divider">
                <div class="inv-meta">
                    <div>
                        <div class="inv-meta-label">Bill to</div>
                        <div class="inv-meta-val">Nova Corp</div>
                        <div style="font-size:11px;color:var(--text3)">nova@corp.com</div>
                    </div>
                    <div>
                        <div class="inv-meta-label">Issue date</div>
                        <div class="inv-meta-val">March 15, 2025</div>
                    </div>
                    <div>
                        <div class="inv-meta-label">Due date</div>
                        <div class="inv-meta-val">March 30, 2025</div>
                    </div>
                </div>
                <table class="inv-table">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Web Design</td>
                            <td>1</td>
                            <td>$2,000</td>
                            <td>$2,000</td>
                        </tr>
                        <tr>
                            <td>Logo Package</td>
                            <td>1</td>
                            <td>$600</td>
                            <td>$600</td>
                        </tr>
                        <tr>
                            <td>SEO Setup</td>
                            <td>2</td>
                            <td>$100</td>
                            <td>$200</td>
                        </tr>
                        <tr class="inv-total-row">
                            <td colspan="3"
                                style="text-align:right;font-size:11px;text-transform:uppercase;letter-spacing:.5px">
                                Total Due</td>
                            <td>$2,800.00</td>
                        </tr>
                    </tbody>
                </table>
                <div class="inv-footer-text">Thank you for your business — Acme Studio</div>
            </div>
            <div class="inv-text-side">
                <div class="section-eyebrow">PDF Invoices</div>
                <h2 class="section-title">Professional invoices that impress.</h2>
                <p class="section-sub">Every invoice looks like it was designed by a professional. Your logo, your
                    colors, your footer — generated in under a second.</p>
                <ul class="inv-features">
                    <li class="inv-feat-item">
                        <div class="inv-feat-check"><svg viewBox="0 0 24 24">
                                <polyline points="20 6 9 17 4 12" />
                            </svg></div>
                        <div class="inv-feat-text">Custom logo, color, and invoice prefix</div>
                    </li>
                    <li class="inv-feat-item">
                        <div class="inv-feat-check"><svg viewBox="0 0 24 24">
                                <polyline points="20 6 9 17 4 12" />
                            </svg></div>
                        <div class="inv-feat-text">Auto-calculated totals from line items</div>
                    </li>
                    <li class="inv-feat-item">
                        <div class="inv-feat-check"><svg viewBox="0 0 24 24">
                                <polyline points="20 6 9 17 4 12" />
                            </svg></div>
                        <div class="inv-feat-text">One-click PDF download with DomPDF</div>
                    </li>
                    <li class="inv-feat-item">
                        <div class="inv-feat-check"><svg viewBox="0 0 24 24">
                                <polyline points="20 6 9 17 4 12" />
                            </svg></div>
                        <div class="inv-feat-text">Share public link — no login for clients</div>
                    </li>
                    <li class="inv-feat-item">
                        <div class="inv-feat-check"><svg viewBox="0 0 24 24">
                                <polyline points="20 6 9 17 4 12" />
                            </svg></div>
                        <div class="inv-feat-text">Custom footer note on every PDF</div>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <!-- How it works -->
    <section class="how-section reveal" id="how">
        <div class="section-eyebrow">How it works</div>
        <h2 class="section-title">Up and running in minutes.</h2>
        <div class="how-grid">
            <div class="how-line"></div>
            <div class="how-card">
                <div class="how-num">01</div>
                <h4>Create your account</h4>
                <p>Register in 30 seconds. No credit card, no setup fees, no contracts.</p>
            </div>
            <div class="how-card">
                <div class="how-num">02</div>
                <h4>Add your clients</h4>
                <p>Name, email, phone. All your clients in one place, ready to invoice.</p>
            </div>
            <div class="how-card">
                <div class="how-num">03</div>
                <h4>Create an invoice</h4>
                <p>Add line items, quantities, and prices. Totals calculate automatically.</p>
            </div>
            <div class="how-card">
                <div class="how-num">04</div>
                <h4>Send & get paid</h4>
                <p>Download PDF or share a secure link directly with your client.</p>
            </div>
        </div>
    </section>

    <!-- Testimonial -->
    <section class="testimonial-section reveal">
        <div class="test-inner">
            <div class="test-quote">
                "Finally an invoicing tool that doesn't try to do <em>everything.</em> It just does invoicing — and it
                does it beautifully."
            </div>
            <div class="test-author">
                <div class="test-avatar">RF</div>
                <div>
                    <div class="test-name">Rafee Frezeq</div>
                    <div class="test-role">Freelance Developer · Built with Laravel</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta-section reveal">
        <div class="cta-glow"></div>
        <h2>Ready to get<br><em>paid faster?</em></h2>
        <p>Join freelancers who send invoices the smart way. Free forever, no card needed.</p>
        <div class="cta-actions">
            <button class="btn-cta">Start for free — it takes 2 minutes</button>
        </div>
        <div class="cta-note">No credit card · Free plan · Cancel anytime</div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-brand">
            <div class="brand-icon" style="width:24px;height:24px;border-radius:6px">
                <svg viewBox="0 0 24 24" style="width:13px;height:13px;stroke:#fff;fill:none;stroke-width:2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                    <polyline points="14 2 14 8 20 8" />
                </svg>
            </div>
            InvoiceApp
        </div>
        <div class="footer-links">
            <a href="#">Features</a>
            <a href="#">Privacy</a>
            <a href="#">Terms</a>
            <a href="#">GitHub</a>
        </div>
        <div class="footer-copy">© 2025 InvoiceApp. Built with Laravel.</div>
    </footer>

    <script>
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    observer.unobserve(e.target);
                }
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    </script>

</body>

</html>