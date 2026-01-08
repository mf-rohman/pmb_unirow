<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login PMB - Universitas</title>
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #5D5FEF;      
            --primary-dark: #4b4ddb;
            --bg-color: #F5F6FA;     
            --text-color: #333;
            --light-text: #fff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: var(--bg-color);
            overflow: hidden;
        }

        .container {
            position: relative;
            width: 850px;
            height: 550px;
            background: #fff;
            border-radius: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin: 10px;
        }

        .form-box {
            position: absolute;
            right: 0;
            width: 50%;
            height: 100%;
            background: #fff;
            display: flex;
            align-items: center;
            color: #333;
            text-align: center;
            padding: 40px;
            z-index: 1;
            transition: .6s ease-in-out 1.2s, visibility 0s 1s;
        
        }

        .container.active .form-box {
            right: 50%;
        }

        .form-box.register {
            visibility: hidden;
        }

        .container.active .form-box.register {
            visibility: visible;
        }

        form {
            width: 100%;
        
        }

        h1 {
            font-size: 32px;
            color: var(--primary);
            margin-bottom: 30px;
            text-align: center;
        }

       .input-box {
            position: relative;
            width: 100%;
            height: 50px;
            margin: 20px 0;
        }

        .input-box input {
            width: 100%;
            height: 100%;
            background: #f0f2f5;
            border: none;
            outline: none;
            border-radius: 10px;
            padding: 0 45px 0 15px;
            font-size: 16px;
            color: #333;
            transition: .3s;
        }

        .input-box input:focus {
            box-shadow: 0 0 0 2px var(--primary);
        }

        .input-box input::placeholder {
            color: #888;
            font-weight: 400;
        }

        .input-box i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: #888;
        }

        .error-text {
            color: #e74c3c;
            font-size: 12px;
            margin-top: -15px;
            margin-bottom: 10px;
            display: block;
            text-align: left;
        }

        .forgot-link {
            margin: -10px 0 20px;
            text-align: right;
        }

        .forgot-link a {
            color: #666;
            text-decoration: none;
            font-size: 14px;
        }

        .forgot-link a:hover { color: var(--primary); }

        .btn {
            width: 100%;
            height: 48px;
            background: var(--primary);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(93, 95, 239, 0.3);
            color: #fff;
            font-size: 16px;
            font-weight: 600;
        }

        .btn:hover { background: var(--primary-dark)}

        p {
            font-size: 14px;
            text-align: center;
            margin: 20px 0 15px;
            color: #666;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .social-icons a {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 40px;
            height: 40px;
            background: transparent;
            border: 1px solid #ccc;
            border-radius: 50%;
            color: #333;
            text-decoration: none;
            font-size: 20px;
            transition: .3s;
        }

        .social-icons a:hover {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }


        .toggle-box {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .toggle-box::before {
            content: '';
            position: absolute;
            left: -250%;
            width: 300%;
            background: linear-gradient(to right, #5D5FEF, #A5A6F6);
            height: 100%;
            border-radius: 150px;
            z-index: 2; 
            transition: 1.8s ease-in-out;
        }

        .container.active .toggle-box::before {
            left: 50%;
        }

        .toggle-panel {
            position: absolute;
            width: 50%;
            height: 100%;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 2;
            transition: .6s ease-in-out;
        }

        .toggle-panel p {
            color: #e0e0e0;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .toggle-panel h1 {
            color: #fff;
            margin-bottom: 15px;
            font-size: 2.2em;
            line-height: 1.2;
        }

        .toggle-panel.toggle-left{
            left: 0;
            transition-delay: 1.2s;
        }


        .btn.registerBtn:hover, .btn.loginBtn:hover {
            background: #fff;
            color: var(--primary);
        }

        .illustration { 
            width: 100%; 
            max-width: 350px; 
            margin-top: 20px;
            border-radius: 20px; 
            mix-blend-mode: screen; 
            margin-bottom: 10px;
        }

        .logoFrame {
            width: 200px; 
            padding: 10px 14px;
            border-radius: 20px;
            background: #e3e6eb;
            gap: 12px; 
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 10px;
        }

        .logo {
            max-width: 120px; 
            max-height: 40px;
            object-fit: contain; 
        }

        .container.active .toggle-panel.toggle-left{
            left: -50%;
            transition-delay: .6s;
        }

        .toggle-panel.toggle-right{
            right: -50%;
            transition-delay: .6s;
        }

        .container.active .toggle-panel.toggle-right{
            right: 0;
            transition-delay: 1.2s;
        }


        .toggle-panel p {
            margin-bottom: 20px;
        }

        .toggle-panel .btn {
            width: 160px;
            height: 46px;
            background: transparent;
            border: 2px solid #fff;
            box-shadow: none;
        }

        /* MOBILE RESPONSIVE */
        @media screen and (max-width: 650px) {
            .container {
                height: calc(100vh - 40px);
            }
        
            .form-box {
                width: 100%;
                height: 65%;
                bottom: 0;
                padding: 30px 25px;
            }
        
            .container.active .form-box {
                right: 0;
                bottom: 35%;
            }
        
            .toggle-box::before {
                left: 0;
                top: -285%;
                width: 100%;
                height: 320%;
                border-radius: 20vw;
            }
        
            .container.active .toggle-box::before {
                left: 0;
                top: 65%;
            }
        
            .toggle-panel {
                width: 100%;
                height: 35%;
                padding: 20px;
                justify-content: flex-start;
                padding-top: 25px;
            }
        
            .toggle-panel.toggle-left {
                top: 0;
            }
        
            .container.active .toggle-panel.toggle-left {
                left: 0;
                top: -35%;
            }
        
            .toggle-panel.toggle-right {
                right: 0;
                bottom: -35%;
            }
        
            .container.active .toggle-panel.toggle-right {
                bottom: 0;
            }

            .logoFrame {
                width: 140px;
                padding: 6px 8px;
                gap: 6px;
            }

            .logo {
                max-width: 75px;
                max-height: 28px;
            }

            .toggle-panel h1 {
                font-size: 1.5em;
                margin-bottom: 5px;
            }

            .toggle-panel p {
                font-size: 13px;
                margin-bottom: 12px;
            }

            .toggle-panel .btn {
                width: 140px;
                height: 42px;
                font-size: 15px;
            }

            .illustration {
                display: none;
            }

            /* Optimasi form for mobile */
            h1 {
                font-size: 26px;
                margin-bottom: 20px;
            }

            .input-box {
                height: 45px;
                margin: 15px 0;
            }

            .input-box input {
                font-size: 15px;
                padding: 0 40px 0 12px;
            }

            .input-box i {
                font-size: 18px;
                right: 12px;
            }

            .forgot-link {
                margin: -8px 0 15px;
            }

            .btn {
                height: 44px;
                font-size: 15px;
            }
        }

        /* EXTRA SMALL MOBILE */
        @media screen and (max-width: 400px) {
            .form-box {
                padding: 25px 20px;
            }
        
            .toggle-panel h1 {
                font-size: 1.3em;
            }

            .toggle-panel p {
                font-size: 12px;
            }

            .toggle-panel .btn {
                width: 120px;
                height: 38px;
                font-size: 14px;
            }

            h1 {
                font-size: 24px;
            }

            .input-box {
                height: 42px;
                margin: 12px 0;
            }
        }

        /* LANDSCAPE MOBILE */
        @media screen and (max-height: 500px) and (orientation: landscape) {
            .container {
                height: 95vh;
            }

            .form-box {
                height: 60%;
                padding: 20px;
                overflow-y: auto;
            }

            .container.active .form-box {
                bottom: 40%;
            }

            .toggle-panel {
                height: 40%;
                padding: 15px;
            }

            .toggle-box::before {
                top: -240%;
                height: 280%;
            }

            .container.active .toggle-box::before {
                top: 60%;
            }

            .toggle-panel.toggle-right {
                bottom: -40%;
            }

            .container.active .toggle-panel.toggle-left {
                top: -40%;
            }

            .toggle-panel h1 {
                font-size: 1.2em;
                margin-bottom: 5px;
            }

            .toggle-panel p {
                font-size: 11px;
                margin-bottom: 8px;
            }

            .logoFrame {
                width: 60px;
                padding: 6px;
            }

            h1 {
                font-size: 22px;
                margin-bottom: 15px;
            }

            .input-box {
                height: 38px;
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    
<div class="container {{ request()->routeIs('register') || $errors->has('name') ? 'active' : '' }}" id="main-container">
        
        <div class="form-box login">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <h1>Login</h1>
                
                @if ($errors->has('email') && !request()->routeIs('register'))
                    <span class="error-text" style="text-align: center; margin-bottom: 10px;">{{ $errors->first('email') }}</span>
                @endif

                <div class="input-box">
                    <input type="email" name="email" placeholder="Email" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password" placeholder="Password" required>
                    <i class='bx bxs-lock'></i>
                </div>

                <div class="forgot-link"><a href="{{ route('password.request') }}">Lupa Password?</a></div>
                <button type="submit" class="btn">Login</button>
            </form>
        </div>

        <div class="form-box register">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <h1>Pendaftaran</h1>

                <div class="input-box">
                    <input type="text" name="name" placeholder="Nama Lengkap" required>
                    <i class='bx bxs-user'></i>
                </div>
                @error('name') <span class="error-text">{{ $message }}</span> @enderror

                <div class="input-box">
                    <input type="email" name="email" placeholder="Email" required>
                    <i class='bx bxs-envelope'></i>
                </div>

                <div class="input-box">
                    <input type="password" name="password" placeholder="Password (Min. 8 Karakter)" required>
                    <i class='bx bxs-lock'></i>
                </div>
                @error('password') <span class="error-text">{{ $message }}</span> @enderror

                <div class="input-box">
                    <input type="password" name="password_confirmation" placeholder="Ulangi Password" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                
                <button type="submit" class="btn">Daftar Sekarang</button>
            </form>
        </div>

        <div class="toggle-box">
            <div class="toggle-panel toggle-left">
                <div class="logoFrame">
                    <img src="{{asset('images/logo-unirow1.png')}}" alt="Logo Unirow" class="logo">
                    <img src="{{asset('images/logo-new.png')}}" alt="Logo Unirow" class="logo">
                </div>
                <h1>Selamat Datang!</h1>
                <p>Don't have an account?</p>
                <img src="{{asset('images/vector-panel1.png')}}" alt="Login Illustration" class="illustration">
                <button class="btn registerBtn" id="loginBtn">Register</button>
            </div>

            <div class="toggle-panel toggle-right">
                <div class="logoFrame">
                    <img src="{{asset('images/logo-unirow1.png')}}" alt="Logo Unirow" class="logo">
                    <img src="{{asset('images/logo-new.png')}}" alt="Logo Unirow" class="logo">
                </div>
                <h1>Hello, MABA!</h1>
                <p>Already have an account?</p>
                <img src="{{asset('images/vector-panel2.png')}}" alt="Register Illustration" class="illustration">
                <button class="btn loginBtn" id="registerBtn">Login</button>
            </div>
        </div>

    </div>

    <script>
        const container = document.querySelector('.container');
        const registerBtn = document.querySelector('.registerBtn');
        const loginBtn = document.querySelector('.loginBtn');

        registerBtn.addEventListener('click', () => {
            container.classList.add('active');
        });

        loginBtn.addEventListener('click', () => {
            container.classList.remove('active');
        });
    </script>
</body>
</html>