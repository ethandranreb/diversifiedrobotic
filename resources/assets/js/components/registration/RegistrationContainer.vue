<template>
    <div class="login-block">
        <h1>DR Social Spot Registration</h1>

        <div class="input-group mt-2">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-pencil-square-o fa-fw"></i></span>
            </div>

            <input type="text" value="" placeholder="First Name" id="first-name" class="form-control" />
        </div>
        <span id="first-name-msg" class="input-error-msg-hide"></span>

        <div class="input-group mt-2">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-pencil-square-o fa-fw"></i></span>
            </div>

            <input type="text" value="" placeholder="Middle Name" id="middle-name" class="form-control" />
        </div>
        <span id="middle-name-msg" class="input-error-msg-hide"></span>

        <div class="input-group mt-2">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-pencil-square-o fa-fw"></i></span>
            </div>

            <input type="text" value="" placeholder="Last Name" id="last-name" class="form-control" />
        </div>
        <span id="last-name-msg" class="input-error-msg-hide"></span>

        <hr>

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

        <div class="input-group mt-2">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-unlock-alt fa-fw"></i></span>
            </div>

            <input type="password" value="" placeholder="Re-type Password" id="rpassword" class="form-control" />
        </div>
        <span id="rpassword-msg" class="input-error-msg-hide"></span>

        <button class="btn btn-primary" @click="verifyRegistrationInfo()">Register</button>
    </div>
</template>

<script>
    export default {
        methods: {
            verifyRegistrationInfo() {
                filterControl.reset();
                errorControl.reset();
                filterControl.filter('first-name', $('#first-name').val().trim(), regex('name'), 'firstName', 'Invalid first name', 'Please enter your first name');
                filterControl.filter('middle-name', $('#middle-name').val().trim(), regex('name'), 'middleName', 'Invalid middle name', 'Please enter your middle name');
                filterControl.filter('last-name', $('#last-name').val().trim(), regex('name'), 'lastName', 'Invalid last name', 'Please enter your last name');
                filterControl.filter('email', $('#email').val().trim(), regex('email'), 'email', 'Invalid email', 'Please enter your email', false, true);
                filterControl.filter('password', $('#password').val().trim(), regex('password'), 'passWord', 'Invalid password', 'Please enter your password');
                filterControl.filter('rpassword', $('#rpassword').val().trim(), regex('password'), 'rpassWord', 'Invalid password', 'Please enter your password');

                if (filterControl.errors.length) { errorControl.displayInputError(); }
                else {
                    if (filterControl.temp.passWord == filterControl.temp.rpassWord) {
                        if (filterControl.temp.passWord.length < 6) {
                            filterControl.errors.push('password');
                            filterControl.errors.push('The password must be at least 6 characters long');
                            filterControl.errors.push('rpassword');
                            filterControl.errors.push('The password must be at least 6 characters long');
                            errorControl.displayInputError();
                        }
                        else {
                            axios.post('register-verify', filterControl.temp)
                                .then(response => {
                                    if (response.data.status == 'success') {
                                        var content = '<div>' +
                                            '<table style="text-align:left;font-size:1.2rem;">' +
                                                '<tbody>' +
                                                    '<tr>' +
                                                        '<td>First Name: </td>' +
                                                        '<td style="padding:0 10px;">' + response.data.values.first_name + '</td>' +
                                                    '</tr>' +
                                                    '<tr>' +
                                                        '<td>Middle Name: </td>' +
                                                        '<td style="padding:0 10px;">' + response.data.values.middle_name + '</td>' +
                                                    '</tr>' +
                                                    '<tr>' +
                                                        '<td>Last Name: </td>' +
                                                        '<td style="padding:0 10px;">' + response.data.values.last_name + '</td>' +
                                                    '</tr>' +
                                                    '<tr>' +
                                                        '<td>Email Address: </td>' +
                                                        '<td style="padding:0 10px;">' + response.data.values.email + '</td>' +
                                                    '</tr>' +
                                                '</tbody>' +
                                            '</table>' +
                                        '</div>';

                                        jConfirm.confirm('Registration Information', content, {
                                            Proceed: function () {
                                                axios.post('register-proceed')
                                                .then(response => {
                                                    if (response.data.status == 'success') {
                                                        jConfirm.confirm('', 'Registration has been successful.  You may now login.', {
                                                            Login: function () { window.location.href = "login" }
                                                        });
                                                    }
                                                    else {
                                                        jConfirm.confirm('', 'An error occurred.  Please refresh the browser and try again.', {
                                                            Refresh: function () { window.location.href = "register"; }
                                                        });
                                                    }
                                                })
                                                .catch(error => { console.log(error); })
                                            },
                                            Cancel: function () {}
                                        })
                                    }
                                    else {
                                        filterControl.errors = response.data.values;
                                        errorControl.displayInputError();
                                    }
                                })
                                .catch(error => { console.log(error); });
                        }
                    }
                    else {
                        filterControl.errors.push('password');
                        filterControl.errors.push('The passwords do not match');
                        filterControl.errors.push('rpassword');
                        filterControl.errors.push('The passwords do not match');
                        errorControl.displayInputError();
                    }
                }
            }
        }
    }
</script>