{% extends "index.volt" %}

{% block title %}login{% endblock %}

{% block content %}

    <div class="row">
        <div class="col-md-4 col-sm-6 col-md-offset-4 col-sm-offset-3">
            <form method="post" action="login">
                <div class="card card-login card-hidden">
                    <div class="card-header text-center" data-background-color="green">
                        <h4 class="card-title">log in to bonuz</h4>
                    </div>
                    <div class="card-content">

                        <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">people</i>
                                                </span>
                            <div class="form-group label-floating">
                                <label class="control-label">e-mail</label>
                                <input type="text" name="email" class="form-control">
                            </div>
                        </div>
                        <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">lock_outline</i>
                                                </span>
                            <div class="form-group label-floating">
                                <label class="control-label">password</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                        </div>

                        <div class="input-group">
                            <div class="togglebutton">
                                <label>
                                    <input type="checkbox" name="remember" checked="">
                                    remember me
                                </label>
                            </div>
                        </div>

                    </div>
                    <div class="footer text-center">
                        <button type="submit" class="btn btn-rose btn-simple btn-wd btn-lg">let's go</button>
                    </div>

                    {{ flash.output() }}


                </div>
            </form>
        </div>
    </div>

{% endblock %}


{% block javascript %}

    <script type="text/javascript">
        $().ready(function() {

            setTimeout(function() {
                $('.card').removeClass('card-hidden');
            }, 700)
        });
    </script>

{% endblock %}
