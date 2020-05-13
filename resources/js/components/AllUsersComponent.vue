<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header row">
                        <div class="col">
                            Users List
                        </div>

                        <div class="col text-right">
                        <router-link :to="{name: 'new_user'}">+</router-link>
                        </div>
                    </div>

                    <div class="card-body">
                        <ul id="example-1">
                            <li class="container py-1" v-for="user in users" :key="user.user_id">
                                <div v-if="!in_edit.includes(user.user_id)">  
                                    {{ user.username }}<span v-if="user.is_admin==1" class="badge badge-success">admin</span>
                                    <button v-if="$cookies.get('user_data').is_admin == 1" class="btn btn-sm btn-primary" v-on:click="in_edit.push(user.user_id)">Edit</button>    
                                </div>
                                <div v-if="in_edit.includes(user.user_id)">
                                    <input v-model="user.username">
                                    <input type="checkbox" value=1 v-model="user.change_access"> <label v-if="user.is_admin == 1">Revoke admin access</label> <label v-else>Grant admin access</label>
                                    <button class="btn btn-sm btn-primary" v-on:click="save_user(user)">Save</button>
                                    <button class="btn btn-sm btn-danger" v-on:click="delete_user(user)">Delete</button>
                                    <a href="#" v-on:click="cancel(user)"><span class="fa fa-times-circle"></span></a>
                                </div>
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
                users: {},
                in_edit: []
            }
        },
        created() {
            console.log('Component mounted.');
            console.log(this.$cookies.get('token'));
            this.loadUsers();
        },
        methods: {
            loadUsers() {
                let uri= 'http://localhost:8000/api/users';
                let auth = 'Bearer ' + this.$cookies.get('token');
                console.log(auth);
                this.axios.get(uri, {
                    headers: {
                        'Authorization': auth
                    }
                }). then((response) => {
                    console.log(response.data);
                    this.users = response.data;
                })
            },
            save_user(user) {

                if (user.change_access == 1) {
                    user.is_admin = !user.is_admin;   
                }
                let uri= 'http://localhost:8000/api/users';
                let auth = 'Bearer ' + this.$cookies.get('token');
                console.log(auth);
                console.log(user);
                this.axios.put(uri, {user_id: user.user_id, username: user.username, is_admin: user.is_admin}, {
                    headers: {
                        'Authorization': auth
                    }
                }). then((response) => {
                    console.log(response.data);
                    var index = this.in_edit.indexOf(user.user_id);
                    if (index !== -1) this.in_edit.splice(index, 1);
                })
            },
            delete_user(user) {
                let uri='http://localhost:8000/api/users/'+user.user_id;
                let auth = 'Bearer ' + this.$cookies.get('token');
                this.axios.delete(uri, {
                    headers: {
                            'Authorization': auth
                        }
                }).then((response) => {
                    console.log(response.data);
                    var index = this.in_edit.indexOf(user.user_id);
                    if (index !== -1) this.in_edit.splice(index, 1);
                    var index = this.categories.indexOf(user);
                    if (index !== -1) this.categories.splice(index, 1);
                });
            },
            cancel(user) {
                var index = this.in_edit.indexOf(user.user_id);
                if (index !== -1) this.in_edit.splice(index, 1);
            }
        },
    }
</script>
