@extends('adminlte::page')
@section('title', 'Dashboard')
@section('plugins.Datatables', true)
@section('content')
    @include('sweetalert::alert')
    <div class="card mt-2">
        <div class="card-header  ">
            <div class="d-flex justify-content-between">
                <div>
                    <a href="{{ $file->public_path }}">{{ $file->public_path }}</a>
                </div>
                <div>
                    <a target="_blank"
                    class="btn btn-primary btn-sm"
                    href="/print/{{ $file->id }}?is_print=true">
                        <i class="fa fa-print"></i>
                        Print
                    </a>
                    <button
                    class="btn btn-success btn-sm"
                    onclick="exportToExcel('xlsx')"
                    >
                        <i class="fa fa-file-excel mr-1"></i>
                        Export
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <x-output :records="$records" :dates="$dates" :title="$title" />
        </div>
    </div>
@stop
@section('js')
    <script src="/js/export2excel.js"></script>
    <script src="/js/jquery.table2excel.js"></script>
    <script>
        function printContent(el){
            var restorepage = $('body').html();
            var printcontent = $('#' + el).clone();
            $('body').empty().html(printcontent);
            window.print();
            $('body').html(restorepage);
        }
    </script>
    
@stop

