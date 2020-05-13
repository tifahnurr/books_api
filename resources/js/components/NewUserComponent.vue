<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>

                    <div class="card-body">
                        <form  @submit.prevent="register">
                            <input placeholder="username" v-model='user.username'>
                            <input placeholder="password" type="password" v-model='user.password'>
                            <input type="checkbox" v-model='user.is_admin' value=1> Admin
                            <button class="btn btn-primary" type="submit">Add New User</button>
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
            register() {
                let uri = 'http://localhost:8000/api/users';
                let auth = 'Bearer ' + this.$cookies.get('token');
                if (this.user.is_admin == 1) {
                    this.user.is_admin = 1;
                } else {
                    this.user.is_admin = 0;
                }
                console.log(this.user)
                this.axios.post(uri, this.user, {
                    headers: {
                            'Authorization': auth
                        }
                }).then((response) => {
                    console.log(response);
                });
            }
        }
    }
</script>
