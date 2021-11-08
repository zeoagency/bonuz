<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>{% block title %}{% endblock %} - bonuz.zeolabs.com</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

    <!-- Bootstrap core CSS     -->
    {{ stylesheet_link("css/bootstrap.min.css") }}

    <!--  Material Dashboard CSS    -->
    {{ stylesheet_link("css/material-dashboard.css") }}

    {{ stylesheet_link("css/demo.css") }}

    <!--     Fonts and icons     -->
    {{ stylesheet_link("http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css", false) }}
    {{ stylesheet_link("https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons", false) }}

    {% block css %}{% endblock %}

</head>

<body>

    <div class="main-panel" style="width: 100%">

        <nav class="navbar navbar-primary navbar-transparent navbar-absolute">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" style="color: black" href="/">bonuz @zeolabs</a>
                </div>
            </div>
        </nav>

        <div class="content">
            <div class="container">
                {% block content %} {% endblock %}
            </div>
        </div>

        <footer class="footer">
            <div class="container-fluid">
                <p class="copyright pull-right">
                    &copy; bonuz.zeolabs.com
                </p>
            </div>
        </footer>

    </div>

</body>



<!--   Core JS Files   -->
{{ javascript_include("js/jquery-3.1.1.min.js") }}
{{ javascript_include("js/jquery-ui.min.js") }}
{{ javascript_include("js/bootstrap.min.js") }}
{{ javascript_include("js/material.min.js") }}
{{ javascript_include("js/perfect-scrollbar.jquery.min.js") }}

{{ javascript_include("js/jquery.bootstrap-wizard.js") }}
{{ javascript_include("js/jquery.validate.min.js") }}

<!--  Notifications Plugin    -->
{{ javascript_include("js/bootstrap-notify.js") }}

<!-- Select Plugin -->
{{ javascript_include("js/jquery.select-bootstrap.js") }}

<!-- Sweet Alert 2 plugin -->
{{ javascript_include("js/sweetalert2.js") }}

<!-- Material Dashboard javascript methods -->
{{ javascript_include("js/material-dashboard.js", true) }}



{% block javascript %}{% endblock %}



</html>
