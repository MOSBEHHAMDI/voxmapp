<template>
    <v-form @submit.prevent="saveDatapoint" class="pale-bg">
      <v-card class="pl-5 pr-5 mt-2 text-left pale-bg" elevation="0">
        <v-card-title class="pl-0 main">{{ tr('General informations') }}</v-card-title>
        <v-card-text class="bg-white rounded pa-2">
          <v-text-field v-model="dataPoint.name"
                        :placeholder="tr('Name')"
                        density="compact"
                        :label="tr('Name')"
                         :rules="[global_rules.required]"
                        class="mr-3 ml-3 mt-2"
                        variant="outlined">
          </v-text-field>
          <v-autocomplete
              v-model="dataPoint.dataPointType"
              :items="$dataPoint_store.dataPoint_page_info.dataPointTypes"
              :placeholder="tr('Category')"
              class="mr-3 ml-3 mt-2"
              closable-chips
              item-title="name"
              return-object
              density="compact"
              :label="tr('Category')"
              variant="outlined"
          ></v-autocomplete>
          <v-text-field v-model="dataPoint.contactNumber"
                        :placeholder="tr('Datapoint contact number')"
                        :rules="rules.contactNumber"
                        class="mr-3 ml-3 mt-2"
                        density="compact"
                        :label="tr('Datapoint contact number')"
                        variant="outlined">
          </v-text-field>
          <v-autocomplete
              v-model="dataPoint.sourceType"
              :label="tr('Source type')"
              :items="$dataPoint_store.dataPoint_page_info.sourceTypes"
              :placeholder="tr('Source type')"
               :rules="[global_rules.required]"
              class="mr-3 ml-3 mt-2"
              closable-chips
              item-title="name"
              density="compact"
              return-object
              variant="outlined"
          ></v-autocomplete>
          <v-autocomplete
              v-model="dataPoint.accessType"
              :items="$dataPoint_store.dataPoint_page_info.accessTypes"
              :placeholder="tr('Access type')"
               :rules="[global_rules.required]"
              class="mr-3 ml-3 mt-2"
              :label="tr('Access type')"
              closable-chips
              item-title="name"
              return-object
              density="compact"
              variant="outlined"
          ></v-autocomplete>
          <v-autocomplete
              v-model="dataPoint.status"
              :items="$dataPoint_store.dataPoint_page_info.statuses"
              :placeholder="tr('Status')"
               :rules="[global_rules.required]"
              class="mr-3 ml-3 mt-2"
              :label="tr('Status')"
              closable-chips
              item-title="name"
              return-object
              density="compact"
              variant="outlined"
          ></v-autocomplete>
          <v-autocomplete
              v-model="dataPoint.project"
              :items="projects"
              :placeholder="tr('Project')"
              :label="tr('Project')"
              class="mr-3 ml-3 mt-2 border-8"
              closable-chips
              item-title="name"
              return-object
              variant="outlined"
              density="compact"
          ></v-autocomplete>
          <v-checkbox-btn
              v-model="dataPoint.public"
              class="ml-4 mb-3"
              density="compact"
              :label="dataPoint.public ? tr('Public data point: Yes') : tr('Public data point: No') "
              type="checkbox" variant="outlined"
          ></v-checkbox-btn>
        </v-card-text>
      </v-card>
      <v-card class="pl-5 pr-5 mt-2 text-left pale-bg" elevation="0" color="transparent">
        <v-card-title class="pl-0 main">{{ tr('Address') }}</v-card-title>
        <v-card-text class="bg-white rounded pa-2">
          <v-text-field v-model="dataPoint.address"
                        density="compact"
                        :label="tr('Datapoint address')"
                        :placeholder="tr('Datapoint address')"
                        class="mr-3 ml-3 border-8 mt-4"
                        variant="outlined">
          </v-text-field>
          <span class="ml-3">{{ tr('District') }}</span>
          <v-autocomplete
              v-model="district"
              :items="districts"
              density="compact"
              :label=" tr('District')"
              :placeholder="tr('District')"
              class="mr-3 ml-3 mt-2 border-8"
              closable-chips
              item-title="name"
              return-object
              variant="outlined"
              @update:model-value="dataPoint.city = null"
          ></v-autocomplete>
          <v-autocomplete
              v-model="dataPoint.city"
              :items="cities"
              :placeholder="tr('City')"
              density="compact"
               :rules="[global_rules.required]"
              :label="tr('City')"
              class="mr-3 ml-3 mt-2 border-8"
              closable-chips
              item-title="name"
              return-object
              variant="outlined"
          ></v-autocomplete>
          <v-text-field
              v-model="dataPoint.latLong"
              :label="tr('Coordinates')"
              :placeholder="tr('Coordinates')"
              append-inner-icon="mdi-crosshairs-gps"
              density="compact"
              class="mr-3 ml-3 mt-2 border-8"
              variant="outlined" @click:append-inner="getCurrentGps()"
          >
          </v-text-field>
        </v-card-text>
        <v-card-actions class="text-center pl-0 pr-0">
          <v-btn elevation="0" type="submit" class="flex-grow-1" @click="$router.go(-1)" variant="outlined" color="orange-darken-1">
            Prev
          </v-btn>
          <v-btn elevation="0" type="submit" class="flex-grow-1" variant="flat" color="orange-darken-1">
            Save
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-form>
</template>

<script>

export default {
  name: "DataPointRegistry",
  created() {
    this.$dataPoint_store.getDatapointsInfo();
    this.district = this.$dataPoint_store.dataPoint_page_info.districts?.find(d => {
      return d.cities.map(c => c.id).includes(this.dataPoint.city.id)
    });
  },
  data: () => ({
    district: null
  }),
  computed: {
    rules() {
      const rules_validation = {
        contactNumber: [
          value => {
            if (!/^\d+$/.test(value)) {
              return 'Contact must be a valid phone number';
            }
          }
        ],

      };
      return rules_validation
    },
    districts() {
      return this.$dataPoint_store.dataPoint_page_info.districts || []
    },
    cities() {
      return this.district ? this.district.cities : this.$dataPoint_store.dataPoint_page_info.cities
    },
    projects() {
      return this.$dataPoint_store.dataPoint_page_info.projects.map((project) => {
        // Create a new object with the modified 'name' property
        return {
          ...project,
          name: this.$utils.getLabel(project.name)
        };
      });
    }
    ,
    dataPoint() {
      return this.$dataPoint_store.currentDataPoint
    },
  },
  methods: {
    getCurrentGps() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            position => {
              this.dataPoint.latLong = position.coords.latitude + ',' + position.coords.longitude
            },
            error => {
              this.error = error.message;
            }
        );
      } else {
        this.error = "Geolocation is not supported by this browser.";
      }
    },
    async saveDatapoint(event) {
      const result = await event;
      if (result && result.valid) {
        this.$dataPoint_store.save();
        this.$router.go(-1);
      }
    }
  }
}
</script>