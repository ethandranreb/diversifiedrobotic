<template>
    <div class="login-block">
        <h1>DR Social Spot</h1>
        <div class="input-group mt-2">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-user fa-fw"></i></span>
            </div>

            <input type="text" value="" placeholder="Email Address" id="email" class="form-control" />
        </div>
        <span id="email-msg" class="input-error-msg-hide"></span>

        <div class="input-group mt-2">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-unlock-alt fa-fw"></i></span>
            </div>

            <input type="password" value="" placeholder="Password" id="password" class="form-control" />
        </div>
        <span id="password-msg" class="input-error-msg-hide"></span>

        <button class="btn btn-primary" @click="verifyLoginCredentials()">Login</button>
        <div style="width:100%;text-align:center;">
            <small>No account yet? <a href="register">Register here</a></small>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            verifyLoginCredentials() {
                filterControl.reset();
                errorControl.reset();
                filterControl.filter('email', $('#email').val().trim(), regex('email'), 'email', 'Invalid email address', 'Please enter your email address', false, true);
                filterControl.filter('password', $('#password').val().trim(), regex('password'), 'passWord', 'Invalid password', 'Please enter your password');

                if (filterControl.errors.length) { errorControl.displayInputError(); }
                else {
                    axios.post('login-request', filterControl.temp)
                        .then(response => {
                            if (response.data.status == 'error') {
                                filterControl.errors = response.data.values;
                                errorControl.displayInputError();
                            }
                            else { window.location.href = 'newsfeeds'; }
                        })
                        .catch(error => {
                            jConfirm.confirm('', 'An error has occurred.  Please refresh the browser.', {
                                Refresh: function () { window.location.href = 'login'; }
                            })
                        });
                }
            }
        }
    }
</script>