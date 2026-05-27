<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dang nhap quan tri | Cong thong tin Hien Mau Tinh Nguyen</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-light: #eff6ff;
            --neutral-dark: #0f172a;
            --neutral-grey: #64748b;
            --neutral-light: #f8fafc;
            --border-color: #e2e8f0;
            --body-bg: #f3f6ff;
            --shadow-md: 0 12px 28px rgba(15, 23, 42, 0.08);
            --font-main: 'Plus Jakarta Sans', sans-serif;
            --font-heading: 'Outfit', sans-serif;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-main);
            background-color: var(--body-bg);
            color: var(--neutral-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 20px;
        }

        .login-shell {
            width: 100%;
            max-width: 980px;
            display: grid;
            grid-template-columns: 1.1fr 1fr;
            gap: 28px;
            align-items: stretch;
        }

        .login-panel,
        .login-aside {
            background-color: #fff;
            border-radius: 18px;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-md);
            padding: 32px;
        }

        .login-aside {
            background: linear-gradient(135deg, #111c43 0%, #1d4ed8 100%);
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
        }

        .brand-logo {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-logo svg {
            width: 22px;
            height: 22px;
            color: #fff;
        }

        .brand-title {
            display: flex;
            flex-direction: column;
        }

        .brand-name {
            font-family: var(--font-heading);
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 0.4px;
        }

        .brand-sub {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255, 255, 255, 0.7);
        }

        .panel-title {
            font-family: var(--font-heading);
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .panel-subtitle {
            color: var(--neutral-grey);
            font-size: 14px;
            margin-bottom: 24px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 16px;
        }

        label {
            font-size: 12px;
            font-weight: 600;
            color: var(--neutral-grey);
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            color: var(--neutral-grey);
        }

        input {
            width: 100%;
            height: 44px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            background-color: var(--neutral-light);
            padding: 10px 14px 10px 44px;
            font-size: 14px;
            font-weight: 500;
            outline: none;
            transition: all 0.2s ease;
            font-family: var(--font-main);
        }

        input:focus {
            background-color: #fff;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 16px 0 24px 0;
            font-size: 13px;
            color: var(--neutral-grey);
        }

        .btn-login {
            width: 100%;
            border: none;
            background-color: var(--primary);
            color: #fff;
            height: 46px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.25);
        }

        .btn-login:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
        }

        .helper-text {
            margin-top: 16px;
            font-size: 12px;
            color: var(--neutral-grey);
        }

        .aside-title {
            font-family: var(--font-heading);
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .aside-text {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
        }

        .aside-card {
            background-color: rgba(255, 255, 255, 0.12);
            border-radius: 16px;
            padding: 18px;
            margin-top: 24px;
        }

        .aside-card strong {
            display: block;
            font-size: 13px;
            letter-spacing: 0.4px;
            margin-bottom: 6px;
        }

        .aside-card span {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.85);
        }

        @media (max-width: 900px) {
            .login-shell {
                grid-template-columns: 1fr;
            }

            .login-aside {
                order: -1;
            }
        }
    </style>
</head>
<body>
    <main class="login-shell">
        <section class="login-panel">
            <div class="brand">
                <div class="brand-logo">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                    </svg>
                </div>
                <div class="brand-title">
                    <span class="brand-name">HIEN MAU</span>
                    <span class="brand-sub">Tinh Nguyen</span>
                </div>
            </div>

            <h1 class="panel-title">Dang nhap quan tri</h1>
            <p class="panel-subtitle">Su dung email hoac so dien thoai va mat khau.</p>

            @if ($errors->any())
                <div style="margin-bottom: 16px; padding: 12px 14px; border-radius: 12px; background-color: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; font-size: 13px; font-weight: 600;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="post" action="{{ route('admin.login.submit') }}">
                @csrf
                <div class="form-group">
                    <label for="admin-identity">Email hoac so dien thoai</label>
                    <div class="input-wrap">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <input id="admin-identity" name="email_or_phone" type="text" placeholder="Email hoac so dien thoai" value="{{ old('email_or_phone') }}" autocomplete="username">
                    </div>
                </div>

                <div class="form-group">
                    <label for="admin-password">Mat khau</label>
                    <div class="input-wrap">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c1.657 0 3-1.343 3-3V7a3 3 0 10-6 0v1c0 1.657 1.343 3 3 3z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 11h14v8H5z" />
                        </svg>
                        <input id="admin-password" name="password" type="password" placeholder="Mat khau" autocomplete="current-password">
                    </div>
                </div>

                <div class="meta-row">
                    <span>Dang nhap bang thong tin da cap</span>
                    <a href="{{ route('admin.home') }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">Ve trang chu</a>
                </div>

                <button class="btn-login" type="submit">Dang nhap</button>
            </form>

            <p class="helper-text">Neu can ho tro, vui long lien he quan tri he thong.</p>
        </section>

        <aside class="login-aside">
            <div>
                <h2 class="aside-title">Khu vuc quan tri</h2>
                <p class="aside-text">Kiem soat chuong trinh, quan ly nguoi dung va theo doi thong ke hoat dong hien mau.</p>
                <div class="aside-card">
                    <strong>Thong tin nhanh</strong>
                    <span>Dang nhap bang tai khoan duoc cap boi he thong.</span>
                </div>
            </div>
            <div class="aside-text">Cong thong tin Hien Mau Tinh Nguyen</div>
        </aside>
    </main>
</body>
</html>
