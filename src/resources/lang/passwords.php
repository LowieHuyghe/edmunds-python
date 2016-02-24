<?php

return array
(

	/*
	|--------------------------------------------------------------------------
	| Passwords Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines contain the messages used by the
	| password reset system.
	|
	*/

	\Illuminate\Support\Facades\Password::RESET_LINK_SENT	=> 'We have e-mailed your password reset link!',
	\Illuminate\Support\Facades\Password::PASSWORD_RESET	=> 'Your password has been reset!',
	\Illuminate\Support\Facades\Password::INVALID_USER		=> "We can't find a user with that e-mail address.",
	\Illuminate\Support\Facades\Password::INVALID_PASSWORD	=> 'Passwords must be at least six characters and match the confirmation.',
	\Illuminate\Support\Facades\Password::INVALID_TOKEN		=> 'This password reset token is invalid.',
	'passwords.email.subject'								=> 'Your Password Reset Link',
);