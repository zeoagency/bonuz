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
