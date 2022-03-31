@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-start">
            @include('layouts.left-menu')
            <div class="col-xs-11 col-sm-11 col-md-11 col-lg-10 col-xl-10 col-xxl-10">
                <div class="row pt-2">
                    <div class="col ps-4">
                        <h1 class="display-6 mb-3">
                            <i class="bi bi-person-lines-fill"></i> Add Student
                        </h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Add Student</li>
                            </ol>
                        </nav>

                        @include('session-messages')

                        <p class="text-primary">
                            <small><i class="bi bi-exclamation-diamond-fill me-2"></i> Remember to create related
                                "Class" and "Section" before adding student</small>
                        </p>
                        <div class="mb-4">
                            <form class="row g-3" action="{{route('school.student.create')}}" method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id_card_number" id="id_card_number" value="{{$idCard}}">

                                <div class="col-md-3">
                                    <label for="formFile" class="form-label">Photo</label>
                                    <input class="form-control" type="file" id="formFile" onchange="previewFile()">
                                    <div id="previewPhoto"></div>
                                    <input type="hidden" id="photoHiddenInput" name="photo" value="">
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label for="inputFirstName" class="form-label">First Name<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="inputFirstName" name="first_name"
                                               placeholder="First Name" required value="{{old('first_name')}}">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="inputLastName" class="form-label">Last Name<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="inputLastName" name="last_name"
                                               placeholder="Last Name" required value="{{old('last_name')}}">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="inputEmail4" class="form-label">Email<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="email" class="form-control" id="inputEmail4" name="email" required
                                               value="{{old('email')}}">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="inputPassword4" class="form-label">Password<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="password" class="form-control" id="inputPassword4" name="password"
                                               required>
                                    </div>

                                    <div class="col-4">
                                        <label for="application_grade" class="form-label">Application Grade (Série a
                                            ser
                                            matriculado)<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <select id="application_grade" class="form-select" name="application_grade"
                                                required>
                                            <option value='1th'>1th
                                            </option>
                                            <option value='2th'>2th
                                            </option>
                                            <option value='3th'>3th
                                            </option>
                                            <option value='4th'>4th
                                            </option>
                                            <option value='5th'>5th
                                            </option>
                                            <option value='6th'>6th
                                            </option>
                                            <option value='7th'>7th
                                            </option>
                                            <option value='8th'>8th
                                            </option>
                                            <option value='9th'>9th
                                            </option>
                                            <option value='10th'>10th
                                            </option>
                                            <option value='11th'>11th
                                            </option>
                                            <option value='12th'>12th
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="inputBirthday" class="form-label">Birthday<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="date" class="form-control" id="inputBirthday" name="birthday"
                                               placeholder="Birthday" required value="{{old('birthday')}}">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="inputState" class="form-label">Gender<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <select id="inputState" class="form-select" name="gender" required>
                                            <option value="Male" {{old('gender') == 'male' ? 'selected' : ''}}>Male
                                            </option>
                                            <option value="Female" {{old('gender') == 'female' ? 'selected' : ''}}>
                                                Female
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-2">
                                        <label for="ethnicity" class="form-label">Ethnicity (Etnia)<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <select id="ethnicity" class="form-select" name="ethnicity" required>
                                            <option value='Brancos'>
                                                Brancos
                                            </option>
                                            <option value='Pardos'>
                                                Pardos
                                            </option>
                                            <option value='Pretos'>
                                                Pretos
                                            </option>
                                            <option value='Amarelos'>
                                                Amarelos
                                            </option>
                                            <option value='Indígenas'>
                                                Indígenas
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-3-md">
                                        <label for="inputAddress" class="form-label">Address<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="inputAddress" name="address"
                                               placeholder="634 Main St" required value="{{old('address')}}">
                                    </div>

                                    <div class="col-3-md">
                                        <label for="inputAddress2" class="form-label">Address 2</label>
                                        <input type="text" class="form-control" id="inputAddress2" name="address2"
                                               placeholder="Apartment, studio, or floor" value="{{old('address2')}}">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="inputCity" class="form-label">City<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="inputCity" name="city"
                                               placeholder="Dhaka..." required value="{{old('city')}}">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="inputZip" class="form-label">Zip<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="inputZip" name="zip" required
                                               value="{{old('zip')}}">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="inputBloodType" class="form-label">BloodType<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <select id="inputBloodType" class="form-select" name="blood_type" required>
                                            <option {{old('blood_type') == 'A+' ? 'selected' : ''}}>A+</option>
                                            <option {{old('blood_type') == 'A-' ? 'selected' : ''}}>A-</option>
                                            <option {{old('blood_type') == 'B+' ? 'selected' : ''}}>B+</option>
                                            <option {{old('blood_type') == 'B-' ? 'selected' : ''}}>B-</option>
                                            <option {{old('blood_type') == 'O+' ? 'selected' : ''}}>O+</option>
                                            <option {{old('blood_type') == 'O-' ? 'selected' : ''}}>O-</option>
                                            <option {{old('blood_type') == 'AB+' ? 'selected' : ''}}>AB+</option>
                                            <option {{old('blood_type') == 'AB-' ? 'selected' : ''}}>AB-</option>
                                            <option {{old('blood_type') == 'other' ? 'selected' : ''}}>Other</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="inputPhone" class="form-label">Phone<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="inputPhone" name="phone"
                                               placeholder="+880 01......" required value="{{old('phone')}}">
                                    </div>
                                </div>

                                <div class="row mt-4 g-3">
                                    <h6>Listar medicamentos:</h6>
                                    <div class="col-12">
                                        <label for="medicines" class="form-label">Medicamentos</label>
                                        <input type="text" class="form-control" id="medicines" name="medicines"
                                               placeholder="alergias, condições médicas ou, se não aplicável"
                                               value="{{old('medicines')}}">
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
                                               value="{{old('date_to_start_school')}}">
                                    </div>

                                    <div class="col-2">
                                        <label for="inputNationality" class="form-label">Nationality<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="inputNationality"
                                               name="nationality"
                                               placeholder="e.g. Bangladeshi, German, ..." required
                                               value="{{old('nationality')}}">
                                    </div>

                                    <div class="col-5">
                                        <label for="language_spoken_at_home" class="form-label">Language spoken at
                                            home (Idioma falado em casa)<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="language_spoken_at_home"
                                               name="language_spoken_at_home"
                                               placeholder="Language spoken at home"
                                               required value="{{old('language_spoken_at_home')}}">
                                    </div>

                                    <div class="col-5">
                                        <label for="last_school_attended" class="form-label">Last School Attended
                                            (Última escola frequentada)<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="last_school_attended"
                                               name="last_school_attended"
                                               placeholder="Last School Attended"
                                               required value="{{old('last_school_attended')}}">
                                    </div>

                                    <div class="col-5">
                                        <label for="last_grade_enrolled" class="form-label">Last Grade Enrolled
                                            (Última série matriculado)<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <select id="last_grade_enrolled" class="form-select" name="last_grade_enrolled"
                                                required>
                                            <option value='1th'>1th
                                            </option>
                                            <option value='2th'>2th
                                            </option>
                                            <option value='3th'>3th
                                            </option>
                                            <option value='4th'>4th
                                            </option>
                                            <option value='5th'>5th
                                            </option>
                                            <option value='6th'>6th
                                            </option>
                                            <option value='7th'>7th
                                            </option>
                                            <option value='8th'>8th
                                            </option>
                                            <option value='9th'>9th
                                            </option>
                                            <option value='10th'>10th
                                            </option>
                                            <option value='11th'>11th
                                            </option>
                                            <option value='12th'>12th
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <label for="attendance_type" class="form-label">IEP (if not required,
                                            mark NO) (Plano de educação individualizado - Se não for
                                            necessário, marcar "NO")</label>

                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="iep"
                                                   id="attendance_type_yes"
                                                   value="yes">
                                            <label class="form-check-label" for="attendance_type">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="iep"
                                                   id="attendance_type_no"
                                                   value="no">
                                            <label class="form-check-label" for="attendance_type">
                                                No
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="special_classroom_needs" class="form-label">Special Classroom Needs
                                            (if not required, mark NO) (Necessidades Especiais em
                                            Sala de Aula - Se não for necessário, marcar "NO")</label>

                                        <div class="form-check">
                                            <input class="form-check-input" type="radio"
                                                   name="scn"
                                                   id="special_classroom_needs_yes"
                                                   value="yes">
                                            <label class="form-check-label" for="special_classroom_needs">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio"
                                                   name="scn"
                                                   id="special_classroom_needs_no"
                                                   value="no">
                                            <label class="form-check-label" for="special_classroom_needs">
                                                No
                                            </label>
                                        </div>
                                    </div>

                                </div>

                                <div class="row mt-4 g-3">
                                    <h6>Parents' Information</h6>

                                    <div class="col-md-3">
                                        <label for="cpf" class="form-label">CPF</label>
                                        <input type="text" class="form-control" id="cpf"
                                               name="cpf"
                                               placeholder="CPF"
                                               value="{{old('cpf')}}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="passport" class="form-label">Passport</label>
                                        <input type="text" class="form-control" id="passport"
                                               name="passport"
                                               placeholder="Passport"
                                               value="{{old('passport')}}">
                                    </div>
                                    <div class="col-4">
                                        <label for="inputFatherName" class="form-label">Father Name<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="inputFatherName"
                                               name="father_name"
                                               placeholder="Father Name" required
                                               value="{{old('father_name')}}">
                                    </div>
                                    <div class="col-3">
                                        <label for="inputFatherPhone" class="form-label">Father's Phone<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="inputFatherPhone"
                                               name="father_phone" placeholder="+880 01......" required
                                               value="{{old('father_phone')}}">
                                    </div>
                                    <div class="col-4">
                                        <label for="inputMotherName" class="form-label">Mother Name<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="inputMotherName"
                                               name="mother_name"
                                               placeholder="Mother Name" required
                                               value="{{old('mother_name')}}">
                                    </div>
                                    <div class="col-3">
                                        <label for="inputMotherPhone" class="form-label">Mother's Phone<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="inputMotherPhone"
                                               name="mother_phone" placeholder="+880 01......" required
                                               value="{{old('mother_phone')}}">
                                    </div>
                                    <div class="col-6">
                                        <label for="inputParentAddress" class="form-label">Address<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="inputParentAddress"
                                               name="parent_address" placeholder="634 Main St" required
                                               value="{{old('parent_address')}}">
                                    </div>

                                </div>
                                <div class="row mt-4 g-3">
                                    <h6>Academic Information</h6>
                                    <div class="col-md-6">
                                        <label for="inputAssignToClass" class="form-label">Assign to class:<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <select onchange="getSections(this);" class="form-select"
                                                id="inputAssignToClass" name="class_id" required>
                                            @isset($school_classes)
                                                <option selected disabled>Please select a class</option>
                                                @foreach ($school_classes as $school_class)
                                                    <option
                                                        value="{{$school_class->id}}">{{$school_class->class_name}}</option>
                                                @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="inputAssignToSection" class="form-label">Assign to section:<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <select class="form-select" id="inputAssignToSection" name="section_id"
                                                required>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="inputBoardRegistrationNumber" class="form-label">Board registration
                                            No.</label>
                                        <input type="text" class="form-control" id="inputBoardRegistrationNumber"
                                               name="board_reg_no" placeholder="Registration No."
                                               value="{{old('board_reg_no')}}">
                                    </div>
                                    <input type="hidden" name="session_id" value="{{$current_school_session_id}}">

                                </div>

                                <div class="row mt-4 g-3">
                                    <h6>Academic Payment</h6>
                                    <div class="col-md-3">
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

                                    <div class="col-md-3">
                                        <label for="percentage_discount" class="form-label">Percentage discount<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <input type="text" class="form-control" id="percentage_discount"
                                               name="percentage_discount"
                                               placeholder="Discount" required
                                               value="{{old('percentage_discount')}}">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="contract_duration" class="form-label">Contract duration:<sup><i
                                                    class="bi bi-asterisk text-primary"></i></sup></label>
                                        <select class="form-select"
                                                id="contract_duration" name="contract_duration" required>
                                            <option value="">Please select a contract duration</option>
                                            @foreach ($months as $key=>$value)
                                                <option
                                                    value="{{$key}}">{{$value}}</option>
                                            @endforeach
                                        </select>
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

                                <div class="row mt-4">
                                    <div class="col-12-md">
                                        <button type="submit" class="btn btn-sm btn-outline-primary"><i
                                                class="bi bi-person-plus"></i> Add
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
    <script>
        function getSections(obj) {
            var class_id = obj.options[obj.selectedIndex].value;

            var url = "{{route('get.sections.courses.by.classId')}}?class_id=" + class_id

            fetch(url)
                .then((resp) => resp.json())
                .then(function (data) {
                    var sectionSelect = document.getElementById('inputAssignToSection');
                    sectionSelect.options.length = 0;
                    data.sections.unshift({'id': 0, 'section_name': 'Please select a section'})
                    data.sections.forEach(function (section, key) {
                        sectionSelect[key] = new Option(section.section_name, section.id);
                    });
                })
                .catch(function (error) {
                    console.log(error);
                });
        }
    </script>
    @include('components.photos.photo-input')
    <script src="/js/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="/js/jquery.mask.js"></script>
    <script>
        $('#cpf').mask('000.000.000-00', {reverse: true});
    </script>
@endsection
