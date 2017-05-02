<script type="text/template" id="login-modal-template">
  <div class="modal fade <% print(modal.class); %>" tabindex="-1" role="dialog" id="<% print(modal.id); %>">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times" aria-hidden="true"></i></button>
          <h4 id="login-modal-brand" class="modal-title">{{ config('app.name', 'Laravel') }}</h4>
        </div>
        <div class="modal-body">
          <div id="login-modal-forms-container">
            <!-- Login Form -->
            <form id="login-form" role="form" method="POST" action="{{ route('login') }}">
              <div class="form-errors" ></div>
              <div class="form-group">
                <label for="login-username"> {{ __("Username") }} </label>
                <input type="text" class="form-control" id="login-username" name="username" placeholder="{{ __("Enter username or email address") }}" required>
                <div class="help-block with-errors"></div>
              </div>
              <div class="form-group">
                <label for="login-password">{{ __("Password") }}</label>
                <input type="password" class="form-control" id="login-password" name="password" placeholder="{{ __("Password") }}" required>
                <div class="help-block with-errors"></div>
              </div>
              <button type="submit" class="btn btn-primary btn-block"> {{ __("Login") }} </button>
              <div class="row">
                <div class="col-md-6">
                  <button type="button" role="button" class="btn btn-link btn-block btn-show-forget-password-form" >{{ __("Forgot Your Password?") }}</button>
                </div>
                <div class="col-md-6">
                  <button type="button" role="button" class="btn btn-link btn-block btn-show-sign-up-form" >{{ __("Create new account") }}</button>
                </div>
              </div>
            </form><!--/#login-form -->
            <!-- Sign Up Form -->
            <form id="sign-up-form" role="form" method="POST" action="{{ route('register') }}" style="display: none;">
                  <div class="form-group has-feedback">
                      <label for="sign-up-name" class="col-md-4 control-label">{{__('Name') }}</label>
                      <div class="col-md-6">
                        <input id="sign-up-name" type="text" class="form-control" name="name" required autofocus>
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        <div class="help-block with-errors"></div>
                      </div>
                  </div>
                  <div class="form-group">
                      <label for="sign-up-username" class="col-md-4 control-label">{{ __('Username') }}</label>
                      <div class="col-md-6">
                          <input id="sign-up-username" type="text" class="form-control" name="username" required>
                          <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                          <div class="help-block with-errors"></div>
                      </div>
                  </div>
                  <div class="form-group">
                      <label for="sign-up-email" class="col-md-4 control-label">{{ __('E-Mail Address') }}</label>
                      <div class="col-md-6">
                          <input id="sign-up-email" type="email" class="form-control" name="email" required>
                          <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                          <div class="help-block with-errors"></div>
                      </div>
                  </div>
                  <div class="form-group">
                      <label for="sign-up-phone" class="col-md-4 control-label">{{ __('Phone Number') }}</label>
                      <div class="col-md-6">
                          <input id="sign-up-phone" type="text" class="form-control" name="phone" required>
                          <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                          <div class="help-block with-errors"></div>
                      </div>
                  </div>
                  <div class="form-group">
                      <label for="sign-up-password" class="col-md-4 control-label">{{ __('Password') }}</label>
                      <div class="col-md-6">
                          <input id="sign-up-password" type="password" class="form-control" name="password" required>
                          <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                          <div class="help-block with-errors"></div>
                      </div>
                  </div>
                  <div class="form-group">
                      <label for="sign-up-password-confirm" class="col-md-4 control-label">{{ __('Confirm Password') }}</label>
                      <div class="col-md-6">
                          <input id="sign-up-password-confirm" type="password" class="form-control" name="password_confirmation" required>
                          <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                          <div class="help-block with-errors"></div>
                      </div>
                  </div>
                  <button type="submit" class="btn btn-primary btn-block">{{ __('Register') }}</button>
                  <div class="row">
                    <div class="col-md-6">
                      <button type="button" role="button" class="btn btn-link btn-block btn-show-forget-password-form" >{{ __("Forgot Your Password?") }}</button>
                    </div>
                    <div class="col-md-6">
                      <button type="button" role="button" class="btn btn-link btn-block btn-show-login-form" >{{ __("Login") }}</button>
                    </div>
                  </div>
            </form><!--/#sign-up-form -->
            <!-- Reset Password Form -->
            <form id="reset-password-form" role="form" method="POST" action="{{ route('password.email') }}" style="display: none;">
              <div class="form-group">
                  <label for="email" class="col-md-4 control-label">{{ __('E-Mail Address') }}</label>
                  <div class="col-md-6">
                      <input id="reset-password-email" type="email" class="form-control" name="email" required>
                      <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                      <div class="help-block with-errors"></div>
                  </div>
              </div>
              <button type="submit" class="btn btn-primary btn-block">{{ __('Send Password Reset Link') }}</button>
              <div class="row">
                <div class="col-md-6">
                  <button type="button" role="button" class="btn btn-link btn-block btn-show-sign-up-form" >{{ __("Create new account") }}</button>
                </div>
                <div class="col-md-6">
                  <button type="button" role="button" class="btn btn-link btn-block btn-show-login-form" >{{ __("Login") }}</button>
                </div>
              </div>
            </form><!--/#reset-password-form -->
          </div>
        </div><!--/.modal-body -->
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
</script>
