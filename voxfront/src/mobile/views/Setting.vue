<template>
    <div class="pale-bg h-100">
      <v-card class="pa-0" color="transparent" min-height="800" elevation="0">
        <v-card-title class="mt-2 mb-2">
          <v-text-field variant="solo" density="compact" placeholder="Search"
                        prepend-inner-icon="mdi-magnify"></v-text-field>
          <v-divider></v-divider>
        </v-card-title>
        <v-card-text>
          <v-card elevation="0" class="main-border border-8">
            <v-card-text>
              <v-row>
                <v-col class="text-left">
                  <v-avatar class="main-bg text-white">
                    <v-img :src="$store.loggedUserProfilePic"></v-img>
                  </v-avatar>
                  <span class="ml-2 main">{{ $store.logged_user.userName }}</span>
                </v-col>
                <v-col class="text-right main ">
                  <v-icon class="mt-2" size="20" @click="$router.push('edit_profile')">mdi-pencil</v-icon>
                </v-col>
              </v-row>
            </v-card-text>
          </v-card>
          <v-card class="pl-0" color="transparent" elevation="0">
            <v-card-title class="pl-0">
              <h5 class="main text-left text-capitalize">App</h5>
            </v-card-title>
            <v-card-text class="bg-white border-8 main-border pa-0">
              <div class="text-left main-border-bottom pa-3">
                <v-icon class="main">mdi-abugida-thai</v-icon>
                <span class="ml-2 font-weight-bold text-capitalize">Change project</span>
              </div>
              <div class="text-left main-border-bottom pa-3">
                <v-icon class="main">mdi-abugida-thai</v-icon>
                <span class="ml-2 font-weight-bold text-capitalize">Go to feedback</span>
              </div>
              <div class="text-left main-border-bottom pa-3">
                <v-icon class="main">mdi-abugida-thai</v-icon>
                <span class="ml-2 font-weight-bold text-capitalize">Map view</span>
              </div>
              <div class="text-left pa-3">
                <v-icon class="main">mdi-abugida-thai</v-icon>
                <span class="ml-2 font-weight-bold text-capitalize">Report an issue</span>
              </div>
            </v-card-text>
          </v-card>
          <v-card class="pl-0" color="transparent" elevation="0">
            <v-card-title class="pl-0">
              <h5 class="main text-left text-capitalize">System</h5>
            </v-card-title>
            <v-card-text class="bg-white border-8 main-border pa-0">
              <div class="text-left main-border-bottom pa-3">
                <v-icon class="main">mdi-abugida-thai</v-icon>
                <span class="ml-2 font-weight-bold text-capitalize">Change language</span>
              </div>
              <div class="text-left pa-3">
                <v-icon class="main">mdi-abugida-thai</v-icon>
                <span class="ml-2 font-weight-bold text-capitalize">Privacy</span>
              </div>
            </v-card-text>
            <v-card-actions>
              <v-btn block @click="$utils.disconnect()" color="danger" variant="flat">
                <v-icon class="mr-1">mdi-logout</v-icon>
                Logout
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-card-text>
      </v-card>
    </div>
</template>

<script>
import MainLayout from "@/mobile/layouts/MainLayout";

export default {
  components: {
    MainLayout
  }
}
</script>
<style scoped>
.fixed-logout-btn {
  width: calc(var(--v-btn-height) + 12px);
  height: calc(var(--v-btn-height) + 12px);
  position: fixed;
  right: 10px;
  bottom: 75px;
}

</style>