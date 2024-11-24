<!-- resources/views/user_form.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Form</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        .container {
            margin-top: 50px;
        }

        .error {
            color: red;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .form-group button {
            padding: 10px 20px;
            border-radius: 5px;
            border: 1px solid #007bff;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #0056b3;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            border: 1px solid #ccc;
            padding: 10px;
        }
    </style>

</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">
                        <h4>User Form</h4>
                    </div>
                    <div class="card-body">

                        <form action="{{ route('api.users.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                                <p class="error" id="name_error"></p>
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                                <p class="error" id="email_error"></p>
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" name="phone" id="phone" class="form-control" required>
                                <p class="error" id="phone_error"></p>
                            </div>

                            <div class="form-group">
                                <label for="profile_image">Profile Image</label>
                                <input type="file" name="profile_image" id="profile_image" class="form-control" required>
                                <p class="error" id="profile_image_error"></p>
                            </div>

                            <div class="form-group">
                                <label for="role_id">Role</label>
                                <select name="role_id" id="role_id" class="form-control" required>
                                    <option value="">Select Role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <p class="error" id="role_id_error"></p>
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                                <p class="error" id="description_error"></p>
                            </div>

                            <div class="form-group">
                                <button type="submit">Submit</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h4>Users</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <select id="role_id_search" class="form-control">
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <button onclick="refresh()">Refresh</button>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Role</th>
                                    <th>Profile Image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr id="user_{{ $user->id }}">
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>{{ $user->role->name }}</td>
                                        <td><img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}" style="width: 50px; height: 50px;"></td>
                                        <td>
                                            <a class="btn btn-danger" onclick="deleteUser({{$user->id}})">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                </div>
        </div>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('#phone').on('input', function() {
            var phone = $(this).val();
            var phonePattern = /^[6-9][0-9]{9}$/;

            if (!phone.match(phonePattern)) {
                $(this).next('.error').text('Invalid phone number');
            } else {
                $(this).next('.error').text('');
            }
        });

        $('#email').change(function() {
            var email = $(this).val();
            var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

            if (!email.match(emailPattern)) {
                $(this).next('.error').text('Invalid email address');
            } else {
                $(this).next('.error').text('');
            }
        });

        $('#profile_image').change(function() {
            var file = $(this).val();
            var extension = file.split('.').pop().toLowerCase();

            if (extension != 'jpg' && extension != 'jpeg' && extension != 'png') {
                $(this).next('.error').text('Invalid file format');
                $(this).val('');
            } else if (this.files[0].size > 2000000) {
                $(this).next('.error').text('File size must be less than 2MB');
                $(this).val('');
            } else {
                $(this).next('.error').text('');
            }
        });

        $('form').submit(function(e) {
            if($('.error').text() != '') {
                alert('Please fill all the fields correctly');
                return false;
            }
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);
                    
                    $('form')[0].reset();
                    alert(response.data.message);
                    appendUser(response.data.user);
                },
                error: function(error) {
                    console.log(error);
                    $.each(error.responseJSON, function(key, value) {
                        $('#' + key + '_error').text(value);
                        console.log(key, value);
                    });
                }
            });
        });

        function appendUser(user) {
            var html = '<tr id="user_' + user.id + '">';
            html += '<td>' + user.name + '</td>';
            html += '<td>' + user.email + '</td>';
            html += '<td>' + user.phone + '</td>';
            html += '<td>' + user.role + '</td>';
            html += '<td><img src="' + user.profile_image + '" alt="' + user.name + '" style="width: 50px; height: 50px;"></td>';
            html += '<td><a class="btn btn-danger" onclick="deleteUser(' + user.id + ')">Delete</a></td>';
            html += '</tr>';

            $('tbody').append(html);
        }

        window.deleteUser = function(id) {
            if(confirm('Are you sure you want to delete this user?')) {
                $.ajax({
                    type: 'DELETE',
                    url: '{{ route('api.users.index') }}/' + id,
                    success: function(response) {
                        $('#user_' + id).remove();
                        alert(response.data.message);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }
        }

        $('#role_id_search').change(function() {
            var role_id = $(this).val();

            $.ajax({
                type: 'GET',
                url: '{{ route('api.users.index') }}?role_id=' + role_id,
                success: function(response) {
                    $('tbody').html('');
                    $.each(response.data.users, function(index, user) {
                        appendUser(user);
                    });
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });

        window.refresh = function() {
            $('#role_id_search').val('');
            $.ajax({
                type: 'GET',
                url: '{{ route('api.users.index') }}',
                success: function(response) {
                    $('tbody').html('');
                    $.each(response.data.users, function(index, user) {
                        appendUser(user);
                    });
                    alert(response.data.message);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    });
</script>
</html>