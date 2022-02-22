@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-start">
            @include('layouts.left-menu')
            <div class="col-xs-11 col-sm-11 col-md-11 col-lg-10 col-xl-10 col-xxl-10">
                <div class="row pt-2">
                    <div class="col ps-4">
                        <h1 class="display-6 mb-3">
                            <i class="bi bi-person-lines-fill"></i> Payment Students
                        </h1>
                        <div class="mb-4 mt-4">
                            <form action="{{route('payment.store')}}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="student_id" class="form-label">Assign to student:<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <select class="form-select"
                                                id="student_id" name="student_id" required>
                                            <option selected disabled>Please select a student</option>
                                            @foreach ($students as $student)
                                                <option
                                                    value="{{$student->id}}">{{$student->first_name . ' '. $student->last_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="due_date" class="form-label">Due date<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="date" class="form-control" id="due_date" name="due_date"
                                               placeholder="Due date" required value="{{old('due_date')}}">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="tuition" class="form-label">Tuition<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="tuition" name="tuition"
                                               placeholder="Tuition" required value="{{old('tuition')}}">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="sdf" class="form-label">SDF<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="sdf" name="sdf"
                                               placeholder="SDF" required value="{{old('sdf')}}">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="hot_lunch" class="form-label">Hot Lunch<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="hot_lunch" name="hot_lunch"
                                               placeholder="Hot Lunch" required value="{{old('hot_lunch')}}">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="enrollment" class="form-label">Enrollment<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="enrollment" name="enrollment"
                                               placeholder="Enrollment" required value="{{old('enrollment')}}">
                                    </div>

                                    <div class="col-md-2">
                                        <label for="percentage_discount" class="form-label">Percentage discount<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="percentage_discount"
                                               name="percentage_discount"
                                               placeholder="Discount" required
                                               value="{{old('percentage_discount')}}">
                                    </div>


                                    <div class="col-md-4">
                                        <label for="type_of_payment" class="form-label">Type of payment</label>
                                        <select class="form-select"
                                                id="type_of_payment" name="type_of_payment">
                                            <option value="">Please select a type of payment</option>
                                            <option value="Down payment">Down payment</option>
                                            <option value="Down payment">Automatic payment</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="status_payment" class="form-label">Status payment<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <select class="form-select"
                                                id="status_payment" name="status_payment" required>
                                            <option value="">Please select a status payment</option>
                                            <option value="Liquidado">Liquidado</option>
                                            <option value="Confirmado">Confirmado</option>
                                            <option value="Rejeitado">Rejeitado</option>
                                            <option value="Não localizado">Não localizado</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="contract_duration" class="form-label">Contract duration:<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <select class="form-select"
                                                id="contract_duration" name="contract_duration" required>
                                            <option selected disabled>Please select a contract duration</option>
                                            @foreach ($months as $key=>$value)
                                                <option
                                                    value="{{$key}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                                <div class="row mt-4">
                                    <div class="col-12-md">
                                        <button type="submit" class="btn btn-outline-primary mb-3"><i
                                                class="bi bi-sort-numeric-up-alt"></i> Payment
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
