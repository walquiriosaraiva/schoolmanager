@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-start">
            @include('layouts.left-menu')
            <div class="col-xs-11 col-sm-11 col-md-11 col-lg-10 col-xl-10 col-xxl-10">
                <div class="row pt-2">
                    <div class="col ps-4">
                        <h1 class="display-6 mb-3">
                            <i class="bi bi-person-lines-fill"></i> Users List
                        </h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Users List</li>
                            </ol>
                        </nav>
                        @include('session-messages')
                        <h6>Filter list by:</h6>
                        <div class="col-md-6">
                            <form class="row g-3" action="{{route('users.search')}}" method="POST"
                                  name="search" id="search">
                                @csrf
                                <label for="role" class="form-label">Roles:</label>
                                <select class="form-select"
                                        id="role" name="role" required>
                                    <option value="">Please select role</option>
                                    @foreach ($roles as $key=>$value)
                                        <option
                                            value="{{$key}}" {{ $key == $role ? 'selected' : ''  }}>{{$value}}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                        <div class="col-md-12">
                            <div class="row bg-white border shadow-sm p-3 mt-4">
                                <table class="table mt-4" id="users">
                                    <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Photo</th>
                                        <th scope="col">First Name</th>
                                        <th scope="col">Last Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <th scope="row">{{$user->id}}</th>
                                            <td>
                                                @if (isset($user->photo))
                                                    <img src="{{asset('/storage'.$user->photo)}}"
                                                         class="rounded" alt="Profile picture" height="30" width="30">
                                                @else
                                                    <i class="bi bi-person-square"></i>
                                                @endif
                                            </td>
                                            <td>{{$user->first_name}}</td>
                                            <td>{{$user->last_name}}</td>
                                            <td>{{$user->email}}</td>
                                            <td>{{formatPhone($user->phone)}}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if($user->role === 'student')
                                                        <a href="{{url('students/view/profile/'.$user->id)}}"
                                                           role="button" class="btn btn-sm btn-outline-primary"><i
                                                                class="bi bi-eye"></i> Profile</a>
                                                        &nbsp;
                                                        <a href="{{route('student.edit.show', ['id' => $user->id])}}"
                                                           role="button" class="btn btn-sm btn-outline-primary"><i
                                                                class="bi bi-pen"></i> Edit</a>
                                                    @endif
                                                    @if($user->role === 'teacher')
                                                        <a href="{{url('teachers/view/profile/'.$user->id)}}"
                                                           role="button" class="btn btn-sm btn-outline-primary"><i
                                                                class="bi bi-eye"></i> Profile</a>
                                                        &nbsp;
                                                        <a href="{{route('teacher.edit.show', ['id' => $user->id])}}"
                                                           role="button" class="btn btn-sm btn-outline-primary"><i
                                                                class="bi bi-pen"></i> Edit</a>
                                                    @endif
                                                    @if($user->role === 'admin')
                                                        <a href="{{route('users.show', $user->id)}}"
                                                           role="button" class="btn btn-sm btn-outline-primary"><i
                                                                class="bi bi-eye"></i> Profile</a>
                                                        &nbsp;
                                                        <a href="{{route('users.edit', $user->id)}}"
                                                           role="button" class="btn btn-sm btn-outline-primary"><i
                                                                class="bi bi-pen"></i> Edit</a>
                                                    @endif
                                                    &nbsp;
                                                    <form action="{{ route('users.destroy', $user->id)}}"
                                                          method="post"
                                                          style="display: inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm"
                                                                type="submit">Excluir
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @include('layouts.footer')
            </div>
        </div>
    </div>
    <script src="/js/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
    <script>

        $(document).ready(function () {

            $("#role").change(function () {
                var student = $(this).val();
                var _token = $('input[name="_token"]').val();
                $("#search").submit();

            });

        });
    </script>
@endsection
