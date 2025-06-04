@extends('parts.main')

@section('main-section')

    <div class="page-header">
		<div class="row">
				<div class="col-md-6 col-sm-12">
					<div class="title">
						<h4>Add User</h4>
					</div>
				</div>

			</div>
	</div>

    <div class="pd-20 card-box mb-30">
        <form method="POST" action="{{ route('employee.profile.update') }}">
            @csrf
            @method('PUT')

            <div class="form-group row">
                <label class="col-sm-12 col-md-2 col-form-label">Name</label>
                <div class="col-sm-12 col-md-10">
                    <input
                        type="text"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        placeholder="Name"
                        value="{{ old('name', $employee->name) }}"
                        required
                    />
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-12 col-md-2 col-form-label">Email</label>
                <div class="col-sm-12 col-md-10">
                    <input
                        type="email"
                        name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="Email"
                        value="{{ old('email', $employee->email) }}"
                        required
                    />
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-12 col-md-2 col-form-label">Contact No</label>
                <div class="col-sm-12 col-md-10">
                    <input
                        type="tel"
                        name="contact_no"
                        class="form-control @error('contact_no') is-invalid @enderror"
                        placeholder="Contact Number"
                        value="{{ old('contact_no', $employee->contact_no) }}"
                        required
                    />
                    @error('contact_no')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-12 col-md-2 col-form-label">Role</label>
                <div class="col-sm-12 col-md-10">
                    <input
                        type="text"
                        class="form-control"
                        value="{{ $employee->role }}"
                        disabled
                    />
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-12 col-md-2 col-form-label">Password (leave blank if not changing)</label>
                <div class="col-sm-12 col-md-10">
                    <input
                        type="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Password"
                    />
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-12 col-md-2 col-form-label">Confirm Password</label>
                <div class="col-sm-12 col-md-10">
                    <input
                        type="password"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Confirm Password"
                    />
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-12 col-md-10 offset-md-2">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>




    </div>




@endsection

