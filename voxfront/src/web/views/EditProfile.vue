<template>
  <div>
    <v-row class="pa-0">
      <v-col class="bg-white main text-left pa-0" md="2">
        <v-card class="main" elevation="0">
          <v-card-title>
            {{ tr('Settings') }}
          </v-card-title>
          <v-card-subtitle>
            {{ tr('My Settings') }}
          </v-card-subtitle>
          <div class="d-flex flex-row">
            <v-tabs
                v-model="tab"
                color="primary"
                direction="vertical"
            >
              <v-tab v-for="(config, i) in tabs_config" :key="i" :value="config.value" class="main-border">
                {{ config.name }}
              </v-tab>
              <v-btn border class="text-capitalize mt-3" color="primary" variant="outlined"
                     @click="this.$utils.disconnect()">
                <span>
                {{ tr('Log Out') }}
              </span>
              </v-btn>
            </v-tabs>
          </div>
        </v-card>
      </v-col>
      <v-col class="pa-0">
        <v-window v-model="tab">
          <v-window-item value="personal_info">
            <v-card class="main-bg  overflow-cols">
              <v-form @submit.prevent="validate">
                <v-card class="pa-2" color="transparent" elevation="0">
                  <v-row class="mt-10">
                    <v-col></v-col>
                    <v-col class="text-right
                    pr-0 text-white" md="2">
                      <v-tooltip text="Update picture">
                        <template v-slot:activator="{ props }">
                          <label>
                            <v-badge class="avatar_badge" content="🖍" location="bottom right" offset-x="10"
                                     offset-y="20"
                                     style="cursor: pointer">
                              <v-avatar :image="profilePicture"
                                        size="80"></v-avatar>
                              <v-file-input accept="image/png, image/jpeg"
                                            hidden hide-details prepend-icon=""
                                            style="display:none"
                                            type="file"
                                            @change="onFileChange">
                              </v-file-input>
                            </v-badge>
                          </label>
                        </template>
                      </v-tooltip>
                    </v-col>
                    <v-col class="text-left pl-0 text-white">
                      <v-card-text class="text-capitalize pb-0">{{ $store.user_to_edit.userName }}</v-card-text>
                      <v-card-subtitle>{{ $store.user_to_edit.email }}</v-card-subtitle>
                    </v-col>
                    <v-col></v-col>
                  </v-row>
                  <v-row class="pa-0 mt-10">
                    <v-col class="pb-0">
                      <v-text-field v-model="$store.user_to_edit.firstName"
                                    :label="tr('First Name')"
                                    density="comfortable"
                                    variant="solo"
                      ></v-text-field>
                    </v-col>
                    <v-col class="pb-0">
                      <v-text-field v-model="$store.user_to_edit.surname"
                                    :label="tr('SurName')"
                                    density="comfortable"
                                    variant="solo"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col class="pb-0">
                      <v-text-field v-model="$store.user_to_edit.userName"
                                    :label="tr('Username')"
                                    density="comfortable"
                                    variant="solo"
                      ></v-text-field>
                    </v-col>
                    <v-col class="pb-0">

                      <v-text-field v-model="$store.user_to_edit.plainPassword"
                                    :append-inner-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                                    :label="tr('Password')"
                                    :rules="rules.password"
                                    :type="show1 ? 'text' : 'password'"
                                    color="primary"
                                    density="comfortable"
                                    variant="solo"
                                    @change="re_authentication=true"
                                    @click:append-inner="show1 = !show1"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row class="pa-0">
                    <v-col class="pb-0">
                      <v-text-field v-model="$store.user_to_edit.email"
                                    :label="tr('Email')"
                                    :rules="rules.email"
                                    density="comfortable"
                                    variant="solo"
                                    @change="re_authentication=true"
                      ></v-text-field>
                    </v-col>
                    <v-col class="pb-0">
                      <v-text-field v-model="$store.user_to_edit.phoneNumber"
                                    :label="tr('Phone Number')"
                                    :rules="rules.phone"
                                    density="comfortable"
                                    variant="solo"
                      ></v-text-field>
                    </v-col>

                  </v-row>
                  <v-row>

                  </v-row>
                  <v-row class="pa-0">
                    <v-col class="pb-0">
                      <v-select
                          v-model="$store.user_to_edit.city"
                          :items="$store.home_page_info.cities"
                          :label="tr('City')"
                          chips
                          clearable
                          color="primary"
                          item-title="name"
                          return-object
                          variant="solo">
                      </v-select>
                    </v-col>
                    <v-col class="pb-0">
                      <v-text-field v-model="$store.user_to_edit.postalCode"
                                    :label="tr('Post code')"
                                    density="comfortable"
                                    variant="solo"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row class="pa-0">
                    <v-col class="pb-0">
                      <v-select
                          v-model="$store.user_to_edit.groups"
                          :items="$store.home_page_info.groups"
                          :label="tr('Groups')"
                          chips
                          clearable
                          color="primary"
                          item-title="label"
                          item-value="id"
                          multiple
                          return-object
                          variant="solo">
                      </v-select>
                    </v-col>
                    <v-col class="pb-0">
                      <v-select
                          v-model="$store.user_to_edit.clients"
                          :items="$store.home_page_info.clients"
                          :label="tr('Clients')"
                          chips
                          clearable
                          item-title="name"
                          item-value="id"
                          multiple
                          return-object
                          variant="solo"></v-select>
                    </v-col>
                  </v-row>
                  <v-row class="pa-0">
                    <v-col class="pb-0">
                      <v-select
                          v-model="$store.user_to_edit.language"
                          :items="$store.home_page_info.languages"
                          :label="tr('Language')"
                          chips
                          clearable
                          density="comfortable"
                          item-title="name"
                          return-object
                          variant="solo"
                      ></v-select>
                    </v-col>
                    <v-col class="pb-0">
                      <v-text-field v-model="$store.user_to_edit.address"
                                    :label="tr('Address')"
                                    density="comfortable"
                                    variant="solo"
                      ></v-text-field>
                    </v-col>

                  </v-row>
                  <v-row class="pa-0">
                    <v-col class="pb-0">
                      <v-select
                          v-model="$store.user_to_edit.subscriptionType"
                          :items="$store.home_page_info.subscriptionTypes"
                          :label="tr('Subscription Type')"
                          clearable
                          density="comfortable"
                          item-title="name"
                          return-object
                          variant="solo"
                      ></v-select>
                    </v-col>
                    <v-col class="pb-0">
                      <v-select
                          v-model="$store.user_to_edit.projects"
                          :item-title="getProjectName"
                          :items="projects_items"
                          :label="tr('Projects')"
                          autocomplete="off"
                          chips
                          clearable
                          density="comfortable"
                          item-value="id"
                          multiple
                          return-object
                          variant="solo"
                      >
                      </v-select>
                    </v-col>
                  </v-row>
                  <v-row class="pa-0">
                    <v-col class="pb-0">
                      <v-select
                          v-model="$store.user_to_edit.facilities"
                          :items="$store.home_page_info.dataPoints"
                          :label="tr('Facilities')"
                          chips
                          clearable
                          color="primary"
                          item-title="name"
                          item-value="id"
                          multiple
                          return-object
                          variant="solo">
                      </v-select>
                    </v-col>
                    <v-col class="pb-0"></v-col>
                  </v-row>
                  <v-row>
                    <v-col>
                      <v-btn class="general-btn mt-2" type="submit">
                        <v-icon>mdi-check</v-icon>
                      </v-btn>
                    </v-col>
                  </v-row>
                </v-card>
              </v-form>
            </v-card>
          </v-window-item>
          <v-window-item value="account">
            <v-card color="grey" height="500"></v-card>
          </v-window-item>
          <v-window-item value="notification">
            <v-card color="cyan" height="500"></v-card>
          </v-window-item>
          <v-window-item value="teams">
            <v-card color="green" height="500"></v-card>
          </v-window-item>
        </v-window>
      </v-col>
      <v-col class="pa-0" md="2">
        <v-card class="pa-0" color="#e9f5ff" elevation="0" style="height: 100% !important;">
          <!--  <img alt="" src="../../assets/profile-bg.png" style="width: 100%"> -->
        </v-card>
      </v-col>
    </v-row>
    <v-dialog v-model="confirmation_dialog" width="500">
      <v-card elevation="0" title="Confirmation">
        <v-card-text>
          Email/Password was Updated, you need to login again.
          <p class="text-red">Logout?</p>
        </v-card-text>
        <v-card-actions class="mt-10">
          <v-btn color="error" variant="tonal" @click="confirmation_dialog=false">Cancel</v-btn>
          <v-spacer></v-spacer>
          <v-btn color="primary" variant="tonal" @click="$store.saveUser($store.user_to_edit, true)">Confirm
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  name: 'EditProfile',
  data: () => ({
    profilePicture: '',
    re_authentication: false,
    confirmation_dialog: false,
    responceData: null,
    show1: false,
    tab: 'option-1',
    tabs_config: {
      0: {name: 'Personal Information', value: 'personal_info'},
      1: {name: 'Account', value: 'account'},
      2: {name: 'Notification', value: 'notification'},
      3: {name: 'Teams', value: 'teams'},
    },
  }),

  computed: {
    projects_items() {
      return this.$store.home_page_info?.projects
    },
    rules() {
      const rules_validation = {
        phone: [
          value => {
            if (!/^\d{6,}$/.test(value)) {
              return 'Phone must be a number with 6 digits at least';
            }
          }
        ]
        ,
        password: [
          value => {
            if (/^.{0,5}$/.test(value)) {
              return 'Enter 6 caracters at least';
            }
          }
        ],
        email: [
          value => {
            if (!value) {
              return 'Please enter an email address';
            } else if (!/^\S+@\S+\.\S+$/.test(value)) {
              return 'Please enter a valid email address';
            } else {
              return true;
            }
          }
        ],
        profilePic: [
          value => {
            if (value && value[0] && typeof value[0] !== 'undefined') {
              const file = value[0];
              if (this.$utils.isValidFileType(file.type)) {
                return true
              } else {
                this.$utils.showSnackbar("Choose a valid profile picture", 'warning')
                this.initProfilePic()
              }
            }
          },
        ]
      };
      return rules_validation;

    },
  },
  created() {
    this.initProfilePic();
  },
  methods: {
    getProjectName(param) {
      let translated = this.$utils.getLabel(param.name);

      return translated ? translated : '';
    }
    ,
  initProfilePic() {
    this.$store.downloadFile(this.$store.user_to_edit.profilePic, "https://avatars0.githubusercontent.com/u/9064066?v=4&s=460").then((response) => {
        this.profilePicture = response
      });
    },
    async validate(event) {
      const result = await event;
      if (result.valid) {
        if (this.re_authentication && this.$store.user_to_edit?.id === this.$store.logged_user.id) {
          this.confirmation_dialog = true
        } else {
          this.$store.saveUser(this.$store.user_to_edit, false)
        }
      }
    },
    onFileChange(e) {
      const file = e.target.files[0];
      if (this.$utils.isValidFileType(file.type)) {
        this.$store.uploadFile(file, 'profile').then(async (response) => {
          this.$store.user_to_edit.profilePic = {"id": "/api/media/" + response.mediaId};
          this.profilePicture = await this.$store.downloadFile(response.mediaId);
        });
      } else {
        this.$utils.showSnackbar("Choose a valid profile picture", 'warning')
      }
    },

  }
}
</script>
<style>
.v-slide-group__content > [aria-selected="true"] {
  background: var(--main-color) !important;
  color: white !important;
}

.avatar_badge .v-input {
  content-visibility: hidden;
}
</style>