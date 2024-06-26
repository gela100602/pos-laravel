@extends('layouts.master')

@section('title', 'User List')

@section('breadcrumb')
    @parent
    <li class="active">User List</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <div class="btn-group">
                    <button onclick="addForm('{{ route('users.store') }}')" class="btn btn-success btn-flat"><i class="fa fa-plus-circle"></i> Add New User</button>
                    <button onclick="deleteSelected('{{ route('users.delete_selected') }}')" class="btn btn-danger btn-flat"><i class="fa fa-trash"></i> Delete</button>
                </div>
            </div>
            <div class="box-body table-responsive">
                <form action="" method="post" class="form-user">
                    @csrf
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="5%"><input type="checkbox" name="select_all" id="select_all"></th>
                                <th width="5%">#</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Role</th>
                                <th>Email</th>
                                <th>Username</th>
                                <th>Contact Number</th>
                                <th width="15%"><i class="fa fa-cog"></i></th>
                            </tr>
                        </thead>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

@include('user.form', ['genders' => $genders, 'roles' => $roles])

@endsection

@push('scripts')
<script>
    $(function () {
        let table = $('.table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('users.data') }}',
            },
            columns: [
                {data: 'select_all', searchable: false, sortable: false},
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {
                    data: 'user_image',
                    searchable: false,
                    sortable: false,
                    render: function(data, type, row) {
<<<<<<< HEAD
                        return data ? '<img src="{{ asset('storage/user_image') }}/' + data + '" alt="User Image" style="max-height: 50px;">' : '<img src="{{ asset('storage/user_image/default-user.png') }}" alt="Default Image" style="max-height: 50px;">';
=======
                        return data ? '<img src="' + '{{ asset('storage/user_image') }}/' + data + '" alt="Image Preview" style="border-radius: 50%; max-height: 50px;">' : '';
>>>>>>> 1d4c1978a9217d8ffa25cf34cb56683233592e71
                    },
                },
                {data: 'name'},
                {data: 'gender'},
                {data: 'role'},
                {data: 'email'},
                {data: 'username'},
                {data: 'contact_number'},
                {data: 'action', searchable: false, sortable: false},
            ]
        });

        // Ensure CSRF token is included in all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#user-form').validator().on('submit', function (e) {
            if (!e.isDefaultPrevented()) {
                let url = $(this).attr('action');
                let method = $(this).find('[name=_method]').val() === 'PUT' ? 'PUT' : 'POST';
                
                // Create FormData object and append necessary fields
                let formData = new FormData(this);
                formData.append('_method', method);
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $('#modal-form').modal('hide');
                        $('#user-form')[0].reset();
                        table.ajax.reload();
                    },
                    error: function (xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            let errors = xhr.responseJSON.errors;
                            for (let field in errors) {
                                let errorMessages = errors[field];
                                alert(field + ": " + errorMessages.join(", "));
                            }
                        } else {
                            alert('Unable to save data');
                        }
                    }
                });

                return false;
            }
        });

        $('#modal-form').on('hidden.bs.modal', function () {
<<<<<<< HEAD
            $('#image-preview').hide().attr('src', '');
=======
            $('#image-preview').hide().attr('src', '{{ asset("storage/user_image/default-user.png") }}');
>>>>>>> 1d4c1978a9217d8ffa25cf34cb56683233592e71
            $('#user-form')[0].reset();
            $('#user-form [name=_method]').val('POST');
            $('#user-form').attr('action', '');
        });

        $('[name=select_all]').on('click', function () {
            $(':checkbox').prop('checked', this.checked);
        });

        $('#user_image').change(function () {
            readURL(this);
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Add User');
        $('#user-form')[0].reset();
        $('#user-form').attr('action', url);
        $('#user-form [name=_method]').val('POST');
        $('#name').focus();
        $('#image-preview').hide().attr('src', '');
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit User');
        $('#user-form').attr('action', url);
        $('#user-form [name=_method]').val('PUT');

        $.get(url)
            .done(function (response) {
                $('#name').val(response.name);
                $('#gender_id').val(response.gender_id);
                $('#role_id').val(response.role_id);
                $('#email').val(response.email);
                $('#username').val(response.username);
                $('#contact_number').val(response.contact_number);
<<<<<<< HEAD
                if(response.user_image) {
                    $('#image-preview').show().attr('src', '{{ asset('storage/user_image') }}/' + response.user_image);
                } else {
                    $('#image-preview').show().attr('src', '{{ asset('storage/user_image/default-user.png') }}');
=======

                if (response.user_image) {
                    $('#image-preview').attr('src', '{{ asset("storage/user_image") }}/' + response.user_image).show();
                } else {
                    $('#image-preview').attr('src', '{{ asset("storage/user_image/default-user.png") }}').show();
>>>>>>> 1d4c1978a9217d8ffa25cf34cb56683233592e71
                }
                
            })
            .fail(function (xhr, status, error) {
                console.error('Error fetching data:', error);
                alert('Unable to display data');
            });
    }

    function deleteData(url) {
        if (confirm('Are you sure you want to delete this user?')) {
            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    '_token': $('[name=csrf-token]').attr('content')
                },
                success: function(response) {
                    table.ajax.reload();
                },
                error: function(xhr) {
                    console.error('Error deleting user:', xhr);
                    alert('Unable to delete user');
                }
            });
        }
    }

    function deleteSelected(url) {
        let selectedUsers = [];
        $('input[name="user_id"]:checked').each(function() {
            selectedUsers.push($(this).val());
        });

        if (selectedUsers.length === 0) {
            alert('Please select at least one user to delete.');
            return;
        }

        if (confirm('Are you sure you want to delete selected users?')) {
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    '_token': $('[name=csrf-token]').attr('content'),
                    'ids': selectedUsers
                },
                success: function(response) {
                    table.ajax.reload();
                },
                error: function(xhr) {
                    console.error('Error deleting users:', xhr);
                    alert('Unable to delete selected users');
                }
            });
        }
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#image-preview').show().attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush