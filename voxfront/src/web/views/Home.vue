<template>
  <div>
    <v-card class="border-8 pa-0 main-bg" elevation="0" height="230px">
      <v-row>
        <v-col>
          <v-card-text class="pt-9 text-left text-white">
         <span>
           <span class="text-h4 font-weight-bold text-capitalize">Hi, {{ $store.logged_user.userName }}</span>
           <br/>
           {{ tr('Welcome to Voxmapp management') }}
         </span>
          </v-card-text>
          <v-card-actions class="mt-5">
            <v-btn class="bg-white home-btn" elevation="0">
              <v-icon size="28">mdi-view-dashboard-outline</v-icon>
              <span class="text-capitalize">{{ tr('General board ') }}<br/> {{ tr('configuration') }}</span>
            </v-btn>
          </v-card-actions>
        </v-col>
        <v-col>
          <img src="../../assets/home_pic.png"/>
        </v-col>
      </v-row>
    </v-card>
    <v-row>
      <v-col class="secondary-bg main-border-right pa-5 mt-3 ml-3 text-left">
        <span class="text-h6 main font-weight-bold">{{ tr('Profile') }}</span>
        <div class="mt-3">
          <span class="main">{{ tr('User list') }}</span>
          <v-row class="mt-1" style="padding: 5px 12px">
            <v-col md="5">
              <v-card color="transparent" elevation="0">
                <v-btn block class="bg-transparent border-dashed mb-2" elevation="0" @click="addUser()">
                  <v-icon class="main" size="25">mdi-plus-circle</v-icon>
                  <span class="main">Add new user</span>
                </v-btn>
                <v-card class="bg-white main-border border-bottom-main" elevation="0">
                  <div v-for="(user, index) in users" :key="index" class="users-list">
                    <div
                        :class="index < users.length -1 ? 'border-bottom-main' : '', $store.selected_user.id === user.id ? ' active ' : ''"
                        class="user-home user_info text-capitalize"
                        @click="$store.selected_user = user"
                        @mouseleave="hover = false, current = 0"
                        @mouseover="hover = true, current = index"
                    >
                      <v-avatar color="#E4EFF4" size="25">U</v-avatar>
                      {{ user.userName }}
                      <span v-if="hover === true && current === index" class="float-right">
                         <v-icon>mdi-comment-outline</v-icon>
                         <v-icon @click="editUser(user)">mdi-arrow-right-circle</v-icon>
                        <v-icon v-if="user.id !== $store.logged_user.id" @click="handleUserConfirm(user.id)">mdi-close-circle</v-icon>
                      </span>
                    </div>
                  </div>
                </v-card>
              </v-card>
            </v-col>
            <v-col>
              <v-card class="main-bg text-white" elevation="0">
                <v-card-title class="text-center text-white text-capitalize">{{ $store.selected_user.userName }}</v-card-title>
                <v-card-text>
                  <v-card v-for="(item, index) in current_user" :key="index" color="transparent"
                          elevation="0">
                    <span class="text-capitalize" style="font-weight: bold" v-html="index"></span>: <span
                      v-html="item"/>
                  </v-card>
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn class="text-capitalize main bg-white" color="white" elevation="0"
                         @click="editUser($store.selected_user)"><span class="main">{{ tr('Complete Profile') }}</span>
                  </v-btn>
                  <v-spacer></v-spacer>
                </v-card-actions>
              </v-card>
            </v-col>
          </v-row>
        </div>
      </v-col>
      <v-col class="bg-white main-border-left pa-5 mt-3 ml-1 text-left">
        <span class="text-h6 main font-weight-bold">{{ tr('Projects') }}</span>
        <!-- <v-alert closable title="Alert title" ></v-alert>-->
        <div class="mt-3">
          <span class="main">{{ tr('Projects list') }} </span>
          <v-row class="mt-1">

            <v-col>
              <v-card color="transparent" elevation="0">
                <v-btn block class="bg-transparent border-dashed mb-2" elevation="0"
                       @click="showProjectPopup()">
                  <v-icon class="main" size="25">mdi-plus-circle</v-icon>
                  <span class="main"> {{ tr('Add new project') }}</span>
                </v-btn>
              </v-card>
              <v-card color="transparent" elevation="0">
                <v-card class="bg-white main-border border-bottom-main" elevation="0">
                  <div v-for="(project, index) in projects" :key="index">
                    <div :class="index < projects.length-1 ? 'border-bottom-main' : ''"
                         class="user-home"
                         @click="selected_project=project">
                      <v-avatar color="#E4EFF4" size="25">P</v-avatar>
                      {{
                        'Project ' + (index + 1) + ': ' + $utils.getLabel(project.name)
                      }}
                      <span class="float-right">
                       <v-icon @click="showProjectPopup(project)">mdi-arrow-right-circle</v-icon>  <v-icon
                          @click="handleProjectConfirm(project.id)">mdi-close-circle</v-icon>
                      </span>
                    </div>
                  </div>
                </v-card>
              </v-card>
            </v-col>
          </v-row>
        </div>
      </v-col>
    </v-row>
    <EditProject ref="edit-project" v-model="new_project_dialog" @update:newProjectDialog="hideNPDialogue"/>
  </div>
</template>

<script>
import EditProject from "@/web/components/EditProject.vue";

export default {
  name: "Home-page",
  components: {
    EditProject
  },
  created() {
    this.$store.getHomePageInfo(true);
  },
  data: () => ({
    new_project_dialog: false,
    hover: false,
    current: 0,
    new_project: {},
    image: undefined,
    imageUrl: ""
  }),
  computed: {
    users() {
      return this.$store.home_page_info.users ?? []
    },
    projects() {
      return this.$store.home_page_info.projects ?? []
    },
    current_user() {
      const config = [
        {name: 'projects', property: 'name'},
        {name: 'language', property: 'name'},
        {name: 'subscriptionType', property: 'name'},
        {name: 'clients', property: 'name'},
        {name: 'projects', property: 'name'},
        {name: 'facilities', property: 'name'},
        {name: 'groups', property: 'label'},
        {name: 'userName', label: 'User Name'},
        {name: 'tasks', property: 'name'}
      ];
      /*
          const tr_config = [
          {name: 'tasks', property: 'name'},
          {name: 'projects', property: 'name'}
           this.$store.selected_user = this.$utils.getChildrenLabels(this.users[0], tr_config );
        ];*/
      if (!Object.keys(this.$store.selected_user).length && this.users.length) {
        this.$store.selected_user = this.users[0];
      }
      if (this.$store.selected_user.tasks) {
        this.$store.selected_user.tasks = this.$utils.getLabel(this.$store.selected_user.tasks, 'name');
      }
      if (this.$store.selected_user.projects) {
        this.$store.selected_user.projects = this.$utils.getLabel(this.$store.selected_user.projects, 'name');
      }
      return this.$utils.getKeysValues(this.$store.selected_user, config)
    }
  },
  methods: {
    hideNPDialogue(newValue) {
      this.new_project_dialog = newValue;
    },
    showProjectPopup(project) {
      this.$refs["edit-project"].init(project);
      this.new_project_dialog = true
    },
    handleProjectConfirm(id) {
      this.$utils.confirmationDialog({
        content: 'Are you sure, you want to delete this project  ?',
      }, () => {
        this.$store.deleteProject(id);
      })
    },
    handleUserConfirm(id) {
      this.$utils.confirmationDialog({
        content: 'Are you sure, you want to delete this user  ?',
      }, () => {
        this.$store.deleteUser(id);
      })
    },
    addUser() {
      this.$store.initializeNewUser()
      this.$router.push('/profile/add')
    },
    editUser(user) {
      this.$store.user_to_edit = Object.assign({}, user);
      this.$router.push({path: '/profile/edit/'});
    },
    createImage(file) {
      const reader = new FileReader();
      reader.onload = e => {
        this.imageUrl = e.target.result;
      };
      reader.readAsDataURL(file);
    },
    onFileChange(file) {
      if (!file) {
        return;
      }
      this.createImage(file);
    }
  }
}
</script>

<style>

</style>
