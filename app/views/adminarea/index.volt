{% extends "index.volt" %}

{% block title %}admin panel{% endblock %}

{% block content %}

<div class="content">
    <div class="container">

        <div class="row">
            <div class="col-md-12">

                <h3 class="title text-center">welcome</h3>
                <div class="nav-center">
                    <ul class="nav nav-pills nav-pills-warning nav-pills-icons" role="tablist">
                        <li class="active">
                            <a href="#generalsettings" role="tab" data-toggle="tab">
                                <i class="material-icons">settings</i> general settings
                            </a>
                        </li>
                        <li>
                            <a href="#friends" role="tab" data-toggle="tab">
                                <i class="material-icons">tag_faces</i> users
                            </a>
                        </li>
                        <li>
                                <a href="#hashtags" role="tab" data-toggle="tab">
                                    <i class="material-icons">clear_all</i> hashtags
                                </a>
                            </li> 
                        <li>
                            <a href="#rewards" role="tab" data-toggle="tab">
                                <i class="material-icons">card_giftcard</i> rewards
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content">

                    <div class="tab-pane active" id="generalsettings">
                        <div class="col-md-12">
                            <div class="card">
                                <form id="optionsForm" method="post" action="#" class="form-horizontal">
                                    <div class="card-header card-header-icon" data-background-color="green">
                                        <i class="material-icons">settings</i>
                                    </div>
                                    <div class="card-content">
                                        <h4 class="card-title">settings</h4>



                                        <div class="row">

                                            <label class="col-sm-2 label-on-left col-sm-offset-1">monthly
                                                limit</label>
                                            <div class="col-sm-2">
                                                <div class="form-group label-floating is-empty">
                                                    <input type="text" name="monthly_limit" class="form-control"
                                                        value="{{ options.monthly_limit }}">
                                                    <span class="material-input"></span></div>
                                            </div>

                                            <label class="col-sm-2 label-on-left">welcome bonus</label>
                                            <div class="col-sm-2">
                                                <div class="form-group label-floating is-empty">
                                                    <input type="text" name="welcome_bonus" class="form-control"
                                                        value="{{ options.welcome_bonus }}">
                                                    <span class="material-input"></span></div>
                                            </div>

                                            <div class="col-sm-2 col-sm-offset-1">
                                                <button id="optionsButton" class="btn btn-success">update</button>
                                            </div>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="friends">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header card-header-icon" data-background-color="green">
                                        <i class="material-icons">tag_faces</i>
                                    </div>
                                    <div class="card-content">
                                        <h4 class="card-title">users</h4>
                                        <div class="toolbar" style="text-align: center;">
                                            <button class="btn btn-simple btn-success" id="addFriend">
                                                <i class="material-icons">plus_one</i> add new user
                                                <div class="ripple-container"></div>
                                            </button>
                                        </div>
                                        <div class="material-datatables">
                                            <table id="friends-dt"
                                                class="table table-striped table-no-bordered table-hover"
                                                cellspacing="0" width="100%" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>#id</th>
                                                        <th>email</th>
                                                        <th>name surname</th>
                                                        <th>bonuz</th>
                                                        <th>limit/spent</th>
                                                        <th>discord_id</th>
                                                        <th class="disabled-sorting text-right">actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    {% for user in users %}
                                                    <tr>
                                                        <td>#{{ user['id'] }}</td>
                                                        <td>{{ user['email'] }}</td>
                                                        <td>{{ user['name'] }} {{ user['surname'] }}</td>
                                                        <td>{{ user['bonuz'] }}</td>
                                                        <td>{{ user['monthly_limit'] }} / {{ user['m_spent'] }}</td>
                                                        <td>{{ user['discord_id'] }} </td>
                                                        <td class="text-right">
                                                            {% if user['status'] == 0 %}
                                                            <a href="#"
                                                                class="btn btn-simple btn-info btn-icon status"><i
                                                                    class="material-icons"
                                                                    title="open account">tag_faces</i></a>
                                                            {% else %}
                                                            <a href="#"
                                                                class="btn btn-simple btn-info btn-icon status"><i
                                                                    class="material-icons"
                                                                    title="suspend account">block</i></a>
                                                            {% endif %}

                                                            {% if user['is_admin'] == 0 %}
                                                            <a href="#"
                                                                class="btn btn-simple btn-info btn-icon is_admin"><i
                                                                    class="material-icons"
                                                                    title="make admin">flight_takeoff</i></a>
                                                            {% else %}
                                                            <a href="#"
                                                                class="btn btn-simple btn-info btn-icon is_admin"><i
                                                                    class="material-icons"
                                                                    title="make user">flight_land</i></a>
                                                            {% endif %}
                                                            <a href="#"
                                                                class="btn btn-simple btn-info btn-icon discord_id"><i
                                                                    class="material-icons"
                                                                    title="set discord id">mood</i></a>

                                                            <a href="#"
                                                                class="btn btn-simple btn-danger btn-icon newpassword"><i
                                                                    class="material-icons"
                                                                    title="send new password">lock</i></a>
                                                        </td>
                                                    </tr>

                                                    {% endfor %}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- end content-->
                                </div>
                                <!--  end card  -->
                            </div>
                            <!-- end col-md-12 -->
                        </div>

                    </div>

                    <div class="tab-pane" id="hashtags">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header card-header-icon" data-background-color="green">
                                        <i class="material-icons">clear_all</i>
                                    </div>
                                    <div class="card-content">
                                        <h4 class="card-title">hashtags</h4>
                                        <div class="toolbar" style="text-align: center;">
                                            <button class="btn btn-simple btn-success" id="addHashtag">
                                                <i class="material-icons">plus_one</i> add new hashtag
                                                <div class="ripple-container"></div>
                                            </button>
                                        </div>
                                        <div class="material-datatables">
                                            <table id="hashtags-dt"
                                                class="table table-striped table-no-bordered table-hover"
                                                cellspacing="0" width="100%" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>#id</th>
                                                        <th>hashtag</th>
                                                        <th>action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {% for hashtag in hashtags %}
                                                    <tr>
                                                        <td>{{ hashtag.id }}</td>
                                                        <td>{{ hashtag.hashtag }}</td>
                                                        <td>
                                                            {% if hashtag.active == 0 %}
                                                            <a href="#"
                                                                class="btn btn-simple btn-info btn-icon active"><i
                                                                    class="material-icons"
                                                                    title="enable hashtag">done</i></a>
                                                            {% else %}
                                                            <a href="#"
                                                                class="btn btn-simple btn-info btn-icon active"><i
                                                                    class="material-icons"
                                                                    title="disable hashtab">block</i></a>
                                                            {% endif %}
                                                        </td>
                                                    </tr>
                                                    {% endfor %}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- end content-->
                                </div>
                                <!--  end card  -->
                            </div>
                            <!-- end col-md-12 -->
                        </div>

                    </div>

                    <div class="tab-pane" id="rewards">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header card-header-icon" data-background-color="green">
                                    <i class="material-icons">card_giftcard</i>
                                </div>
                                <div class="card-content">
                                    <h4 class="card-title">rewards</h4>

                                    <div class="row">
                                        <div class="nav-center">
                                            <ul class="nav nav-center nav-pills nav-pills-warning nav-pills-icons"
                                                role="tablist">
                                                <li class="active">
                                                    <a href="#listDemands" role="tab" data-toggle="tab">
                                                        <i class="material-icons">list</i> list demands
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#listRewards" role="tab" data-toggle="tab">
                                                        <i class="material-icons">card_giftcard</i> list rewards
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="listDemands">

                                                <div class="material-datatables">
                                                    <table id="demands-dt"
                                                        class="table table-striped table-no-bordered table-hover"
                                                        cellspacing="0" width="100%" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>#id</th>
                                                                <th>date</th>
                                                                <th>user</th>
                                                                <th>reward</th>
                                                                <th>quantity</th>
                                                                <th>status</th>
                                                                <th>action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            {% for s in spents %}
                                                            <tr>
                                                                <td>{{ s.id }}</td>
                                                                <td>{{ s.date }}</td>
                                                                <td>{{ s.Users.email }}</td>
                                                                <td>{{ s.Rewards.name }}</td>
                                                                <td>{{ s.quantity }}</td>
                                                                <td>
                                                                    {% if s.status == 0 %}
                                                                    <p class="text-info">waiting approval</p>
                                                                    {% elseif s.status == 1 %}
                                                                    <p class="text-success">approved</p>
                                                                    {% else %}
                                                                    <p class="text-danger">rejected</p>
                                                                    {% endif %}
                                                                </td>
                                                                <td class="text-right">
                                                                    {% if s.status == 0 %}
                                                                    <a href="#"
                                                                        class="btn btn-simple btn-info btn-icon approveReward"><i
                                                                            class="material-icons"
                                                                            title="approve">done</i></a>

                                                                    <a href="#"
                                                                        class="btn btn-simple btn-info btn-icon rejectReward"><i
                                                                            class="material-icons"
                                                                            title="reject">clear</i></a>

                                                                    {% elseif s.status == 1 %}
                                                                    <a href="#"
                                                                        class="btn btn-simple btn-info btn-icon rejectReward"><i
                                                                            class="material-icons"
                                                                            title="reject">clear</i></a>
                                                                    {% else %}
                                                                    <a href="#"
                                                                        class="btn btn-simple btn-info btn-icon approveReward"><i
                                                                            class="material-icons"
                                                                            title="approve">done</i></a>
                                                                    {% endif %}

                                                                </td>
                                                            </tr>
                                                            {% endfor %}
                                                        </tbody>
                                                    </table>
                                                </div>


                                            </div>
                                            <div class="tab-pane" id="listRewards">

                                                <div class="nav-center" style="margin-left: 40%">
                                                    <ul class="nav nav-pills nav-pills-success">
                                                        <li class="active">
                                                            <a href="#listallrewardsPanel" data-toggle="tab">list
                                                                all</a>
                                                        </li>
                                                        <li>
                                                            <a href="#addnewrewardPanel" data-toggle="tab">add new
                                                                reward</a>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <div class="tab-content">
                                                    <div class="tab-pane active" id="listallrewardsPanel">

                                                        <div class="material-datatables">
                                                            <table id="rewards-dt"
                                                                class="table table-striped table-no-bordered table-hover"
                                                                cellspacing="0" width="100%" style="width:100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#id</th>
                                                                        <th>name</th>
                                                                        <th>description</th>
                                                                        <th>price</th>
                                                                        <th></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    {% for reward in rewards %}
                                                                    <tr>
                                                                        <td>{{ reward.id }}</td>
                                                                        <td>{{ reward.name }}</td>
                                                                        <td>{{ reward.description }}</td>
                                                                        <td>{{ reward.quantity }}</td>
                                                                        <td class="text-right">

                                                                            <a href="#"
                                                                                class="btn btn-simple btn-info btn-icon updatePrice"
                                                                                title="update price">
                                                                                <i
                                                                                    class="material-icons">attach_money</i>
                                                                            </a>


                                                                            {% if reward.status == 0 %}
                                                                            <a href="#"
                                                                                class="btn btn-simple btn-info btn-icon status"><i
                                                                                    class="material-icons"
                                                                                    title="enable reward">done</i></a>
                                                                            {% else %}
                                                                            <a href="#"
                                                                                class="btn btn-simple btn-info btn-icon status"><i
                                                                                    class="material-icons"
                                                                                    title="disable reward">block</i></a>
                                                                            {% endif %}


                                                                        </td>
                                                                    </tr>
                                                                    {% endfor %}
                                                                </tbody>
                                                            </table>
                                                        </div>


                                                    </div>
                                                    <div class="tab-pane" id="addnewrewardPanel">
                                                        <div class="row">
                                                            <div class="col-md-8 col-md-offset-2">
                                                                <form id="addnewrewardForm" method="POST" action="#">
                                                                    <div class="form-group label-floating">
                                                                        <label class="control-label">title of
                                                                            reward</label>
                                                                        <input type="text" name="name"
                                                                            class="form-control">
                                                                    </div>
                                                                    <div class="form-group label-floating">
                                                                        <label class="control-label">description</label>
                                                                        <input type="text" name="description"
                                                                            class="form-control">
                                                                    </div>
                                                                    <div class="form-group label-floating">
                                                                        <label class="control-label">price</label>
                                                                        <input type="text" name="quantity"
                                                                            class="form-control">
                                                                    </div>
                                                                    <button id="addNewRewardButton" type="submit"
                                                                        class="btn btn-fill btn-success">add
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>


                                </div> <!-- card-content -->
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


<!--  DataTables.net Plugin    -->
{{ javascript_include("js/jquery.datatables.js") }}


<script type="text/javascript">


    $(function () {

        $("#optionsButton").click(function (event) /* update options */ {
            event.preventDefault();
            $.ajax({
                type: 'POST', url: "area/updateoptions", data: $('#optionsForm').serialize(),
                success: function (cevap) {
                    $.notify({
                        icon: "notifications", message: cevap
                    }, {
                        type: "info", timer: 2000, placement: { from: "top", align: "center" }
                    });
                }
            })
        }
        );


        /* FRIENDS */

        $("#addFriend").click(function () {
            swal({
                title: 'submit email to add a new friend',
                input: 'email',
                showCancelButton: true,
                confirmButtonText: 'say welcome',
                showLoaderOnConfirm: true,
                allowOutsideClick: true
            }).then(function (email) {
                $.post("area/addnewfriend", { email: email })
                    .done(function (res) {
                        if (res == 200) {
                            swal({ type: 'success', title: 'friend added!', html: 'mail was sent to ' + email });
                        } else {
                            swal({ type: 'warning', title: 'error :/', html: res });
                        }
                    });
            })
        }
        );

        $('#friends-dt').DataTable({
            "pagingType": "full_numbers",
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "order": [[0, "desc"]],
            responsive: true,
            language: { search: "_INPUT_", searchPlaceholder: "search friend" }
        });

        var table = $('#friends-dt').DataTable();

        table.on('click', '.status', function () {
            $tr = $(this).closest('tr');
            var data = table.row($tr).data();
            swal({ type: 'success', title: 'status updated', html: 'user status updated!' });
            $.post("area/updatestatus", { userId: data[0].replace('#','') })
                .done(function (res) {
                    if (res == 0) {
                        swal({ type: 'success', title: 'profile updated', html: 'user is disabled' });
                    } else {
                        swal({ type: 'success', title: 'profile updated', html: 'user is enabled' });
                    }
                });
        });

        table.on('click', '.is_admin', function () {
            $tr = $(this).closest('tr');
            var data = table.row($tr).data();
            swal({ type: 'success', title: 'status updated', html: 'user status updated!' });
            $.post("area/updateadmin", { userId: data[0].replace('#','') })
                .done(function (res) {
                    if (res == 0) {
                        swal({ type: 'success', title: 'profile updated', html: 'user is no more an administrator' });
                    } else {
                        swal({ type: 'success', title: 'profile updated', html: 'user is now an administrator' });
                    }
                });
        });

        table.on('click', '.newpassword', function (e) {
            $tr = $(this).closest('tr');
            var data = table.row($tr).data();
            swal({ type: 'success', title: 'password renewed!', html: 'new passwrod was sent to ' + data[1] });
            $.post("area/renewpassword", { userId: data[0].replace('#','') })
                .done(function (res) {
                    swal({ type: 'success', title: 'password updated', html: 'new password is ' + res });
                });

        });

        /* Setup discord id */
        table.on('click', '.discord_id', function () {
            $tr = $(this).closest('tr');
            var data = table.row($tr).data();
           swal({
                title: "please paste this user's discord id",
                input: 'text',
                showCancelButton: true,
                confirmButtonText: 'update',
                showLoaderOnConfirm: true,
                allowOutsideClick: true
            }).then(function (discord_id) {
                $.post("area/discord_id", { user_id:data[0].replace('#','') ,discord_id: discord_id })
                    .done(function (res) {
                        if (res == 200) {
                            swal({ type: 'success', title: 'discord id is set', html: 'Now this user can give and take bonuz from discord.' });
                          
                        } else {
                            swal({ type: 'warning', title: 'error :/', html: res });
                        }
                    });
            })
        });

        $('.card .material-datatables label').addClass('form-group');

        /* FRIENDS */


        /* HASHTAGS */
        $('#hashtags-dt').DataTable({
            "pagingType": "full_numbers",
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            aaSorting: [[0, 'desc']],
            responsive: true,
            language: { search: "_INPUT_", searchPlaceholder: "search hashtag" }
        });

        var hashtagDT = $('#hashtags-dt').DataTable();

        hashtagDT.on('click', '.active', function () {
            $tr = $(this).closest('tr');
            var data = hashtagDT.row($tr).data();
            $.post("area/updatehashtag", { hashtagId: data[0].replace('#','') })
                .done(function (res) {
                    if (res == 0) {
                        swal({ type: 'success', title: 'hashtag updated', html: 'hashtag disabled' });
                    } else {
                        swal({ type: 'success', title: 'hashtag updated', html: 'hashtag enabled' });
                    }
                });
        });

        $("#addHashtag").click(function () {
            swal({
                title: 'add new hashtag',
                input: 'text',
                showCancelButton: true,
                inputPlaceholder: "#takipedenitakipederim",
                confirmButtonText: 'insert',
                showLoaderOnConfirm: true,
                allowOutsideClick: true
            }).then(function (hashtag) {
                $.post("area/addhashtag", { hashtag: hashtag })
                    .done(function (res) {
                        if (res == 200) {
                            var last_row = hashtagDT.row(':last').data();
                            hashtagDT.row.add([parseInt(last_row[0]) + 1, hashtag]).draw();

                            swal({ type: 'success', title: 'hashtag added!', html: hashtag + ' added.' });
                        } else {
                            swal({ type: 'warning', title: 'error :/', html: res });
                        }
                    });
            })
        }
        );

        /* HASHTAGS */


        /* REWARDS */


        $('#demands-dt').DataTable({
            "pagingType": "full_numbers",
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "order": [[0, "desc"]],
            responsive: true,
            language: { search: "_INPUT_", searchPlaceholder: "search demand" }
        });


        var demandsDT = $('#demands-dt').DataTable();

        demandsDT.on('click', '.approveReward', function () {
            $tr = $(this).closest('tr');
            var data = demandsDT.row($tr).data();

            $.post("area/approveorder", { orderId: data[0].replace('#','') })
                .done(function (res) {
                    console.log(res);
                    swal({ type: 'success', title: 'demand updated', html: 'demand approved!' });
                });
        });

        demandsDT.on('click', '.rejectReward', function () {
            $tr = $(this).closest('tr');
            var data = demandsDT.row($tr).data();
            $.post("area/rejectorder", { orderId: data[0].replace('#','') })
                .done(function () {
                    swal({ type: 'success', title: 'demand updated', html: 'demand rejected!' });
                });
        });


        $('#rewards-dt').DataTable({
            "pagingType": "full_numbers",
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "order": [[0, "desc"]],
            responsive: true,
            language: { search: "_INPUT_", searchPlaceholder: "search hashtag" }
        });

        var rewardDT = $('#rewards-dt').DataTable();

        rewardDT.on('click', '.updatePrice', function () {
            $tr = $(this).closest('tr');
            var data = rewardDT.row($tr).data();

            swal({
                title: 'update price',
                input: 'text',
                inputValue: data[3],
                showCancelButton: true,
                confirmButtonText: 'update',
                showLoaderOnConfirm: true,
                allowOutsideClick: true
            }).then(function (price) {
                $.post("area/updaterewardprice", { rewardId: data[0].replace('#',''), price: price })
                    .done(function (res) {
                        console.log(res);
                        swal({ type: 'success', title: 'price updated!', html: 'reward\s price updated' });
                    });
            })

        });

        rewardDT.on('click', '.status', function () {
            $tr = $(this).closest('tr');
            var data = rewardDT.row($tr).data();
            swal({ type: 'success', title: 'status updated', html: 'reward status updated!' });
            $.post("area/updaterewardstatus", { rewardId: data[0].replace('#','') })
                .done(function (res) {
                    if (res == 0) {
                        swal({ type: 'success', title: 'reward settings updated', html: 'reward disabled' });
                    } else {

                        swal({ type: 'success', title: 'reward settings updated', html: 'reward enabled' });
                    }
                });
        });

        $("#addNewRewardButton").click(function (event) /* add new reward */ {
            event.preventDefault();
            $.ajax({
                type: 'POST', url: "area/addnewreward", data: $('#addnewrewardForm').serialize(),
                success: function (cevap) {

                    if (cevap == 200) {
                        $.notify({
                            icon: "notifications", message: "reward successfully created"
                        }, {
                            type: "info", timer: 2000, placement: { from: "top", align: "center" }
                        });
                    } else {
                        $.notify({
                            icon: "notifications", message: cevap
                        }, {
                            type: "warning", timer: 2000, placement: { from: "top", align: "center" }
                        });
                    }

                }
            })
        }
        );


        /* REWARDS */


    });
</script>

{% endblock %}