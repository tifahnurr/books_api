<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5>{{ book.title }}</h5>
                                <small v-for="category in book.categories" :key="category.category_id">
                                    {{ category.name }}  |  
                                </small>
                            </div>
                            <div class="col text-right">
                                    <div id="rating" v-on:click="rateBook()" class="text-right">
                                        <input id="rating-1" v-model="rate" type="radio" value=5>
                                        <label for="rating-1"></label>
                                        <input id="rating-2" v-model="rate" type="radio" value=4>
                                        <label for="rating-2"></label>
                                        <input id="rating-3" v-model="rate" type="radio" value=3>
                                        <label for="rating-3"></label>
                                        <input id="rating-4" v-model="rate" type="radio" value=2>
                                        <label for="rating-4"></label>
                                        <input id="rating-5" v-model="rate" type="radio" value=1>
                                        <label for="rating-5"></label>
                                    </div>
                                <div class="row">
                                    <div class="col">
                                        <span class="fa fa-star fa-fw" aria-hidden="true" style="color:gold"></span> {{ book.rating }}
                                    </div>
                                </div>
                                <div class="row" v-if="this.$cookies.get('user_data').is_admin == 1">
                                    <div class="col">
                                        <router-link class="btn btn-primary btn-sm" :to="{name: 'edit_book', params: {book_id: book.book_id}}">Edit</router-link>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <h5>Comment</h5>
                        <form @submit.prevent="newComment"> 
                            <div class="row">
                                <div class="col-9 form-group">
                                    <textarea class="form-control" rows="5" v-model="newcomment.comment" width="100%"></textarea>
                                </div>
                                <div class="col-2">
                                    <button class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                        <div v-if="comments">
                        <div class="card my-3" v-for="comment in comments" :key="comment.comment_id">
                            <div class="card-header text-info">
                                    {{ comment.username }}
                            </div>
                            <div class="card-body">
                                <div class="row">
                                <div class="col">
                                    {{ comment.comment }}
                                </div>
                                </div>
                            </div>
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
                book: {},
                categories: {},
                newcomment: {},
                newrating: {},
                comments: [],
                rate: 0,
                rated: false
            }
        },
        created() {
            console.log('Component created.');
            console.log(this.$cookies.get('token'));
            console.log(this.book_id)
            this.loadDetail();
        },
        methods: {
            newComment() {
                let uri='http://localhost:8000/api/comments';
                let auth = 'Bearer ' + this.$cookies.get('token');
                this.newcomment.book_id=this.book_id;
                this.axios.post(uri, this.newcomment, {
                    headers: {
                            'Authorization': auth
                        }
                }).then((response) => {
                    this.loadDetail();
                });
            },
            
            loadComment() {
                let auth = 'Bearer ' + this.$cookies.get('token');
                let apps = this;
                let length = this.comments.length;
                let a = 0;
                this.comments.forEach(function(item, index) {
                    let uri="http://localhost:8000/api/users/"+item.user_id;
                    apps.axios.get(uri, {
                        headers: {
                            'Authorization': auth
                        }
                    }). then((response) => {
                        console.log(response.data);
                        apps.comments[index].username=response.data.username;
                        a++;
                        console.log(index);
                        if (a == length) {
                            apps.$forceUpdate();
                        }
                    })
                });
                
            },
            rateBook() {
                let apps = this;
                if (!this.rated) {
                    apps.rated = true;
                    setTimeout(function() { 
                        console.log(apps.rate);
                        let uri='http://localhost:8000/api/ratings';
                        let auth = 'Bearer ' + apps.$cookies.get('token');
                        apps.newrating.book_id=apps.book_id;
                        apps.newrating.rating = apps.rate;
                        apps.axios.post(uri, apps.newrating, {
                            headers: {
                                    'Authorization': auth
                                }
                        }).then((response) => {
                            apps.loadDetail();
                        });
                        apps.rated = false;
                    }, 50);
                }
            },
            loadDetail() {
                let uri= 'http://localhost:8000/api/books/'+this.book_id;
                let auth = 'Bearer ' + this.$cookies.get('token');
                console.log(auth);
                this.axios.get(uri, {
                    headers: {
                        'Authorization': auth
                    }
                }). then((response) => {
                    console.log(response.data);
                    this.book=response.data;
                    console.log(this.book.comments[0].comment);
                    console.log(this.book.categories);
                    this.book.rating = Math.round(this.book.rating * 100) / 100
                    this.comments=this.book.comments;
                    this.loadComment();
                    let uri= 'http://localhost:8000/api/books/'+this.book_id + '/rating'; 
                    this.axios.get(uri, {
                        headers: {
                            'Authorization': auth
                        }
                    }). then((response) => {
                       if (response.data != null) {
                           this.rate = response.data.rating;
                       }
                    });
                })
            }
        },
        props: [
            'book_id'
        ],
        watch: {
            comments: {
                handler(val){
                    console.log('comments changed');
                },
                deep: true
            }
        }
    }
</script>
