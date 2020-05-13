<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>

                    <div class="card-body">
                        <form  @submit.prevent="login">
                            <input placeholder="username" v-model='user.username'>
                            <input placeholder="password" type="password" v-model='user.password'>
                            <button class="btn btn-primary" type="submit">Login</button>
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
                user: {}
            }
        },
        mounted() {
            console.log('Component mounted.')
        },

        methods: {
            login() {
                let uri = 'http://localhost:8000/api/login';
                this.axios.post(uri, this.user).then((response) => {
                    console.log(response);
                    if (response.data != "") {
                        this.$cookies.set('token', response.data);
                        this.$router.push({name: 'home'});
                        let uri = 'http://localhost:8000/api/user';
                        let auth = 'Bearer ' + this.$cookies.get('token');
                        this.axios.get(uri, {
                            headers: {
                                'Authorization': auth
                            }
                        } ,this.user).then((response) => {
                            this.$cookies.set('user_data', response.data);
                            console.log(this.$cookies.get('user_data'));
                        });
                            
                    }
                });
            }
        }
    }
</script>
