@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-start">
            @include('layouts.left-menu')
            <div class="col-xs-11 col-sm-11 col-md-11 col-lg-10 col-xl-10 col-xxl-10">
                <div class="row pt-2">
                    <div class="col ps-4">
                        <h1 class="display-6 mb-3">
                            <i class="bi bi-sort-numeric-up-alt"></i> Payment
                        </h1>
                        @if (Auth::user()->role == "admin")
                            <h6>
                                <a href="{{ route('payment.create')}}"
                                   class="btn btn-primary btn-sm">New</a>
                            </h6>
                        @endif
                        <h6>
                            @if (session('success'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger" role="alert">
                                    {{ session('error') }}
                                </div>
                            @endif
                            @csrf
                        </h6>
                        <div class="mb-4 mt-4">
                            <table class="table mt-4" id="tablePayment">
                                <thead>
                                <tr>
                                    <th scope="col">Student</th>
                                    <th scope="col">Due date</th>
                                    <th scope="col">Tuition</th>
                                    <th scope="col">SDF</th>
                                    <th scope="col">Hot lunch</th>
                                    <th scope="col">Enrollment</th>
                                    <th scope="col">Type of payment</th>
                                    <th scope="col">Status payment</th>
                                    <th scope="col">Percentage discount</th>
                                    <th scope="col">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @isset($result)
                                    @foreach ($result as $object)
                                        <tr>
                                            <td>{{$object->first_name . ' ' . $object->last_name}}</td>
                                            <td>{{$object->due_date->format('d/m/Y')}}</td>
                                            <td>{{$object->tuition}}</td>
                                            <td>{{$object->sdf}}</td>
                                            <td>{{$object->hot_lunch}}</td>
                                            <td>{{$object->enrollment}}</td>
                                            <td>{{$object->type_of_payment}}</td>
                                            <td>{{$object->status_payment}}</td>
                                            <td>{{$object->percentage_discount}}</td>
                                            @if (Auth::user()->role == "admin")
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{route('payment.edit', ['id' => $object->id])}}"
                                                           role="button" class="btn btn-sm btn-outline-primary"><i
                                                                class="bi bi-sort-numeric-up-alt"></i> Edit</a>
                                                    </div>
                                                    @if($object->upload_ticket === '1')
                                                        <div class="btn-group" role="group">
                                                            <a href="{{route('payment.student', ['payment' => $object->id, 'student' => $object->student_id])}}"
                                                               download class="btn btn-sm btn-outline-primary"><i
                                                                    class="bi bi-download"></i> Download</a>
                                                        </div>
                                                    @endif
                                                </td>


                                            @else
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{route('payment.student', ['payment' => $object->id, 'student' => $object->student_id])}}"
                                                           role="button" class="btn btn-sm btn-outline-primary"><i
                                                                class="bi bi-sort-numeric-up-alt"></i>Download</a>
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endisset
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @include('layouts.footer')
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
            crossorigin="anonymous"></script>
    <script>

        function confirmPayment(object) {
            var ids = $(this).val();
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{ route('payment.confirm') }}",
                method: "POST",
                data: {ids: ids, _token: _token},
                success: function (data) {
                    window.location.reload();
                }
            });
        }


        $(document).ready(function () {

            $("#student").blur(function () {

                var student = $(this).val();
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('payment.student.show') }}",
                    method: "POST",
                    data: {student: student, _token: _token},
                    success: function (data) {
                        console.log('resultado: ', data)
                    }
                });

            });

        });
    </script>
@endsection
