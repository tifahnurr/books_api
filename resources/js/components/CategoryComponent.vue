<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Category List</div>

                    <div class="card-body">
                        <ul id="example-1">
                            <li v-for="category in categories" :key="category.category_id">
                                {{ category.name }} 
                            </li>
                        </ul>
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
</template>

<script>
    export default {
        data() {
            return {
                categories: {},
                newcategory: {}
            }
        },
        mounted() {
            console.log('Component mounted.');
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
            }
        },
    }
</script>
