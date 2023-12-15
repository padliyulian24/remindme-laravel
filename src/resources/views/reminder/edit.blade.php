@extends('layouts.dashboard')

@push('page-css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css" integrity="sha512-f0tzWhCwVFS3WeYaofoLWkTP62ObhewQ1EZn65oSYDZUg1+CyywGKkWzm8BxaJj5HGKI72PnMH9jYyIFz+GH7g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                        <li class="breadcrumb-item">
                            <a href="{{ url('/reminder') }}">Reminder</a>
                        </li>
                        <li class="breadcrumb-item active">
                            Edit
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-lg-6">
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
                        <form id="form-edit" action="#" method="POST">
                            @csrf
                            <div class="row">
                                <div class="form-group col-lg-12">
                                    <input id="title" type="text" name="title" class="form-control" placeholder="Title"/>
                                    <span class="invalid-feedback" role="alert">
                                        <strong id="title_err"></strong>
                                    </span>
                                </div>               
                                <div class="form-group col-lg-12">
                                    <textarea id="description" name="description" class="form-control" cols="30" rows="10" placeholder="Description"></textarea>
                                    <span class="invalid-feedback" role="alert">
                                        <strong id="description_err"></strong>
                                    </span>
                                </div>
                                <div class="form-group col-lg-12">
                                    <label for="event_at" class="form-label">Event At</label>
                                    <input id="event_at" type="text" class="form-control" name="event_at" autocomplete="off">
                                    <span class="invalid-feedback" role="alert">
                                        <strong id="event_at_err"></strong>
                                    </span>
                                </div>   
                                <div class="form-group col-lg-12">
                                    <label for="remind_at" class="form-label">Remind At</label>
                                    <input id="remind_at" type="text" class="form-control" name="remind_at" autocomplete="off">
                                    <span class="invalid-feedback" role="alert">
                                        <strong id="remind_at_err"></strong>
                                    </span>
                                </div> 
                                <div class="form-group col-lg-12">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('page-js')
<script src="{{ asset('/assets/vendor/axios/axios.min.js') }}"></script>
<script type="text/javascript" src="{{asset('/assets/vendor/jquery/jquery.datetimepicker.full.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $('#event_at').datetimepicker()
        $('#remind_at').datetimepicker()

        let id = @json(request()->id);
        let token = localStorage.getItem('access_token')
        if (!token) {
            location.href = '/login'
        }
        let config = {headers: {Authorization: `Bearer ${token}`}}

        function clearForm() {
            $('#title').val('')
            $('#description').val('')
            $('#event_at').val('')
            $('#remind_at').val('')
        }

        function clearError() {
            $('#title').removeClass('is-invalid')
            $('#description').removeClass('is-invalid')
            $('#event_at').removeClass('is-invalid')
            $('#remind_at').removeClass('is-invalid')

            $('#title_err').empty()
            $('#description_err').empty()
            $('#event_at_err').empty()
            $('#remind_at_err').empty()
        }

        $('#form-edit').on('submit', function(e){
            e.preventDefault()

            let title = $('#title').val()
            let description = $('#description').val()
            let remind_at = $('#remind_at').val()
            let event_at = $('#event_at').val()
            
            axios.put(`/api/reminders/${id}`, {title, description, event_at, remind_at}, config)
                .then(res => {
                    if (res.data.ok === true) {
                        clearForm()
                        clearError()

                        Swal.fire({
                            icon: 'success',
                            title: "Success",
                            text: 'Success Update'
                        })

                        setTimeout(() => location.href = '/reminder', 1000)
                    }
                })
                .catch(err => {
                    if (err.response.data.ok === false) {
                        Swal.fire({
                            icon: 'warning',
                            title: "Failed",
                            text: err.response.data.msg,
                        })

                        clearForm()
                        clearError()
                    }

                    if (err.response.data.errors) {
                        clearError()

                        if (err.response.data.errors.title) {
                            $('#title').addClass('is-invalid')
                            $('#title_err').text(err.response.data.errors.title[0])
                        }
                        if (err.response.data.errors.description) {
                            $('#description').addClass('is-invalid')
                            $('#description_err').text(err.response.data.errors.description[0])
                        }
                        if (err.response.data.errors.remind_at) {
                            $('#remind_at').addClass('is-invalid')
                            $('#remind_at_err').text(err.response.data.errors.remind_at[0])
                        }
                        if (err.response.data.errors.event_at) {
                            $('#event_at').addClass('is-invalid')
                            $('#event_at_err').text(err.response.data.errors.event_at[0])
                        }
                    }
                })
        })

        function getData() {
            axios.get(`/api/reminders/${id}`, config)
                .then(res => {
                    if (res.data.ok === true) {
                        $('#title').val(res.data.data.title)
                        $('#description').val(res.data.data.description)
                        $('#event_at').val(res.data.data.event_at)
                        $('#remind_at').val(res.data.data.remind_at)
                    }
                })
                .catch()
        }

        getData()
    })  
</script>
@endpush