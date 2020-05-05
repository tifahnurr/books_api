<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Book List</div>

                    <div class="card-body">
                        <ul id="example-1">
                            <li v-for="comment in comments" :key="comment.comment_id">
                                {{ comment.user_id }} <br>
                                {{ comment.book_id }} <br>
                                {{ comment.comment }} <br>
                                {{ comment.approved }}
                                <button v-if="comment.approved == 0" v-on:click="approveComment(comment.comment_id)">Approve</button>
                            </li>
                        </ul>
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
                comments: {}
            }
        },
        created() {
            console.log('Component mounted.');
            console.log(this.$cookies.get('token'));
            this.loadComments();
        },
        methods: {
            approveComment(comment_id) {
                let uri='http://localhost:8000/api/comments/approve';
                let auth = 'Bearer ' + this.$cookies.get('token');
                let comment = {
                    comment_id : comment_id,
                }
                this.axios.post(uri, comment, {
                    headers: {
                            'Authorization': auth
                        }
                }).then((response) => {
                    console.log(response.data);
                    this.loadComments;
                });
            },
            loadComments() {
                let uri= 'http://localhost:8000/api/comments';
                let auth = 'Bearer ' + this.$cookies.get('token');
                console.log(auth);
                this.axios.get(uri, {
                    headers: {
                        'Authorization': auth
                    }
                }). then((response) => {
                    console.log(response.data);
                    this.comments = response.data;
                })
            }
        },
    }
</script>
