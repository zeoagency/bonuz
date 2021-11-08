{% extends "index.volt" %}

{% block title %}{{ user.name }}'s profile{% endblock %}


{% block content %}

    <div class="row">

        <div class="col-md-8">

            <div class="nav-center">
                <ul class="nav nav-pills nav-pills-warning nav-pills-icons" role="tablist">
                    <li class="active">
                        <a href="#received" role="tab" data-toggle="tab">
                            <i class="material-icons">call_received</i> {{ receivedBonuzs.total_items }} bonuzes received
                        </a>
                    </li>
                    <li>
                        <a href="#given" role="tab" data-toggle="tab">
                            <i class="material-icons">call_made</i> {{ givenBonuzs.total_items }} bonuzes given
                        </a>
                    </li>
                </ul>
            </div>


            <div class="tab-content">
                <div class="tab-pane active" id="received">
                    <div class="card-content">

                        <ul id="timeline-container-received" class="timeline timeline-simple">


                            {% for rb in receivedBonuzs.items %}
                                <li class="timeline-inverted">


                                    <div class="timeline-badge">
                                        <a href="{{ url('profile/' ~ rb.Bonuz.Users.id) }}"><img
                                                    style="width: 50px; height: 50px; border-top-right-radius: 50%; border-top-left-radius: 50%; border-bottom-left-radius: 50%; border-bottom-right-radius: 50%;"
                                                    src="https://www.gravatar.com/avatar/{{ rb.Bonuz.Users.email|md5 }}">
                                        </a>
                                    </div>

                                    <div class="timeline-panel">
                                        <div class="timeline-heading">
                                            <span class="label label-success">+{{ getTotalPoints(rb.Bonuz.id) }}</span>
                                            {% for bd in rb.Bonuz.BonuzDetails %}
                                                <a href="{{ url('profile/' ~ bd.Users.id) }}"><span
                                                            class="label label-info">{{ bd.Users.name }}</span></a>
                                            {% endfor %}
                                        </div>

                                        <div class="timeline-body">
                                            <p><a href="{{ url('profile/' ~ rb.Bonuz.Users.id) }}"><b>{{ rb.Bonuz.Users.name }}
                                                        : </b></a> {{ bonuzCommentBeautify(rb.Bonuz.comment) }}
                                            </p>
                                        </div>

                                        <div class="comments">

                                            {% if rb.Bonuz.BonuzComments|length > 0 %}
                                                {% for bc in rb.Bonuz.BonuzComments %}
                                                    <p>
                                                        <a href="{{ url('profile/' ~ bc.from) }}">
                                                            <b>{{ bc.Users.name }}: </b>
                                                        </a>
                                                        {{ bonuzCommentBeautify(bc.comment) }}
                                                    </p>
                                                    <hr>
                                                {% endfor %}
                                            {% endif %}

                                            <div id="realComment-{{ rb.Bonuz.id }}" class="row realComment"
                                                 style="display: none">
                                                <div class="col-md-10">
                                                    <div id="comment-{{ rb.Bonuz.id }}" class="addComment commentDiv"
                                                         placeholder="add comment"
                                                         contenteditable="true"></div>
                                                </div>
                                                <div class="col-md-2" style="margin-left: inherit; margin-top: -7px">
                                                    <button id="{{ rb.Bonuz.id }}"
                                                            class="btn btn-success btn-round addComment addCommentButton">
                                                        add
                                                    </button>
                                                </div>
                                            </div>

                                            <div id="fakeComment-{{ rb.Bonuz.id }}" class="row fakeComment">
                                                <div class="col-md-12 addComment">
                                                    <div class="addComment" id="{{ rb.Bonuz.id }}" placeholder="add comment"
                                                         contenteditable="true"></div>
                                                </div>
                                            </div>

                                        </div>

                                        <h6>
                                            <a href="{{ url('bonuz/' ~ rb.Bonuz.id) }}" class="bonuzTime">
                                                {{ bonuzDateBeautify(rb.Bonuz.date) }}
                                            </a>
                                        </h6>



                                    </div>

                                </li>
                            {% endfor %}

                        <div class="loader" style="margin: 0 auto; display: none"></div>

                    </div>
                </div>

                <div class="tab-pane" id="given">
                    <div class="card-content">

                        <ul id="timeline-container-given" class="timeline timeline-simple">


                            {% for bonuz in givenBonuzs.items %}
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

                    </div>
                </div>
            </div>


        </div>


        <div class="col-md-4">

            <div class="card card-profile">
                <div class="card-avatar">
                    <img class="img" src="https://www.gravatar.com/avatar/{{ user.email|md5 }}">
                </div>
                <div class="card-content">
                    <h4 class="card-title">
                        {{ user.name }} {{ user.surname }}</a>
                    </h4>
                    <p>{{ user.email }}</p>
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



            $(document).on("click",".commentDiv", function(){
                $(".commentDiv").tagautocomplete({
                    source: [{{ commentAutoComplete }}],
                    character: '#'
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

                $.post("{{ url('bonuz/comment') }}", {bonuzId: bonuzId, message: message})
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



            var mpR = {{ receivedBonuzs.last }};
            var mpG = {{ givenBonuzs.last }};
            var pgR = 1;
            var pgG = 1;

            var profileId = {{ user.id }};

            $(window).scroll(function () {
                if ($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
                    $('.tab-pane').each(function () {
                        if ($(this).hasClass('active')) {

                            if($(this).attr('id') == "received")
                            {
                                pgR += 1;
                                if (pgR <= mpR) {
                                    $('.loader').show();
                                    $.ajax({
                                        url: '{{ url('getUserReceivedBonuzs?page=') }}' + pgR + '&profileId=' + profileId,
                                        success: function (data) {
                                            $('#timeline-container-received').append(data);
                                            $('.loader').hide();
                                        }
                                    });
                                }

                            }else{


                                pgG += 1;
                                if (pgG <= mpG) {
                                    $('.loader').show();
                                    $.ajax({
                                        url: '{{ url('getUserGivenBonuzs?page=') }}' + pgG + '&profileId=' + profileId,
                                        success: function (data) {
                                            $('#timeline-container-given').append(data);
                                            $('.loader').hide();
                                        }
                                    });
                                }



                            }

                        }
                    });
                }
            });


        });

    </script>



{% endblock %}