@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content_header')
    <h1>Dashboard</h1>
@stop
@section('plugins.Datatables', true)
@section('content')
    @include('sweetalert::alert')
    <div class="card">
        <div class="card-header">
            File new record
        </div>
        <div class="card-body">
            <form action="/upload" enctype="multipart/form-data" method="POST">
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

    <div class="card">
        <div class="card-header">
            LIST OF FILES
        </div>
        <div class="card-body">
            <table id="files" class="table">
                <thead>
                    <tr>
                        <th>
                        ID
                        </th>
                        <th>
                            FILENAME
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
                                {{ \Str::limit($file->path, 10) }}
                            </td>
                            <td>
                                {{ $file->created_at }}
                            </td>
                            <td>
                                <a href="/records/{{ $file->id }}" class="btn btn-primary btn-sm">
                                    View Summary
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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

            $('#files').DataTable();
            
        })

    </script>
@stop