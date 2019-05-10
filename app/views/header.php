<html>
<head>
    <title>4IZ278</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width = device-width, initial-scale = 1">
    <base href="/~novp19/"/>
    <script src="public/js/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="public/bootstrap/js/bootstrap.min.js"></script>
    <script src="public/js/script.js"></script>
    <link rel="stylesheet" type="text/css" href="public/css/styles.css"/>
    <link rel="stylesheet" type="text/css" href="public/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
          integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf"
          crossorigin="anonymous">
</head>
<body>
<div id="header">

    <nav class="navigace navbar navbar-expand-md navbar-dark bg-dark">

        <button class="navbar-toggler"
                data-toggle="collapse"
                data-target="#collapse_target"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="collapse_target">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="index" class="nav-link">Dashboard</a>
                </li>
                <li class="nav-item dropdown">
                    <a href="#"
                       class="nav-link dropdown-toggle"
                       data-toggle="dropdown"
                       data-target="#employees_target">Pracovníci
                    <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="employees_target">
                        <a href="employees" class="dropdown-item">Přehled</a>
                        <a href="employees/create" class="dropdown-item">Nový pracovník</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a href="#"
                       class="nav-link dropdown-toggle"
                       data-toggle="dropdown"
                       data-target="#departments_target">Oddělení
                        <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="departments_target">
                        <a href="departments" class="dropdown-item">Přehled</a>
                        <a href="departments/create" class="dropdown-item">Nové oddělení</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a href="#"
                       class="nav-link dropdown-toggle"
                       data-toggle="dropdown"
                       data-target="#events_target">Akce
                        <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="events_target">
                        <a href="events" class="dropdown-item">Přehled</a>
                        <a href="events/create" class="dropdown-item">Nová akce</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</div>

<div id="content">
<div class="container">
