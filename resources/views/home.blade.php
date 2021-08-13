@extends('adminlte::page')

@section('title', 'Dashboard')
@section('content_header')
    <h1 class="text-center">Home</h1>
@stop
@section('plugins.Datatables', true)
@section('content')
    @include('sweetalert::alert')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card card-primary">
                    <div class="card-header">
                        File new record
                    </div>
                    <div class="card-body">
                        <form action="/upload" enctype="multipart/form-data" method="POST">
                            <label for="">Preferred Name/Short Details</label>
                            <x-adminlte-input name="name" required/>
                            @csrf
                            <label for="">File Upload (.xls) File : <span class="text-success" id="file-selected">----</span></label>
                            <x-adminlte-input-file name="file" accept=".xls" igroup-size="sm" id="file" placeholder="Choose a file..." required>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text bg-lightblue">
                                        <i class="fas fa-upload"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input-file>
                            <div class="text-right">
                                <x-adminlte-button type="submit" label="Save" class="btn-sm" theme="primary" icon="fas fa-check"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                 <div class="card card-primary">
                    <div class="card-header">
                        LIST OF FILES
                    </div>
                    <div class="card-body">
                        <table id="files" class="table">
                            <thead>
                                <tr>
                                    <th>
                                    REC. ID
                                    </th>
                                    <th>
                                        NAME
                                    </th>
                                    <th>
                                        FILEPATH
                                    </th>
                                    <th>
                                        DATE UPLOADED
                                    </th>
                                    <th>
                                        ACTION
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($files as $file)
                                    <tr>
                                        <td>
                                            {{ $file->id }}
                                        </td>
                                        <td>
                                        {{$file->name}}
                                        </td>
                                        <td>
                                            {{ \Str::limit($file->path, 10) }}
                                        </td>
                                        <td>
                                            {{ $file->created_at->format('m/d/y H:ia') }}
                                        </td>
                                        <td>
                                            <form action="/records/{{$file->id}}" method="POST">
                                            @csrf 
                                            @method('DELETE')
                                                <a href="/records/{{ $file->id }}" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-eye"></i>
                                                    View
                                                </a>
                                                <a
                                                    class="btn btn-success btn-sm"
                                                    href="/print/{{ $file->id }}?is_excel=true"
                                                    >
                                                            <i class="fa fa-file-excel mr-1"></i>
                                                            Excel
                                                </a>
                                                <button onclick="deleteForm(this)" type="button" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-trash"></i>
                                                    Delete
                                                </button>
                                                
                                            </form>
                                                
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
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>

        $(function(){
            //to view file selected
            $('#file').change((e)=>{
                e.preventDefault();
                $('#file-selected').text(()=>{
                    let arr = e.target.value.split(`\\`);
                    return arr[arr.length - 1] || '----';
                });
            })

            $('#files').DataTable({
                "order":[]
            });

            
            
        });
        function deleteForm(e){
               e.parentElement.submit();
            }

    </script>
@stop