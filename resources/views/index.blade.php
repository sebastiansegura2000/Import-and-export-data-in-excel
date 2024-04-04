    @extends('layouts.app')

    @section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    {{-- <div class="card-header"> --}}
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <img src="{{ asset('images/goku.gif') }}" width="100" alt="Logo" class="img-fluid">
                        {{-- {{ __('Dashboard') }} --}}
                        <h4>Import Excel Data into Database</h4>
                        {{-- <a class="btn btn-danger float-end" href="{{ route('users.export') }}">Export User Data</a> --}}
                    </div>


                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif


                        <div class="card-body">

                            <form action="{{ url('user/import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <label>Import User Data in Excel File</label>

                                <div class="input-group">
                                    <input type="file" name="import_file" class="form-control" />
                                    <button type="submit" class="btn btn-primary">Import</button>
                                </div>

                            </form>

                            <hr>


                            <form action="{{ url('user/export') }}" method="GET">
                                <label>Export User Data in Excel File</label>
                                <div class="input-group mt-2">
                                    <select name="type" class="form-control" required>
                                        <option value="">Select Excel Format</option>
                                        <option value="xlsx">XLSX</option>
                                        <option value="csv">CSV</option>
                                        <option value="xls">XLS</option>
                                    </select>
                                    <button type="submit" class="btn btn-success">Export</button>
                                </div>
                            </form>

                            <hr>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $item)
                                    <tr>
                                        <td>{{$item->id}}</td>
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->email}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>

                        {{ __('You are logged in!') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

