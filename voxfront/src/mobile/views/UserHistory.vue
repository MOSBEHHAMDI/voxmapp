<template>
  <div>
    <v-row style="background: #faecd9; height: 100%">
    <v-col v-if="noHistory">
      <img src="../../assets/history.png" width="100%" height="100%"/>
    </v-col>
    <v-col v-else>
      <v-card class="main pale-bg">
        <v-card-text class=" text-left">
          <v-list class="pale-bg">
            <v-list-item v-for="(action,index) in  $store.groupedUserActions">
              <v-card class="main-border rounded-xl text-left" elevation="0" @click="goToHistory(action,index)">
                <template #title>
                  <span class="ml-2 font-weight-bold"> {{
                      action[0]['history'].customDate
                    }}</span>
                </template>
                <template #subtitle> <span
                    class="ml-3">{{
                    'Last update ' + action[0]['history'].customDate + ' - ' + $utils.extractTime(action[0]['history'].date)
                  }}</span>
                </template>
                <template #append>
             <badge :number="action.length" />
                  <v-icon class="ml-2 main font-weight-bold">
                    mdi-arrow-right
                  </v-icon>
                </template>
              </v-card>

            </v-list-item>
          </v-list>
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>
  </div>
</template>
<script>
import Badge from "@/mobile/components/badge.vue";

export default {
  name: "UserHistory",
  components: {Badge},
  data() {
    return {
      lastDate: null,
    }
  },
  created() {
    this.buildUserHistory();
    this.sortUserHistory();
    this.setAppBarSubTitle();
  },
  computed: {
    noHistory() {
      const history = this.$store.logged_user.facilities.flatMap(f => f.history)
      return history.length === 0;
    },
  },
  methods: {
    goToHistory(actions, lastDate) {
      this.$store.currentActions = {list: actions, lastDate}
      this.$router.push('UserHistoryDetails');
    },

    setAppBarSubTitle() {
      const formattedDate = this.$utils.formatDate(this.lastDate,'-');
      const extractedTime = this.$utils.extractTime(this.lastDate);
      const title = formattedDate === 'Today' ? extractedTime : formattedDate;
      this.$store.MobileDynamicSubTitle = 'Last update ' + title
    },
    buildUserHistory() {
      this.$store.groupedUserActions = {};
      this.$store.logged_user.facilities.forEach(facility => {
        facility.history.forEach(history => {
          const date = this.$utils.formatDate(history.date,'-');
          const facilityWithoutHistory = {...facility};
          delete facilityWithoutHistory.history;
          history = {...history, customDate: this.$utils.formatDate(history.date,'/'), facility: facilityWithoutHistory};
          this.$store.groupedUserActions[date] = this.$store.groupedUserActions[date] || [];
          this.$store.groupedUserActions[date].push({history});
        });
      });
    },

    sortUserHistory() {
      const sortedDates = Object.keys(this.$store.groupedUserActions).sort((a, b) => {
        const convertToDateValue = (dateStr) => {
          if (dateStr === 'Today') return new Date();
          if (dateStr === 'Yesterday') {
            const yesterday = new Date();
            yesterday.setDate(yesterday.getDate() - 1);
            return yesterday;
          }
          return new Date(dateStr);
        };

        const dateA = convertToDateValue(a);
        const dateB = convertToDateValue(b);

        return dateB - dateA;
      });

      const lastDate = sortedDates[0];
      this.lastDate = this.$store.groupedUserActions[lastDate] ? this.$store.groupedUserActions[lastDate][0].history.date : null
      this.$store.groupedUserActions = sortedDates.reduce((obj, key) => {
        obj[key] = this.$store.groupedUserActions[key];
        return obj;
      }, {});
    }
  }

}
</script>

<style scoped>

</style>