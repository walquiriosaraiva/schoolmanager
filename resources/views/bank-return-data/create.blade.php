@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-start">
            @include('layouts.left-menu')
            <div class="col-xs-11 col-sm-11 col-md-11 col-lg-10 col-xl-10 col-xxl-10">
                <div class="row pt-2">
                    <div class="col ps-4">
                        <h1 class="display-6 mb-3">
                            <i class="bi bi-person-lines-fill"></i> Bank return data upload
                        </h1>
                        <div class="mb-4 mt-4">
                            <form action="{{route('bank-return-data.store')}}" method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label for="file" class="form-label">File upload bank return
                                            data<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="file" name="file" required class="form-control" id="file">
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-12-md">
                                        <button type="submit" class="btn btn-outline-primary mb-3"><i
                                                class="bi bi-sort-numeric-up-alt"></i> Submit upload
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @include('layouts.footer')
            </div>
        </div>
    </div>
@endsection
