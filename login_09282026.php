<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Civil Service Commission Regional Office VIII – Integrated Management Information System">
  <meta name="robots" content="noindex, nofollow">
  <title>Sign In — CSC RO VIII IMIS</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <style>
    /* ═══════════════════════════════════════════════════
       DESIGN TOKENS
    ═══════════════════════════════════════════════════ */
    :root {
      /* Navy / Government Blue */
      --navy-950: #03091a;
      --navy-900: #06112f;
      --navy-800: #0b1d4a;
      --navy-700: #112660;
      --navy-600: #173280;
      --navy-500: #1d3fa0;
      --navy-400: #2d56c8;
      --navy-300: #6b8fe8;
      --navy-100: #dce6fa;
      --navy-50: #f0f4fd;

      /* Philippine Sun Gold — accent */
      --gold: #b8860b;
      --gold-light: #d4a017;
      --gold-pale: #fdf5e0;

      /* Neutral */
      --white: #ffffff;
      --gray-50: #f8f9fb;
      --gray-100: #f0f2f6;
      --gray-200: #e0e4ec;
      --gray-300: #c8cdd8;
      --gray-400: #9199a8;
      --gray-500: #636b7a;
      --gray-600: #454d5c;
      --gray-700: #2e3542;
      --gray-800: #1c2130;
      --gray-900: #0f1422;

      /* Semantic */
      --success: #0f7a4a;
      --danger: #c62828;
      --danger-bg: #fff5f5;
      --info: #0277bd;
      --info-bg: #e3f2fd;

      /* Radii */
      --r-xs: 3px;
      --r-sm: 6px;
      --r-md: 10px;
      --r-lg: 14px;
      --r-xl: 20px;
      --r-pill: 999px;

      /* Shadows */
      --shadow-form: 0 2px 4px rgba(0, 0, 0, 0.04), 0 8px 32px rgba(17, 38, 96, 0.12);
      --shadow-btn: 0 2px 8px rgba(23, 50, 128, 0.30);
      --shadow-input: 0 1px 3px rgba(0, 0, 0, 0.05);

      /* Transitions */
      --t: 180ms cubic-bezier(0.4, 0, 0.2, 1);
      --t-slow: 320ms cubic-bezier(0.4, 0, 0.2, 1);

      /* Field */
      --field-h: 44px;

      /* Typography */
      --font: 'Poppins', sans-serif;
    }

    /* ═══════════════════════════════════════════════════
       RESET
    ═══════════════════════════════════════════════════ */
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    html {
      height: 100%;
    }

    body {
      font-family: var(--font);
      -webkit-font-smoothing: antialiased;
      height: 100%;
      overflow: hidden;
    }

    @media (max-width: 767px) {
      body {
        overflow-y: auto;
        height: auto;
      }
    }

    img {
      display: block;
      max-width: 100%;
    }

    a {
      color: inherit;
    }

    button {
      font-family: var(--font);
      cursor: pointer;
    }

    /* ═══════════════════════════════════════════════════
       BACKGROUND — Deep navy with subtle texture
    ═══════════════════════════════════════════════════ */
    .page-bg {
      position: fixed;
      inset: 0;
      z-index: 0;
      background:
        radial-gradient(ellipse 80% 60% at 18% 42%, #0e1f5e 0%, transparent 58%),
        radial-gradient(ellipse 60% 80% at 82% 15%, #091438 0%, transparent 55%),
        linear-gradient(152deg, #040c24 0%, #081430 50%, #030921 100%);
    }

    /* Subtle fine grid — the "government document" texture */
    .page-bg::after {
      content: '';
      position: absolute;
      inset: 0;
      background-image:
        linear-gradient(rgba(255, 255, 255, 0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.025) 1px, transparent 1px);
      background-size: 40px 40px;
    }

    /* Accent glow — restrained, not flashy */
    .bg-glow {
      position: absolute;
      border-radius: 50%;
      pointer-events: none;
      filter: blur(90px);
    }

    .bg-glow-1 {
      width: 560px;
      height: 560px;
      background: rgba(29, 63, 160, 0.16);
      top: -120px;
      left: -100px;
    }

    .bg-glow-2 {
      width: 400px;
      height: 400px;
      background: rgba(184, 134, 11, 0.05);
      bottom: -80px;
      right: 100px;
    }

    /* ═══════════════════════════════════════════════════
       PAGE WRAPPER
    ═══════════════════════════════════════════════════ */
    .page-wrap {
      position: relative;
      z-index: 1;
      display: grid;
      grid-template-columns: 1fr 1fr;
      min-height: 100vh;
    }

    /* ═══════════════════════════════════════════════════
       LEFT COLUMN — Branding
    ═══════════════════════════════════════════════════ */
    .brand-col {
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 3rem 3.5rem 3rem 4rem;
      position: relative;
    }

    /* Vertical rule separating columns */
    .brand-col::after {
      content: '';
      position: absolute;
      right: 0;
      top: 8%;
      bottom: 8%;
      width: 1px;
      background: linear-gradient(to bottom,
          transparent,
          rgba(255, 255, 255, 0.10) 25%,
          rgba(184, 134, 11, 0.35) 50%,
          rgba(255, 255, 255, 0.10) 75%,
          transparent);
    }

    /* Official seal header */
    .brand-seal {
      display: flex;
      align-items: center;
      gap: 16px;
      margin-bottom: 3.5rem;
    }

    .seal-emblem {
      flex-shrink: 0;
      width: 60px;
      height: 60px;
      border-radius: 50%;
      background: linear-gradient(145deg, var(--navy-600), var(--navy-800));
      border: 2px solid rgba(184, 134, 11, 0.4);
      box-shadow: 0 0 0 4px rgba(184, 134, 11, 0.06), 0 4px 16px rgba(0, 0, 0, 0.3);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .seal-emblem i {
      font-size: 1.5rem;
      color: #d4a017;
    }

    .seal-meta {
      display: flex;
      flex-direction: column;
      gap: 2px;
    }

    .seal-country {
      font-size: 0.58rem;
      font-weight: 600;
      letter-spacing: 0.2em;
      text-transform: uppercase;
      color: rgba(255, 255, 255, 0.35);
    }

    .seal-agency {
      font-size: 0.72rem;
      font-weight: 600;
      color: rgba(255, 255, 255, 0.75);
      line-height: 1.4;
    }

    /* Primary Heading */
    .brand-kicker {
      font-size: 0.6rem;
      font-weight: 700;
      letter-spacing: 0.24em;
      text-transform: uppercase;
      color: var(--gold-light);
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 0.85rem;
    }

    .brand-kicker::before {
      content: '';
      width: 24px;
      height: 2px;
      background: var(--gold-light);
      flex-shrink: 0;
    }

    .brand-h1 {
      font-size: clamp(1.55rem, 2.2vw, 2.1rem);
      font-weight: 800;
      color: #fff;
      line-height: 1.18;
      letter-spacing: -0.025em;
      margin-bottom: 0.6rem;
    }

    .brand-h2 {
      font-size: clamp(0.85rem, 1.1vw, 1rem);
      font-weight: 400;
      color: rgba(255, 255, 255, 0.5);
      letter-spacing: 0.01em;
      margin-bottom: 1.75rem;
    }

    /* Gold rule */
    .brand-rule {
      width: 44px;
      height: 3px;
      background: linear-gradient(90deg, var(--gold), transparent);
      border-radius: 2px;
      margin-bottom: 1.75rem;
    }

    .brand-desc {
      font-size: 0.77rem;
      color: rgba(255, 255, 255, 0.40);
      line-height: 1.80;
      max-width: 360px;
      margin-bottom: 2.5rem;
    }

    /* Feature list */
    .feature-list {
      display: flex;
      flex-direction: column;
      gap: 8px;
      margin-bottom: 2.5rem;
    }

    .feature-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 8px 14px;
      border: 1px solid rgba(255, 255, 255, 0.06);
      border-radius: var(--r-sm);
      background: rgba(255, 255, 255, 0.028);
      transition: background var(--t), border-color var(--t);
    }

    .feature-item:hover {
      background: rgba(255, 255, 255, 0.05);
      border-color: rgba(255, 255, 255, 0.10);
    }

    .feature-dot {
      width: 4px;
      height: 4px;
      border-radius: 50%;
      background: var(--navy-300);
      flex-shrink: 0;
    }

    .feature-item span {
      font-size: 0.72rem;
      color: rgba(255, 255, 255, 0.48);
      font-weight: 500;
    }

    /* System status pill */
    .status-row {
      display: flex;
      align-items: center;
      gap: 8px;
      padding-top: 1.75rem;
      border-top: 1px solid rgba(255, 255, 255, 0.07);
    }

    .status-pip {
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: #22c55e;
      box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4);
      animation: status-pulse 2.5s ease infinite;
    }

    @keyframes status-pulse {
      0% {
        box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.45);
      }

      60% {
        box-shadow: 0 0 0 5px rgba(34, 197, 94, 0);
      }

      100% {
        box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
      }
    }

    .status-label {
      font-size: 0.6rem;
      font-weight: 600;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      color: rgba(255, 255, 255, 0.3);
    }

    .status-label strong {
      color: #4ade80;
      font-weight: 700;
    }

    /* ═══════════════════════════════════════════════════
       RIGHT COLUMN — Login Form
    ═══════════════════════════════════════════════════ */
    .form-col {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2.5rem 3rem;
      background: rgba(246, 248, 252, 0.97);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
    }

    /* ═══════════════════════════════════════════════════
       LOGIN CARD
    ═══════════════════════════════════════════════════ */
    .login-card {
      width: 100%;
      max-width: 428px;
      animation: card-enter 0.45s cubic-bezier(0.22, 1, 0.36, 1) both;
    }

    @keyframes card-enter {
      from {
        opacity: 0;
        transform: translateY(18px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* ── Card Header ── */
    .card-head {
      background: linear-gradient(138deg, var(--navy-800) 0%, var(--navy-950) 100%);
      border-radius: var(--r-lg) var(--r-lg) 0 0;
      padding: 0;
      position: relative;
      overflow: hidden;
    }

    /* Gold bottom border of header */
    .card-head::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      height: 2px;
      background: linear-gradient(90deg, transparent 0%, var(--gold-light) 40%, var(--gold) 60%, transparent 100%);
    }

    .card-head-inner {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 18px 22px 20px;
      position: relative;
      z-index: 1;
    }

    /* Faint radial glow in header */
    .card-head::before {
      content: '';
      position: absolute;
      top: -30px;
      left: -30px;
      width: 150px;
      height: 150px;
      background: radial-gradient(circle, rgba(45, 86, 200, 0.2), transparent 70%);
      pointer-events: none;
    }

    .head-icon-box {
      width: 38px;
      height: 38px;
      border-radius: var(--r-sm);
      background: rgba(184, 134, 11, 0.12);
      border: 1px solid rgba(184, 134, 11, 0.28);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .head-icon-box i {
      font-size: 1.1rem;
      color: #d4a017;
    }

    .head-text {
      flex: 1;
    }

    .head-title {
      font-size: 0.72rem;
      font-weight: 700;
      color: rgba(255, 255, 255, 0.92);
      letter-spacing: 0.07em;
      text-transform: uppercase;
    }

    .head-sub {
      font-size: 0.62rem;
      color: rgba(255, 255, 255, 0.36);
      margin-top: 2px;
    }

    .head-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 3px 10px;
      border-radius: var(--r-pill);
      background: rgba(34, 197, 94, 0.12);
      border: 1px solid rgba(34, 197, 94, 0.28);
      font-size: 0.56rem;
      font-weight: 700;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      color: #4ade80;
    }

    .head-badge-dot {
      width: 4px;
      height: 4px;
      border-radius: 50%;
      background: currentColor;
    }

    /* ── Card Body ── */
    .card-body {
      background: var(--white);
      border: 1px solid rgba(210, 218, 232, 0.8);
      border-top: none;
      border-radius: 0 0 var(--r-lg) var(--r-lg);
      padding: 26px 24px 22px;
      box-shadow: var(--shadow-form);
    }

    /* ── Welcome Block ── */
    .welcome-block {
      margin-bottom: 20px;
    }

    .welcome-eyebrow {
      font-size: 0.58rem;
      font-weight: 700;
      letter-spacing: 0.18em;
      text-transform: uppercase;
      color: var(--navy-400);
      margin-bottom: 5px;
    }

    .welcome-h {
      font-size: 1.18rem;
      font-weight: 700;
      color: var(--gray-900);
      letter-spacing: -0.015em;
      margin-bottom: 3px;
    }

    .welcome-sub {
      font-size: 0.72rem;
      color: var(--gray-500);
    }

    /* ── Mode Toggle ── */
    .mode-toggle {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 0;
      background: var(--gray-100);
      border: 1.5px solid var(--gray-200);
      border-radius: var(--r-md);
      padding: 3px;
      margin-bottom: 20px;
    }

    .mode-btn {
      border: none;
      background: none;
      padding: 7px 10px;
      font-family: var(--font);
      font-size: 0.68rem;
      font-weight: 600;
      color: var(--gray-500);
      border-radius: calc(var(--r-md) - 3px);
      transition: var(--t);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
    }

    .mode-btn.is-active {
      background: var(--white);
      color: var(--navy-600);
      box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08), 0 0 0 0.5px rgba(0, 0, 0, 0.04);
    }

    .mode-btn.is-active-super {
      background: var(--navy-900);
      color: var(--gold-light);
      box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
    }

    /* ── Privacy Notice ── */
    .privacy-bar {
      display: flex;
      align-items: flex-start;
      gap: 8px;
      background: var(--info-bg);
      border: 1px solid #b3d4f7;
      border-radius: var(--r-sm);
      padding: 8px 12px;
      margin-bottom: 18px;
      font-size: 0.65rem;
      color: #01579b;
      line-height: 1.5;
    }

    .privacy-bar i {
      font-size: 0.82rem;
      flex-shrink: 0;
      margin-top: 1px;
      color: var(--info);
    }

    .privacy-bar a {
      font-weight: 700;
      color: var(--navy-500);
      text-decoration: underline;
      text-underline-offset: 2px;
    }

    /* ── Alert ── */
    .alert {
      display: none;
      align-items: center;
      gap: 8px;
      padding: 9px 12px;
      border-radius: var(--r-sm);
      font-size: 0.68rem;
      font-weight: 500;
      margin-bottom: 16px;
    }

    .alert.is-visible {
      display: flex;
      animation: alert-in 0.2s ease;
    }

    @keyframes alert-in {
      from {
        opacity: 0;
        transform: translateY(-3px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .alert-danger {
      background: var(--danger-bg);
      border: 1px solid #fecaca;
      color: var(--danger);
    }

    .alert i {
      font-size: 0.9rem;
      flex-shrink: 0;
    }

    /* ── Form Fields ── */
    .field {
      margin-bottom: 15px;
    }

    .field-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 5px;
    }

    .field-label {
      font-size: 0.65rem;
      font-weight: 700;
      color: var(--gray-600);
      letter-spacing: 0.09em;
      text-transform: uppercase;
      display: block;
    }

    .field-link {
      font-size: 0.65rem;
      font-weight: 600;
      color: var(--navy-500);
      text-decoration: none;
      transition: color var(--t);
    }

    .field-link:hover {
      color: var(--navy-700);
      text-decoration: underline;
    }

    .input-shell {
      position: relative;
      display: flex;
      align-items: center;
    }

    .input-icon {
      position: absolute;
      left: 13px;
      font-size: 0.92rem;
      color: var(--gray-400);
      pointer-events: none;
      transition: color var(--t);
      z-index: 1;
    }

    .input-shell:focus-within .input-icon {
      color: var(--navy-500);
    }

    .form-input {
      width: 100%;
      height: var(--field-h);
      padding: 0 14px 0 40px;
      font-family: var(--font);
      font-size: 0.8rem;
      color: var(--gray-800);
      background: var(--gray-50);
      border: 1.5px solid var(--gray-200);
      border-radius: var(--r-md);
      outline: none;
      transition: border-color var(--t), box-shadow var(--t), background var(--t);
      box-shadow: var(--shadow-input);
    }

    .form-input::placeholder {
      color: var(--gray-400);
    }

    .form-input:focus {
      border-color: var(--navy-400);
      box-shadow: 0 0 0 3px rgba(29, 63, 160, 0.09);
      background: var(--white);
    }

    .form-input.is-error {
      border-color: var(--danger);
      box-shadow: 0 0 0 3px rgba(198, 40, 40, 0.07);
    }

    .form-input.is-success {
      border-color: var(--success);
      box-shadow: 0 0 0 3px rgba(15, 122, 74, 0.07);
    }

    /* Password with trailing button */
    .input-shell--pw .form-input {
      padding-right: 42px;
    }

    .pw-reveal {
      position: absolute;
      right: 6px;
      background: none;
      border: none;
      padding: 6px;
      color: var(--gray-400);
      border-radius: var(--r-xs);
      display: flex;
      align-items: center;
      justify-content: center;
      min-width: 32px;
      min-height: 32px;
      transition: color var(--t);
    }

    .pw-reveal:hover {
      color: var(--navy-500);
    }

    .pw-reveal:focus-visible {
      outline: 2px solid var(--navy-400);
      outline-offset: 1px;
    }

    /* Remember Me */
    .remember-row {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 18px;
    }

    .remember-row input[type="checkbox"] {
      width: 15px;
      height: 15px;
      accent-color: var(--navy-500);
      cursor: pointer;
    }

    .remember-row label {
      font-size: 0.7rem;
      font-weight: 500;
      color: var(--gray-600);
      cursor: pointer;
      user-select: none;
    }

    /* ── CAPTCHA ── */
    .captcha-block {
      background: var(--gray-50);
      border: 1.5px solid var(--gray-200);
      border-radius: var(--r-md);
      padding: 13px 15px;
      margin-bottom: 18px;
    }

    .captcha-head {
      display: flex;
      align-items: center;
      gap: 6px;
      margin-bottom: 10px;
    }

    .captcha-head i {
      font-size: 0.78rem;
      color: var(--navy-400);
    }

    .captcha-head span {
      font-size: 0.6rem;
      font-weight: 700;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      color: var(--gray-500);
    }

    .captcha-row {
      display: flex;
      gap: 10px;
      align-items: center;
    }

    .captcha-row .input-shell {
      flex: 1;
    }

    .captcha-code {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 0 12px;
      height: var(--field-h);
      background: var(--white);
      border: 1.5px solid var(--gray-200);
      border-radius: var(--r-md);
      min-width: 118px;
      flex-shrink: 0;
      box-shadow: var(--shadow-input);
    }

    .captcha-digits {
      font-size: 1.05rem;
      font-weight: 700;
      color: var(--navy-600);
      letter-spacing: 0.2em;
      text-decoration: line-through;
      text-decoration-color: rgba(29, 63, 160, 0.15);
      text-decoration-thickness: 2px;
      user-select: none;
    }

    .captcha-refresh {
      background: none;
      border: none;
      padding: 4px;
      color: var(--gray-400);
      border-radius: var(--r-xs);
      display: flex;
      align-items: center;
      justify-content: center;
      min-width: 26px;
      min-height: 26px;
      transition: color var(--t), transform 360ms ease;
    }

    .captcha-refresh:hover {
      color: var(--navy-500);
      transform: rotate(180deg);
    }

    .captcha-refresh:focus-visible {
      outline: 2px solid var(--navy-400);
      outline-offset: 1px;
    }

    /* ── Submit Button ── */
    .btn-primary {
      width: 100%;
      height: 46px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      border: none;
      border-radius: var(--r-md);
      background: linear-gradient(138deg, var(--navy-500) 0%, var(--navy-700) 100%);
      color: var(--white);
      font-family: var(--font);
      font-size: 0.82rem;
      font-weight: 700;
      letter-spacing: 0.05em;
      box-shadow: var(--shadow-btn);
      position: relative;
      overflow: hidden;
      transition: box-shadow var(--t), transform var(--t), background var(--t);
      margin-bottom: 14px;
    }

    /* Inner sheen */
    .btn-primary::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(160deg, rgba(255, 255, 255, 0.09) 0%, transparent 55%);
      pointer-events: none;
    }

    .btn-primary:hover:not(:disabled) {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(17, 38, 96, 0.35);
      background: linear-gradient(138deg, var(--navy-400) 0%, var(--navy-600) 100%);
    }

    .btn-primary:active:not(:disabled) {
      transform: translateY(0);
      box-shadow: var(--shadow-btn);
    }

    .btn-primary:focus-visible {
      outline: 3px solid var(--navy-300);
      outline-offset: 2px;
    }

    .btn-primary:disabled {
      background: var(--gray-300);
      color: var(--gray-500);
      box-shadow: none;
      cursor: not-allowed;
    }

    .btn-spinner {
      display: none;
      width: 16px;
      height: 16px;
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-top-color: #fff;
      border-radius: 50%;
      animation: spin 0.7s linear infinite;
    }

    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }

    /* Superadmin button variant */
    .btn-super {
      background: linear-gradient(138deg, var(--gold) 0%, #7c5200 100%);
      box-shadow: 0 2px 8px rgba(184, 134, 11, 0.30);
    }

    .btn-super:hover:not(:disabled) {
      background: linear-gradient(138deg, var(--gold-light) 0%, var(--gold) 100%);
      box-shadow: 0 6px 20px rgba(184, 134, 11, 0.38);
      color: #1a0e00;
    }

    /* ── Secure Strip ── */
    .secure-strip {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      font-size: 0.62rem;
      color: var(--gray-400);
      padding-top: 12px;
      border-top: 1px solid var(--gray-100);
    }

    .secure-strip i {
      color: var(--success);
      font-size: 0.75rem;
    }

    /* ── Superadmin Dark Card ── */
    .login-card.is-super .card-head {
      background: linear-gradient(138deg, #1a0e00 0%, #0d0700 100%);
    }

    .login-card.is-super .head-badge {
      display: none;
    }

    .login-card.is-super .card-body {
      background: #14100c;
      border-color: #2d1d08;
    }

    .login-card.is-super .welcome-eyebrow {
      color: var(--gold-light);
    }

    .login-card.is-super .welcome-h {
      color: #fde68a;
    }

    .login-card.is-super .welcome-sub {
      color: #92400e;
    }

    .login-card.is-super .form-input {
      background: #0e0b07;
      border-color: #2a1a07;
      color: #fde68a;
    }

    .login-card.is-super .form-input::placeholder {
      color: #5c3d10;
    }

    .login-card.is-super .form-input:focus {
      border-color: var(--gold);
      box-shadow: 0 0 0 3px rgba(184, 134, 11, 0.10);
    }

    .login-card.is-super .field-label {
      color: #78350f;
    }

    .login-card.is-super .field-link {
      color: var(--gold-light);
    }

    .login-card.is-super .input-icon {
      color: #5c3d10;
    }

    .login-card.is-super .input-shell:focus-within .input-icon {
      color: var(--gold-light);
    }

    .login-card.is-super .captcha-block {
      background: #0e0b07;
      border-color: #2a1a07;
    }

    .login-card.is-super .captcha-head i {
      color: var(--gold-light);
    }

    .login-card.is-super .captcha-head span {
      color: #78350f;
    }

    .login-card.is-super .captcha-code {
      background: #090704;
      border-color: #2a1a07;
    }

    .login-card.is-super .captcha-digits {
      color: var(--gold-light);
      text-decoration-color: rgba(212, 160, 23, 0.2);
    }

    .login-card.is-super .captcha-refresh:hover {
      color: var(--gold-light);
    }

    .login-card.is-super .privacy-bar {
      background: rgba(184, 134, 11, 0.07);
      border-color: rgba(184, 134, 11, 0.2);
      color: #c07d0a;
    }

    .login-card.is-super .privacy-bar i {
      color: var(--gold-light);
    }

    .login-card.is-super .privacy-bar a {
      color: var(--gold-light);
    }

    .login-card.is-super .remember-row label {
      color: #78350f;
    }

    .login-card.is-super .secure-strip {
      border-top-color: #1a1005;
      color: #4a3010;
    }

    .login-card.is-super .pw-reveal {
      color: #5c3d10;
    }

    .login-card.is-super .pw-reveal:hover {
      color: var(--gold-light);
    }

    /* ── Shake on Error ── */
    .login-card.is-shaking {
      animation: shake 0.42s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
    }

    @keyframes shake {

      10%,
      90% {
        transform: translateX(-2px);
      }

      20%,
      80% {
        transform: translateX(5px);
      }

      30%,
      50%,
      70% {
        transform: translateX(-6px);
      }

      40%,
      60% {
        transform: translateX(6px);
      }
    }

    /* ═══════════════════════════════════════════════════
       MOBILE BRANDING (visible only on mobile)
    ═══════════════════════════════════════════════════ */
    .mobile-brand {
      display: none;
      text-align: center;
      margin-bottom: 24px;
    }

    .mobile-emblem {
      width: 54px;
      height: 54px;
      border-radius: 50%;
      background: linear-gradient(145deg, var(--navy-600), var(--navy-800));
      border: 2px solid rgba(184, 134, 11, 0.4);
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 10px;
    }

    .mobile-emblem i {
      font-size: 1.4rem;
      color: #d4a017;
    }

    .mobile-org {
      font-size: 0.85rem;
      font-weight: 700;
      color: rgba(255, 255, 255, 0.88);
    }

    .mobile-sys {
      font-size: 0.7rem;
      color: rgba(255, 255, 255, 0.45);
      margin-top: 2px;
    }

    /* ═══════════════════════════════════════════════════
       FOOTER
    ═══════════════════════════════════════════════════ */
    .page-footer {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 7px 2rem;
      background: rgba(4, 9, 26, 0.80);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border-top: 1px solid rgba(255, 255, 255, 0.07);
      font-size: 0.59rem;
      color: rgba(255, 255, 255, 0.35);
      z-index: 100;
      gap: 12px;
    }

    .footer-links {
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .footer-links a {
      color: rgba(255, 255, 255, 0.45);
      text-decoration: none;
      transition: color var(--t);
    }

    .footer-links a:hover {
      color: rgba(255, 255, 255, 0.85);
    }

    .footer-sep {
      color: rgba(255, 255, 255, 0.18);
    }

    /* ═══════════════════════════════════════════════════
       RESPONSIVE
    ═══════════════════════════════════════════════════ */
    @media (max-width: 991px) {
      .brand-col {
        padding: 2.5rem 2rem;
      }

      .feature-list {
        display: none;
      }

      .brand-desc {
        display: none;
      }
    }

    @media (max-width: 767px) {
      body {
        overflow-y: auto;
      }

      .page-wrap {
        grid-template-columns: 1fr;
        min-height: auto;
      }

      .brand-col {
        display: none;
      }

      .form-col {
        min-height: 100vh;
        padding: 2rem 1.25rem 4rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        background: rgba(6, 12, 38, 0.96);
      }

      .mobile-brand {
        display: block;
      }

      /* Adapt card colors for dark mobile bg */
      .card-body {
        background: rgba(255, 255, 255, 0.05);
        border-color: rgba(255, 255, 255, 0.08);
      }

      .welcome-h {
        color: #fff;
      }

      .welcome-sub {
        color: rgba(255, 255, 255, 0.45);
      }

      .field-label {
        color: rgba(255, 255, 255, 0.55);
      }

      .form-input {
        background: rgba(255, 255, 255, 0.06);
        border-color: rgba(255, 255, 255, 0.09);
        color: #fff;
      }

      .form-input::placeholder {
        color: rgba(255, 255, 255, 0.28);
      }

      .form-input:focus {
        border-color: var(--navy-300);
        background: rgba(255, 255, 255, 0.09);
        box-shadow: 0 0 0 3px rgba(107, 143, 232, 0.12);
      }

      .captcha-block {
        background: rgba(255, 255, 255, 0.04);
        border-color: rgba(255, 255, 255, 0.09);
      }

      .captcha-code {
        background: rgba(255, 255, 255, 0.05);
        border-color: rgba(255, 255, 255, 0.09);
      }

      .captcha-digits {
        color: var(--navy-300);
      }

      .captcha-head span {
        color: rgba(255, 255, 255, 0.35);
      }

      .captcha-row {
        flex-direction: column;
      }

      .captcha-code {
        min-width: unset;
        width: 100%;
      }

      .secure-strip {
        border-top-color: rgba(255, 255, 255, 0.07);
        color: rgba(255, 255, 255, 0.22);
      }

      .field-link {
        color: var(--navy-300);
      }

      .remember-row label {
        color: rgba(255, 255, 255, 0.45);
      }

      .privacy-bar {
        background: rgba(2, 119, 189, 0.08);
        border-color: rgba(2, 119, 189, 0.2);
        color: rgba(179, 212, 247, 0.85);
      }

      .privacy-bar a {
        color: #90caf9;
      }

      .mode-toggle {
        background: rgba(255, 255, 255, 0.06);
        border-color: rgba(255, 255, 255, 0.09);
      }

      .mode-btn {
        color: rgba(255, 255, 255, 0.35);
      }

      .page-footer {
        flex-direction: column;
        gap: 2px;
        padding: 6px 1rem;
        text-align: center;
        height: auto;
      }

      .footer-links {
        gap: 10px;
      }
    }

    /* Compact height (laptop screens) */
    @media (max-height: 760px) and (min-width: 768px) {
      :root {
        --field-h: 40px;
      }

      .brand-seal {
        margin-bottom: 2rem;
      }

      .brand-h1 {
        font-size: 1.55rem;
      }

      .card-body {
        padding: 20px 22px 18px;
      }

      .welcome-block {
        margin-bottom: 14px;
      }

      .field {
        margin-bottom: 12px;
      }

      .captcha-block {
        padding: 10px 13px;
        margin-bottom: 14px;
      }

      .btn-primary {
        height: 42px;
      }
    }

    @media (max-height: 660px) and (min-width: 768px) {
      :root {
        --field-h: 38px;
      }

      .card-body {
        padding: 16px 20px 14px;
      }

      .privacy-bar {
        display: none;
      }

      .feature-list {
        display: none;
      }
    }
  </style>
</head>

<body>

  <!-- Background -->
  <div class="page-bg" aria-hidden="true">
    <div class="bg-glow bg-glow-1"></div>
    <div class="bg-glow bg-glow-2"></div>
  </div>

  <!-- Main layout -->
  <div class="page-wrap">

    <!-- ══════════════════════════
         LEFT — Branding Column
    ══════════════════════════ -->
    <aside class="brand-col" aria-hidden="true">

      <!-- Official seal -->
      <div class="brand-seal">
        <div class="seal-emblem">
          <i class="bi bi-shield-fill-check"></i>
        </div>
        <div class="seal-meta">
          <span class="seal-country">Republic of the Philippines</span>
          <span class="seal-agency">Civil Service Commission<br>Regional Office VIII</span>
        </div>
      </div>

      <p class="brand-kicker">Official Digital Portal</p>

      <!-- Primary heading — highest emphasis -->
      <h1 class="brand-h1">Civil Service Commission<br>Regional Office VIII</h1>

      <!-- Secondary heading -->
      <p class="brand-h2">Integrated Management Information System (IMIS)</p>

      <div class="brand-rule"></div>

      <!-- Supporting description -->
      <p class="brand-desc">
        A centralized digital platform integrating human resource management,
        examination services, client assistance, financial operations,
        technical support, and inter-division workflows.
      </p>

      <!-- Feature pillars -->
      <div class="feature-list" role="list">
        <div class="feature-item" role="listitem">
          <span class="feature-dot"></span>
          <span>Human Resource Management</span>
        </div>
        <div class="feature-item" role="listitem">
          <span class="feature-dot"></span>
          <span>Examination Services</span>
        </div>
        <div class="feature-item" role="listitem">
          <span class="feature-dot"></span>
          <span>Client Assistance</span>
        </div>
        <div class="feature-item" role="listitem">
          <span class="feature-dot"></span>
          <span>Financial Operations</span>
        </div>
        <div class="feature-item" role="listitem">
          <span class="feature-dot"></span>
          <span>Technical Support</span>
        </div>
      </div>

      <!-- Status -->
      <div class="status-row">
        <div class="status-pip"></div>
        <p class="status-label"><strong>All Systems Operational</strong>&ensp;·&ensp;Secured Connection</p>
      </div>

    </aside>

    <!-- ══════════════════════════
         RIGHT — Form Column
    ══════════════════════════ -->
    <main class="form-col">

      <!-- Mobile branding header -->
      <div class="mobile-brand" aria-hidden="true">
        <div class="mobile-emblem">
          <i class="bi bi-shield-fill-check"></i>
        </div>
        <p class="mobile-org">Civil Service Commission RO VIII</p>
        <p class="mobile-sys">Integrated Management Information System</p>
      </div>

      <div class="login-card" id="loginCard" aria-label="IMIS Login">

        <!-- Card Header -->
        <div class="card-head">
          <div class="card-head-inner">
            <div class="head-icon-box">
              <i class="bi bi-person-badge-fill"></i>
            </div>
            <div class="head-text">
              <p class="head-title" id="headTitle">Employee Sign In</p>
              <p class="head-sub" id="headSub">CSC RO VIII Staff Portal</p>
            </div>
            <div class="head-badge" id="headBadge">
              <span class="head-badge-dot"></span>
              Secure
            </div>
          </div>
        </div>

        <!-- Card Body -->
        <div class="card-body">

          <form id="loginForm" novalidate autocomplete="on" aria-label="Sign in to IMIS">
            <input type="hidden" id="loginMode" name="login_mode" value="employee">

            <!-- Welcome -->
            <div class="welcome-block">
              <p class="welcome-eyebrow" id="welcomeEyebrow">Authorized Personnel Only</p>
              <h2 class="welcome-h" id="welcomeH">Welcome Back</h2>
              <p class="welcome-sub" id="welcomeSub">Sign in to access the IMIS Portal</p>
            </div>

            <!-- Mode Toggle -->
            <div class="mode-toggle" role="group" aria-label="Select login mode">
              <button type="button" class="mode-btn is-active" id="btnEmployee" aria-pressed="true">
                <i class="bi bi-person-fill"></i> Employee
              </button>
              <button type="button" class="mode-btn" id="btnSuper" aria-pressed="false">
                <i class="bi bi-shield-lock-fill"></i> Superadmin
              </button>
            </div>

            <!-- Privacy Notice -->
            <div class="privacy-bar" role="note">
              <i class="bi bi-info-circle-fill"></i>
              <span>
                Official government system protected under
                <strong>RA 10173</strong> (Data Privacy Act of 2012).
                <a href="privacy_policy" target="_blank" rel="noopener">Privacy Policy</a>
              </span>
            </div>

            <!-- Alert -->
            <div class="alert alert-danger" id="alertBox" role="alert" aria-live="assertive">
              <i class="bi bi-exclamation-triangle-fill"></i>
              <span id="alertText">Invalid credentials. Please try again.</span>
            </div>

            <!-- Username / Employee ID -->
            <div class="field">
              <div class="field-row">
                <label class="field-label" for="username">Employee ID / Username</label>
              </div>
              <div class="input-shell">
                <i class="bi bi-person input-icon"></i>
                <input
                  type="text"
                  class="form-input"
                  id="username"
                  name="username"
                  placeholder="Enter your employee ID or username"
                  autocomplete="username"
                  spellcheck="false"
                  autocapitalize="none"
                  required
                  aria-required="true"
                  autofocus>
              </div>
            </div>

            <!-- Password -->
            <div class="field">
              <div class="field-row">
                <label class="field-label" for="password">Password</label>
                <a href="forgot_pw" class="field-link" tabindex="0">Forgot Password?</a>
              </div>
              <div class="input-shell input-shell--pw">
                <i class="bi bi-lock input-icon"></i>
                <input
                  type="password"
                  class="form-input"
                  id="password"
                  name="password"
                  placeholder="Enter your password"
                  autocomplete="current-password"
                  required
                  aria-required="true">
                <button
                  type="button"
                  class="pw-reveal"
                  id="pwReveal"
                  aria-label="Show password"
                  aria-pressed="false">
                  <i class="bi bi-eye-slash" id="pwIcon"></i>
                </button>
              </div>
            </div>

            <!-- Remember Me -->
            <div class="remember-row">
              <input type="checkbox" id="rememberMe" name="remember_me">
              <label for="rememberMe">Keep me signed in on this device</label>
            </div>

            <!-- CAPTCHA -->
            <div class="captcha-block">
              <div class="captcha-head">
                <i class="bi bi-shield-lock-fill"></i>
                <span>Security Verification</span>
              </div>
              <div class="captcha-row">
                <div class="input-shell">
                  <i class="bi bi-keyboard input-icon"></i>
                  <input
                    type="tel"
                    class="form-input"
                    id="captchaInput"
                    name="captcha"
                    placeholder="Enter the 4-digit code"
                    maxlength="4"
                    inputmode="numeric"
                    pattern="[0-9]{4}"
                    autocomplete="off"
                    required
                    aria-required="true"
                    aria-label="Enter the 4-digit CAPTCHA code shown beside this field">
                </div>
                <div class="captcha-code" aria-label="CAPTCHA code display">
                  <span class="captcha-digits" id="captchaValue" aria-live="polite" aria-atomic="true">····</span>
                  <button type="button" class="captcha-refresh" id="refreshCaptcha" aria-label="Generate new CAPTCHA code">
                    <i class="bi bi-arrow-clockwise"></i>
                  </button>
                </div>
              </div>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn-primary" id="loginBtn">
              <i class="bi bi-box-arrow-in-right" id="btnIcon"></i>
              <span id="btnLabel">Sign In</span>
              <span class="btn-spinner" id="btnSpinner" role="status" aria-label="Signing in, please wait…"></span>
            </button>

            <!-- Secure strip -->
            <div class="secure-strip">
              <i class="bi bi-lock-fill"></i>
              <span>256-bit TLS encryption — All data is protected in transit</span>
            </div>

          </form>

        </div>
      </div><!-- /.login-card -->

    </main>

  </div><!-- /.page-wrap -->

  <!-- Footer -->
  <footer class="page-footer" role="contentinfo">
    <span>&copy; <span id="footerYear"></span> Civil Service Commission — Regional Office VIII — IMIS v2.0</span>
    <nav class="footer-links" aria-label="Footer navigation">
      <a href="privacy_policy" target="_blank" rel="noopener noreferrer">Privacy Policy</a>
      <span class="footer-sep" aria-hidden="true">|</span>
      <a href="mailto:ro8@csc.gov.ph">Contact Support</a>
      <span class="footer-sep" aria-hidden="true">|</span>
      <span>All Rights Reserved</span>
    </nav>
  </footer>

  <script>
    /* ── Year ── */
    document.getElementById('footerYear').textContent = new Date().getFullYear();

    /* ── CAPTCHA ── */
    let captchaCode = '';

    function generateCaptcha() {
      captchaCode = String(Math.floor(1000 + Math.random() * 9000));
      document.getElementById('captchaValue').textContent = captchaCode;
      document.getElementById('captchaInput').value = '';
    }

    generateCaptcha();

    document.getElementById('refreshCaptcha').addEventListener('click', generateCaptcha);

    /* ── Password Reveal ── */
    const pwReveal = document.getElementById('pwReveal');
    const pwIcon = document.getElementById('pwIcon');
    const pwField = document.getElementById('password');

    pwReveal.addEventListener('click', function() {
      const showing = pwField.type === 'password';
      pwField.type = showing ? 'text' : 'password';
      pwIcon.className = showing ? 'bi bi-eye' : 'bi bi-eye-slash';
      this.setAttribute('aria-pressed', String(showing));
      this.setAttribute('aria-label', showing ? 'Hide password' : 'Show password');
    });

    /* ── Mode Toggle ── */
    const btnEmployee = document.getElementById('btnEmployee');
    const btnSuper = document.getElementById('btnSuper');
    const loginCard = document.getElementById('loginCard');
    const loginMode = document.getElementById('loginMode');
    const headTitle = document.getElementById('headTitle');
    const headSub = document.getElementById('headSub');
    const headBadge = document.getElementById('headBadge');
    const welcomeEyebrow = document.getElementById('welcomeEyebrow');
    const welcomeH = document.getElementById('welcomeH');
    const welcomeSub = document.getElementById('welcomeSub');
    const loginBtn = document.getElementById('loginBtn');

    function setMode(mode) {
      const isSuper = mode === 'super';

      loginMode.value = isSuper ? 'superadmin' : 'employee';
      loginCard.classList.toggle('is-super', isSuper);

      // Toggle buttons
      btnEmployee.classList.toggle('is-active', !isSuper);
      btnEmployee.classList.remove('is-active-super');
      btnSuper.classList.toggle('is-active-super', isSuper);
      btnSuper.classList.remove('is-active');

      btnEmployee.setAttribute('aria-pressed', String(!isSuper));
      btnSuper.setAttribute('aria-pressed', String(isSuper));

      // Header text
      if (isSuper) {
        headTitle.textContent = 'Superadmin Access';
        headSub.textContent = 'Restricted Administrative Portal';
        welcomeEyebrow.textContent = 'Elevated Privilege Mode';
        welcomeH.textContent = 'Administrator Login';
        welcomeSub.textContent = 'Superadmin credentials required';
        loginBtn.classList.add('btn-super');
      } else {
        headTitle.textContent = 'Employee Sign In';
        headSub.textContent = 'CSC RO VIII Staff Portal';
        welcomeEyebrow.textContent = 'Authorized Personnel Only';
        welcomeH.textContent = 'Welcome Back';
        welcomeSub.textContent = 'Sign in to access the IMIS Portal';
        loginBtn.classList.remove('btn-super');
      }
    }

    btnEmployee.addEventListener('click', () => setMode('employee'));
    btnSuper.addEventListener('click', () => setMode('super'));

    /* ── Alert ── */
    const alertBox = document.getElementById('alertBox');
    const alertText = document.getElementById('alertText');

    function showAlert(msg) {
      alertText.textContent = msg;
      alertBox.classList.add('is-visible');
      loginCard.classList.remove('is-shaking');
      void loginCard.offsetWidth;
      loginCard.classList.add('is-shaking');
      loginCard.addEventListener('animationend', () => {
        loginCard.classList.remove('is-shaking');
      }, {
        once: true
      });
    }

    function hideAlert() {
      alertBox.classList.remove('is-visible');
    }

    /* ── Loading ── */
    const btnLabel = document.getElementById('btnLabel');
    const btnIcon = document.getElementById('btnIcon');
    const btnSpinner = document.getElementById('btnSpinner');

    function setLoading(on) {
      loginBtn.disabled = on;
      btnLabel.textContent = on ? 'Signing in…' : 'Sign In';
      btnSpinner.style.display = on ? 'inline-block' : 'none';
      btnIcon.style.display = on ? 'none' : 'inline';
    }

    /* ── Inline Validation ── */
    const usernameField = document.getElementById('username');
    const passwordField = document.getElementById('password');
    const captchaField = document.getElementById('captchaInput');

    function setFieldState(input, state) {
      input.classList.remove('is-error', 'is-success');
      if (state) input.classList.add(state);
    }

    usernameField.addEventListener('blur', function() {
      setFieldState(this, this.value.trim() ? 'is-success' : 'is-error');
    });

    passwordField.addEventListener('blur', function() {
      setFieldState(this, this.value ? 'is-success' : 'is-error');
    });

    /* ── Form Submit ── */
    document.getElementById('loginForm').addEventListener('submit', function(e) {
      e.preventDefault();
      hideAlert();

      const uname = usernameField.value.trim();
      const pw = passwordField.value;
      const cap = captchaField.value.trim();

      if (!uname) {
        showAlert('Please enter your Employee ID or Username.');
        setFieldState(usernameField, 'is-error');
        usernameField.focus();
        return;
      }

      if (!pw) {
        showAlert('Please enter your password.');
        setFieldState(passwordField, 'is-error');
        passwordField.focus();
        return;
      }

      if (!cap) {
        showAlert('Please complete the security verification.');
        captchaField.focus();
        return;
      }

      if (cap !== captchaCode) {
        showAlert('Incorrect security code. A new code has been generated.');
        setFieldState(captchaField, 'is-error');
        generateCaptcha();
        captchaField.focus();
        return;
      }

      setLoading(true);

      // ↓ Replace this timeout with your actual authentication call
      setTimeout(() => {
        setLoading(false);
        showAlert('Invalid credentials. Please check your username and password.');
        setFieldState(usernameField, 'is-error');
        setFieldState(passwordField, 'is-error');
        generateCaptcha();
      }, 1800);
    });

    /* ── Session messages via URL params ── */
    (function() {
      const p = new URLSearchParams(window.location.search);
      if (p.get('logout') === 'idle') {
        showAlert('Your session expired due to inactivity. Please sign in again.');
      }
    })();
  </script>

</body>

</html>