{% extends "index.volt" %}

{% block title %}user dashboard{% endblock %}


{% block content %}

    <div class="content">
        <div class="container">

            <div class="row">
                <div class="col-md-8">

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                you have ♡{{ user.monthly_limit - totalSpentBonuz }} to give away
                            </h4>
                        </div>
                        <div class="card-content">
                            <div class="row">
                                <div class="col-md-10">

                                    <div id="example" placeholder="+10 @someone for something #hashtag"
                                         contenteditable="true"></div>
                                </div>
                                <div class="col-md-2" style="margin-left: -10px; margin-top: -7px">
                                    <button id="giveButton" class="btn btn-success btn-round"><i class="material-icons">favorite</i>
                                        give
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <ul id="timeline-container" class="timeline timeline-simple">
                        <!-- timeline goes here -->

                        {% for bonuz in bonuzs.items %}
                            <li class="timeline-inverted">

                                <div class="timeline-badge">
                                    <a href="{{ url('profile/' ~ bonuz.from) }}"><img
                                                style="width: 50px; height: 50px; border-top-right-radius: 50%; border-top-left-radius: 50%; border-bottom-left-radius: 50%; border-bottom-right-radius: 50%;"
                                                src="https://www.gravatar.com/avatar/{{ bonuz.Users.email|md5 }}">
                                    </a>
                                </div>

                                <div class="timeline-panel">

                                    <div class="timeline-heading">
                                        <span class="label label-success">+{{ getTotalPoints(bonuz.id) }}</span>
                                        {% for bd in bonuz.BonuzDetails %}
                                            <a href="{{ url('profile/' ~ bd.Users.id) }}"><span
                                                        class="label label-info">{{ bd.Users.name }}</span></a>
                                        {% endfor %}
                                    </div>

                                    <div class="timeline-body">
                                        <p><a href="{{ url('profile/' ~ bonuz.from) }}"><b>{{ bonuz.Users.name }}
                                                    : </b></a> {{ bonuzCommentBeautify(bonuz.comment) }}
                                        </p>
                                    </div>

                                    <div class="comments">

                                        {% if bonuz.BonuzComments|length > 0 %}
                                            {% for bc in bonuz.BonuzComments %}
                                                <p>
                                                    <a href="{{ url('profile/' ~ bc.from) }}">
                                                        <b>{{ bc.Users.name }}: </b>
                                                    </a>
                                                    {{ bonuzCommentBeautify(bc.comment) }}
                                                </p>
                                                <hr>
                                            {% endfor %}
                                        {% endif %}

                                        <div id="realComment-{{ bonuz.id }}" class="row realComment"
                                             style="display: none">
                                            <div class="col-md-10">
                                                <div id="comment-{{ bonuz.id }}" class="addComment commentDiv"
                                                     placeholder="add comment"
                                                     contenteditable="true"></div>
                                            </div>
                                            <div class="col-md-2" style="margin-left: inherit; margin-top: -7px">
                                                <button id="{{ bonuz.id }}"
                                                        class="btn btn-success btn-round addComment addCommentButton">
                                                    add
                                                </button>
                                            </div>
                                        </div>

                                        <div id="fakeComment-{{ bonuz.id }}" class="row fakeComment">
                                            <div class="col-md-12 addComment">
                                                <div class="addComment" id="{{ bonuz.id }}" placeholder="add comment"
                                                     contenteditable="true"></div>
                                            </div>
                                        </div>

                                    </div>

                                    <h6>
                                        <a href="{{ url('bonuz/' ~ bonuz.id) }}" class="bonuzTime">
                                            {{ bonuzDateBeautify(bonuz.date) }}
                                        </a>
                                    </h6>


                                </div>

                            </li>
                        {% endfor %}

                    </ul>

                    <div class="loader" style="margin: 0 auto; display: none"></div>
                    <div id="nomoreitems"></div>

                </div>


                <div class="col-md-4">
                    <div class="card card-profile">
                        <div class="card-avatar">
                            <img class="img" src="https://www.gravatar.com/avatar/{{ user.email|md5 }}">
                        </div>
                        <div class="card-content">
                            <h4 class="card-title">
                                <a href="profile/{{ user.id }}" class="btn btn-simple btn-behance">
                                    {{ user.name }} {{ user.surname }}
                                </a>
                            </h4>
                            <div class="row">
                                <div class="col-sm-6">
                                    <a href="profile/settings" class="btn btn-simple btn-behance"><i
                                                class="material-icons">settings</i> settings</a>
                                </div>
                                <div class="col-sm-6">
                                    <a href="logout" class="btn btn-behance btn-simple"><i class="material-icons">exit_to_app</i>
                                        logout</a>
                                </div>
                            </div>

                            {% if user.is_admin == 1 %}
                                <div class="row">
                                    <div class="col-sm-6 col-md-offset-3">
                                        <a href="{{ url('admin') }}" class="btn btn-simple btn-behance"><i
                                                    class="material-icons">airplanemode_active</i> admin area</a>
                                    </div>
                                </div>
                            {% endif %}

                            <hr>

                            <h4>You have ♡{{ accountBonuz }} to redeem</h4>
                            <p class="description">
                                <a href="rewards" class="btn btn-primary btn-round">
                                    <i class="material-icons">card_giftcard</i> pick a reward
                                </a>
                            </p>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">#TRENDINGTOPICS</h4>
                        </div>
                        <div class="card-content">
                            <div class="row">
                                <div class="card-content table-responsive">
                                    <table class="table table-hover">
                                        <tbody>
                                        {% for tt in trendingTopics %}
                                            <tr>
                                                <td><a href="hashtag/{{ str_replace("#", "", tt['hashtag']) }}">{{ tt['hashtag'] }}</a></td>
                                                <td class="text-right">{{ tt['cnt'] }}</td>
                                            </tr>
                                        {% endfor %}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="card">
                            <div class="card-content">
                                <div class="row">
                                    <div class="card-content">
                                        <div class="col-md-6">
                                            <div class="radio">
                                                <label><input id="showTopReceivers" type="radio" name="optionsRadios"
                                                              checked="true">top receivers</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="radio">
                                                <label><input id="showTopGenerous" type="radio" name="optionsRadios">most
                                                    generous</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-10 col-md-offset-1">
                                        <div class="card-content table-responsive topReceivers">
                                            <table class="table table-hover">
                                                <tbody>
                                                {% for tr in topReceivers %}
                                                    <tr>
                                                        <td><a href="profile/{{ tr['uId'] }}">{{ tr['uName'] }}</a></td>
                                                        <td class="text-right">{{ tr['cnt'] }}</td>
                                                    </tr>
                                                {% endfor %}
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="card-content table-responsive topGenerous" style="display: none">
                                            <table class="table table-hover">
                                                <tbody>
                                                {% for tg in topGenerous %}
                                                    <tr>
                                                        <td><a href="profile/{{ tg['uId'] }}">{{ tg['uName'] }}</a></td>
                                                        <td class="text-right">{{ tg['cnt'] }}</td>
                                                    </tr>
                                                {% endfor %}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>


        </div>
    </div>

{% endblock %}


{% block javascript %}

    {{ javascript_include("js/bootstrap-typeahead.js") }}
    {{ javascript_include("js/rangy-core.js") }}
    {{ javascript_include("js/caret-position.js") }}
    {{ javascript_include("js/bootstrap-tagautocomplete.js") }}

    <script type="text/javascript">

        $(function () {

            $('div#example').tagautocomplete({
                source: [{{ autoComplete }}],
                character: '@#'
            });


            $(document).on("click",".commentDiv", function(){
                $(".commentDiv").tagautocomplete({
                    source: [{{ commentAutoComplete }}],
                    character: '#'
                });
            });




            $("#giveButton").click(function (e) {
                e.preventDefault();
                $("#giveButton").prop('disabled', true);
                var message = $("div#example").text();

                $.post("bonuz/addnew", {message: message})
                    .done(function (result) {
                        $("#giveButton").prop('disabled', false);

                        if (result == 200) {
                            location.reload();
                        } else {
                            $.notify({
                                icon: "notifications", message: result
                            }, {
                                type: "warning", timer: 2000, placement: {from: "top", align: "center"}
                            });

                        }
                    });
            });


            $(document).click(function (e) {

                console.log($(e.target).attr('class'));

                if (!$(e.target).hasClass('addComment')) { /* close bonuz comment area */
                    $(".realComment").hide();
                    $(".fakeComment").show();
                } else {
                    var bonuzId = $(e.target).attr('id');
                    $("#fakeComment-" + bonuzId).hide();
                    $("#realComment-" + bonuzId).fadeIn("slow");
                }
            });



            $(document).on('click', '.addCommentButton', function(e){

   

                e.preventDefault();
                $(".addCommentButton").prop('disabled', true);
                var bonuzId = $(e.target).attr('id');
                var message = $("#comment-" + bonuzId).text();

                $.post("bonuz/comment", {bonuzId: bonuzId, message: message})
                    .done(function (result) {
                        $("#giveButton").prop('disabled', false);

                        if (result == 200) {
                            window.location.replace('{{ url('bonuz/') }}' + bonuzId);
                        } else {
                            $.notify({
                                icon: "notifications", message: result
                            }, {
                                type: "warning", timer: 2000, placement: {from: "top", align: "center"}
                            });

                        }
                        $(".addCommentButton").prop('disabled', false);

                    });
            });


            var maxPages = {{ bonuzs.last }};
            var page = 1;

            $(window).scroll(function () {
                if ($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
                    page += 1;
                    if (page <= maxPages) {
                        $('.loader').show();
                        $.ajax({
                            url: '{{ url('gettimeline?page=') }}' + page,
                            success: function (data) {
                                $('#timeline-container').append(data);
                                $('.loader').hide();
                            }
                        });
                    }
                }
            });


            $("#showTopGenerous").click(function () {
                $('.topGenerous').show();
                $('.topReceivers').hide();
            });

            $("#showTopReceivers").click(function () {
                $('.topReceivers').show();
                $('.topGenerous').hide();
            });


        });
    </script>


{% endblock %}