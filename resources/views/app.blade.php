<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title') - Stisla</title>

    @stack('styles')

    @vite(['resources/js/app.js'])

<style>
        body {
            margin-top: 35px;
        }
    
        .bi.mobile-nav-toggle.d-xl-none {
            top: unset;
        }
    
        .support-palestine,
        .support-palestine:visited {
            position: absolute;
            left: 0;
            top: 0;
            right: 0;
            background: rgb(0, 0, 0);
            display: flex;
            justify-content: center;
            padding-top: 5px;
            padding-bottom: 5px;
            z-index: 10000;
            text-decoration: none;
            font-family: arial;
        }
    
        .support-palestine:hover,
        .support-palestine:active {
            background: black;
            display: flex;
            background: rgb(80, 80, 80);
            text-decoration: none;
        }
    
        .support-palestine__flag {
            margin-right: 10px;
        }
    
        .support-palestine__label {
            color: white;
            font-size: 12px;
            line-height: 24px;
        }
    
        .background {
            background: darkgreen;
    
            height: 21px;
        }
    
        .top {
            background: black;
            width: 40px;
            height: 8px;
            z-index: 1;
        }
    
        .middle {
            background: white;
            width: 100%;
            height: 8px;
            z-index: 1;
        }
    
        .triangle {
            background: auto;
            border-top: 12px solid transparent;
            border-bottom: 12px solid transparent;
            border-left: 20px solid red;
            z-index: 2;
            position: relative;
            top: -16px;
            left: 0;
        }
    </style>
</head>

<body class="layout-3">
    <div id="app">
        <div class="main-wrapper container">
            <span class="support-palestine" href="#" rel="nofollow noopener" title="Donate to support palestine">
                <div class="support-palestine__flag" role="img" aria-label="Flag of palestine">
                    <div class="background">
                        <div class="top"></div>
                        <div class="middle"></div>
                        <div class="triangle"></div>
                    </div>
                </div>
                <div class="support-palestine__label">#StandWithPalestine</div>
            </span>
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-secondary navbar-expand-lg">
            </nav>

            <div class="main-content" style="padding-top: 120px;">
                <section class="section">
                    @yield('content')
                </section>
            </div>
            <footer class="main-footer">
                <div class="footer-left">
                    Stisla Admin Template Implementation - <a href="https://github.com/TheArKaID/laravel-stisla-boilerplate">Laravel Stisla</a>
                </div>
            </footer>
        </div>
    </div>
    
    @stack('scripts')
</body>

</html>