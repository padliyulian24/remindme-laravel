@extends('layouts.dashboard')

@push('page-css')
<style>
    .c--pointer {
        cursor: pointer;
    }
</style>
@endpush

@section('main')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Reminder</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Reminder</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-lg-2">
                        <a href="{{url('/reminder/create')}}" class="btn btn-primary btn-block">Add</a>
                    </div>
                    <div class="form-group col-lg-2">
                        <a href="#" class="btn btn-info btn-block btn-reset">Reset</a>
                    </div>
                    <div class="form-group col-lg-1">
                        <select onchange="changeLength()" name="length" id="length" class="custom-select">
                            <option value="5">5</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-7">
                        <input value="" class="form-control" type="text" name="search" id="search" placeholder="Cari data ...">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table class="table table-hover table-striped table-responsive-sm">
                            <thead>
                                <tr>
                                    <th onclick="sortBy('title')" class="c--pointer" scope="col">
                                        <i class="fas fa-sort"></i> Title
                                    </th>
                                    <th>Description</th>
                                    <th>Event At</th>
                                    <th>Remind At</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="data-remind">
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada data ...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-footer">
            </div>
        </div>

    </section>
</div>
@endsection

@push('page-js')
<script src="{{ asset('/assets/vendor/axios/axios.min.js') }}"></script>
<script>
    let token = localStorage.getItem('access_token')
    if (!token) {
        location.href = '/login'
    }
    let config = {headers: {Authorization: `Bearer ${token}`}}
    let app_url = `/api/reminders`
    let dir = @JSON(request()->get('dir'));
    let key = 'id'

    if (!dir) dir = 'desc'

    function sortBy(column) {
        key = column
        if (dir == 'desc') dir = 'asc'
        else dir = 'desc'
        getData(`${app_url}?page=1&limit=${$('#length').val()}&column=${key}&dir=${dir}&search=${$('#search').val()}`)
    }

    function changeLength() {
        getData(`${app_url}?page=1&limit=${$('#length').val()}&column=${key}&dir=${dir}&search=${$('#search').val()}`)
    }

    $('#search').on('keyup', function(ev){
        if (ev.keyCode === 13) {
            getData(`${app_url}?page=1&limit=${$('#length').val()}&column=${key}&dir=${dir}&search=${$('#search').val()}`)
        }
    })

    $('.btn-reset').on('click', function(ev){
        ev.preventDefault()
        $('#search').val('')
        $('#length').val('5')
        getData(app_url)
    })

    function getData(url) {
        axios.get(url, config)
            .then(res => {
                if (res.data.ok === true) {
                    $('#data-remind').empty()
                    res.data.data.data.forEach(item => {
                        $('#data-remind').append(`
                            <tr>
                                <td>${item.title}</td>
                                <td>${item.description}</td>
                                <td>${item.event_at}</td>
                                <td>${item.remind_at}</td>
                                <td>
                                    <a href="/reminder/edit?id=${item.id}" class="text-warning btn-edit" title="Edit">
                                        <span>
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                        </span>
                                    </a>    
                                    <a href="${item.id}" class="text-danger btn-delete" title="Delete">
                                        <span>
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </span>
                                    </a>
                                </td>
                            </tr>
                        `)
                    })
                }
            })
            .catch()
    }

    function deleteData(id) {
        axios.delete(`/api/reminders/${id}`, config)
            .then(res => {
                if (res.data.ok === true) {
                    Swal.fire({
                        icon: 'success',
                        title: "Success",
                        text: "Success Delete",
                    })
                    getData(app_url)
                }
            })
            .catch()
    }

    $(document).on('click', '.btn-delete', function(ev){
        ev.preventDefault()
        ev.stopPropagation()
        Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteData($(this).attr('href'))
                }
            })
    })

    getData(app_url)
</script>
@endpush