<template>
    <div class="w-full">
        <div class="w-1/2 mx-auto rounded-lg border-2 p-8 pt-4">
            <div class="text-xl font-bold">
                Login
            </div>

            <input type="text" v-model="email" class="p-2 rounded border my-2 w-full" placeholder="Email">
            <input type="password" v-model="password" class="p-2 rounded border my-2 w-full" placeholder="Password">
            <a href="javascript:;" @click="submitLogin" type="submit" class="rounded bg-gray-800 text-white px-8 py-2 shadow font-semibold text-sm ml-2">Login</a>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return {
            email: '',
            password: ''
        }
    },
    methods: {
        submitLogin(){
            axios.post('/admin/login', {
                email: this.email,
                password: this.password
            })
            .then(function (response) {
                window.location = "/admin";
            })
            .catch(function (error) {
                alert("Bad Email / Password Combo");
                console.log("ERROR");
                console.log(error.response);
            })
        }
    }
}
</script>