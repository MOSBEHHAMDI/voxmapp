<template>
  <v-virtual-scroll :key="i" :height="'calc(100vh - 135px)'" :items="Object.keys(groupedMessages)"
                    class="pale-bg">
    <template v-slot="{ item }">
      <v-card class="overflow-cols mt-4 mb-4" color="transparent"
              elevation="0">
        <v-row>
          <v-col>
            <v-label :text="item"
                     class="pr-2 pl-2 pt-0 rounded-xl bg-grey text-white"/>
          </v-col>
        </v-row>
        <div v-for="message in groupedMessages[item]">
          <!--  Messages (green)  -->
          <v-row v-if="logged(message)  " class="text-right mr-1">
            <v-col>
              <div :class="{'resp-tri resp-tri-right ':message.lastMsgPerUserOrDate}"
                   class="rounded talk-bubble green-bg pa-2 mr-4 mai"
                   style="background-color:  #eefdcb;">
                <div class="talktext">
                  <div class="text-left" style="font-size: 15px;width:200px  ">{{ message.messageText }}</div>
                  <div>
                    <cached-img v-for="(file) in message.mediaFiles" :ref="file.id"
                                :src="file"
                                class="rounded"
                                @click="openFullScreen(file)"
                    />
                  </div>
                  <div class="text-right text-grey" style="font-size: 12px">
                  <span :class="{'overMediaPosition': message.mediaFiles.length }">{{
                      $utils.extractTime(message.sendDate, false)
                    }}</span>
                  </div>

                </div>
              </div>
            </v-col>
          </v-row>
          <!--  Responses (with avatar) -->
          <v-row v-else class=" text-left">
            <v-col class="ml-3">
              <div v-if="isGroupChat" class="avatar-container">
                <div v-if="!message.lastMsgPerUserOrDate" class="avatar-container">
                  <v-avatar image=""></v-avatar>
                </div>
                <div v-else class="avatar-container">
                  <v-avatar color="grey-lighten-1">
                    <CachedImg :defaulPic="defaultAvatar" :src="message.sender.profilePic"></CachedImg>
                  </v-avatar>
                </div>
              </div>
              <div :class="{'resp-tri tri-left left-in':message.lastMsgPerUserOrDate}" class="talk-bubble pa-2 rounded">
                <div class="talktext">
                  <div :style="{ color:namesColors[message.sender.id] }" class="text-capitalize font-weight-bold">
                    {{ message.sender.userName }}
                  </div>

                  <div style="font-size: 15px; width: 213px">{{ message.messageText }}</div>
                  <div class="text-right mt-n2 text-grey" style="font-size: 12px">
                    {{ $utils.extractTime(message.sendDate, false) }}
                  </div>
                </div>
              </div>
            </v-col>
          </v-row>

        </div>
        <v-card class="customOpacity text-center rounded ml-16 bg-grey text-white" max-height="10%"
                max-width="200px">
          <!--   <v-card-text class=" mr-n2 ml-n2">
               Marcos was added to the project (Classical satuses)
             </v-card-text>  -->
        </v-card>
      </v-card>
    </template>
  </v-virtual-scroll>
  <v-dialog v-model="openImageDialog">
    <v-card>
      <cached-img v-if="fullSCreenImage" :src="fullSCreenImage" class="border"></cached-img>
    </v-card>
  </v-dialog>
  <div class="bottom-nav bg-grey-lighten-3">
    <v-text-field v-model="newMessage.messageText" class="bg-grey-lighten-3 mt-2 msg_field"
                  density="compact"
                  variant="solo-filled">
      <template #append>
        <v-icon @click="sendMessage()">
          mdi-send
        </v-icon>
      </template>
      <template #append-inner>
        <v-icon class="incline">
          mdi-attachment
        </v-icon>
      </template>
    </v-text-field>
  </div>
</template>
<script>
import CachedImg from "@/web/components/CachedImg.vue";

export default {
  name: "ChatMessages",
  components: {CachedImg},
  data() {
    return {
      defaultAvatar: 'https://cdn.vuetifyjs.com/images/lists/2.jpg',
      isResponce: true,
      fullSCreenImage: null,
      dialog: false,
      openImageDialog: false,
      colorsList: ['green', '#3988b0', '#808080', 'black', 'gray'],
      namesColors: {},
      newMessage: {},
      interval: null,
    };
  },

  created() {
    this.$chat_store.getChat().then((resp) => {
      this.currentChat = resp;
      this.currentChat.messages.forEach(async message => {
        if (this.$store.logged_user.id !== message.sender.id && !message.seenBy.includes(this.$store.logged_user.id)) {
          await message.seenBy.push(this.$store.logged_user.id); // it supposed to work without async message
        }
      });
      this.$chat_store.save();
    });
    this.currentChat.receivers.forEach(receiver => {
      if (!this.namesColors.hasOwnProperty(receiver.id)) {
        const colorIndex = Object.keys(this.namesColors).length % this.colorsList.length;
        this.namesColors[receiver.id] = this.colorsList[colorIndex]
      }
    })
    this.newMessage = this.$chat_store.initMessage();
    if (this.currentChat.id) {
      //  this.$chat_store.getChat();
      this.interval = setInterval(async () => {
        const oldMsgsNumber = this.currentChat.messages.length;
        this.$chat_store.getChat().then((resp) => {
          if (resp.messages.length > oldMsgsNumber) {
            //  we need to do the same process of created to set new received messages ass seen
          }
        });
      }, 5000);
    }
  },
  beforeRouteLeave() {
    clearInterval(this.interval);
    this.currentChat = {};
  },
  computed: {
    currentChat: {
      get() {
        return this.$chat_store.currentChat
      }
      , set(newValue) {
        this.$chat_store.currentChat = newValue
      }
    },
    isGroupChat() {
      return this.currentChat.receivers.length > 2
    },
    groupedMessages() {
      let today = new Date();
      const messages = this.currentChat.messages ? JSON.parse(JSON.stringify(this.currentChat.messages)) : [];
      let groupedMessages = {};
      messages.forEach((message, index) => {
        const sendDate = this.$utils.formatDate(message.sendDate, '-');
        if (!groupedMessages[sendDate]) {
          groupedMessages[sendDate] = [];
        }
        const nextMessage = messages[index + 1];
        message.lastMsgPerUserOrDate = !nextMessage || (sendDate !== this.$utils.formatDate(nextMessage.sendDate))
            || (nextMessage.sender.id !== message.sender.id);
        groupedMessages[sendDate].push(message);
      });
      return groupedMessages;
    }
  }
  ,
  methods: {
    sendMessage() {
      if (this.newMessage.lastMsgPerUserOrDate) {
        delete this.newMessage.lastMsgPerUserOrDate
      }
      this.$chat_store.sendMessage(this.newMessage);
      this.newMessage = this.$chat_store.initMessage();
    },
    openFullScreen(file) {
      this.fullSCreenImage = file
      this.openImageDialog = true
    },
    logged(message) {
      return message && message.sender ? this.$store.logged_user.id === message.sender.id : false;
    },
  }
}
</script>

<style>
.avatar-container {
  display: inline-block;
}

.overMediaPosition {
  position: absolute;
  width: 90px;
  bottom: 12px;
  color: white;
  font-weight: bold;
  right: 16px;
  color: white;
  text-shadow: 2px 2px 4px #000000;
}


.msg_field .v-field {
  border-radius: 50px !important;
}

.customOpacity {
  opacity: 0.5;
}

.bottom-nav {
  position: fixed;
  bottom: 1px;
  padding: 2px 10px;
  height: 70px;
  width: 100%;
}

.incline {
  transform: rotate(140deg);

}

.talk-bubble {
  margin-left: 20px;
  display: inline-block;
  position: relative;
  width: 250px;
  height: auto;
  background-color: #ffffff;
  right: 0;
}

.resp-tri:after {
  content: ' ';
  position: absolute;
  width: 0;
  height: 0;
  left: auto;
  right: -10px;
  bottom: -12px;
  border: 12px solid;
  border-color: #ffffff transparent transparent #ffffff;
  transform: rotate(45deg);
}

.tri-left.left-in:after {
  left: -10px;
}

.resp-tri-right.resp-right-in:after {
  right: -10px;

}

.resp-tri-right.green-bg:after {
  border-color: #eefdcb transparent transparent #eefdcb;
}

</style>