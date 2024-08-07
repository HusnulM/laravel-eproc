@extends('layouts/App')

@section('title', 'Laporan History Stock')

@section('additional-css')
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                    <div class="card-tools">

                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="{{ url('report/exportstockhistory') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-2">
                                        <label for="Warehouse">Warehouse</label>
                                        <select name="Warehouse" id="Warehouse" class="form-control">
                                            <option value="0">--Select Warehouse--</option>
                                            @foreach ($warehouse as $row)
                                                <option value="{{ $row->id }}">{{ $row->whsname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="">Start Date</label>
                                        <input type="date" class="form-control" name="datefrom" id="datefrom" value="{{ $_GET['datefrom'] ?? date('Y-m-d') }}" required>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="">End Date</label>
                                        <input type="date" class="form-control" name="dateto" id="dateto" value="{{ $_GET['dateto'] ?? date('Y-m-d') }}" required>
                                    </div>
                                    <div class="col-lg-6" style="text-align:right;">
                                        <button type="button" class="btn btn-default mt-2 btn-search">
                                            <i class="fa fa-search"></i> Filter
                                        </button>
                                        <button type="submit" class="btn btn-success mt-2 btn-export pull-right">
                                            <i class="fa fa-download"></i> Export Data
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="table-responsive">
                            <table id="tbl-budget-list" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                <thead>
                                    <th>No</th>
                                    <th>Part Number</th>
                                    <th>Description</th>
                                    <th>Warehouse</th>
                                    <th>Begin QTY</th>
                                    <th>IN</th>
                                    <th>OUT</th>
                                    <th>End Qty</th>
                                    <th>Unit</th>
                                    <th></th>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('additional-modal')
<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="matMoveDetail">
    <div class="modal-dialog modal-xl">
        <form class="form-horizontal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalApprovalTitle">Material History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="position-relative row form-group">
                    <div class="col-lg-12">
                        <table id="tbl-matmove-list" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                            <thead>
                                <th>No</th>
                                <th>Docnum</th>
                                <th>Year</th>
                                <th>Date</th>
                                <th>Material</th>
                                <th>Description</th>
                                <th>Warehouse</th>
                                {{-- <th>WHS Dest</th> --}}
                                <th>Quantity</th>
                                <th>Unit</th>
                                <th>Remark</th>
                                <th>Trans Note</th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <form action="{{ url('report/exportstockhistorydetails') }}" method="post">
                    @csrf
                    {{-- <div class="row">
                        <input type="hidden" name="dtlDate1" id="dtlDate1">
                        <input type="hidden" name="dtlDate2" id="dtlDate2">
                        <input type="hidden" name="whsCode1" id="whsCode1">
                        <input type="hidden" name="Material1" id="Material1">
                        <div style="text-align:right;">
                            <button type="submit" class="btn btn-success mt-2 btn-export pull-right">
                                <i class="fa fa-download"></i> Export Data
                            </button>
                        </div>
                    </div> --}}
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="submit-approval"> OK</button>
                </form>
            </div>
        </div>
        </form>
    </div>
</div>
@endsection

@section('additional-js')
<script>
    function validate(evt) {
        var theEvent = evt || window.event;

        // Handle paste
        if (theEvent.type === 'paste') {
            key = event.clipboardData.getData('text/plain');
        } else {
        // Handle key press
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode(key);
        }
        var regex = /[0-9]|\./;
        if( !regex.test(key) ) {
            theEvent.returnValue = false;
            if(theEvent.preventDefault) theEvent.preventDefault();
        }
    }

    $(document).ready(function(){
        let _token   = $('meta[name="csrf-token"]').attr('content');

        $('.btn-search').on('click', function(){
            var param = '?whsid='+$('#Warehouse').val()+'&datefrom='+ $('#datefrom').val() +'&dateto='+ $('#dateto').val();
            loadDocument(param);
        });

        // loadDocument('');
        $("#tbl-budget-list").DataTable();

        function loadDocument(_params){
            $("#tbl-budget-list").DataTable({
                serverSide: true,
                ajax: {
                    url: base_url+'/report/stockhistorylist'+_params,
                    data: function (data) {
                        data.params = {
                            sac: "sac"
                        }
                    }
                },
                buttons: false,
                searching: true,
                scrollY: 500,
                scrollX: true,
                scrollCollapse: true,
                bDestroy: true,
                columns: [
                    { "data": null,"sortable": false, "searchable": false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {data: "material", className: 'uid'},
                    {data: "matdesc", className: 'uid'},
                    {data: "whsname", className: 'uid'},
                    {data: "begin_qty", className: 'uid', "className": "text-right"},
                    {data: "qty_in", "sortable": false,
                        render: function (data, type, row){
                            return ``+ row.qty_in.in + ``;
                        },
                        "className": "text-right",
                    },
                    {data: "qty_out", "sortable": false,
                        render: function (data, type, row){
                            return ``+ row.qty_out.out + ``;
                        },
                        "className": "text-right",
                    },
                    {data: null, className: 'uid',
                        render: function (data, type, row, meta) {
                            // console.log(row)
                            return (Number(row.begin_qty)) + (Number(row.qty_in.in)) - (Number(row.qty_out.out));
                        },
                        "className": "text-right"
                    },
                    {data: "unit"},
                    {"defaultContent":
                        `
                         <button class='btn btn-primary btn-sm button-detail'> <i class='fa fa-search'></i> View Detail</button>
                        `,
                        "className": "text-center"
                    }
                ]
            });

            $('#tbl-budget-list tbody').on( 'click', '.button-detail', function () {
                var table = $('#tbl-budget-list').DataTable();
                selected_data = [];
                selected_data = table.row($(this).closest('tr')).data();
                console.log(selected_data);
                var matDetails = {
                    "material"  : selected_data.material,
                    "whscode"   : selected_data.whscode,
                    "strdate"   : $('#datefrom').val(),
                    "enddate"   : $('#dateto').val(),
                    "_token" : _token
                }

                $('#dtlDate1').val($('#datefrom').val());
                $('#dtlDate2').val($('#dateto').val());
                $('#whsCode1').val(selected_data.whscode);
                $('#Material1').val(selected_data.material);

                $("#tbl-matmove-list").DataTable({
                    serverSide: true,
                    ajax: {
                        url: base_url+'/report/stockhistory',
                        data: matDetails,
                        type: 'POST'
                    },
                    buttons: false,
                    searching: true,
                    scrollY: 500,
                    scrollX: true,
                    scrollCollapse: true,
                    bDestroy: true,
                    columns: [
                        { "data": null,"sortable": false, "searchable": false,
                            render: function (data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {data: "docnum", className: 'uid'},
                        {data: "docyear", className: 'uid'},
                        {data: "postdate", className: 'uid'},
                        {data: "material", className: 'uid'},
                        {data: "matdesc", className: 'uid'},
                        {data: "whsname", className: 'uid'},
                        {data: "quantity", "sortable": false,
                            render: function (data, type, row){
                                return ``+ row.quantity.qty + ``;
                            },
                            "className": "text-right",
                        },
                        {data: "unit"},
                        {data: "remark", className: 'uid'},
                        {data: "movement_info", className: 'uid'},
                    ]
                });

                $('#matMoveDetail').modal('show');
            });

            // let table = new DataTable('#tbl-budget-list');

            // table.on('click', 'tbody tr', function () {
            //     let data = table.row(this).data();
            //     console.log(data);

            //     // alert('You clicked on ' + data[0] + "'s row");
            // });
        }


        $('.inputNumber').on('change', function(){
            this.value = formatRupiah(this.value,'');
        });

        function formatRupiah(angka, prefix){
            var number_string = angka.toString().replace(/[^.\d]/g, '').toString(),
            split   		  = number_string.split('.'),
            sisa     		  = split[0].length % 3,
            rupiah     		  = split[0].substr(0, sisa),
            ribuan     		  = split[0].substr(sisa).match(/\d{3}/gi);

            if(ribuan){
                separator = sisa ? ',' : '';
                rupiah += separator + ribuan.join(',');
            }

            rupiah = split[1] != undefined ? rupiah + '.' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
        }
    });
</script>
@endsection
