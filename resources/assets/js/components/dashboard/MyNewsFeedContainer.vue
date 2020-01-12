<template>
    <div class="login-block">
        <div style="margin-top:20px;">
            <!-- <button class="btn btn-outline-success btn-sm float-left" data-toggle="modal" data-target="#addPostModal">Add Post</button> -->
            <div class="float-left">
                <button class="btn btn-outline-secondary btn-sm" @click="editProfileView()">Edit My Profile</button>
                <button class="btn btn-outline-primary btn-sm">My Post</button>
                <button class="btn btn-outline-secondary btn-sm" @click="statusProfileView()">My Status</button>
            </div>

            <nav aria-label="Page navigation example" class="float-right">
                <ul class="pagination" id="page-controller-id" :value="page.current_page">
                    <li class="page-item"><a class="page-link" href="#" @click.prevent="getPosts(page.previous_page)">Previous</a></li>
                    <li class="page-item"><a class="page-link" href="" @click.prevent="">Page {{ page.current_page }} of {{ page.last_page }}</a></li>
                    <li class="page-item"><a class="page-link" href="#" @click.prevent="getPosts(page.next_page)">Next</a></li>
                </ul>
            </nav>
        </div>

        <div v-for="postItem in postList" class="mt-4">
            <div class="card mt-2" style="width: 100%;">
                <div class="card-body">
                    <h5 class="card-title" style="font-style:italic;">{{ postItem.postTitle }}</h5>
                    <p class="card-text" style="font-weight:lighter;">{{ postItem.postBody }}</p>
                    <hr>
                    <div>
                        <small class="text-muted">Published by: {{ postItem.postBy }}</br>Published on: {{ postItem.postDate }}</br><i class="fa fa-thumbs-up" aria-hidden="true"></i> {{ postItem.postLikes }}</small>
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
                postList: [],
                postItem: {
                    postId: '',
                    postDate: '',
                    postBy: '',
                    postTitle: '',
                    postBody: '',
                    postComments: [],
                    postLikes: 0,
                },
                postComment: {
                    postCommentDate: '',
                    postCommentBy: '',
                },
                page: []
            }
        },

        created() {
            $(document).ready(function ($) {
                $('li#newsfeed-li-id').removeClass('active');
                $('li#profile-li-id').addClass('active');
            });

            this.getPosts(1);
        },

        methods: {
            getPosts(p) {
                axios.post('my-newsfeeds-get', { page: p })
                    .then(response => {
                        this.postList = response.data.postList;
                        this.page = response.data.page;
                    })
                    .catch(error => {});
            },

            editProfileView() {
                window.location.href = 'profile-edit';
            },

            statusProfileView() {
                window.location.href = 'profile-status';
            },

            sharePost(id) {
                if (id) {
                    localStorage.setItem('post-id', id);
                    jConfirm.confirm('', 'Share post?', {
                        Share: function () {
                            var id = localStorage.getItem('post-id');
                            localStorage.removeItem('post-id');

                            axios.post('post-share', { id: id })
                                .then(response => {
                                    if (response.data.status == 'success') {
                                        jConfirm.confirm('', 'Post has been shared', {
                                            Close:  function () {}
                                        })
                                    }
                                })
                        },
                        Cancel: function () { localStorage.removeItem('post-id'); }
                    });
                }
            },
        }
    }
</script>
