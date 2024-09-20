<template>
   <div class="pa-0">
     <v-row class="pa-0 h-100" style=" height: 100% ; width: 100%">
       <v-col class="pa-0 h-100 overflow-y-auto">
         <v-card elevation="0" class="text-left h-100">
           <v-card-title><img height="80" src="../../assets/logo/logo.png" alt="logo"></v-card-title>
           <v-card-text style="padding: 12px 120px !important;">
             <p class="text-h4 main font-weight-bold">{{tr('Log In')}}</p>
             <p class="text-h6 main mt-2 mb-7">Login in with your data <br/>{{tr('you entered during the registration')}}</p>
             <br/>
             <v-alert v-if="login_message" :title="login_message" class="mb-2" variant="tonal" color="warning" density="compact"></v-alert>
             <v-form @submit.prevent>
               <v-text-field v-model="email" :placeholder="tr('Email')" variant="solo"></v-text-field>
               <v-text-field v-model="password" type="password" :placeholder="tr('Password')" variant="solo"></v-text-field>
               <v-checkbox :label="tr('Keep me logged in')" color="#3988b0" id="keep_logged_in"></v-checkbox>
               <v-btn type="submit" elevation="0" class="main-bg text-white mb-3" style="padding: 25px 30px !important;" block
                      @click="logIn()">
                 <span v-if="!loading">{{tr('Log In')}}</span>
                 <v-progress-circular
                   v-model="loading"
                   v-else
                   :width="2"
                   color="white"
                   indeterminate
                 ></v-progress-circular>
               </v-btn>
             </v-form>
             <p>
               <span class="main">{{tr("Don't have an account? ")}}</span>
               <a class="link" href="#">{{tr('Sing Up')}}</a>
               <br/>
               <a class="link" href="#">{{tr('Forgot Password?')}}</a>
             </p>
           </v-card-text>
         </v-card>
       </v-col>
       <v-col style="background: #3581aa; height: 100vh">
         <v-card elevation="0" class="main-bg pa-0 h-100">
           <img class="h-100" src="../../assets/login.png" alt="login">
         </v-card>
       </v-col>
     </v-row>
   </div>
</template>

<script>

export default {
  name: "Login-Page",
  data: () => ({
    email: '',
    password: '',
    login_message: null,
    loading: false
  }),
  created() {
    this.$utils.redirectToTarget();
  },
  methods: {
    logIn() {
      this.overlay = true;
      this.$axios
        .post('login_check', {
            username: this.email,
            password: this.password
          }
        )
        .then(response => {
          this.$store.getUser("/api/users/"+response.data.user )
          window.token = response.data.token
          this.$store.token = response.data.token
        })
        .catch(error => {
          this.overlay = false;
          if (error.response && error.response.data) {
            this.login_message = 'Invalid Credentials';
          } else {
            this.login_message =  error && error.message ? error.message : 'A problem occurred, if the issue persist contact administration';
          }
        })
    }
  }
}
</script>

<style>
.link {
  text-decoration: none !important;
  color: var(--main-color) !important;
  font-weight: bold;
}
</style>
