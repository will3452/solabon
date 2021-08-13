@extends('adminlte::page')
@section('title', 'Dashboard')
@section('plugins.Datatables', true)
@section('content')
    @include('sweetalert::alert')
    <div class="card mt-2">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>
                   <a target="_blank"
                    href="{{ $file->public_path }}" download>
                        <i class="fa fa-download"></i>
                        {{$file->name}}
                    </a>
                </div>
                <div>
                    <a target="_blank"
                    class="btn btn-success btn-sm"
                    href="/print/{{ $file->id }}?is_print=true">
                        Print | Save As PDF (Converted File)
                    </a>
                    
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="pdfme">
            <x-output :records="$records" :dates="$dates" :title="$title" :name="$file->name"/>
            </div>
        </div>
    </div>
@stop
@section('js')
    

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

