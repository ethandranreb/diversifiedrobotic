<template>
    <div class="login-block">
        <div style="margin-top:20px;">
            <button class="btn btn-outline-secondary btn-sm" @click="editProfileView()">Edit My Profile</button>
            <button class="btn btn-outline-secondary btn-sm" @click="myPostsProfileView()">My Post</button>
            <button class="btn btn-outline-primary btn-sm">My Status</button>
        </div>

        <div style="text-align:center;margin-top:30px;">
            <div style="width:80%;margin:0 auto;">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">My Status</h5>

                        <div><h2>{{ status }}</h2></div>
                        <div>
                            <button class="btn btn-outline-secondary btn-sm" @click="updateStatus(0)">Offline</button>
                            <button class="btn btn-outline-success btn-sm" @click="updateStatus(1)">Active</button>
                            <button class="btn btn-outline-warning btn-sm" @click="updateStatus(2)">Away</button>
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
                status: ''
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
                            var appendString = response.data.values.status_verbose.toLowerCase();

                            $("#navbarDropdown").removeClass (function (index, className) {
                                return (className.match (/(^|\s)class-\S+/g) || []).join(' ');
                            }).addClass('class-' + appendString);

                            this.status = response.data.values.status_verbose;
                        }
                    })
                    .catch(error => {});
            },

            editProfileView() {
                window.location.href = 'profile-edit';
            },

            myPostsProfileView() {
                window.location.href = 'profile-my-newsfeeds';
            },

            updateStatus(val) {
                axios.post('profile-status-update', { status: val })
                    .then(response => {
                        if (response.data.status == 'success') {
                            this.getUserInfo();
                        }
                    })
                    .catch(err => {});
            }

            // refreshInfo() {
            //     window.location.href = "profile-status";
            // },

            // verifyProfileInfo() {
            //     filterControl.reset();
            //     errorControl.reset();
            //     filterControl.filter('first-name', $('#first-name').val().trim(), regex('name'), 'firstName', 'Invalid first name', 'Please enter your first name');
            //     filterControl.filter('middle-name', $('#middle-name').val().trim(), regex('name'), 'middleName', 'Invalid middle name', 'Please enter your middle name');
            //     filterControl.filter('last-name', $('#last-name').val().trim(), regex('name'), 'lastName', 'Invalid last name', 'Please enter your last name');
            //     filterControl.filter('email', $('#email').val().trim(), regex('email'), 'email', 'Invalid email', 'Please enter your email', false, true);
            //     filterControl.filter('password', $('#password').val().trim(), regex('password'), 'passWord', 'Invalid password', '', true);
            //     filterControl.filter('rpassword', $('#rpassword').val().trim(), regex('password'), 'rpassWord', 'Invalid password', '', true);

            //     if (filterControl.errors.length) { errorControl.displayInputError(); }
            //     else {
            //         if (filterControl.temp.passWord.length || filterControl.temp.rpassWord.length){
            //             if (filterControl.temp.passWord != filterControl.temp.rpassWord){
            //                 filterControl.errors.push('password');
            //                 filterControl.errors.push('The passwords do not match');
            //                 filterControl.errors.push('rpassword');
            //                 filterControl.errors.push('The passwords do not match');
            //             } else if (filterControl.temp.passWord.length < 6) {
            //                 filterControl.errors.push('password');
            //                 filterControl.errors.push('The password must be at least 6 characters long');
            //                 filterControl.errors.push('rpassword');
            //                 filterControl.errors.push('The password must be at least 6 characters long');
            //             }
            //         }

            //         if (filterControl.errors.length){ errorControl.displayInputError(); }
            //         else {
            //             axios.post('profile-save', filterControl.temp)
            //                 .then(response => {
            //                     if (response.data.status == 'no-change') { jConfirm.confirm('', 'No changes have been made', { Close: function () {} }); }
            //                     else if (response.data.status == 'success') { jConfirm.confirm('', 'Changes successfully saved', { Close: function () { window.location.href = 'profile'; } }); }
            //                     else if (response.data.status == 'error') {
            //                         filterControl.errors = response.data.values;
            //                         errorControl.displayInputError();
            //                     }
            //                     else { jConfirm.confirm('', 'Unable to save.  Please refresh to try again.', { Refresh: function () { window.location.href = 'profile'; } }) }
            //                 })
            //                 .catch(error => { jConfirm.confirm('', 'Unable to save.  Please refresh to try again.', { Refresh: function () { window.location.href = 'profile'; } }) });
            //         }
            //     }
            // }
        }
    }
</script>
