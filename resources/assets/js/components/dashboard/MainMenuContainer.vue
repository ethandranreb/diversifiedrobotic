<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#" @click.prevent="goToNewsFeed()" style="color:blue;font-style:italic;">DR Social Spot</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li id="newsfeed-li-id" class="nav-item active">
                    <a class="nav-link" href="#" @click.prevent="goToNewsFeed()">News Feed <span class="sr-only">(current)</span></a>
                </li>

                <li id="profile-li-id" class="nav-item">
                    <a class="nav-link" href="#" @click.prevent="goToProfileEdit()">Profile</a>
                </li>
            </ul>

            <form class="form-inline my-2 my-lg-0">
                <div class="navbar-nav mr-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i> {{ firstName }}</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#" @click.prevent="goToNewsFeed()">News Feed</a>
                            <a class="dropdown-item" href="#" @click.prevent="goToProfileEdit()">Profile</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" @click.prevent="logOut()">Log Out</a>
                        </div>
                    </li>
                </div>
            </form>
        </div>
    </nav>
</template>

<script>
    export default {
        data() {
            return {
                firstName: '',
                classStatus: ''
            }
        },

        created() {
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

                            this.firstName = response.data.values.first_name;
                        }
                    })
                    .catch(error => {});
            },

            goToNewsFeed() {
                window.location.href = 'newsfeeds';
            },

            goToProfileEdit() {
                window.location.href = 'profile-edit';
            },

            logOut() {
                jConfirm.confirm('', 'Proceed to logout?', {
                    Logout: function () {
                        axios.post('logout-request')
                            .then(response => {
                                if (response.data.status == 'success') {
                                    window.location.href = 'login';
                                }
                                else {
                                    jConfirm.confirm('', 'An error has occurred.  Please refresh.', {
                                        Refresh: function () {
                                            window.location.href = "login";
                                        }
                                    });
                                }
                            })
                            .catch(error => {
                                jConfirm.confirm('', 'An error has occurred.  Please refresh.', {
                                    Refresh: function () {
                                        window.location.href = "login";
                                    }
                                });
                            });
                    },
                    Cancel: function () {}
                });
            }
        }
    }
</script>