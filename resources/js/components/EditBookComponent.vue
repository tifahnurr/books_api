<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <input type="text" v-model="new_title" style="width:100%">
                            </div>
                            <div class="col text-right">
                                <button type="button" class="btn btn-danger" v-on:click="deleteBook()">Delete</button>
                            </div>
                        </div>
                        <div class="row py-1">
                            <div class="col">
                                <h6>Categories <a href="#" id="show-modal" data-toggle="modal" data-target="#addCatModal"><span class="fa fa-fw fa-plus"></span></a></h6>
                                <ul>
                                    <li v-for="category in book.categories" :key="category.category_id">
                                        {{ category.name }}
                                    </li>
                                    <li class="text-muted" v-for="category in checked" :key="category.category_id">
                                        {{ category.name }}
                                    </li>
                                </ul>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="col">
                                <button class="btn btn-primary" v-on:click="save()">Save changes</button>
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="modal fade" id="addCatModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div v-for="category in all_categories" :key="category.category_id">
                            <div v-if="!book.categories.map(a => a.category_id).includes(category.category_id)">
                                <input type="checkbox" v-model="checked"  :value="category">
                                <label> {{ category.name }} </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                all_categories: {},
                newcomment: {},
                newrating: {},
                showModal: false,
                checked: [],
                new_title: "",
            }
        },
        mounted() {
            console.log('Component created.');
            console.log(this.$cookies.get('token'));
            console.log(this.book_id)
            this.loadDetail();
            this.loadAllCategories();
        },
        methods: {
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
                    this.new_title=this.book.title;
                })
            },
            loadAllCategories() {
                let uri= 'http://localhost:8000/api/categories';
                let auth = 'Bearer ' + this.$cookies.get('token');
                console.log(auth);
                this.axios.get(uri, {
                    headers: {
                        'Authorization': auth
                    }
                }). then((response) => {
                    console.log(response.data);
                    this.all_categories=response.data;
                })
            },
            save() {
                console.log(this.checked);
                console.log(this.new_title);
                let auth = 'Bearer ' + this.$cookies.get('token');
                let uri_edit= 'http://localhost:8000/api/books';
                if (this.new_title != this.book.title) {
                    this.axios.put(uri_edit, {book_id: this.book.book_id, title: this.new_title},{
                        headers: {
                            'Authorization': auth
                        }
                    }). then((response) => {
                        console.log(response.data);
                    })
                }

                let uri_cat= 'http://localhost:8000/api/books/categories';
                let sementara = this;
                this.checked.forEach(function(item) {
                    sementara.axios.post(uri_cat, {book_id: sementara.book.book_id, category_id: item},{
                        headers: {
                            'Authorization': auth
                        }
                    }). then((response) => {
                        console.log(response.data);
                    })
                });
            },
            deleteBook() {
                let auth = 'Bearer ' + this.$cookies.get('token');
                let uri= 'http://localhost:8000/api/books/'+this.book.book_id;
                this.axios.delete(uri,{
                    headers: {
                        'Authorization': auth
                    }
                }). then((response) => {
                    console.log(response.data);
                    this.$router.push({name: 'books'});
                })
            
            }
        },
        props: [
            'book_id'
        ],

    }
</script>
