<template>
    <div class="login-block">
        <div style="margin-top:20px;">
            <button class="btn btn-outline-success btn-sm float-left" data-toggle="modal" data-target="#addPostModal">Add Post</button>

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
                        <small class="text-muted">Published by: {{ postItem.postBy }} <span :class="'class-' + postItem.postStatus">({{ postItem.postStatus }})</span></br>Published on: {{ postItem.postDate }}</br><i class="fa fa-thumbs-up" aria-hidden="true"></i> <span :id="'like-count-' + postItem.postId">{{ postItem.postLikes }}</span></small>
                    </div>
                    <div>
                        <span v-if="postItem.postEdit">
                            <button type="button" class="btn btn-primary btn-sm" @click="sharePost(postItem.postId)">Share Post</button>
                        </span>

                        <span v-if="postItem.postCanLike">
                            <button type="button" class="btn btn-primary btn-sm" :id="postItem.postId" @click="likePost(postItem.postId)">Like Post</button>
                        </span>

                        <span v-if="postItem.postCanComment">
                            <button type="button" class="btn btn-primary btn-sm" :id="'comment-id-' + postItem.postId" data-toggle="modal" data-target="#addCommentModal" @click="cacheId(postItem.postId)">Add Comment</button>
                        </span>
                    </div>
                    <div v-for="postComment in postItem.postComments">
                        <div class="card mt-2">
                            <div class="card-body">
                                <div class="text-muted">{{ postComment.postCommentText }}</div>
                                <div class="float-right">
                                    <small>- {{ postComment.postCommentBy }} ({{ postComment.postCommentDate }})</small></br><small></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="modal fade" id="addPostModal" tabindex="-1" role="dialog" aria-labelledby="addPostModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPostModalLabel">Add Post</h5>
                    </div>
                    <div class="modal-body">
                        <div class="input-group mt-2">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Title</span>
                            </div>

                            <input type="text" value="" placeholder="Post Title" id="post-title" class="form-control" />
                        </div>
                        <span id="post-title-msg" class="input-error-msg-hide"></span>

                        <textarea name="" id="post-body" cols="30" rows="10" style="width:100%;" class="mt-2"></textarea>
                        <span id="post-body-msg" class="input-error-msg-hide"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary btn-sm" @click="savePost()">Save post</button>
                    </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="modal fade" id="addCommentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="commentModalLabel">Add Comment</h5>
                    </div>
                    <div class="modal-body">
                        <textarea name="" id="comment-body" cols="30" rows="10" style="width:100%;" class="mt-2"></textarea>
                        <span id="comment-body-msg" class="input-error-msg-hide"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" @click="saveComment()">Save comment</button>
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
                postList: [],
                postItem: {
                    postId: '',
                    postDate: '',
                    postBy: '',
                    postTitle: '',
                    postBody: '',
                    postEdit: 0,
                    postComments: [],
                    postLikes: 0,
                    postCanLike: 0,
                    postBtnId: '',
                    postStatus: '',
                    postCanComment: ''
                },
                postComment: {
                    postCommentDate: '',
                    postCommentBy: '',
                    postCommentText: ''
                },
                page: []
            }
        },

        created() {
            $(document).ready(function ($) {
                $('li#newsfeed-li-id').addClass('active');
                $('li#profile-li-id').removeClass('active');
            });

            this.getPosts(1);
        },

        methods: {
            getPosts(p) {
                axios.post('newsfeeds-get', { page: p })
                    .then(response => {
                        this.postList = response.data.postList;
                        this.page = response.data.page;
                    })
                    .catch(error => {});
            },

            cacheId(id) {
                if (id) {
                    localStorage.setItem('post-id', id);
                }
            },

            savePost() {
                filterControl.reset();
                errorControl.reset();
                filterControl.filter('post-title', $('#post-title').val().trim(), regex('text'), 'postTitle', 'Invalid post title', 'Please enter your post title');
                filterControl.filter('post-body', $('#post-body').val().trim(), regex('text'), 'postBody', 'Invalid post content', 'Please enter your post content');

                if (filterControl.errors.length) { errorControl.displayInputError(); }
                else {
                    axios.post('post-new-save', filterControl.temp)
                        .then(response => {
                            if (response.data.status == 'error') {
                                filterControl.errors = response.data.values;
                                errorControl.displayInputError();
                            }
                            else { jConfirm.confirm('', 'Post successfully saved.', {
                                Close: function () { window.location.href = 'newsfeeds'; }
                            }); }
                        })
                        .catch(error => {
                            jConfirm.confirm('', 'An error has occurred.  Please refresh the browser.', {
                                Refresh: function () { window.location.href = 'login'; }
                            })
                        });
                }
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
                                .catch(error => {});
                        },
                        Cancel: function () { localStorage.removeItem('post-id'); }
                    });
                }
            },

            likePost(id) {
                if (id) {
                    axios.post('post-like', { id: id })
                        .then(response => {
                            if (response.data.status == 'success') {
                                $('button#' + response.data.postId).hide();
                                $('#like-count-' + response.data.postId).text(response.data.likes);
                            }
                        })
                        .catch(error => {});
                }
            },

            saveComment() {
                var id = localStorage.getItem('post-id');
                $('#addCommentModal').modal('hide');

                if (id) {
                    filterControl.reset();
                    errorControl.reset();
                    filterControl.filter('comment-body', $('#comment-body').val().trim(), regex('text'), 'commentBody', 'Invalid content', 'Please enter your comments');

                    if (filterControl.errors.length) { errorControl.displayInputError(); }
                    else {
                        filterControl.temp.id = id;

                        axios.post('post-save-comment', filterControl.temp)
                            .then(response => {
                                if (response.data.status == 'success') {
                                    window.location.href = 'newsfeeds';
                                }
                            })
                            .catch(error => {});
                    }
                }

            }
        }
    }
</script>
