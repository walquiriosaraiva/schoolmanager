@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-start">
            @include('layouts.left-menu')
            <div class="col-xs-11 col-sm-11 col-md-11 col-lg-10 col-xl-10 col-xxl-10">
                <div class="row pt-2">
                    <div class="col ps-4">
                        <h1 class="display-6 mb-3">
                            <i class="bi bi-person-lines-fill"></i> Edit Student
                        </h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{url()->previous()}}">Student List</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit Student</li>
                            </ol>
                        </nav>

                        @include('session-messages')
                        <div class="mb-4">
                            <form class="row g-3" action="{{route('school.student.update')}}" method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="student_id" value="{{$student->id}}">
                                <div class="row g-3">

                                    <div class="col-sm-5 col-md-3">
                                        <div class="bg-light">
                                            <div class="px-5 pt-2">
                                                @if (isset($student->photo))
                                                    <img src="{{asset('/storage'.$student->photo)}}"
                                                         class="rounded-3 card-img-top"
                                                         alt="Profile photo">
                                                @else
                                                    <img src="{{asset('imgs/profile.png')}}"
                                                         class="rounded-3 card-img-top"
                                                         alt="Profile photo">
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-9">
                                        <label for="formFile" class="form-label">Photo</label>
                                        <input class="form-control" type="file" id="formFile"
                                               onchange="previewFile()">
                                        <div id="previewPhoto"></div>
                                        <input type="hidden" id="photoHiddenInput" name="photo" value="">
                                    </div>

                                    <div class="row mt-4 g-3">
                                        <h6>General Information (Informações Gerais):</h6>

                                        <div class="col-4">
                                            <label for="inputFirstName" class="form-label">First Name<sup><i
                                                        class="bi bi-asterisk text-primary"></i></sup></label>
                                            <input type="text" class="form-control" id="inputFirstName"
                                                   name="first_name"
                                                   placeholder="First Name" required value="{{$student->first_name}}">
                                        </div>

                                        <div class="col-4">
                                            <label for="inputLastName" class="form-label">Last Name<sup><i
                                                        class="bi bi-asterisk text-primary"></i></sup></label>
                                            <input type="text" class="form-control" id="inputLastName" name="last_name"
                                                   placeholder="Last Name" required value="{{$student->last_name}}">
                                        </div>

                                        <div class="col-4">
                                            <label for="application_grade" class="form-label">Application Grade (Série a
                                                ser
                                                matriculado)<sup><i
                                                        class="bi bi-asterisk text-primary"></i></sup></label>
                                            <select id="application_grade" class="form-select" name="application_grade"
                                                    required>
                                                <option {{($student->application_grade == '1th')?'selected':null}}>1th
                                                </option>
                                                <option {{($student->application_grade == '2th')?'selected':null}}>2th
                                                </option>
                                                <option {{($student->application_grade == '3th')?'selected':null}}>3th
                                                </option>
                                                <option {{($student->application_grade == '4th')?'selected':null}}>4th
                                                </option>
                                                <option {{($student->application_grade == '5th')?'selected':null}}>5th
                                                </option>
                                                <option {{($student->application_grade == '6th')?'selected':null}}>6th
                                                </option>
                                                <option {{($student->application_grade == '7th')?'selected':null}}>7th
                                                </option>
                                                <option {{($student->application_grade == '8th')?'selected':null}}>8th
                                                </option>
                                                <option {{($student->application_grade == '9th')?'selected':null}}>9th
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-3">
                                            <label for="inputBirthday" class="form-label">Birthday<sup><i
                                                        class="bi bi-asterisk text-primary"></i></sup></label>
                                            <input type="date" class="form-control" id="inputBirthday" name="birthday"
                                                   placeholder="Birthday" required value="{{$student->birthday}}">
                                        </div>

                                        <div class="col-2">
                                            <label for="inputState" class="form-label">Gender<sup><i
                                                        class="bi bi-asterisk text-primary"></i></sup></label>
                                            <select id="inputState" class="form-select" name="gender" required>
                                                <option value="Male" {{($student->gender == 'Male')?'selected':null}}>
                                                    Male
                                                </option>
                                                <option
                                                    value="Female" {{($student->gender == 'Female')?'selected':null}}>
                                                    Female
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-2">
                                            <label for="ethnicity" class="form-label">Ethnicity (Etnia)<sup><i
                                                        class="bi bi-asterisk text-primary"></i></sup></label>
                                            <select id="ethnicity" class="form-select" name="ethnicity" required>
                                                <option {{($student->ethnicity == 'Brancos')?'selected':null}}>
                                                    Brancos
                                                </option>
                                                <option {{($student->ethnicity == 'Pardos')?'selected':null}}>
                                                    Pardos
                                                </option>
                                                <option {{($student->ethnicity == 'Pretos')?'selected':null}}>
                                                    Pretos
                                                </option>
                                                <option {{($student->ethnicity == 'Amarelos')?'selected':null}}>
                                                    Amarelos
                                                </option>
                                                <option {{($student->ethnicity == 'Indígenas')?'selected':null}}>
                                                    Indígenas
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-3">
                                            <label for="inputAddress" class="form-label">Address<sup><i
                                                        class="bi bi-asterisk text-primary"></i></sup></label>
                                            <input type="text" class="form-control" id="inputAddress" name="address"
                                                   placeholder="634 Main St" required value="{{$student->address}}">
                                        </div>
                                        <div class="col-3">
                                            <label for="inputAddress2" class="form-label">Address 2</label>
                                            <input type="text" class="form-control" id="inputAddress2" name="address2"
                                                   placeholder="Apartment, studio, or floor"
                                                   value="{{$student->address2}}">
                                        </div>
                                        <div class="col-2">
                                            <label for="inputCity" class="form-label">City<sup><i
                                                        class="bi bi-asterisk text-primary"></i></sup></label>
                                            <input type="text" class="form-control" id="inputCity" name="city"
                                                   placeholder="Dhaka..." required value="{{$student->city}}">
                                        </div>
                                        <div class="col-2">
                                            <label for="inputZip" class="form-label">Zip<sup><i
                                                        class="bi bi-asterisk text-primary"></i></sup></label>
                                            <input type="text" class="form-control" id="inputZip" name="zip" required
                                                   value="{{$student->zip}}">
                                        </div>

                                        <div class="col-2">
                                            <label for="inputBloodType" class="form-label">BloodType<sup><i
                                                        class="bi bi-asterisk text-primary"></i></sup></label>
                                            <select id="inputBloodType" class="form-select" name="blood_type" required>
                                                <option value="A+" {{($student->blood_type == 'A+')?'selected':null}}>A+
                                                </option>
                                                <option value="A-" {{($student->blood_type == 'A-')?'selected':null}}>A-
                                                </option>
                                                <option value="B+" {{($student->blood_type == 'B+')?'selected':null}}>B+
                                                </option>
                                                <option value="B-" {{($student->blood_type == 'B-')?'selected':null}}>B-
                                                </option>
                                                <option value="O+" {{($student->blood_type == 'O+')?'selected':null}}>O+
                                                </option>
                                                <option value="O-" {{($student->blood_type == 'O-')?'selected':null}}>O-
                                                </option>
                                                <option value="AB+" {{($student->blood_type == 'AB+')?'selected':null}}>
                                                    AB+
                                                </option>
                                                <option value="AB-" {{($student->blood_type == 'AB-')?'selected':null}}>
                                                    AB-
                                                </option>
                                                <option
                                                    value="Other" {{($student->blood_type == 'Other')?'selected':null}}>
                                                    Other
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-3">
                                            <label for="inputPhone" class="form-label">Phone<sup><i
                                                        class="bi bi-asterisk text-primary"></i></sup></label>
                                            <input type="text" class="form-control" id="inputPhone" name="phone"
                                                   placeholder="+880 01......" required value="{{$student->phone}}">
                                        </div>

                                        <div class="col-3">
                                            <label for="inputIdCardNumber" class="form-label">Id Card Number<sup><i
                                                        class="bi bi-asterisk text-primary"></i></sup></label>
                                            <input type="text" class="form-control" id="inputIdCardNumber"
                                                   name="id_card_number"
                                                   placeholder="e.g. 2021-03-01-02-01 (Year Semester Class Section Roll)"
                                                   required value="{{$promotion_info->id_card_number}}">
                                        </div>

                                    </div>

                                    <div class="row mt-4 g-3">
                                        <h6>Listar medicamentos:</h6>
                                        <div class="col-12">
                                            <label for="medicines" class="form-label">Medicamentos<sup><i
                                                        class="bi bi-asterisk text-primary"></i></sup></label>
                                            <input type="text" class="form-control" id="medicines" name="medicines"
                                                   placeholder="alergias, condições médicas ou, se não aplicável"
                                                   required value="{{$student->medicines}}">
                                        </div>
                                    </div>

                                    <div class="row mt-4 g-3">
                                        <h6>Additional Fields (Campos Adicionais)</h6>

                                        <div class="col-5">
                                            <label for="date_to_start_school" class="form-label">Date to Start School
                                                (Data
                                                para início na escola)<sup><i
                                                        class="bi bi-asterisk text-primary"></i></sup></label>
                                            <input type="date" class="form-control" id="date_to_start_school"
                                                   name="date_to_start_school"
                                                   placeholder="Birthday" required
                                                   value="{{$student->date_to_start_school}}">
                                        </div>

                                        <div class="col-2">
                                            <label for="inputNationality" class="form-label">Nationality<sup><i
                                                        class="bi bi-asterisk text-primary"></i></sup></label>
                                            <input type="text" class="form-control" id="inputNationality"
                                                   name="nationality"
                                                   placeholder="e.g. Bangladeshi, German, ..." required
                                                   value="{{$student->nationality}}">
                                        </div>

                                        <div class="col-5">
                                            <label for="language_spoken_at_home" class="form-label">Language spoken at
                                                home (Idioma falado em casa)<sup><i
                                                        class="bi bi-asterisk text-primary"></i></sup></label>
                                            <input type="text" class="form-control" id="language_spoken_at_home"
                                                   name="language_spoken_at_home"
                                                   placeholder="Language spoken at home"
                                                   required value="{{$student->language_spoken_at_home}}">
                                        </div>

                                        <div class="col-5">
                                            <label for="last_school_attended" class="form-label">Last School Attended
                                                (Última escola frequentada)<sup><i
                                                        class="bi bi-asterisk text-primary"></i></sup></label>
                                            <input type="text" class="form-control" id="last_school_attended"
                                                   name="last_school_attended"
                                                   placeholder="Last School Attended"
                                                   required value="{{$student->last_school_attended}}">
                                        </div>

                                        <div class="col-5">
                                            <label for="last_grade_enrolled" class="form-label">Last Grade Enrolled
                                                (Última série matriculado)<sup><i
                                                        class="bi bi-asterisk text-primary"></i></sup></label>
                                            <input type="text" class="form-control" id="last_grade_enrolled"
                                                   name="last_grade_enrolled"
                                                   placeholder="Last Grade Enrolled"
                                                   required value="{{$student->last_grade_enrolled}}">
                                        </div>

                                        <div class="col-12">
                                            <label for="last_grade_enrolled" class="form-label">IEP (if not required,
                                                mark NO) (Plano de educação individualizado - Se não for
                                                necessário, marcar "NO")</label>

                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="iep"
                                                       id="attendance_type_yes"
                                                       {{($student->iep == 'yes')?'checked="checked"':null}} value="yes">
                                                <label class="form-check-label" for="attendance_type_yes">
                                                    Yes
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="iep"
                                                       id="attendance_type_no"
                                                       {{($student->iep == 'no')?'checked="checked"':null}} value="no">
                                                <label class="form-check-label" for="attendance_type_no">
                                                    No
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label for="last_grade_enrolled" class="form-label">Special Classroom Needs
                                                (if not required, mark NO) (Necessidades Especiais em
                                                Sala de Aula - Se não for necessário, marcar "NO")</label>

                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                       name="special_classroom_needs"
                                                       id="special_classroom_needs_yes"
                                                       {{($student->special_classroom_needs == 'yes')?'checked="checked"':null}} value="yes">
                                                <label class="form-check-label" for="special_classroom_needs_yes">
                                                    Yes
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                       name="special_classroom_needs"
                                                       id="special_classroom_needs_no"
                                                       {{($student->special_classroom_needs == 'no')?'checked="checked"':null}} value="no">
                                                <label class="form-check-label" for="special_classroom_needs_no">
                                                    No
                                                </label>
                                            </div>
                                        </div>

                                    </div>


                                </div>
                                <div class="row mt-4 g-3">
                                    <h6>Parents' Information</h6>
                                    <div class="col-3">
                                        <label for="inputEmail4" class="form-label">Email<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="email" class="form-control" id="inputEmail4" name="email" required
                                               value="{{$student->email}}">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="cpf" class="form-label">CPF</label>
                                        <input type="text" class="form-control" id="cpf"
                                               name="cpf"
                                               placeholder="CPF"
                                               value="{{$parent_info->cpf}}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="passport" class="form-label">Passport</label>
                                        <input type="text" class="form-control" id="passport"
                                               name="passport"
                                               placeholder="Passport"
                                               value="{{$parent_info->passport}}">
                                    </div>
                                    <div class="col-4">
                                        <label for="inputFatherName" class="form-label">Father Name<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="inputFatherName"
                                               name="father_name"
                                               placeholder="Father Name" required
                                               value="{{$parent_info->father_name}}">
                                    </div>
                                    <div class="col-3">
                                        <label for="inputFatherPhone" class="form-label">Father's Phone<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="inputFatherPhone"
                                               name="father_phone" placeholder="+880 01......" required
                                               value="{{$parent_info->father_phone}}">
                                    </div>
                                    <div class="col-4">
                                        <label for="inputMotherName" class="form-label">Mother Name<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="inputMotherName"
                                               name="mother_name"
                                               placeholder="Mother Name" required
                                               value="{{$parent_info->mother_name}}">
                                    </div>
                                    <div class="col-3">
                                        <label for="inputMotherPhone" class="form-label">Mother's Phone<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="inputMotherPhone"
                                               name="mother_phone" placeholder="+880 01......" required
                                               value="{{$parent_info->mother_phone}}">
                                    </div>
                                    <div class="col-4">
                                        <label for="inputParentAddress" class="form-label">Address<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="inputParentAddress"
                                               name="parent_address" placeholder="634 Main St" required
                                               value="{{$parent_info->parent_address}}">
                                    </div>

                                </div>

                                <div class="row mt-4 g-3">
                                    <h6>Anexar documentos (PDF ou fotos)</h6>

                                    <div class="col-md-6">
                                        <label for="transcript" class="form-label">Transcript (Histórico
                                            Escolar)</label>
                                        <input type="file" name="transcript" class="form-control" id="transcript">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="student_identidade" class="form-label">Students ID (Identidade do
                                            aluno)</label>
                                        <input type="file" name="student_identidade" class="form-control"
                                               id="student_identidade">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="vaccination_record" class="form-label">Vaccination Record (Cartão de
                                            vacina)</label>
                                        <input type="file" name="vaccination_record" class="form-control"
                                               id="vaccination_record">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="digital_student_photo" class="form-label">Digital Student Photo
                                            (Foto
                                            digital do aluno)</label>
                                        <input type="file" name="digital_student_photo" class="form-control"
                                               id="digital_student_photo">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="primary_parent_passport" class="form-label">Primary Parent Passport
                                            (Passaporte primário do pai/responsável</label>
                                        <input type="file" name="primary_parent_passport" class="form-control"
                                               id="primary_parent_passport">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="outros_documentos" class="form-label">Outros documentos (pode
                                            compactar mais documentos e anexar aqui)</label>
                                        <input type="file" name="outros_documentos" class="form-control"
                                               id="outros_documentos">
                                    </div>
                                </div>

                                <div class="row mt-4 g-3">
                                    <div class="col-md-12">
                                        <a href="{{ route('students.pdf-contract', ['id' => $student->id]) }}"
                                           role="button" class="btn btn-sm btn-outline-info" download><i
                                                class="bi bi-eye"></i> Download contract</a>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-sm btn-outline-primary"><i
                                                class="bi bi-person-check"></i> Update
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
    @include('components.photos.photo-input')
    <script src="/js/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="/js/jquery.mask.js"></script>
    <script>
        $('#cpf').mask('000.000.000-00', {reverse: true});
    </script>

@endsection
