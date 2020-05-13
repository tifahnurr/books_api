<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Category List</div>

                    <div class="card-body">
                        <ul id="example-1">
                            <li v-for="category in categories" :key="category.category_id" class="py-1">
                                <div v-if="!in_edit.includes(category.category_id)">  
                                    {{ category.name }} 
                                    <button v-if="$cookies.get('user_data').is_admin == 1" class="btn btn-sm btn-primary" v-on:click="in_edit.push(category.category_id)">Edit</button>    
                                </div>
                                <div v-if="in_edit.includes(category.category_id)">
                                    <input v-model="category.name">
                                    <button class="btn btn-sm btn-primary" v-on:click="save_cat(category)">Save</button>
                                    <button class="btn btn-sm btn-danger" v-on:click="delete_cat(category)">Delete</button>
                                    <a href="#" v-on:click="cancel(category)"><span class="fa fa-times-circle"></span></a>
                                </div>
                                
                            </li>
                        </ul>
                        <div v-if="this.$cookies.get('user_data').is_admin == 1">
                            <h5> Add new </h5>
                            <form @submit.prevent="newCategory">
                                <input placeholder="Category Name" v-model='newcategory.name'>
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </form>
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
                categories: {},
                newcategory: {},
                in_edit: []
            }
        },
        mounted() {
            console.log('Component mounted.');
            console.log(this.categories);
            console.log(this.$cookies.get('token'));
            let uri= 'http://localhost:8000/api/categories';
            let auth = 'Bearer ' + this.$cookies.get('token');
            console.log(auth);
            this.axios.get(uri, {
                headers: {
                    'Authorization': auth
                }
            }). then((response) => {
                console.log(response.data);
                this.categories=response.data;
            })
        },
        methods: {
            newCategory() {
                let uri='http://localhost:8000/api/categories';
                let auth = 'Bearer ' + this.$cookies.get('token');
                this.axios.post(uri, this.newcategory, {
                    headers: {
                            'Authorization': auth
                        }
                }).then((response) => {
                    console.log(response.data);
                    this.categories.push(response.data);
                });
            },
            test(category) {
                category.in_edit = true;
                console.log(category);
            },
            save_cat(category) {
                console.log(category);
                let uri='http://localhost:8000/api/categories';
                let auth = 'Bearer ' + this.$cookies.get('token');
                this.axios.put(uri, category, {
                    headers: {
                            'Authorization': auth
                        }
                }).then((response) => {
                    console.log(response.data);
                    var index = this.in_edit.indexOf(category.category_id);
                    if (index !== -1) this.in_edit.splice(index, 1);
                });
            },
            delete_cat(category) {
                let uri='http://localhost:8000/api/categories/'+category.category_id;
                let auth = 'Bearer ' + this.$cookies.get('token');
                this.axios.delete(uri, {
                    headers: {
                            'Authorization': auth
                        }
                }).then((response) => {
                    console.log(response.data);
                    var index = this.in_edit.indexOf(category.category_id);
                    if (index !== -1) this.in_edit.splice(index, 1);
                    var index = this.categories.indexOf(category);
                    if (index !== -1) this.categories.splice(index, 1);
                });
            },
            cancel(category) {
                var index = this.in_edit.indexOf(category.category_id);
                if (index !== -1) this.in_edit.splice(index, 1);
            }
        },
    }
</script>
