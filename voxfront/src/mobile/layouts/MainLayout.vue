<template>
  <v-layout ref="app" class="pa-0 w-100">
    <v-app-bar v-if="layout_params.app_bar" color="white" elevation="0" style="border-bottom: var(--main-color) solid 7px !important;">
      <v-row class="font-weight-bold" no-gutters style="height: 100%">
        <v-col cols="2" v-if="layout_params.tabsHeader">
          <v-sheet class="pa-2 ma-2 pl-0 ml-0">
          <v-icon @click="$router.go(-1)">mdi-arrow-left</v-icon>
          </v-sheet>
        </v-col>
        <!-- Title, Subtitle, Options Button -->
        <v-col v-if="layout_params.app_bar_title || layout_params.dynamicTitle">
          <v-sheet class="pa-2 ma-2">
            <div class="main">{{ layout_params.dynamicTitle ? $store.MobileDynamicTitle : layout_params.app_bar_title }}</div>
            <div v-if="layout_params.dynamicSubTitle" class="v-card-subtitle pa-0 text-grey">
              {{ $store.MobileDynamicSubTitle }}
            </div>
          </v-sheet>

        </v-col>
        <!-- Tabs Header -->
        <v-col v-else-if="layout_params.tabsHeader">
          <v-tabs v-model="$store.MobileCurrentTab" align-tabs="center">
            <v-tab v-for="tab in layout_params.tabsHeader" class="menu-tab" :key="tab" :value="tab">
              <v-btn>{{ tab }}</v-btn>
            </v-tab>
          </v-tabs>
        </v-col>

        <!-- Default Content -->
        <template v-else>
          <v-col class="text-left pl-7 mt-1" cols="2">
            <v-badge id="avatar_badge" color="green" location="right bottom">
              <v-avatar class="text-white" size="50">
                <v-img :src="$store.loggedUserProfilePic"></v-img>
              </v-avatar>
            </v-badge>
          </v-col>
          <v-col class="text-left ml-2">
            <p class="main text-capitalize mb-n2 mt-3">{{ $store.logged_user.userName }}</p>
            <p style="font-size: 15px; color: grey">{{ $store.logged_user.email }}</p>
          </v-col>
        </template>
        <!-- Baseline icon in all pages (at the end) -->
        <v-col class="text-right main" cols="2">
          <v-sheet class="pa-2 ma-2 pt-0">
            <img alt="baseline" src="@/assets/icons/baseline_icon_primary.png"
                 width="50" @click="$router.push('/baseline')">
          </v-sheet>
        </v-col>
      </v-row>
    </v-app-bar>
    <v-main>
      <div>
        <slot/>
      </div>
    </v-main>
    <v-bottom-navigation v-if="layout_params.bottom_navigation">
      <v-row class="mt-1 ml-2 mr-2 overflow-cols">
        <v-col>
          <v-btn class="mr-2 ml-2" size="25" @click="() => $router.push('/userTasks')">
            <v-icon>mdi-progress-check</v-icon>
            {{ tr('Tasks') }}
          </v-btn>
        </v-col>
        <v-col>
          <v-btn v-if="notifyMe" class="mr-2 ml-2" size="25" @click="() => { $router.push('/chat') }">
            <v-badge>
              <template #badge>
                <v-icon color="red" size="9">mdi-circle</v-icon>
              </template>
              <v-icon size="22" style="transform: scaleX(-1);">mdi-chat-outline</v-icon>
            </v-badge>
            {{ tr('Chats') }}
          </v-btn>
          <v-btn v-else class="mr-2 ml-2" size="25" @click="() => $router.push('/chat')">
            <v-icon style="transform: scaleX(-1);">mdi-chat-outline</v-icon>
            {{ tr('Chats') }}
          </v-btn>
        </v-col>

        <v-col>
          <v-btn class="mr-2 ml-2 main text-white" size="25"
                 @click="() => { $router.push('/baseline'); openMapOptions = true; }">
            <v-icon>mdi-plus-circle</v-icon>
            {{ tr('Add') }}
          </v-btn>
        </v-col>

        <v-col>
          <v-btn class="mr-2 ml-2" size="25" @click="() => $router.push('/history')">
            <v-icon>mdi-history</v-icon>
            {{ tr('History') }}

          </v-btn>
        </v-col>

        <v-col>
          <v-btn class="mr-2 ml-2" size="25" @click="() => $router.push('/settings')">
            <v-icon>mdi-cog-outline</v-icon>
            {{ tr('Settings') }}

          </v-btn>
        </v-col>

      </v-row>
    </v-bottom-navigation>
    <v-bottom-sheet v-model="openMapOptions">
      <v-card class="white-bg">
        <v-card-text class="card-text text-center">
          <p class="font-weight-bold mb-2">{{ tr('Add new place') }}</p>
          <v-row>
            <v-col>
              <v-col>
                <v-btn class="bg-blue" icon="mdi-cursor-pointer" size="x-large" @click="handDrawing"/>
                <div class="mt-1">{{ tr('Hand drawing') }}</div>
              </v-col>
            </v-col>
            <v-col>
              <v-col cols="auto">
                <v-btn class="bg-green" icon="mdi-walk" size="x-large" @click="walkToDraw"/>
                <div class="mt-1">{{ tr('Walk-To -Draw') }}</div>
              </v-col>
            </v-col>
            <v-col>
              <v-col cols="auto">
                <v-btn class="text-white" icon="mdi-map-marker-outline" size="x-large"
                       style="background-color:  #c653c6" @click="pinOnMap"/>
                <div class="mt-1">{{ tr('Pin on map') }}</div>
              </v-col>
            </v-col>
          </v-row>
        </v-card-text>
        <v-card-text class="card-text text-center">
          <p class="font-weight-bold mb-2">{{ tr('Others') }}</p>
          <v-row>
            <v-col>
                <v-btn class="bg-yellow" icon="mdi-exclamation-thick" size="x-large" @click="reportInMap"/>
                <div class="mt-1">{{ tr('Report in map') }}</div>
            </v-col>
            <v-col>
                <v-btn class="bg-red" icon="mdi-circle-double" size="x-large" @click="startLiveTracking()"/>
                <div class="mt-1">{{ tr('Live tracking') }}</div>
            </v-col>
            <v-col>
                <v-btn class="bg-indigo" icon="mdi-format-list-group-plus" size="x-large" @click="questionnaire_list = true, openMapOptions = false"/>
                <div class="mt-1">{{ tr('New from questionnare') }}</div>
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              <v-btn block color="warning" variant="outlined" @click="openMapOptions = false">{{ tr('Close') }}</v-btn>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-bottom-sheet>
    <QuestionnaireListDialog v-model="questionnaire_list"></QuestionnaireListDialog>
  </v-layout>
</template>

<script>
import QuestionnaireListDialog from "@/mobile/components/QuestionnaireListDialog.vue";

export default {
  name: "MainLayout",
  components: {QuestionnaireListDialog},
  data() {
    return {
      tab: null,
      openMapOptions: false,
      questionnaire_list: false,
    }
  },

  computed: {
    notifyMe() {
      return this.$chat_store.chatList.some(chat => {
        return chat?.messages?.some(message => {
           return !message.seenBy.some(user => user.id === this.$store.logged_user.id);
        });
      });
    }
    ,
    layout_params() {
      let route = this.$route.matched[0]
      if ((typeof route !== "undefined" && typeof route.props !== "undefined" && route.props.default !== false)) {
        return route.props.default
      }
      return {
        app_bar: true,
        bottom_navigation: true,
        tabsHeader: false,
        dynamicTitle: false,
        dynamicSubTitle: false,
        optionsButton: false,
      }
    }
  },
  methods: {
    handDrawing() {
      this.openMapOptions = false;
      window.mainMap.handDrawing()
    },
    reportInMap() {
      this.$chat_store.initReport();
      this.$router.push("/report");
      this.openMapOptions = false;
    },
    walkToDraw() {
      this.openMapOptions = false;
      window.mainMap.walkToDraw()
    },
    pinOnMap() {
      this.openMapOptions = false;
      window.mainMap.pinOnMap();
    },
    startLiveTracking() {
      window.mainMap.startLiveTracking()
      this.openMapOptions = false
    }
  }
}
</script>

<style>
#avatar_badge .v-badge__badge {
  outline: white 3px solid !important;
  height: 12px;
  min-width: 10px !important;
}

.v-badge__badge {
  top: -3px;
  background-color: transparent;
}

.mdi-circle {
  background-color: white;
  left: -3px;
}
.menu-tab{
  padding: 0;
  color: #e5992c;
}

.menu-tab.v-tab--selected .v-tab__slider {
    display: none;
}

.menu-tab.v-tab--selected button {
  background: #e5992c !important;
  text-decoration: unset !important;
  color: white;
}
</style>

