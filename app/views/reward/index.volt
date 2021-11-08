{% extends "index.volt" %}

{% block title %}pick reward{% endblock %}


{% block content %}


<div class="nav-center">
    <ul class="nav nav-pills nav-pills-warning nav-pills-icons" role="tablist">
        <li class="active">
            <a href="#rewards" role="tab" data-toggle="tab">
                <i class="material-icons">card_giftcard</i> rewards
            </a>
        </li>
        <li>
            <a href="#history" role="tab" data-toggle="tab">
                <i class="material-icons">history</i> history
            </a>
        </li>
        <li>
            <a href="#faq" role="tab" data-toggle="tab">
                <i class="material-icons">help_outline</i> FAQ
            </a>
        </li>

    </ul>
</div>

<div class="tab-content">

    <div class="tab-pane active" id="rewards">


        <div class="row" style="text-align: center;margin-top: 20px">
            <h4>You have ♡{{ accountBonuz }} to spend</h4>

            {{ flash.output() }}

        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">reward list</h4>
            </div>
            <div class="card-content">

                <div class="material-datatables">
                    <table id="datatables2" class="table table-striped table-no-bordered table-hover" cellspacing="0"
                        width="100%" style="width:100%">
                        <thead>
                            <tr>
                                <th>name</th>
                                <th>description</th>
                                <th>price</th>
                                <th>action</th>
                            </tr>
                        </thead>
                        <tbody>

                            {% for reward in rewards %}
                            <tr>
                                <td>{{ reward.name }}</td>
                                <td>{{ reward.description }}</td>
                                <td>{{ reward.quantity }}</td>
                                <td>
                                    <btn class="btn btn-twitter" onclick="buyItem('{{ reward.id }}')">BUY</btn>
                                </td>
                            </tr>
                            {% endfor %}
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>

    <div class="tab-pane" id="history">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">order history</h4>
            </div>
            <div class="card-content">

                <div class="material-datatables">
                    <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0"
                        width="100%" style="width:100%">
                        <thead>
                            <tr>
                                <th>#id</th>
                                <th>date</th>
                                <th>name</th>
                                <th>price</th>
                                <th>status</th>
                            </tr>
                        </thead>
                        <tbody>

                            {% for s in spents %}
                            <tr>
                                <td>{{ s.id }}</td>
                                <td>{{ s.date }}</td>
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
                            </tr>
                            {% endfor %}
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>

    <div class="tab-pane" id="faq">
        <div class="row" style="text-align: center;margin-top: 20px">
            <h4>bonuz listemiz</h4>
            - Gider gösterilebilecek elektronik eşyalar, ev eşyaları<br>
            - Uçak bileti<br>
            - Konaklama<br>
            - Grupanya fırsatları<br>
            - MSA gibi kurumsal fatura kesebilecek eğitim yerlerinden eğitim<br>
            - English Ninjas, Cambly gibi kurumsal fatura kesebilecek yerlerden eğitim<br>
            - Yurtdışı konferans bileti<br>
            - Her türlü kurumsal fatura alabileceğimiz seçenek de önceden fikir alınmak koşulu ile değerlendirilecektir.<br>
        </div>
    </div>

</div>

{% endblock %}


{% block javascript %}

<!--  DataTables.net Plugin    -->
{{ javascript_include("js/jquery.datatables.js") }}

<script>

    $(document).ready(function () {
        $(this).scrollTop(0);
    });

    function buyItem(rewardId) {

        swal({
            title: 'Are you sure?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            confirmButtonText: 'Yes, i want it!',
            buttonsStyling: false
        }).then(function () {

            $.post("rewards/buyitem", { rewardId: rewardId })
                .done(function (result) {

                    if (result == 200) {

                        location.reload();


                    } else {
                        $.notify({
                            icon: "notifications", message: result
                        }, {
                            type: "warning", timer: 2000, placement: { from: "top", align: "center" }
                        });

                    }
                });

        });

    }


    $('#datatables').DataTable({
        "pagingType": "full_numbers",
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "order": [[0, "desc"]],
        responsive: true,
        language: { search: "_INPUT_", searchPlaceholder: "search order" }
    });

    $('#datatables2').DataTable({
        "pagingType": "full_numbers",
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "order": [[2, "asc"]],
        responsive: true,
        language: { search: "_INPUT_", searchPlaceholder: "search order" }
    });


</script>

{% endblock %}