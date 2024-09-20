<template>
  <div class="pale-bg h-100">
    <v-card class="pa-0" color="transparent" elevation="0" min-height="800">
      <v-card-text>
        <v-card class="main-border border-8" elevation="0">
          <v-card-text>
            <v-badge class="avatar_badge" content="🖍" location="bottom right" offset-x="10" offset-y="20"
                     style="cursor: pointer"
                     @click="openCamera">
              <v-avatar class="main-bg text-white" size="80">
                <v-img :src="$store.loggedUserProfilePic"></v-img>
              </v-avatar>
              <!---To do once activate cordova chose file or camera -->
            </v-badge>
          </v-card-text>
        </v-card>
        <v-card color="transparent" elevation="0">
          <v-card-title class="pl-0">
            <h5 class="main text-left text-capitalize">App</h5>
          </v-card-title>
          <v-card-text class="bg-white border-8 pa-5">
            <v-text-field v-model="$store.logged_user.userName" density="compact"
                          placeholder="Username"
                          variant="outlined"></v-text-field>
            <v-text-field v-model="$store.logged_user.email" density="compact"
                          placeholder="Email"
                          variant="outlined"></v-text-field>
            <v-text-field v-model="$store.logged_user.surname" density="compact"
                          placeholder="Last Name"
                          variant="outlined"></v-text-field>
            <v-text-field v-model="$store.logged_user.firstName" density="compact"
                          placeholder="First Name"
                          variant="outlined"></v-text-field>
            <v-text-field v-model="$store.logged_user.phoneNumber" density="compact"
                          placeholder="Phone Number"
                          variant="outlined"></v-text-field>
          </v-card-text>
        </v-card>
        <v-card color="transparent" elevation="0">
          <v-card-title class="pl-0">
            <h5 class="main text-left text-capitalize">Role/s</h5>
          </v-card-title>
          <v-card-text class="bg-white border-8 pa-5 text-left">
            <p class="font-weight-bold text-capitalize">{{ $store.logged_user.roles.join(' + ') }}</p>
          </v-card-text>
        </v-card>
      </v-card-text>
      <v-card-actions>
        <v-btn @click="$store.saveUser($store.logged_user)" block color="orange" class="text-white" variant="flat">Save</v-btn>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
import MainLayout from "@/mobile/layouts/MainLayout";

export default {
  data() {
    return {
      select: {state: 'France', abbr: 'Fr'},
      items: [
        {state: 'France', abbr: 'FR'},
        {state: 'United Kingdom', abbr: 'UK'},
        {state: 'Afghanistan', abbr: 'Af'}
      ],
    }
  },
  components: {
    MainLayout
  },
  methods: {
    openCamera() {
        console.log("navigator camera : ", navigator.camera);
        this.$utils.takePhoto(Camera.PictureSourceType.CAMERA)
    }
  }
}
</script>
