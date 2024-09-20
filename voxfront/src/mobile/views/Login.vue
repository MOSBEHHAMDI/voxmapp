<template>
  <v-card color="transparent" elevation="0" class="text-center">
    <v-overlay
        :model-value="overlay"
        class="align-center justify-center"
    >
      <v-progress-circular
          color="primary"
          indeterminate
          size="64"
      ></v-progress-circular>
    </v-overlay>
    <div class="text-center ma-auto pt-15">
      <img class="mt-15" src="@/assets/logo/icon.svg" width="120" alt="logo"/>
      <br>
      <img src="@/assets/logo/logo_text.png" width="140" alt="logo"/>
    </div>
    <div class="text-center pl-10 pr-10 mt-10">
      <v-text-field v-model="email" placeholder="Email" variant="outlined" density="compact"></v-text-field>
      <v-text-field
          v-model="password"
          :append-inner-icon="show_password ? 'mdi-eye' : 'mdi-eye-off'"
          :type="show_password ? 'text' : 'password'"
          name="input-10-1"
          placeholder="Password"
          variant="outlined"
          density="compact"
          @click:append-inner="show_password = !show_password"
      ></v-text-field>
      <v-alert v-if="login_message !== ''" variant="outlined" density="compact" rounded color="red" class="mb-3">
        {{ login_message }}
      </v-alert>
      <v-btn @click="logIn" color="primary" elevation="0" width="150" class="border-11">Login</v-btn>
      <div class="text-grey mt-2">
        <p>Don't have an account? <a class="primary-text text-decoration-none" href="sign_up">Sign Up</a></p>
      </div>
    </div>
  </v-card>
</template>

<script>
export default {
  data() {
    return {
      overlay: false,
      show_password: false,
      email: '',
      password: '',
      login_message: '',
    }
  },
  methods: {
    logIn() {
      this.overlay = true
      this.login_message = ''
      this.$axios
          .post('login_check', {
            username: this.email, password: this.password
          })
          .then(response => {
            this.$store.getUser("/api/users/" + response.data.user)
            this.$dataPoint_store.getDatapointsList();
            this.$dataPoint_store.getDatapointsInfo();
            this.$store.getHomePageInfo();
            window.token = response.data.token
            this.$store.token = response.data.token
          })
          .catch(error => {
            this.overlay = false;
            if (error.response && error.response.data) {
              this.login_message = 'Invalid Credentials';
            } else {
              this.login_message = error && error.message ? error.message : 'A problem occurred, if the issue persist contact administration';
            }
          })
    }
  }
}
</script>