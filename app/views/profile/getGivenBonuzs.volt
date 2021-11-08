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
