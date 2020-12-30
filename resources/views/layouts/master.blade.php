<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Xtrine Shop</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="/css/app.css">
    </head>
    <body>
        <div id="app">
            <nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light custom-nav" style="background-color: #e3f2fd;">
                <a class="navbar-brand" href="#">Xtrine Shop</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarText">
                  <!-- Left navbar links -->
                  <ul class="navbar-nav">
                    <li class="nav-item d-none d-sm-inline-block">
                      <a href="index3.html" class="nav-link">Products</a>
                    </li>
                    <li class="nav-item d-none d-sm-inline-block">
                      <a href="#" class="nav-link">Transactions</a>
                    </li>
                  </ul>
                  <!-- Right navbar links -->
                  <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>
                    
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                            </a>
                    
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                  </ul>
                </div>
            </nav>
                <br>
                <br>
                <router-view></router-view>
                <!-- set progressbar -->
                <vue-progress-bar></vue-progress-bar>
        </div>
    </body>
    <script src="/js/app.js"></script>
  </html>