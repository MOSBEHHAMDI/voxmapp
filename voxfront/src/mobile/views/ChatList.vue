<template>
  <div       v-if="filtredChats.length"
>
   <v-text-field
      v-model="search"
      :placeholder="tr('Search')"
      class="ml-2 mr-2 mt-4 mb-4"
      density="compact"
      hide-details
      prepend-inner-icon="mdi-magnify"
      variant="solo-filled"
  ></v-text-field>
  <v-card
      v-for="chat in filtredChats"
      :key="chat.id"
      :class="{ 'bg-blue-lighten-5': selectedCard === chat.id }"
      @click="editChat(chat)"
  >
    <template v-slot:text>
      <v-list-item class="pa-0">
        <v-row>
          <v-col cols="2">
           <div v-if="chat.receivers.length == 2">
              <v-avatar v-if="chat.receivers[0].profilePic" color="grey-lighten-1">
              <CachedImg :src="chat.receivers[0].profilePic"></CachedImg>
            </v-avatar>
            <v-avatar v-else :image="defaultAvatar"></v-avatar>
           </div>

              <v-btn v-else class="mb-1 ml-n2 mt-2"  icon="mdi-account-group"></v-btn>

          </v-col>
          <v-col class="text-left mb-1 ml-n3">
            <div class="mt-2">
              <p class="font-weight-bold text-capitalize">{{ $utils.truncateText(chat.label, 30) }}</p>
              <p>{{ chat.latest_message?.messageText }}</p>
            </div>
          </v-col>
          <v-col cols="4">
            <div class="mb-1 mt-1" :class="{'main' : chat.unseen_messages > 0}">{{ $utils.getDateOrTime(chat.latest_message?.sendDate, '/') }}</div>
           <badge v-if="chat.unseen_messages>0"
                   :number="chat.unseen_messages" class="mr-12 ml-12"/>
          </v-col>
        </v-row>
      </v-list-item>
    </template>
  </v-card>
  </div>
<v-card v-else variant="tonal">
 <v-card-text>
    {{tr('No chat found')}}
 </v-card-text>
</v-card>
  <v-btn class="fixedBtn main-bg text-white" icon="mdi-plus" @click="newChatDialog = true"/>
  <v-bottom-sheet v-model="newChatDialog">
    <div v-if="chatReceivers.length">
          <v-card class="white-bg">
      <v-text-field
          v-model="newChatSearch"
          :placeholder="tr('Open new chat with .. ')"
          class="ml-2 mr-2 mt-4 mb-6"
          density="compact"
          hide-details
          prepend-inner-icon="mdi-magnify"
          variant="solo-filled"
      ></v-text-field>
    </v-card>
    <v-list class="border-t">
      <v-list-item v-for="(user, i) in chatReceivers"
                   :key="i"
                   :subtitle="user.email"
                   :title="user.userName"
                   class="rounded-8"
                   style="min-height: 80px"
                   variant="text"
                   @click="createNewChat(user)"
      >
        <template #prepend>
          <v-avatar color="grey-lighten-1">
            <CachedImg :defaulPic="defaultAvatar" :src="user.profilePic"></CachedImg>
          </v-avatar>
        </template>
        <v-divider class="mt-2"></v-divider>
      </v-list-item>
    </v-list>
    </div>

      <v-card v-else variant="tonal">
 <v-card-text>
    {{tr('No user found')}}
 </v-card-text>
</v-card>

  </v-bottom-sheet>
</template>

<script>
import Badge from "@/mobile/components/badge.vue";
import CachedImg from "@/web/components/CachedImg.vue";

export default {
  name: "ChatList",
  components: {CachedImg, Badge},
  data() {
    return {
      defaultAvatar : "https://cdn.vuetifyjs.com/images/lists/2.jpg" ,
      selectedCard: null,
      search: "",
      newChatSearch: "",
      newChatDialog: false,
      newMessage: {},
      interval : null,
    };
  },
  computed: {
    filtredChats() {
      return this.$chat_store.chatList.filter(chat => chat.label.includes(this.search));
    },
    chatReceivers() {
      return this.$store.home_page_info?.users.filter(user => {
        return user.userName.toLowerCase().includes(this.newChatSearch.toLowerCase())
            && user.id !== this.$store.logged_user.id;
      });
    }
  },
  created() {
    this.$chat_store.getChatList();
      this.interval = setInterval(async () => {
        this.$chat_store.getChatList();
      }, 15000);
    this.$store.getHomePageInfo();
    this.newMessage = this.$chat_store.initMessage();
  },
  beforeRouteLeave() {
    clearInterval(this.interval);
  },
  methods: {
    createNewChat(user) {
      this.$chat_store.createNewChat([user], user.userName);
      this.$store.MobileDynamicTitle = user.userName;
      this.$router.push('ChatMessages')
    },
    getNumber(number) {
      return number > 100 ? '100 + ' : number
    },
    editChat(chat) {
      this.selectedCard = chat.id;
      this.$chat_store.currentChat = chat;
      this.$store.MobileDynamicTitle = this.$utils.truncateText(chat.label, 30);
      this.$router.push('ChatMessages');
    }
  }
};
</script>
<style scoped>
.fixedBtn {
  width: calc(var(--v-btn-height) + 12px);
  height: calc(var(--v-btn-height) + 12px);
  color: black;
  position: fixed;
  left: 80%;
  bottom: 85px;
}
</style>