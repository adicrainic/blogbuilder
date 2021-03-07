<template>
    <div>
        <div class="container">
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20 col-md-4">
                    <div class="login-header">
                        <h1>Login to dashboard</h1>
                    </div>
                    <div class="space">
                        <Input  v-model="data.email"  type="email" placeholder="Email" />
                    </div>
                    <div class="space">
                        <Input  v-model="data.password" type="password" placeholder="Password" />
                    </div>

                    <div class="login_footer">
                        <Button type="primary" @click="login" :disabled="isLoggedIn" :loading="isLoggedIn">{{ isLoggedIn ? 'Login in...' : 'Login' }}</Button>
                    </div>
                </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            data : {
                email: '',
                password: ''
            },
            isLoggedIn : false,
        }
    },
    methods: {
        async login() {
            if(this.data.email.trim() == '')
                return this.errorMessage('Email is required!')
            if(this.data.password.trim() == '')
                return this.errorMessage('Password is required!')
            if(this.data.password.length < 6)
                return this.errorMessage('Incorrect login details!')

            this.isLoggedIn = true;
            const res = await this.callApi('post','app/admin_login', this.data)

            if(res.status === 200) {
                this.successMessage(res.data.msg);
                window.location = '/';
            }else{
                if(res.status === 401) {
                    this.errorMessage(res.data.msg)
                }else if(res.status === 422) {
                    for(let i in res.data.errors){
                        this.errorMessage(res.data.errors[i][0])
                    }
                }
                else{
                    this.genericMessage()
                }
            }

            this.isLoggedIn = false;

        }
    }
}
</script>

<style scoped>
    ._1adminOverveiw_table_recent {
        margin:0  auto;
        margin-top: 100px;
    }
    .login_footer {
        margin-top:30px;
        text-align: center;
    }
    .login-header {
        margin-bottom:30px;
        text-align: center;
    }
</style>
