{% extends "index.volt" %}

{% block title %}edit user profile{% endblock %}

{% block content %}



    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="card">
                <div class="card-header card-header-icon" data-background-color="green">
                    <i class="material-icons">perm_identity</i>
                </div>
                <div class="card-content">
                    <h4 class="card-title">edit profile
                    </h4>
                    <form id="profileInfo" method="post" action="#">

                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <div class="form-group label-floating">
                                    <label class="control-label">e-mail</label>
                                    <input type="text" value="{{ user.email }}" class="form-control" disabled>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group label-floating">
                                    <label class="control-label">first name</label>
                                    <input type="text" name="name" value="{{ user.name }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group label-floating">
                                    <label class="control-label">last name</label>
                                    <input type="text" name="surname" value="{{ user.surname }}" class="form-control">
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group label-floating">
                                    <label class="control-label">new password</label>
                                    <input type="password" name="password" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group label-floating">
                                    <div class="togglebutton">
                                        <label>
                                            <input type="checkbox" name="passwordUpdate"> update password
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group label-floating">
                                    <label class="control-label">birthday(d-m-Y)</label>
                                    <input type="text" name="birthday" class="form-control" value="{{ date('d-m-Y',strtotime( user.birthday)) }}">
                                </div>
                            </div>
                           
                        </div>

                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="alert alert-info">
                                    <span>
                                            <b> Info - </b>In order to change your profile photo, login <a
                                                href="https://gravatar.com">Gravatar</a> with {{ user.email }}</span>
                                </div>
                            </div>
                        </div>


                        <button type="submit" id="updateProfileButton" class="btn btn-success pull-right">update
                        </button>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

{% endblock %}




{% block javascript %}

    <script type="text/javascript">

        $(function () {

            $("#updateProfileButton").click(function (event) /* update options */ {
                    event.preventDefault();
                    $.ajax({
                        type: 'POST', url: "update", data: $('#profileInfo').serialize(),
                        success: function (cevap) {
                            $.notify({
                                icon: "notifications", message: cevap
                            }, {
                                type: "info", timer: 2000, placement: {from: "top", align: "center"}
                            });
                        }
                    })
                }
            );

        });
    </script>


{% endblock %}