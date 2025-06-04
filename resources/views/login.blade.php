@extends('parts.mainGuest')

@section('main-section')

        <div class="login-wrap d-flex align-items-center flex-wrap justify-content-center" >
			<div class="container">
				<div class="row align-items-center">
					<div class="col-md-6 col-lg-7">
						<img src="vendors/images/login-page-img.png" alt="" />
					</div>
					<div class="col-md-6 col-lg-5">
						<div class="login-box bg-white box-shadow border-radius-10">
							<div class="login-title">
								<h2 class="text-center text-primary">Login To Crynoverse</h2>
							</div>
							<form method="POST" action="{{ route('employee.login.submit') }}">
                                @csrf

                                <div class="input-group custom mb-3">
                                    <input
                                        type="text"
                                        name="email"
                                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        placeholder="Email"
                                        value="{{ old('email') }}"
                                        required
                                    />
                                    <div class="input-group-append custom">
                                        <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                                <div class="input-group custom mb-3">
                                    <input
                                        type="password"
                                        name="password"
                                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        placeholder="**********"
                                        required
                                    />
                                    <div class="input-group-append custom">
                                        <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                                <div class="row pb-30">
                                    <div class="col-6">
                                        <div class="custom-control custom-checkbox">
                                            <input
                                                type="checkbox"
                                                class="custom-control-input"
                                                id="customCheck1"
                                                name="remember"
                                            />
                                            <label class="custom-control-label" for="customCheck1">Remember</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="forgot-password">
                                            <a href="#">Forgot Password</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="input-group mb-0">
                                            <button type="submit" class="btn btn-primary btn-lg btn-block">Sign In</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

						</div>
					</div>
				</div>
			</div>
		</div>


@endsection

