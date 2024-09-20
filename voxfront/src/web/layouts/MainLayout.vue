<template>
  <v-layout ref="app" class="pa-0 w-100">
    <v-app-bar v-if="layout_params.app_bar" color="white" data-name="app-bar" elevation="0">
      <div class="d-flex justify-center align-center w-100">
        <v-row class="hpx-65">
          <v-col class="hpx-65 pa-1 text-left">
            <img alt="logo" height="60" src="../../assets/logo/logo.png" @click="$router.push('/home')">
          </v-col>
          <v-col class="pa-1 hpx-65">
            <v-text-field
                :placeholder="tr('Search')"
                class="my-3"
                hide-details
                variant="solo"
            ></v-text-field>
          </v-col>
          <v-col v-if="$store.logged_user" class="hpx-65 pa-1 pr-5 text-right" md="4">
            <v-menu>
              <template v-slot:activator="{ props }">
                <v-avatar class="mr-5" :text="  $utils.currentLanguage().code.toUpperCase()" color="light-blue-lighten-5"
                          v-bind="props"></v-avatar>
              </template>
              <v-list class="text-left">
                <v-list-item>
                  <v-list-item-title class="primary-text">{{ $utils.currentLanguage().name }}</v-list-item-title>

                  <v-list-item-title class="mt-2" v-for="language in $store.home_page_info.languages" @click="setCurrentLanguage(language)">
                    <span v-if="language.code !==  $utils.currentLanguage().code"> {{language.name}}</span>
                  </v-list-item-title>
                </v-list-item>

              </v-list>
            </v-menu>
            <span class="mr-2 user-name text-capitalize">{{ $store.logged_user.userName }}</span>

            <v-menu>
              <template v-slot:activator="{ props }">
                <v-avatar
                    color="primary"
                    size="50"
                    v-bind="props"
                >
                  <v-img :src="$store.loggedUserProfilePic"></v-img>
                </v-avatar>
              </template>
              <v-list class="text-left">
                <v-list-item>
                  <v-list-item-title class="primary-text">{{ $store.logged_user.email }}</v-list-item-title>
                </v-list-item>
                <v-list-item>
                  <v-list-item-title>
                    <div @click="editAdminProfile">
                      <v-icon class="mr-2">mdi-account</v-icon>
                      <span>{{ tr('Profile') }}</span>
                    </div>
                  </v-list-item-title>
                </v-list-item>
                <v-divider></v-divider>
                <v-list-item>
                  <v-list-item-title class="danger" @click="this.$utils.disconnect()">
                    <v-icon class="mr-2">mdi-logout</v-icon>
                    <span>{{ tr('Log Out') }}</span></v-list-item-title>
                </v-list-item>
              </v-list>
            </v-menu>
          </v-col>
        </v-row>
      </div>
    </v-app-bar>
    <v-app-bar v-if="layout_params.breadcrumb" color="white" data-name="breadcrumb" elevation="0" height="35">
      <v-btn class="main pa-0" @click="$router.go(-1)">
        <v-icon class="ml-1 main">mdi-arrow-left-thin</v-icon>
        Back
      </v-btn>
      <div class="main ml-12 mr-2">
        <span @click="$router.push('/home')">Home / </span>
        <span v-for="(matched, idx) in $route.matched"
              :key="idx">
        <a
            :class="idx === Object.keys($route.matched).length - 1 ? 'font-weight-bold': ''" :href="matched.path"
            class="breadcrumb_text main">
        {{ matched.name }}
        </a>
        <span v-if="idx !== Object.keys($route.matched).length - 1"> / </span>
        </span>
      </div>
    </v-app-bar>
    <v-navigation-drawer v-if="layout_params.navigation_drawer" id="nav-drawer" color="white" name="drawer" permanent
                         width="105">
      <v-btn color="transparent" elevation="0" @click="editAdminProfile">
        <div>
          <v-icon color="var(--main-color)">mdi-account-group-outline</v-icon>
          <br/>
          <span class="list-text">Profile</span>
        </div>
      </v-btn>

      <v-btn color="transparent" elevation="0" @click="$router.push('/questionnaire/list')">
        <div>
          <v-icon color="var(--main-color)">mdi-poll</v-icon>
          <br/>
          <span class="list-text">Questions</span>
        </div>
      </v-btn>


      <v-btn color="transparent" elevation="0" @click="$router.push('/datapoint/list')">
        <div>
          <v-icon color="var(--main-color)">mdi-map-marker-multiple</v-icon>
          <br/>
          <span class="list-text">Data Points</span>
        </div>
      </v-btn>

      <v-btn color="transparent" elevation="0">
        <div>
          <v-icon color="var(--main-color)">mdi-comment-quote-outline</v-icon>
          <br/>
          <span class="list-text">Feedbacks</span>
        </div>
      </v-btn>

      <v-btn elevation="0">
        <div>
          <v-icon color="var(--main-color)">mdi-monitor-dashboard</v-icon>
          <br/>
          <span class="list-text">Dashboards</span>
        </div>
      </v-btn>

      <v-btn elevation="0" @click="$router.push('/task/list')">
        <div>
          <v-icon color="var(--main-color)">mdi-calendar-check-outline</v-icon>
          <br/>
          <span class="list-text">Tasks</span>
        </div>
      </v-btn>
      <v-btn elevation="0" @click="$router.push('/translation')">
        <div>
          <v-icon color="var(--main-color)">mdi-translate</v-icon>
          <br/>
          <span class="list-text">Translation</span>
        </div>
      </v-btn>

      <v-btn color="transparent" elevation="0" style="position: absolute; left: 20px; bottom: 10px"
             @click="$router.push('/chat/list')">
        <div>
          <v-icon color="var(--main-color)">mdi-chat-outline</v-icon>
          <br/>
          <span class="list-text">Chat</span>
        </div>
      </v-btn>
    </v-navigation-drawer>
    <v-main class="d-flex align-center justify-center" style="height: 100% !important;">
      <v-container fluid>
        <slot/>
        <div class="text-center ma-2">
          <VSonner :duration="2000" expand position="top-right"/>
        </div>
      </v-container>
    </v-main>
  </v-layout>
</template>

<script>
import {VSonner} from 'vuetify-sonner'
import 'vuetify-sonner/style.css'

export default {
  name: "MainLayout",
  components: {VSonner},
  data: () => ({}),
  methods: {
    setCurrentLanguage(language) {
      this.$store.logged_user.language = language;
      this.$store.saveUser(this.$store.logged_user,false,false);
    },
    editAdminProfile() {
      this.$store.selected_user = this.$store.logged_user;
      this.$store.user_to_edit = this.$store.logged_user;
      this.$router.push('/profile/edit');
    }
  },
  computed: {
    breadcrumbItems() {
      return [
        {
          text: 'Home',
          disabled: false,
          href: '/home',
        },
        {
          text: this.$route.name,
          disabled: false,
          href: this.$route.path,
        },
      ]
    },
    layout_params() {
      let route = this.$route.matched[0]
      console.log(route)
      if ((typeof route !== "undefined" && typeof route.meta !== "undefined" && Object.keys(route.meta).length !== 0)) {
        return route.meta
      }
      return {
        breadcrumb: true,
        app_bar: true,
        navigation_drawer: true
      }
    }
  }
}
</script>

<style>
.v-list-item__content {
  cursor: pointer !important;
}
</style>
