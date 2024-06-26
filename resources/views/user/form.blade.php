<div id="modal-form" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <form id="user-form" action="" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name" class="control-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required autofocus>
                        <span class="help-block with-errors"></span>
                    </div>

                    <!-- Gender -->
                    <div class="form-group">
                        <label for="gender_id" class="control-label">Gender</label>
                            <select name="gender_id" id="gender_id" class="form-control" required>
                                <option value="">Select Gender</option>
                                @foreach ($genders as $id => $gender)
                                    <option value="{{ $id }}">{{ $gender }}</option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>       
                    </div>

                    <!-- Role -->
                    <div class="form-group">
                        <label for="role_id" class="control-label">Role</label>
                            <select name="role_id" id="role_id" class="form-control" required>
                                <option value="">Select Role</option>
                                @foreach ($roles as $id => $role)
                                    <option value="{{ $id }}">{{ $role }}</option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                    </div>

                    <div class="form-group">
                        <label for="username" class="control-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" autofocus>
                        <span class="help-block with-errors"></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="control-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                            <span class="help-block with-errors"></span>
                    </div>

                    <div class="form-group">
                        <label for="password" class="control-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" 
                            minlength="6">
                            <span class="help-block with-errors"></span>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="control-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" 
                                data-match="#password">
                            <span class="help-block with-errors"></span>
                    </div>

                    <div class="form-group">
                        <label for="contact_number" class="control-label">Contact Number</label>
                        <input type="text" class="form-control" id="contact_number" name="contact_number">
                    </div>

                    <div class="form-group">
                        <label for="user_image" class="control-label">User Image</label>
                            <input type="file" name="user_image" id="user_image" class="form-control">
                            <span class="help-block with-errors"></span>
                            <img id="image-preview" src="#" alt="User Image" style="display:none; max-height:100px; margin-top:10px;">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-save"></i> Save</button>
                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>