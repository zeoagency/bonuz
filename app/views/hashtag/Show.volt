{% extends "index.volt" %}

{% block title %}bonuz details{% endblock %}


{% block content %}



    <div class="row">

        <div class="col-md-6 col-md-offset-3" style="text-align: center">
            <h4>#{{ hashtag }} tagged {{ hashtagTimeline.total_items }} times</h4>
        </div>

        <div class="col-md-8 col-md-offset-2">

            <ul id="timeline-container" class="timeline timeline-simple">

                {% for bh in hashtagTimeline.items %}
                    {% if bh.Bonuz.top_id == 0 %}

                        <li class="timeline-inverted">

                            <div class="timeline-badge">
                                <a href="{{ url('profile/' ~ bh.Bonuz.from) }}"><img
                                            style="width: 50px; height: 50px; border-top-right-radius: 50%; border-top-left-radius: 50%; border-bottom-left-radius: 50%; border-bottom-right-radius: 50%;"
                                            src="https://www.gravatar.com/avatar/{{ bh.Bonuz.Users.email|md5 }}">
                                </a>
                            </div>

                            <div class="timeline-panel">

                                <div class="timeline-heading">
                                    <span class="label label-success">+{{ getTotalPoints(bh.Bonuz.id) }}</span>
                                    {% for bd in bh.Bonuz.BonuzDetails %}
                                        <a href="{{ url('profile/' ~ bd.Users.id) }}"><span
                                                    class="label label-info">{{ bd.Users.name }}</span></a>
                                    {% endfor %}
                                </div>

                                <div class="timeline-body">
                                    <p><a href="{{ url('profile/' ~ bh.Bonuz.from) }}"><b>{{ bh.Bonuz.Users.name }}
                                                : </b></a> {{ bonuzCommentBeautify(bh.Bonuz.comment) }}
                                    </p>
                                </div>

                                <div class="comments">

                                    {% if bh.Bonuz.BonuzComments|length > 0 %}
                                        {% for bc in bh.Bonuz.BonuzComments %}
                                            <p>
                                                <a href="{{ url('profile/' ~ bc.from) }}">
                                                    <b>{{ bc.Users.name }}: </b>
                                                </a>
                                                {{ bonuzCommentBeautify(bc.comment) }}
                                            </p>
                                            <hr>
                                        {% endfor %}
                                    {% endif %}

                                    <div id="realComment-{{ bh.Bonuz.id }}" class="row realComment"
                                         style="display: none">
                                        <div class="col-md-10">
                                            <div id="comment-{{ bh.Bonuz.id }}" class="addComment commentDiv"
                                                 placeholder="add comment"
                                                 contenteditable="true"></div>
                                        </div>
                                        <div class="col-md-2" style="margin-left: inherit; margin-top: -7px">
                                            <button id="{{ bh.Bonuz.id }}"
                                                    class="btn btn-success btn-round addComment addCommentButton">
                                                add
                                            </button>
                                        </div>
                                    </div>

                                    <div id="fakeComment-{{ bh.Bonuz.id }}" class="row fakeComment">
                                        <div class="col-md-12 addComment">
                                            <div class="addComment" id="{{ bh.Bonuz.id }}" placeholder="add comment"
                                                 contenteditable="true"></div>
                                        </div>
                                    </div>

                                </div>

                                <h6>
                                    <a href="{{ url('bonuz/' ~ bh.Bonuz.id) }}" class="bonuzTime">
                                        {{ bonuzDateBeautify(bh.Bonuz.date) }}
                                    </a>
                                </h6>

                            </div>

                        </li>

                    {% endif %}
                {% endfor %}

            </ul>

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




            var maxPages = {{ hashtagTimeline.last }};
            var page = 1;
            var hashtagId = {{ hashtagId }};

            $(window).scroll(function () {
                if ($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
                    page += 1;
                    if (page <= maxPages) {

                        console.log(page);

                        $('.loader').show();
                        $.ajax({
                            url: '{{ url('hashtag/loadmore?page=') }}' + page + '&hashtagId=' + hashtagId,
                            success: function (data) {
                                $('#timeline-container').append(data);
                                $('.loader').hide();
                            }
                        });
                    }
                }
            });



            $(document).on("click", ".commentDiv", function () {
                $(".commentDiv").tagautocomplete({
                    source: [{{ commentAutoComplete }}],
                    character: '#'
                });
            });

            $(document).click(function (e) {

                if (!$(e.target).hasClass('addComment')) { /* close bonuz comment area */
                    $(".realComment").hide();
                    $(".fakeComment").show();
                } else {
                    var bonuzId = $(e.target).attr('id');
                    $("#fakeComment-" + bonuzId).hide();
                    $("#realComment-" + bonuzId).fadeIn("slow");
                }
            });


            $(document).on('click', '.addCommentButton', function (e) {

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

        });

    </script>



{% endblock %}