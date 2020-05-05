<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><h4>Book {{ book.title }}</h4></div>

                    <div class="card-body">
                        <h5>Rating {{ book.rating }}</h5>
                        <h5>Categories</h5>
                        <p v-for="categories in book.categories" :key="categories.category_id"> {{ categories.name }} </p>
                        <h5>Comments</h5>
                        <p v-for="comment in book.comments" :key="comment.comment_id">{{ comment.comment }}</p>
                        <h5>Add comment</h5>
                        <form @submit.prevent="newComment"> 
                            <textarea rows="5" v-model="newcomment.comment"></textarea>
                            <button class="btn btn-primary">Submit</button>
                        </form>
                        <h5>Add rating</h5>
                        <form @submit.prevent="newRating">
                            <input type="number" min="0" max="5" v-model="newrating.rating">
                            <button class="btn btn-primary">Submit</button>
                        </form>
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
            newRating() {
                let uri='http://localhost:8000/api/ratings';
                let auth = 'Bearer ' + this.$cookies.get('token');
                this.newrating.book_id=this.book_id;
                this.axios.post(uri, this.newrating, {
                    headers: {
                            'Authorization': auth
                        }
                }).then((response) => {
                    this.loadDetail();
                });
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
                
                })
            }
        },
        props: [
            'book_id'
        ]
    }
</script>
