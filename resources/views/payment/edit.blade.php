@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-start">
            @include('layouts.left-menu')
            <div class="col-xs-11 col-sm-11 col-md-11 col-lg-10 col-xl-10 col-xxl-10">
                <div class="row pt-2">
                    <div class="col ps-4">
                        <h1 class="display-6 mb-3">
                            <i class="bi bi-person-lines-fill"></i> Edit payment Students
                        </h1>
                        <div class="mb-4 mt-4">
                            <form action="{{route('payment.update', $payment->id)}}" method="post">
                                @method('PUT')
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="student_id" class="form-label">Assign to student:<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <select onchange="getSections(this);" class="form-select"
                                                id="student_id" name="student_id" required>
                                            <option selected disabled>Please select a student</option>
                                            @foreach ($students as $student)
                                                <option
                                                    value="{{$student->id}}" {{$selected = $student->id === $payment->student_id ? 'selected' : ''}}>
                                                    {{$student->first_name . ' '. $student->last_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="due_date" class="form-label">Due date<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="date" class="form-control" id="due_date" name="due_date"
                                               placeholder="Due date" required
                                               value="{{$payment->due_date->format('Y-m-d')}}">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="tuition" class="form-label">Tuition<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="tuition" name="tuition"
                                               placeholder="Tuition" required value="{{$payment->tuition}}">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="sdf" class="form-label">SDF<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="sdf" name="sdf"
                                               placeholder="SDF" required value="{{$payment->sdf}}">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="hot_lunch" class="form-label">Hot Lunch<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="hot_lunch" name="hot_lunch"
                                               placeholder="Hot Lunch" required value="{{$payment->hot_lunch}}">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="enrollment" class="form-label">Enrollment<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="enrollment" name="enrollment"
                                               placeholder="Enrollment" required value="{{$payment->enrollment}}">
                                    </div>

                                    <div class="col-md-2">
                                        <label for="percentage_discount" class="form-label">Percentage discount<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="percentage_discount"
                                               name="percentage_discount"
                                               placeholder="Discount" required
                                               value="{{$payment->percentage_discount}}">
                                    </div>


                                    <div class="col-md-4">
                                        <label for="type_of_payment" class="form-label">Type of payment</label>
                                        <select class="form-select"
                                                id="type_of_payment" name="type_of_payment">
                                            <option value="">Please select a type of payment</option>
                                            <option
                                                value="Down payment" {{ $payment->type_of_payment === 'Down payment' ? 'selected' : ''  }}>
                                                Down payment
                                            </option>
                                            <option
                                                value="Automatic payment"{{ $payment->type_of_payment === 'Automatic payment' ? 'selected' : ''  }}>
                                                Automatic payment
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="status_payment" class="form-label">Status payment<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <select class="form-select"
                                                id="status_payment" name="status_payment" required>
                                            <option value="">Please select a status payment</option>
                                            <option
                                                value="Liquidado" {{ $payment->status_payment === 'Liquidado' ? 'selected' : '' }}>
                                                Liquidado
                                            </option>
                                            <option
                                                value="Confirmado"{{ $payment->status_payment === 'Confirmado' ? 'selected' : '' }}>
                                                Confirmado
                                            </option>
                                            <option
                                                value="Rejeitado"{{ $payment->status_payment === 'Rejeitado' ? 'selected' : '' }}>
                                                Rejeitado
                                            </option>
                                            <option
                                                value="Não localizado"{{ $payment->status_payment === 'Não localizado' ? 'selected' : '' }}>
                                                Não localizado
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="status_payment" class="form-label">Payment bank data<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <select class="form-select"
                                                id="bank_return_data_id" name="bank_return_data_id">
                                            <option value="">--select--</option>
                                            @foreach ($bankReturnData as $data)
                                                <option
                                                    value="{{$data->id}}">{{$data->nome_do_sacado . ' - '. $data->valor_principal .' - '. $data->data_de_ocorrencia->format('d/m/Y')}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Parents</label>
                                        <div><strong>Pai:</strong> {{$studentsParents->father_name}}
                                            <br/><strong>Mae:</strong> {{$studentsParents->mother_name}}</div>
                                    </div>

                                </div>
                                <div class="row mt-4">
                                    <div class="col-12-md">
                                        <button type="submit" class="btn btn-outline-primary mb-3"><i
                                                class="bi bi-sort-numeric-up-alt"></i> Update payment
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
