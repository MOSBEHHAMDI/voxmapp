<template>
  <div>
    <v-dialog v-model="editChatPopup" width="700">
      <v-card class="relative">
        <v-card-title v-if="!inviteUsers && usersList?.length" class="pt-5 pb-5">
          {{ tr('Create a new group conversation ') }}
        </v-card-title>
        <v-card-text class="text-left mb-10">
          <v-text-field v-if="usersList?.length && !inviteUsers" v-model="chatLabel" :clearable="true"
                        label="Name" placeholder="Name"
                        variant="outlined"></v-text-field>
          <h4 class="mb-5">Add Participants</h4>
          <v-text-field  v-model="filteredUser" :clearable="true"
                        label="Search participants"
                        placeholder="Search participants" variant="outlined"></v-text-field>
          <v-list class="pa-0 rounded">
            <template v-for="(user, index) in usersList">
              <v-list-item v-if="user.id !== $store.logged_user.id" :key="index" :subtitle="user.email" :title="user.userName"
                           class="pa-0">
                <template #prepend>
                  <v-avatar color="grey-lighten-1">
                    <CachedImg v-if="user.profilePic" :src="user.profilePic"></CachedImg>
                  </v-avatar>
                </template>
                <template #append>
                  <v-checkbox v-model="selectedUsers" :value="user"></v-checkbox>
                </template>
              </v-list-item>
            </template>
            <v-list-item v-if="!usersList.length" :subtitle="tr('No user found !')"
                         :title="tr('Ou ou ...')"></v-list-item>
          </v-list>
        </v-card-text>
        <v-btn v-if="usersList?.length" color="primary" @click="inviteUsers ? sendRequests() : createNewChat()">
          {{ inviteUsers ? 'Send Requests' : '  Create Conversation' }}
        </v-btn>
      </v-card>
    </v-dialog>
    <v-row>
      <!-- chats -->
      <v-col cols="3" class="pl-5 pr-5">
        <v-card class="main" min-height="50%" elevation="0">
          <div class="text-left">
            <span class="main font-weight-bold font-size-medium text-left">{{ tr('Chats') }}</span>
            <v-btn class="float-end" color="primary" elevation="0" variant="elevated"
                   @click="showPopup(false)">{{
                tr('new chat')
              }}
              <v-icon class="ml-1" size="25">mdi-plus-circle</v-icon>
            </v-btn>
          </div>
          <v-card-text class="text-left pa-0 mt-3">
            <v-list v-if="chats.length" class="secondary-bg main pa-0">
              <v-list-item v-for="(chat, i) in chats"
                           :key="i"
                           prepend-icon="mdi-chat-outline"
                           :title="$utils.truncateText($utils.getLabel(chat.label),20)"
                           density="comfortable"
                           @click="selectChat(chat)" class="pa-5 border-bottom-main">
                <template #append><!-- i need this delete icon for test (cleaning up chats ...)-->
                  <v-icon @click="$chat_store.deleteChat(chat.id)">mdi-close-circle</v-icon>
                </template>

              </v-list-item>
            </v-list>
          </v-card-text>
        </v-card>
      </v-col>
      <!--  Messages  -->
      <v-col cols="6" class="pt-3">
        <div class="pa-0 secondary-bg rounded full-height">
          <v-card ref="messagesDiv" color="transparent" elevation="0" style="height: calc(100vh - 190px)">
            <v-row v-for="message in currentChat.messages" :key="message.id">
              <v-col v-if="$store.logged_user.id === message.sender?.id">
              </v-col>
              <v-col>
                <div class="talk-bubble  tri-right right-in pa-2 rounded-8">
                  <div class="talktext text-left">
                    <b class="text-capitalize">{{ message.sender?.userName }}</b>
                    <p>{{ $utils.getLabel(message.messageText) }}</p>
                  </div>
                </div>

                <v-avatar class="mt-2">
                  <img src="https://i.pravatar.cc/64">
                </v-avatar>
                <v-row v-if="message.mediaFiles.length">
                  <v-col
                      v-for="(file,n) in message.mediaFiles"
                      class="d-flex child-flex float-end"
                      cols="4"
                  >
                    <CachedImg
                        :src="file"
                        aspect-ratio="16/9"
                        cover
                    ></CachedImg>
                  </v-col>
                </v-row>
              </v-col>
              <v-col v-if="$store.logged_user.id !== message.sender?.id">
              </v-col>
            </v-row>
          </v-card>
          <v-card color="transparent" elevation="0">
            <v-row v-if="isCurrentChat" align="end" class="fill-height" justify="center">
              <v-col>
                <v-text-field v-model="newMessage.messageText" class="ml-4 special_input"
                              placeholder="New message"
                              variant="solo">
                </v-text-field>
              </v-col>
              <v-col class="text-center ml-0 mr-5" cols="1">
                <v-col>
                  <label style="cursor: pointer">
                    <v-avatar color="white" icon="mdi-folder-multiple-image" size="60">
                    </v-avatar>
                    <v-file-input
                        hidden hide-details prepend-icon=""
                        style="display:none"
                        type="file"
                        @change="uploadMedia">
                    </v-file-input>
                  </label>
                </v-col>
              </v-col>
              <v-col class="text-center ml-0 mr-5" cols="1">
                <v-btn class="main-general-btn mb-3"
                       @click="$chat_store.sendMessage(newMessage),newMessage =$chat_store.initMessage()">
                  <v-icon>mdi-check</v-icon>
                </v-btn>
              </v-col>
            </v-row>
          </v-card>
        </div>
      </v-col>
      <!-- Right Sidebar for Media and groups -->
      <v-col cols="3">
        <div class="main-bg text-left rounded full-height">
          <v-card color="transparent" elevation="0">
            <v-card-title v-if="isCurrentChat" class="text-white">
              <h3 class="text-center text-capitalize mb-10">
                {{ $utils.truncateText($utils.getLabel(currentChat.label), 20) }}</h3>
              <v-row>
                <v-col>{{ tr('Participants ') + '(' + this.currentChat.receivers.length + ')' }}</v-col>
                <v-col>
                  <v-btn
                      block
                      class="text-white bg-transparent white-dashed-border"
                      prepend-icon="mdi-plus-circle"
                      elevation="0"
                      @click="showPopup(true)"
                  >
                    {{ tr('Invite') }}
                  </v-btn>
                </v-col>
              </v-row>
            </v-card-title>
            <v-card-text>
              <v-row v-if="isCurrentChat">
                <!-- Big avatar column -->
                <v-col cols="3">
                  <v-avatar color="grey-lighten-1" size="120">
                    <CachedImg :src="avatarsToShow[0]"></CachedImg>
                  </v-avatar>
                </v-col>
                <!-- Small avatars column -->
                <v-col class="ml-10">
                  <v-row>
                    <v-col v-for="(avatar, index) in avatarsToShow.slice(1)" :key="index" cols="6">
                      <v-avatar color="grey-lighten-1" size="60">
                        <CachedImg :src="avatar"></CachedImg>
                      </v-avatar>
                    </v-col>
                  </v-row>
                  <div v-if="shouldShowExpandButton" class="text-white" @click="toggleExpand">
                    <v-icon>{{ expanded ? 'mdi-chevron-up' : 'mdi-chevron-down' }}</v-icon>
                  </div>
                </v-col>
              </v-row>
            </v-card-text>
          </v-card>
          <v-card color="transparent" elevation="0">
            <v-card-title v-if="allMedia?.length" class="text-white">
              {{ tr('Media') + '(' + allMedia.length + ')' }}
            </v-card-title>
            <v-card-text>
              <v-row class="mt-4">
                <v-col
                    v-for="(file,n) in allMedia"
                    class="d-flex child-flex"
                    cols="3"
                >
                  <CachedImg
                      :src="file"
                      aspect-ratio="16/9"
                      cover
                  ></CachedImg>
                </v-col>
              </v-row>
            </v-card-text>
          </v-card>
        </div>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import Translatable from "@/web/components/Translatable.vue";
import CachedImg from "@/web/components/CachedImg.vue";

export default {
  components: {CachedImg, Translatable},
  data() {
    return {
      downloadedFile: null,
      expanded: false,
      chatLabel: null,
      filteredUser: '',
      selectedUsers: [],
      editChatPopup: false,
      inviteUsers: false,
      limitToShow: 4,
      newMessage: {}
    };
  },
  created() {
    this.$chat_store.getChatList();
    this.$store.getHomePageInfo();
    this.currentChat = {};
  },
  mounted() {
    this.$utils.scrollToBottom();
    this.newMessage = this.$chat_store.initMessage();
  },
  computed: {
    allMedia() {
      return this.currentChat.messages?.flatMap(message => message.mediaFiles);
    },
    isCurrentChat() {
      return this.currentChat && Object.keys(this.currentChat).length
    },
    currentChat: {
      get() {
        return this.$chat_store.currentChat;
      },
      set(value) {
        this.$chat_store.currentChat = value;
      }
    },
    avatarsToShow() {
      const receiversAvatars = this.currentChat.receivers.map(receiver => receiver.profilePic?.id);
      return this.expanded ? receiversAvatars : receiversAvatars.slice(0, this.limitToShow);
    },
    shouldShowExpandButton() {
      return this.currentChat.receivers.length > this.limitToShow;
    },
    usersList() {
      const usersInfo = this.$store.home_page_info.users;
      if (!usersInfo) {
        return []; // or handle the null case appropriately
      }
      const receivers = this.inviteUsers && this.currentChat.receivers ? this.currentChat.receivers.map(receiver => receiver.id) : [];
      const lowerFilter = this.filteredUser?.toLowerCase();

      return usersInfo.filter(user =>
          !receivers.includes(user.id) &&
          (!lowerFilter ||
              user.userName.toLowerCase().includes(lowerFilter) ||
              user.email.toLowerCase().includes(lowerFilter)
          )
      );
    },
    chats: {
      get() {
        return this.$chat_store.chatList;
      },
      set(value) {
        this.$chat_store.chatList = value;
      }
    }
  },
  methods: {
    uploadMedia(e) {
      //we need to solve nginx file upload issue to accept all files types
      const file = e.target.files[0]
      this.$store.uploadFile(file, 'chat').then(async (response) => {
        const mediaIri = '/api/media/' + response.mediaId; // it supposed to be an iri by defaults !
        this.newMessage.mediaFiles.push(mediaIri);
        this.$chat_store.sendMessage(this.newMessage);
        this.newMessage = this.$chat_store.initMessage();
      });
    },
    selectChat(chat) {
      this.currentChat = chat
    },
    showPopup(inviteUsers) {
      this.selectedUsers = [];
      this.editChatPopup = true;
      this.inviteUsers = inviteUsers;
    },
    handleChatConfirm(id) {
      this.$utils.confirmationDialog({
        content: 'Are you sure, you want to delete this chat ?',
      }, () => {
        if (id) {
          this.$chat_store.deleteChat(id);
        } else {
          this.$chat_store.chatList = this.$chat_store.chatList.filter(chat => chat && chat.id)
        }
      })
    },
    toggleExpand() {
      this.expanded = !this.expanded;
    },
    createNewChat() {
      this.editChatPopup = false;
      this.$chat_store.createNewChat(this.selectedUsers,this.chatLabel);
      this.selectedUsers = [];
      this.filteredUser = '';
      this.chatLabel =  null;
    },
    sendRequests() {
      if (this.selectedUsers.length) {
        this.editChatPopup = false;
        this.selectedUsers.forEach(user => {
          this.currentChat.receivers.push(user);
        })
         this.$chat_store.save();
        this.selectedUsers = [];
        this.filteredUser = '';

      }
    },
  }
};
</script>

<style>
.talk-bubble {
  margin: 20px;
  display: inline-block;
  position: relative;
  width: 300px;
  height: auto;
  background-color: #ffffff;
}

.tri-right.right-in:after {
  content: ' ';
  position: absolute;
  width: 0;
  height: 0;
  left: auto;
  right: -15px;
  bottom: -1px;
  border: 12px solid;
  border-color: #ffffff transparent transparent #ffffff;
}

.float {
  position: fixed;
  width: 60px;
  height: 60px;
  bottom: 20px;
  right: 20px;
  text-align: center;
}

.full-height {
  height: 100%;
}

</style>
