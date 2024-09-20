<template>
  <v-dialog width="700">
    <v-form @submit.prevent="validate">
      <v-card class="pa-2 relative" color="primary">
        <v-card-title>{{ tr((current_project.id ? 'Edit' : 'New') + ' Project') }}</v-card-title>
        <v-card-text>

          <translatable v-model="current_project.name"
                        :placeholder="tr('Name')"
                        :rows-number="1"
                        density="compact"
                        required
                        variant="solo"
          />

          <!--     <text-editor v-model="current_project.description" class="bg-white"></text-editor>-->
        </v-card-text>
          <v-card-text>
            <translatable v-model="current_project.description"
                          :placeholder="tr('Description')"
                          :rows-number="4"
                          density="compact"
                          required
                          variant="solo"
            />

            <v-row class="text-center">
              <v-col>
                <v-btn class="general-btn" type="submit">
                  <v-icon>mdi-check</v-icon>
                </v-btn>
              </v-col>
            </v-row>
          </v-card-text>
      </v-card>
    </v-form>
  </v-dialog>
</template>

<script>
import TextEditor from "@/web/components/TextEditor.vue";
import Translatable from "@/web/components/Translatable.vue";

export default {
  name: 'EditProject',
  components: {Translatable, TextEditor},
  data: () => ({
    selectedPicUrl: '',
    selectedBackgroundPicUrl: '',
    current_project: null
  }),
  methods: {
    async validate(event) {
      const result = await event;
      if (result.valid) {
        this.$store.saveProject(this.current_project)
        this.$emit('update:newProjectDialog', false);
      }
    },

    init(project) {
      if (project && Object.keys(project).length) {
        this.current_project = project;
      } else {
        const currentLanguage = this.$utils.currentLanguage();
        this.current_project = {
          name: JSON.stringify({[currentLanguage.code]: ""}),
          description: JSON.stringify({[currentLanguage.code]: ""}),
        }
      }
    }
  }
}
</script>

<style>
.v-slide-group__content > [aria-selected="true"] {
  background: var(--main-color) !important;
  color: white !important;
}
</style>