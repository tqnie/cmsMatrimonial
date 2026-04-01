@extends('frontend.layouts.app')

@section('content')
<div class="py-4 py-lg-5">
	<div class="container">
		<div class="row">
			<div class="col-xxl-6 col-xl-6 col-md-8 mx-auto">
				<div class="card">
					<div class="card-body">

						<div class="mb-5 text-center">
							<h1 class="h3 text-primary mb-0">{{ translate('Create Your Account') }}</h1>
							<p>{{ translate('Fill out the form to get started') }}.</p>
						</div>
						<form class="form-default" id="reg-form" role="form" action="{{ route('register') }}" method="POST">
							@csrf
							 @php $on_behalves = \App\Models\OnBehalf::all(); @endphp
								@if ($on_behalves->isNotEmpty())
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group mb-3">
											<label class="form-label"
												for="on_behalf">{{ translate('On Behalf') }}</label>
											<select
												class="form-control aiz-selectpicker @error('on_behalf') is-invalid @enderror"
												name="on_behalf" {{ $on_behalves->isNotEmpty() ? 'required' : '' }}>
												@foreach ($on_behalves as $on_behalf)
												<option value="{{ $on_behalf->id }}">{{ $on_behalf->name }}
												</option>
												@endforeach
											</select>
											@error('on_behalf')
											<span class="invalid-feedback"
												role="alert">{{ $message }}</span>
											@enderror
										</div>
									</div>
								</div>
								@endif
							<div class="row">
						        <div class="col-lg-6">
						            <div class="form-group mb-3">
										<label class="form-label" for="name">{{ translate('First Name') }}</label>
										<input type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" id="first_name" placeholder="{{translate('First Name')}}"  required>
										@error('first_name')
											<span class="invalid-feedback" role="alert">{{ $message }}</span>
										@enderror
						            </div>
						        </div>
								<div class="col-lg-6">
									<div class="form-group mb-3">
										<label class="form-label" for="name">{{ translate('Last Name') }}</label>
										<input type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" id="last_name" placeholder="{{ translate('Last Name') }}"  required>
										@error('last_name')
										<span class="invalid-feedback" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>
    						</div>
														<div class="row">
								<div class="col-lg-6">
									<div class="form-group mb-3">
										<label class="form-label" for="gender">{{ translate('Gender') }}</label>
										<select id="gender" class="form-control aiz-selectpicker select_gender" name="gender" required>
											<option startdate="{{ get_max_date_male() }}" value="1"  selected>Male</option>
											<option startdate="{{ get_max_date_female() }}" value="2" >Female</option>
										</select>
										@error('gender')
										<span class="invalid-feedback" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group mb-3">
										<label class="form-label" for="name">{{ translate('Date Of Birth') }}</label>
										<input type="text" id="date_of_birth" class="form-control aiz-date-range" name="date_of_birth"
                                        placeholder="Date Of Birth" data-single="true" data-show-dropdown="true" data-max-date="{{ get_max_date_male() }}" 
										autocomplete="off" required>
										@error('date_of_birth')
										<span class="invalid-feedback" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>
							</div>
							@if (addon_activation('otp_system'))
                                <div>
                                    <div id="emailOrPhoneDiv">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <label class="form-label"
                                                for="email">{{ translate('Email / Phone') }}</label>
                                            <button class="btn btn-link p-0 opacity-50 text-reset fs-12"
                                                type="button"
                                                onclick="toggleEmailPhone(this)">{{ translate('Use Email Instead') }}</button>
                                        </div>
                                        <div class="form-group phone-form-group mb-3">
                                            <div class="input-group registration-iti">
                                                <input type="tel" id="phone-code"
                                                    class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                                    value="{{ old('phone') }}" placeholder="" name="phone"
                                                    autocomplete="off">
                                                @if(get_setting('registration_verification') == '1')
                                                <button class="btn btn-primary ml-2" type="button" id="sendOtpPhoneBtn" onclick="sendVerificationCode(this)">
                                                            {{ translate('Verify') }} 
                                                </button>
                                                @endif
                                            </div>
                                        </div>

                                        <input type="hidden" name="country_code" value="">

                                        <div class="form-group email-form-group mb-3 d-none">
                                            <div class="input-group">
                                                <input type="email"
                                                    class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }} "
                                                    value="{{ old('email') }}"
                                                    placeholder="{{ translate('Email') }}" name="email" id="signinAddonEmail"
                                                    autocomplete="off">
                                                    @if(get_setting('registration_verification') == '1')
                                                    <button class="btn btn-primary ml-2" type="button" id="sendOtpBtn" onclick="sendVerificationCode(this)">
                                                            {{ translate('Verify') }} 
                                                    </button>
                                                    @endif
                                            </div>
                                            @if ($errors->has('email'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group mb-3 d-none">
                                        <label class="form-label" for="verification_code">{{ translate('Verification Code') }}</label>
                                        <div class="input-group">
                                            <input type="text"
                                                class="form-control @error('verification_code') is-invalid @enderror border-right-0"
                                                name="code" id="verification_code"
                                                placeholder="{{ translate('Verification Code') }}"
                                                maxlength="6">
                                            <span class="btn border border-left-0" id="verifyOtpBtn">
                                                <i class="las la-lg la-arrow-right"></i> 
                                            </span>
                                            @error('otp')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group mb-3 email-phone-div" id="emailOrPhoneDiv">
                                            <label class="form-label"
                                                for="email">{{ translate('Email address') }}</label>
                                            <div class="input-group">
                                                <input type="email"
                                                    class="form-control @error('email') is-invalid @enderror "
                                                    name="email" id="signinSrEmail"
                                                    placeholder="{{ translate('Email Address') }}">
                                                    @if(get_setting('registration_verification') == '1')
                                                    <button class="btn btn-primary ml-2" type="button" id="sendOtpBtn" onclick="sendVerificationCode()">
                                                        {{ translate('Verify') }} 
                                                    </button>
                                                    @endif
                                            </div>
                                            @error('email')
                                            <span class="invalid-feedback"
                                                role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>


                                        <div class="form-group mb-3 d-none">
                                            <label class="form-label" for="verification_code">{{ translate('Verification Code') }}</label>
                                            <div class="input-group">
                                                <input type="text"
                                                    class="form-control @error('verification_code') is-invalid @enderror border-right-0"
                                                    name="code" id="verification_code"
                                                    placeholder="{{ translate('Verification Code') }}"
                                                    maxlength="6">
                                                <span class="btn border border-left-0" id="verifyOtpBtn">
                                                    <i class="las la-lg la-arrow-right"></i> 
                                                </span>
                                                @error('otp')
                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                @endif
							<div class="row">
								<div class="col-lg-6">
									<div class="form-group mb-3">
										<label class="form-label" for="password">{{ translate('Password') }}</label>
										<input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="********" aria-label="********" required>
										<small>{{ translate('Minimun 8 characters') }}</small>
										@error('password')
										<span class="invalid-feedback" role="alert">{{ $message }}</span>
										@enderror
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group mb-3">
										<label class="form-label" for="password-confirm">{{ translate('Confirm password') }}</label>
										<input type="password" class="form-control" name="password_confirmation" placeholder="********" required>
										<small>{{ translate('Minimun 8 characters') }}</small>
									</div>
								</div>
							</div>

							@if(addon_activation('referral_system'))
							<div class="row">
								<div class="col-lg-12">
									<div class="form-group mb-3">
										<label class="form-label" for="email">{{ translate('Referral Code') }}</label>
										<input type="text" class="form-control{{ $errors->has('referral_code') ? ' is-invalid' : '' }}" value="{{ old('referral_code') }}" placeholder="{{  translate('Referral Code') }}" name="referral_code">
										@if ($errors->has('referral_code'))
											<span class="invalid-feedback" role="alert">
												<strong>{{ $errors->first('referral_code') }}</strong>
											</span>
										@endif
									</div>
								</div>
							</div>
							@endif

							<!-- Recaptcha -->
							@if(get_setting('google_recaptcha_activation') == 1 && get_setting('recaptcha_user_register') == 1)

							@if ($errors->has('g-recaptcha-response'))
							<span class="border invalid-feedback rounded p-2 mb-3 bg-danger text-white" role="alert" style="display: block;">
								<strong>{{ $errors->first('g-recaptcha-response') }}</strong>
							</span>
							@endif
							@endif

							<div class="mb-3">
								<label class="aiz-checkbox">
								<input type="checkbox" name="checkbox_example_1" required>
									<span class=opacity-60>{{ translate('By signing up you agree to our')}}
										<a href="{{ env('APP_URL').'/terms-conditions' }}" target="_blank">{{ translate('terms and conditions')}}.</a>
									</span>
									<span class="aiz-square-check"></span>
								</label>
							</div>
							@error('checkbox_example_1')
								<span class="invalid-feedback" role="alert">{{ $message }}</span>
							@enderror

							<div class="mb-5">
								<button type="submit" id="createAccountBtn" class="btn btn-block btn-primary">{{ translate('Create Account') }}</button>
							</div>
							@if(get_setting('google_login_activation') == 1 || get_setting('facebook_login_activation') == 1 || get_setting('twitter_login_activation') == 1 || get_setting('apple_login_activation') == 1)
			                <div class="mb-5">
			                    <div class="separator mb-3">
			                        <span class="bg-white px-3">{{ translate('Or Join With') }}</span>
			                    </div>
	                    		<ul class="list-inline social colored text-center">
									@if(get_setting('facebook_login_activation') == 1)
			                        <li class="list-inline-item">
			                            <a href="{{ route('social.login', ['provider' => 'facebook']) }}" class="facebook" title="{{ translate('Facebook') }}"><i class="lab la-facebook-f"></i></a>
			                        </li>
									@endif
									@if(get_setting('google_login_activation') == 1)
									<li class="list-inline-item">
										<a href="{{ route('social.login', ['provider' => 'google']) }}" class="google" title="{{ translate('Google') }}"><i class="lab la-google"></i></a>
									</li>
									@endif
									@if(get_setting('twitter_login_activation') == 1)
			                        <li class="list-inline-item">
			                            <a href="{{ route('social.login', ['provider' => 'twitter']) }}" class="twitter" title="{{ translate('Twitter') }}"><i class="lab la-twitter"></i></a>
			                        </li>
									@endif
									@if(get_setting('apple_login_activation') == 1)
			                        <li class="list-inline-item">
			                            <a href="{{ route('social.login', ['provider' => 'apple']) }}" class="apple" title="{{ translate('Apple') }}"><i class="lab la-apple"></i></a>
			                        </li>
									@endif
								</ul>
							</div>
							@endif

							<div class="text-center">
								<p class="text-muted mb-0">{{ translate("Already have an account?") }}</p>
								<a href="{{ route('login') }}">{{ translate('Login to your account') }}</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection


@section('script')

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const select = document.getElementById('gender');
			const dobInput = document.getElementById("date_of_birth"); 

			function initDatepicker(maxDate) {
				if ($(dobInput).data('daterangepicker')) {
					$(dobInput).data('daterangepicker').remove();
				}
				$(dobInput).daterangepicker({
					singleDatePicker: $(dobInput).data('single') ?? true,
					showDropdowns: $(dobInput).data('show-dropdown') ?? true,
					maxDate: maxDate,
					startDate: maxDate, 
					autoUpdateInput: true,
					locale: {
						format: $(dobInput).data('format') || 'YYYY-MM-DD',
						applyLabel: "Select",
						cancelLabel: "Clear",
					},
				});
				dobInput.value = maxDate;
			}
			initDatepicker(select.options[select.selectedIndex].getAttribute('startdate'));
			select.addEventListener('change', function() {
				const selectedOption = this.options[this.selectedIndex];
				const maxDate = selectedOption.getAttribute('startdate');
				dobInput.setAttribute('data-max-date', maxDate);

				initDatepicker(maxDate);
			});
		});


	</script>
	@if(get_setting('google_recaptcha_activation') == 1 && get_setting('recaptcha_user_register') == 1)
		@include('partials.recaptcha', ['action' => 'recaptcha_user_register','form_id' => 'reg-form'])
	@endif
	@if(addon_activation('otp_system'))
		@include('partials.emailOrPhone')
	@endif

	 @if (get_setting('registration_verification'))
        @include('partials.verifyEmailOrPhone')
    @endif

	<script type="text/javascript">
		const regVerifyRequired = {{get_setting('registration_verification') ? 'true' : 'false' }};
		const createBtn   = $('#createAccountBtn');
		const termsCheckbox = $('input[name="checkbox_example_1"]');
		function toggleCreateBtn() {
			const termsChecked = termsCheckbox.is(':checked');
			const regVerified  = regVerifyRequired ? (verifyBtn && verifyBtn.classList.contains('disabled')) : true;
			let enableBtn = false;
			if (regVerifyRequired) {
				enableBtn = termsChecked && regVerified;
			} else {
				enableBtn = termsChecked;
			}
			createBtn.prop('disabled', !enableBtn);
		}

		document.addEventListener('DOMContentLoaded', function() {
			toggleCreateBtn();
			termsCheckbox.on('change', toggleCreateBtn);
		});
	</script>
@endsection
