@extends('layouts/App')

@section('title', 'Change PBJ')

@section('additional-css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('/assets/css/customstyle.css') }}">
    <style type="text/css">
        .select2-container {
            display: block
        }

        .select2-container .select2-selection--single {
            height: 36px;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4">
            <form action="{{ url('transaction/document/save') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detail PBJ <b>[ {{ $pbjhdr->pbjnumber }} ]</b></h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="tglpbj">Tanggal PBJ</label>
                                            <input type="date" name="tglpbj" class="form-control" value="{{ $pbjhdr->tgl_pbj }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="requestto">Tujuan Permintaan</label>
                                            <input type="text" name="requestto" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="kepada">Kepada</label>
                                            <!-- <input type="text" name="kepada" class="form-control"> -->
                                            <select name="kepada" id="kepada" class="form-control" required>
                                                <option value="">Pilih Department</option>
                                                @foreach($department as $key => $row)
                                                    <option value="{{ $row->department }}">{{ $row->department }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="unitdesc">Unit Desc / Code</label>
                                            <select name="unitdesc" id="find-unitdesc" class="form-control"></select>
                                            <!-- <input type="text" name="unitdesc" class="form-control"> -->
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="engine">Engine Model</label>
                                            <input type="text" name="engine" id="engine_model" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="chassis">Chassis S/N</label>
                                            <input type="text" name="chassis" id="chassis" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="refrensi">Refrensi Permintaan</label>
                                            <input type="text" name="refrensi" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="requestor">Requestor</label>
                                            <input type="text" name="requestor" class="form-control" value="{{ Auth::user()->name }}">
                                        </div>
                                    </div>                                    
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="typeModel">Type / Model</label>
                                            <input type="text" name="typeModel" id="typeModel" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="user">User</label>
                                            <!-- <input type="text" name="user" class="form-control"> -->
                                            <select name="user" id="user" class="form-control" required>
                                                <option value="">Pilih Mekanik</option>
                                                @foreach($mekanik as $key => $row)
                                                    <option value="{{ $row->nama }}">{{ $row->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>        
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="kodeJasa">Kode Barang / Jasa</label>
                                            <input type="text" name="kodeJasa" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="nginesn">Engine S/N</label>
                                            <input type="text" name="nginesn" id="nginesn" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="hmkm">HM</label>
                                            <input type="text" name="hmkm" id="hmkm" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="km">KM</label>
                                            <input type="text" name="km" id="km" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="budgetcode">Budget / Cost Code</label>
                                            <input type="text" name="budgetcode" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="no_rangka">No. Rangka</label>
                                            <input type="text" name="no_rangka" id="no_rangka" class="form-control">
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="attachment">Attachment</label>
                                            <input type="file" class="form-control" name="efile[]" multiple="multiple">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-lg-12">
                                
                                <div class="form-group">
                                    <label for="doctitle">PBJ Number</label>
                                    <p>{{ $pbjhdr->pbjnumber }}</p>
                                    <input type="hidden" id="pbjNumber" value="{{ $pbjhdr->pbjnumber }}">
                                </div>  
                                <div class="form-group">
                                    <label>Checklist No:</label> {{$pbjhdr->cheklistnumber}}
                                </div>
                                <div class="form-group">
                                    <label>Created By:</label> {{$pbjhdr->createdby}}
                                </div>
                                <div class="form-group">
                                    <label>Created Date:</label>
                                    <p>{!! formatDateTime($pbjhdr->createdon) !!}
                                    </p>
                                </div>
                            </div>   --}}
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    
                    <div class="card-tools">
                        <a href="{{ url('/printdoc/pbj/print/') }}/{{ $pbjhdr->id}}" target="_blank" class='btn btn-success btn-sm button-print'> 
                            <i class='fa fa-print'></i> Print
                        </a>
                        <a href="{{ url('/transaction/pbj') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-header">
                                <div class="row">
                                    <ul class="nav nav-tabs" id="custom-content-above-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="custom-content-above-home-tab" data-toggle="pill" href="#custom-content-above-home" role="tab" aria-controls="custom-content-above-home" aria-selected="true">PBJ Items</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-content-above-approval-tab" data-toggle="pill" href="#custom-content-above-approval" role="tab" aria-controls="custom-content-above-approval" aria-selected="false">Approval Status</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-content-above-attachment-tab" data-toggle="pill" href="#custom-content-above-attachment" role="tab" aria-controls="custom-content-above-attachment" aria-selected="false">Attachment</a>
                                        </li>
                                    </ul>
                                </div>                                    
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="tab-content" id="custom-content-above-tabContent">
                                            <div class="tab-pane fade show active" id="custom-content-above-home" role="tabpanel" aria-labelledby="custom-content-above-home-tab">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <table id="tbl-pbj" class="table table-bordered table-hover table-striped table-sm">
                                                            <thead>
                                                                {{-- <th>No</th> --}}
                                                                {{-- <th>PBJ Item</th> --}}
                                                                <th>Part Number</th>
                                                                {{-- <th>Description</th> --}}
                                                                <th style="text-align:center;">Quantity</th>
                                                                <th>Unit</th>
                                                                <th>Figure</th>
                                                                <th>Remark</th>
                                                                <th></th>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($pbjitem as $key => $row)
                                                                    <tr>
                                                                        <td>
                                                                            {{ $row->partnumber }} - {{ $row->description }}
                                                                            <input type="hidden" name="parts[]" id="parts`+fCount+`" class="form-control" value="{{ $row->partnumber }}" readonly>
                                                                            <input type="hidden" name="partdesc[]" id="partdesc`+fCount+`" class="form-control" value="{{ $row->description }}" readonly>
                                                                        </td>
                                                                        <td style="text-align:right;">
                                                                            <input type="text" name="uoms[]" value="{{ number_format($row->quantity,0) }}" class="form-control" style="text-align: right;">
                                                                        </td>
                                                                        <td>
                                                                            {{ $row->unit }}
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="figures[]" class="form-control" value="{{ $row->figure }}" required>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="remarks[]" class="form-control" value="{{ $row->remark }}" required>
                                                                        </td>
                                                                        <td style="text-align: center;">
                                                                            <button type="button" class="btn btn-danger btn-sm btn-delete-item" data-pbjnumber="{{ $row->pbjnumber }}" data-pbjitem="{{ $row->pbjitem }}">
                                                                                <i class="fa fa-trash"></i>
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>                                
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="custom-content-above-approval" role="tabpanel" aria-labelledby="custom-content-above-approval-tab">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <table id="tbl-approval" class="table table-bordered table-hover table-striped table-sm" style="width:100%;">
                                                            <thead>
                                                                <th>Approver Name</th>
                                                                <th>Approver Level</th>
                                                                <th>PBJ Item</th>
                                                                <th>Approval Status</th>
                                                                <th>Approve/Reject Date</th>
                                                                {{-- <th>Approver Note</th> --}}
                                                            </thead>
                                                            <tbody>
                                                                @foreach($approvals as $key => $row)
                                                                <tr>
                                                                    <td>{{ $row->approver_name }}</td>
                                                                    <td>{{ $row->approver_level }}</td>
                                                                    <td>{{ $row->pbjitem }}</td>
                                                                    @if($row->approval_status == "A")
                                                                    <td style="text-align:center; background-color:green; color:white;">
                                                                        Approved
                                                                    </td>
                                                                    @elseif($row->approval_status == "R")
                                                                    <td style="text-align:center; background-color:red; color:white;">
                                                                        Rejected
                                                                    </td>
                                                                    @else
                                                                    <td style="text-align:center; background-color:yellow; color:black;">
                                                                        Open
                                                                    </td>
                                                                    @endif
                                                                    
                                                                    <td>
                                                                        @if($row->approval_date != null)
                                                                            <i class="fa fa-clock"></i> 
                                                                            ({{ formatDateTime($row->approval_date) }})
                                                                        @endif
                                                                    </td>
                                                                    {{-- <td>{!! $row->approval_remark !!}</td> --}}
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>                                                    
                                                    </div>
                                                </div>
                                            </div>   

                                            <div class="tab-pane fade" id="custom-content-above-attachment" role="tabpanel" aria-labelledby="custom-content-above-attachment-tab">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <table class="table table-sm">
                                                            <thead>
                                                                <th>No</th>
                                                                <th>File Name</th>
                                                                <th>Upload Date</th>
                                                                <th></th>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($attachments as $key => $file)
                                                                <tr>
                                                                    <td>{{ $key+1 }}</td>
                                                                    <td>
                                                                        {{ $file->efile }}
                                                                    </td>
                                                                    <td>
                                                                        <i class="fa fa-clock"></i> {!! formatDateTime($file->createdon) !!}
                                                                    </td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-sm btn-default" onclick="previewFile('files/PBJ/{{$file->efile}}#toolbar=0')">
                                                                            <i class="fa fa-search"></i> Preview File
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
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
            </div>
                
        </div>
    </div>
</div>
@endsection

@section('additional-modal')
<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modalPreviewFile">
    <div class="modal-dialog modal-xl">
        <form class="form-horizontal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPreviewFileTitle">Preview Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="position-relative row form-group">
                    <div class="col-lg-12" id="fileViewer">
                        <!-- <div id="example1"></div> -->
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"> Close</button>
                <a href="#" id="btnDownloadFile" class="btn btn-default btnDownloadFile" download="">
                    <i class="fa fa-download"></i> Download Document
                </a>
            </div>
        </div>
        </form>
    </div>
</div>   
@endsection

@section('additional-js')
<script src="{{ asset('/assets/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('/assets/ckeditor/adapters/jquery.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- <script src="https://cdn.scaleflex.it/plugins/filerobot-image-editor/3/filerobot-image-editor.min.js"></script> -->

<script type="text/javascript">
    function previewFile(files){         
        // alert(base_url)
        var pathfile = base_url+'/'+files;
        if(files !== ""){
            $('#fileViewer').html('');
            $('#fileViewer').append(`
                <embed src="`+ pathfile +`" frameborder="0" width="100%" height="500px">
            
            `);

            var fileUri = pathfile;
            fileUri = fileUri.replace("#toolbar=0", "?force=true");
            
            document.getElementById("btnDownloadFile").href=fileUri; 
            $('#modalPreviewFile').modal('show');
        } else{
            swal("File Not Found", "", "warning");
        }
    }

    $(document).ready(function () { 
        // $('#tbl-pbj').DataTable();

        $('#btn-approve').on('click', function(){
            $('#btn-approve').prop('disabled', true);
            $('#btn-reject').prop('disabled', true);
            approveDocument('A');
        });

        $('#btn-reject').on('click', function(){
            $('#btn-approve').prop('disabled', true);
            $('#btn-reject').prop('disabled', true);
            approveDocument('R');
        });

        function approveDocument(_action){
            let _token   = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: base_url+'/approve/pbj/save',
                type:"POST",
                data:{
                    pbjNumber: "{{ $pbjhdr->pbjnumber }}",
                    action:_action,
                    approvernote:$('#approver_note').val(),
                    _token: _token
                },
                success:function(response){
                    console.log(response);
                    if(response.msgtype === "200"){
                        if(_action === "A"){
                            toastr.success(response.message)
                        }else if(_action === "R"){
                            toastr.success(response.message)
                        }                        

                        setTimeout(function(){ 
                            window.location.href = base_url+'/approve/pbj';
                        }, 2000);
                    }
                },
                error: function(error) {
                    console.log(error);
                    toastr.error(error)

                    setTimeout(function(){ 
                        location.reload();
                    }, 2000);
                }
            });
        }
    });
</script>
@endsection