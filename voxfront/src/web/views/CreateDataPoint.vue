<template>
  <div>
    <v-row>
      <v-col class="text-left">
        <v-form class="quick_edit" @submit.prevent="saveDatapoint">
          <v-card class="main-bg pa-0 text-capitalize text-white pa-5" color="transparent" elevation="0">
            <v-card-title class="text-center pb-8">
                 <span v-if="dataPoint.id">
                  {{ tr('Edit DataPoint') }}
              </span>
              <span v-else>
                  {{ tr('ADD New DataPoint') }}
              </span>
            </v-card-title>
            <v-card-text class="pl-0 pr-0 pt-0 pb-5">
              <v-row class="pa-0">
                <v-col class="pa-0">
                  <span>{{ tr('Name of data point') }}</span>
                  <v-text-field v-model="dataPoint.name"
                                :placeholder="tr('Datapoint Name')"
                                 :rules="[global_rules.required]"
                                class="special_input"
                                variant="solo">
                  </v-text-field>
                </v-col>
                <v-col class="pa-0">
                  <span>{{ tr('Address of data point') }}</span>
                  <v-text-field v-model="dataPoint.address"
                                :placeholder="tr('Datapoint Address')"
                                class="special_input"
                                variant="solo">
                  </v-text-field>
                </v-col>
              </v-row>
              <v-row class="pa-0">
                <v-col class="pa-0">
                  <span>{{ tr('Contact number of data point') }}</span>
                  <v-text-field v-model="dataPoint.contactNumber"
                                :placeholder="tr('Datapoint contact number')"
                                :rules="rules.contactNumber"
                                class="special_input"
                                variant="solo">
                  </v-text-field>
                </v-col>
                <v-col class="pa-0">
                  <span>{{ tr('City of data point') }}</span>
                  <v-autocomplete
                      v-model="dataPoint.city"
                      :items="infos('cities')"
                      :placeholder="tr('City')"
                       :rules="[global_rules.required]"
                      class="special_autocomplete"
                      closable-chips
                      item-title="name"
                      return-object
                      variant="solo"
                  ></v-autocomplete>
                </v-col>
              </v-row>
            </v-card-text>
            <v-row class="pa-0">
              <v-col class="pa-0">
                <span>{{ tr('Source type of data point') }}</span>
                <v-autocomplete
                    v-model="dataPoint.sourceType"
                    :items="infos('sourceTypes')"
                    :placeholder="tr('Source type')"
                     :rules="[global_rules.required]"
                    class="special_autocomplete"
                    closable-chips
                    item-title="name"
                    return-object
                    variant="solo"
                ></v-autocomplete>
              </v-col>
              <v-col class="pa-0">
                <span>{{ tr('Type of data point') }}</span>
                <v-autocomplete
                    v-model="dataPoint.dataPointType"
                    :items="infos('dataPointTypes')"
                    :placeholder="tr('Data point type')"
                     :rules="[global_rules.required]"
                    class="special_autocomplete"
                    closable-chips
                    item-title="name"
                    return-object
                    variant="solo"
                ></v-autocomplete>
              </v-col>
            </v-row>
            <v-row class="pa-0">
              <v-col class="pa-0">
                <span>{{ tr('Access type of data point') }}</span>
                <v-autocomplete
                    v-model="dataPoint.accessType"
                    :items="infos('accessTypes')"
                    :placeholder="tr('Access type')"
                    :rules="[global_rules.required]"
                    class="special_autocomplete"
                    closable-chips
                    item-title="name"
                    return-object
                    variant="solo"
                ></v-autocomplete>
              </v-col>
              <v-col class="pa-0">
                <span>{{ tr('Status') }}</span>
                <v-autocomplete
                    v-model="dataPoint.status"
                    :items="infos('statuses')"
                    :placeholder="tr('Status')"
                     :rules="[global_rules.required]"
                    class="special_autocomplete"
                    closable-chips
                    item-title="name"
                    return-object
                    variant="solo"
                ></v-autocomplete>
              </v-col>
            </v-row>
            <v-row class="pa-0 mb-4">
              <v-col class="pa-0">
                <span>{{ tr('Project') }}</span>
                <v-autocomplete
                    v-model="dataPoint.project"
                    :items="projects_info"
                    :placeholder="tr('Project')"
                     :rules="[global_rules.required]"
                    class="special_autocomplete"
                    closable-chips
                    item-title="name"
                    return-object
                    variant="solo"
                ></v-autocomplete>
              </v-col>
              <v-col class="pa-0" cols="4">
                <span>{{ tr("Lattitude & Longitude seperated by a comma ','") }}</span>
                <v-text-field
                    v-model="dataPoint.latLong"
                    :placeholder="tr('latLong')"
                    append-inner-icon="mdi-crosshairs-gps"
                    class="special_input"
                    variant="solo"
                    @click:append-inner="getCurrentGps()"
                >
                </v-text-field>
              </v-col>
              <v-col class="pa-0" cols="2">
                <span>{{ tr('Public Data Point') }}
                 <v-checkbox-btn
                     v-model="dataPoint.public"
                     label="Is public"
                     type="checkbox" variant="tonal"
                 ></v-checkbox-btn>
                </span>
              </v-col>
            </v-row>
            <v-row>
              <v-col class="bg-white">
                <DynamicList v-slot="slotProps" v-model:list="uploads" :item-name="'Picture'" :item-value="inputFile"
                             :sortable="false">
                  <v-row>
                    <v-col>
                      <v-file-input
                          :clearable="false"
                          :label="uploads[slotProps.index]['label']"
                          :rules="rules.uploads"
                          accept="image/png, image/jpeg"
                          variant="solo"
                          @change="onFileChange($event,slotProps.index )">
                      </v-file-input>
                    </v-col>
                    <v-col>
                      <v-card
                          v-if="uploads[slotProps.index]['src']"
                          :image="uploads[slotProps.index]['src']"
                          class="mx-auto"
                          height="80"
                          max-width="80"
                          rounded
                          theme="light"
                      ></v-card>
                    </v-col>
                  </v-row>
                </DynamicList>
              </v-col>
            </v-row>
            <v-row>
              <v-col class="text-center">
                <v-btn class="general-btn" type="submit">
                  <v-icon>mdi-check</v-icon>
                </v-btn>
              </v-col>
            </v-row>
          </v-card>
        </v-form>
      </v-col>
    </v-row>
  </div>
</template>
<script>
import DynamicList from "@/web/components/DynamicList.vue";

export default {
  name: 'CreateDataPoint',
  components: {DynamicList},
  data: () => ({
    submitted: false,
    uploads: [],
    windowSize: {
      x: 0,
      y: 0,
    },
  }),
  computed: {
    dataPoint: {
      get() {
        return this.$dataPoint_store.currentDataPoint;
      },
      set(newValue) {
        this.$dataPoint_store.currentDataPoint = newValue;
      }
    },
    rules() {
      const rules_validation = {
        contactNumber: [
          value => {
            if (!/^\d+$/.test(value)) {
              return 'Contact must be a valid phone number';
            }
          }
        ],
        uploads: [
          value => {
            if (value && value[0] && typeof value[0] !== 'undefined') {
              const file = value[0];
              if (this.$utils.isValidFileType(file.type)) {
                return true
              } else {
                this.$utils.showSnackbar("Choose a valid image", 'warning')
                return false
              }
            }
          },
        ],
      };
      return rules_validation
    },
    inputFile() {
      const newFileInput = {
        label: "Upload the new file " + (this.uploads.length + 1),
        id: null,
        src: null,

      };
      return newFileInput
    },
    projects_info() {
      return this.$dataPoint_store.dataPoint_page_info?.projects?.map(project => {
        return {id: project.id, name: this.$utils.getLabel(project.name)};
      });
    },
  },
  async created() {
    this.$dataPoint_store.oldDataPoint = {...this.$dataPoint_store.currentDataPoint};
    if (this.dataPoint?.uploads?.length) {
      for (const upload of this.dataPoint.uploads) {
        try {
          const fileData = await this.$store.downloadFile(upload.id);
          const media = {id: upload.id, src: fileData, label: 'Upload file'};
          this.uploads.push(media);
        } catch (error) {
          console.error('Error downloading file:', error);
        }
      }
    } else {
      this.uploads = [{
        id: null, src: null, label: 'Upload file',
      }];
    }
  },
  methods: {
    infos(info) {
      const items = this.$dataPoint_store.dataPoint_page_info[info];
      return items ? items : [];
    },
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
    }
    ,
    onResize() {
      this.windowSize = {x: window.innerWidth, y: window.innerHeight}
    }
    ,
    async onFileChange(e, index) {
      const file = e.target.files[0];
      if (this.$utils.isValidFileType(file.type)) {
        try {
          const response = await this.$store.uploadFile(file, 'uploads');
          const fileData = await this.$store.downloadFile(response.mediaId);
          const label = "Upload the new file " + (this.uploads.length + 1);
          const media = {id: "/api/media/" + response.mediaId, src: fileData, label: label};
          this.uploads[index] = media;
        } catch (error) {
          console.error("Error occurred:", error);
        }
      }
    }
    ,
    async saveDatapoint(event) {
      const result = await event;
      if (result && result.valid) {
        this.submitted = true;
        this.dataPoint.createdBy = {id: this.$store.logged_user.id}
        this.dataPoint.uploads = this.uploads
            .filter(upload => upload.id !== null) // Filter out uploads with id === null
            .map(upload => upload.id);
        this.$dataPoint_store.save();
        this.$router.go(-1);
      }
    }
  },
  beforeRouteLeave() {
    if (!this.submitted) {
      this.dataPoint = {}
    }
  },
}
</script>
