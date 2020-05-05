<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Book List</div>

                    <div class="card-body">
                        <ul id="example-1">
                            <li v-for="book in books" :key="book.book_id">
                                <router-link :to="{name: 'book_detail', params: {book_id: book.book_id}}">{{ book.title }}</router-link>
                                <br>
                                {{ book.category }}
                            </li>
                        </ul>
                        <h5> Add new </h5>
                        <form @submit.prevent="newBook">
                            <input placeholder="Book Title" v-model='newbook.title'>
                            <button class="btn btn-primary" type="submit">Submit</button>
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
                books: {},
                newbook: {}
            }
        },
        created() {
            console.log('Component mounted.');
            console.log(this.$cookies.get('token'));
            let uri= 'http://localhost:8000/api/books';
            let auth = 'Bearer ' + this.$cookies.get('token');
            console.log(auth);
            this.axios.get(uri, {
                headers: {
                    'Authorization': auth
                }
            }). then((response) => {
                console.log(response.data);
                this.books=response.data;
            })
        },
        methods: {
            newBook() {
                let uri='http://localhost:8000/api/books';
                let auth = 'Bearer ' + this.$cookies.get('token');
                this.axios.post(uri, this.newbook, {
                    headers: {
                            'Authorization': auth
                        }
                }).then((response) => {
                    console.log(response.data);
                    this.books.push(response.data);
                });
            }
        },
    }
</script>
