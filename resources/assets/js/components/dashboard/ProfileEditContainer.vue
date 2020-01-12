<template>
    <div class="login-block">
        <div style="margin-top:20px;">
            <button class="btn btn-outline-primary btn-sm">Edit My Profile</button>
            <button class="btn btn-outline-secondary btn-sm" @click="myPostsProfileView()">My Post</button>
            <button class="btn btn-outline-secondary btn-sm" @click="statusProfileView()">My Status</button>
        </div>

        <div style="text-align:center;margin-top:30px;">
            <div style="width:80%;margin:0 auto;">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Profile</h5>

                        <input type="text" :value="firstName" placeholder="First Name" id="first-name" class="form-control mt-2" />
                        <span id="first-name-msg" class="input-error-msg-hide"></span>
                        <input type="text" :value="middleName" placeholder="Middle Name" id="middle-name" class="form-control mt-2" />
                        <span id="middle-name-msg" class="input-error-msg-hide"></span>
                        <input type="text" :value="lastName" placeholder="Last Name" id="last-name" class="form-control mt-2" />
                        <span id="last-name-msg" class="input-error-msg-hide"></span>

                        <hr>

                        <input type="text" :value="emailAddress" placeholder="Email Address" id="email" class="form-control mt-2" />
                        <span id="email-msg" class="input-error-msg-hide"></span>
                        <input type="password" value="" placeholder="Password" id="password" class="form-control mt-2" />
                        <span id="password-msg" class="input-error-msg-hide"></span>
                        <input type="password" value="" placeholder="Re-type Password" id="rpassword" class="form-control mt-2" />
                        <span id="rpassword-msg" class="input-error-msg-hide"></span>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-success float-left" style="width:100px;" @click.prevent="verifyProfileInfo()"><i class="fa fa-floppy-o"></i> Save</button>
                            <button type="submit" class="btn btn-primary float-right" style="width:100px;" @click.prevent="refreshInfo()"><i class="fa fa-refresh"></i> Refresh</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                firstName: '',
                middleName: '',
                lastName: '',
                emailAddress: ''
            }
        },

        created() {
            $(document).ready(function ($) {
                $('li#newsfeed-li-id').removeClass('active');
                $('li#profile-li-id').addClass('active');

            });

            this.getUserInfo();
        },

        methods: {
            getUserInfo() {
                axios.post('user-info')
                    .then(response => {
                        if (response.data.status == 'success') {
                            this.firstName = response.data.values.first_name;
                            this.middleName = response.data.values.middle_name;
                            this.lastName = response.data.values.last_name;
                            this.emailAddress = response.data.values.email;
                        }
                    })
                    .catch(error => {});
            },

            refreshInfo() {
                window.location.href = "profile-edit";
            },

            statusProfileView() {
                window.location.href = 'profile-status';
            },

            myPostsProfileView() {
                window.location.href = 'profile-my-newsfeeds';
            },

            verifyProfileInfo() {
                filterControl.reset();
                errorControl.reset();
                filterControl.filter('first-name', $('#first-name').val().trim(), regex('name'), 'firstName', 'Invalid first name', 'Please enter your first name');
                filterControl.filter('middle-name', $('#middle-name').val().trim(), regex('name'), 'middleName', 'Invalid middle name', 'Please enter your middle name');
                filterControl.filter('last-name', $('#last-name').val().trim(), regex('name'), 'lastName', 'Invalid last name', 'Please enter your last name');
                filterControl.filter('email', $('#email').val().trim(), regex('email'), 'email', 'Invalid email', 'Please enter your email', false, true);
                filterControl.filter('password', $('#password').val().trim(), regex('password'), 'passWord', 'Invalid password', '', true);
                filterControl.filter('rpassword', $('#rpassword').val().trim(), regex('password'), 'rpassWord', 'Invalid password', '', true);

                if (filterControl.errors.length) { errorControl.displayInputError(); }
                else {
                    if (filterControl.temp.passWord.length || filterControl.temp.rpassWord.length){
                        if (filterControl.temp.passWord != filterControl.temp.rpassWord){
                            filterControl.errors.push('password');
                            filterControl.errors.push('The passwords do not match');
                            filterControl.errors.push('rpassword');
                            filterControl.errors.push('The passwords do not match');
                        } else if (filterControl.temp.passWord.length < 6) {
                            filterControl.errors.push('password');
                            filterControl.errors.push('The password must be at least 6 characters long');
                            filterControl.errors.push('rpassword');
                            filterControl.errors.push('The password must be at least 6 characters long');
                        }
                    }

                    if (filterControl.errors.length){ errorControl.displayInputError(); }
                    else {
                        axios.post('profile-save', filterControl.temp)
                            .then(response => {
                                if (response.data.status == 'no-change') { jConfirm.confirm('', 'No changes have been made', { Close: function () {} }); }
                                else if (response.data.status == 'success') { jConfirm.confirm('', 'Changes successfully saved', { Close: function () { window.location.href = 'profile-edit'; } }); }
                                else if (response.data.status == 'error') {
                                    filterControl.errors = response.data.values;
                                    errorControl.displayInputError();
                                }
                                else { jConfirm.confirm('', 'Unable to save.  Please refresh to try again.', { Refresh: function () { window.location.href = 'profile'; } }) }
                            })
                            .catch(error => { jConfirm.confirm('', 'Unable to save.  Please refresh to try again.', { Refresh: function () { window.location.href = 'profile'; } }) });
                    }
                }
            }
        }
    }
</script>
