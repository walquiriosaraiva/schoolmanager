@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-start">
            @include('layouts.left-menu')
            <div class="col-xs-11 col-sm-11 col-md-11 col-lg-10 col-xl-10 col-xxl-10">
                <div class="row pt-2">
                    <div class="col ps-4">
                        <h1 class="display-6 mb-3">
                            <i class="bi bi-sort-numeric-up-alt"></i> Bank return data
                        </h1>
                        <h6>
                            <a href="{{ route('bank-return-data.create')}}"
                               class="btn btn-primary btn-sm">New</a>
                        </h6>
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
                        </h6>
                        <div class="mb-4 mt-4">
                            <table class="table mt-4">
                                <thead>
                                <tr>
                                    <th scope="col">Our number</th>
                                    <th scope="col">Value</th>
                                    <th scope="col">Date of occurrence</th>
                                    <th scope="col">Wallet</th>
                                    <th scope="col">Upload Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @isset($result)
                                    @foreach ($result as $object)
                                        <tr>
                                            <td>{{$object->nosso_numero}}</td>
                                            <td>{{$object->valor_principal}}</td>
                                            <td>{{$object->data_de_ocorrencia->format('d/m/Y')}}</td>
                                            <td>{{$object->carteira}}</td>
                                            <td>{{$object->created_at->format('d/m/Y')}}</td>
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
