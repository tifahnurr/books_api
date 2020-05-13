<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Book List</div>

                    <div class="card-body">
                            <div class="card my-4" v-for="comment in comments" :key="comment.comment_id">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col text-info">
                                            {{ comment.username }}
                                        </div>
                                        <div class="col text-right">
                                            on <span class="text-info">{{ comment.book_title }} </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body row">
                                    <div class="col">
                                        {{ comment.comment }}
                                    </div> 
                                </div>
                                <div v-if="comment.approved == 0" class="card-footer text-center">
                                    <button class="btn btn-primary" style="width:100%" v-on:click="approveComment(comment.comment_id)">Approve</button>
                                </div>
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
