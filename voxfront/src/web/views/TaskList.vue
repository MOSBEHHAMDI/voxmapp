<template>
  <div>
    <v-row style="height: 100vh">
      <v-col class="text-left" cols="2">
        <span>
          <span class="main font-weight-bold font-size-medium">{{ tr('Task') }}</span>
          <v-btn class="float-end" color="primary" elevation="0" variant="elevated" @click="$task_store.createNewTask">{{
              tr('Add')
            }} <v-icon class="ml-1" size="25">mdi-plus-circle</v-icon></v-btn>
        </span>
        <div class="border-8 border-bottom-main main-border mt-15">
          <p class="border-bottom-main pa-3">Project</p>
          <p class="pa-3">Data Point</p>
        </div>
        <div>
          <img class="mt-15 ml-n8" src="@/assets/task_illustration.png" width="250">
        </div>
      </v-col>
      <v-col class="secondary-bg">
        <v-row>
          <v-row class="mt-2">
            <v-col>
              <List v-if="tasks" :config="config" :dynamic_data="tasks"
                    @triggerEvent="triggerEvent"
              ></List>
            </v-col>
          </v-row>
        </v-row>
      </v-col>
    </v-row>

  </div>
</template>
<script>
import List from "@/web/components/DataList";

export default {
  name: 'TaskList',
  components: {List},
  created() {
    this.$task_store.getTasksInfo()
    this.$task_store.getTasksList()
    this.$task_store.currentTask = {};
    // to avoid setting last selected (or updated) datapoint to the future new task
    this.$dataPoint_store.currentDataPoint = {};
  },
  computed: {
    config() {
      return {
        title: 'Tasks',
        header: [
          {
            name: 'name',
            label: 'Name',
            extra_actions: [
              {name: 'validate', event: 'validate'},
              {name: 'unlock', event: 'unlock'},

            ],
            visible: true,
            title: 'name',
          },
          {
            name: 'dataPoints',
            visible: true,
            label: 'Entity',
            property: 'name',
          },
          {
            name: 'users',
            label: 'User associate',
            visible: true,
            property: 'tooltipUserName',
            filterProperty : 'userName'
          },
          {
            name: 'period',
            visible: true,
            label: 'Start/end date',
            property: 'dataProperty',
            filterProperty: 'filterProperty'
          },
          {
            name: 'customStatus',
            visible: true,
            label: 'Status',
            property: 'dataProperty',
            filterProperty: 'filterProperty'
          },
          {
            name: 'actions',
            actions: [{
              name: 'Edit',
              icon: 'pencil-outline',
              event: 'editTask'
            }, {
              name: 'Delete',
              icon: 'close',
              event: 'removeTask'
            }],
            visible: false,
            label: 'Actions',
          }
        ],
        save_row: 'saveTask'
      }
    },
    tasks: {
      get() {
        let tasks = JSON.parse(JSON.stringify(this.$task_store.tasksList));
        let taskList = tasks.map(task => {
          let period = [this.$utils.formatDate(task.startDate,'-'), this.$utils.formatDate(task.endDate,'-')].filter(v => v);
          const periodFilterProperty = period.join(' - ');
          const periodDataProperty = period.join('<br>');
          const statusDataProperty = `
            <v-row class="main">
              <v-col class="text-left">0 %</v-col>
              <v-col class="text-center">${task.status} %</v-col>
              <v-col class="text-right">100 %</v-col>
            </v-row>
        <div>
          <v-progress-linear
              model-value="${task.status}"
              color="primary"
              height="10"
              class="mr-4"
              rounded
          ></v-progress-linear>
        </div>
            `;
          task.users = task.users.map(user => {
            return {
              id: user.id,
              tooltipUserName: `<span class="text-center">${user.userName}
                <v-tooltip
                    activator="parent"
                    location="start"
                >${user.email}</v-tooltip>
                  </span>`,
              userName:user.userName,
            }
          });
          const statusFilterProperty = task.status + ' %';
          return {
            ...task,
            customStatus: {filterProperty: statusFilterProperty, dataProperty: statusDataProperty},
            period: {filterProperty: periodFilterProperty, dataProperty: periodDataProperty},
          };
        });
        return taskList
      },
      set(list) {
        this.$task_store.tasksList = list
      }
    },
  },
  methods: {
    triggerEvent(params) {
      if (this[params.event]) {
        this[params.event](params.data)
      } else {
        console.log(params.event, ' method does not exist in task list.')
      }
    },
    saveTask(task) {
      console.log(task)
    },
    editTask(task) {
      this.$task_store.currentTask = task;
      this.$router.push('/task/edit');
    },
    removeTask(task) {
      this.handleConfirm(task.id);
    },
    handleConfirm(id) {
      this.$utils.confirmationDialog({
        content: 'Are you sure, you want to delete this task ?',
      }, () => {
        this.$task_store.deleteTask(id);
      })
    },

    redirectToDashboardTask(task) {
      console.log(task)
    },
    viewTask(task) {
      console.log(task)
    },
    viewTaskData(task) {
      console.log(task)
    },
  },
}
</script>

<style>
.new_task_btn {
  transition: background-color 0.3s ease;
  animation: scaleIn 0.5s ease-out 1s;
  color: white;
}

.new_task_btn:hover {
  background-color: var(--secondary-color) !important;
  color: var(--main-color) !important;
}

@keyframes scaleIn {
  from {
    transform: scale(0.8);
  }
  to {
    transform: scale(1);
  }
}
</style>
