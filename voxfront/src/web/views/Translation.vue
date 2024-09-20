<template>
  <div>
    <v-row>
      <v-col><v-sheet class="ma-auto pt-3 text-left main"><h3>Translations Configuration</h3></v-sheet></v-col>
      <v-col><v-text-field placeholder="search in translation" label="Search" variant="underlined" ></v-text-field></v-col>
    </v-row>
    <table style="width: 100%" class="tr_table">
      <thead class="fixed-header main-bg text-white">
        <tr>
          <th v-for="language in languages">
            {{ language.code }}
          </th>
          <th style="width: 8%">
            <span>Edit</span>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(tr, index) in $store.home_page_info.translations" :key="tr.id" :class="{'grey-bg': index % 2 !== 0}" class="text-left">
          <td v-for="language in languages" :key="language.code" class="pl-2 pr-1">
            <v-text-field v-model="tr.translation[language.code]" :disabled="!(editableTranslationRow?.id ===  tr.id)" density="compact" variant="underlined" hide-details>
              <template #append-inner>
                <v-icon class="mr-1" @click="editInPopup(tr.translation[language.code],language.code)">
                  mdi-tooltip-edit-outline
                </v-icon>
              </template>
            </v-text-field>
          </td>
          <td class="text-center">
            <v-btn class="main" icon="mdi-pencil-outline" variant="plain" @click="edit = true, editTranslation(tr,index)"></v-btn>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="fixed-actions">
    <v-tooltip  text="Create New Translation" location="top">
      <template v-slot:activator="{ props }">
        <v-btn class="float-end mt-4 mr-4 mb-4" color="primary" elevation="0" icon="mdi-plus-circle" size="35"
               variant="elevated" @click="createNewTranslation" v-bind="props"></v-btn>
      </template>
    </v-tooltip>

    <v-tooltip bottom text="Update Translation">
      <template v-slot:activator="{ props }">
        <v-btn class="float-end mt-4 mr-2 mb-4" color="primary" elevation="0" icon="mdi-check" size="35"
               variant="elevated" @click="updateTr" v-bind="props"></v-btn>
      </template>
    </v-tooltip>
  </div>

  <v-dialog v-model="creation_dialog" max-width="700">
    <v-card class="text-center">
      <template #title>
        <span class="primary-text">{{ tr('Add new translations') }}</span>
      </template>
      <v-card-text>
        <v-form>
          <v-text-field v-for="language in languages" v-model="newTranslation.translation[language.code]" :label="language.code"></v-text-field>
        </v-form>
      </v-card-text>
      <v-card-actions class="pl-6 pr-6">
        <v-btn width="200" class="white-text" variant="flat" color="danger" @click="creation_dialog = false">{{ tr('Cancel') }}</v-btn>
        <v-spacer></v-spacer>
        <v-btn width="200" variant="flat" color="success" @click="addTr">{{ tr('Add') }}</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>

  <v-dialog v-model="textEditionDialog" max-width="600" min-height="400">
    <v-card class="main-bg">
      <v-card-title class="text-white">Edit
        <v-icon class="float-end" color="white" @click="textEditionDialog = false">mdi-close-circle</v-icon>
      </v-card-title>
      <v-card-text>
        <v-textarea v-model="editableText" class="rounded special_input" density="compact" variant="plain" placeholder="Text...">
          <template #append-inner>
            <v-icon color="primary" @click="updateTr">mdi-check</v-icon>
          </template>
        </v-textarea>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  data() {
    return {
      editableTranslationRow: null,
      editableText: null,
      editableTextLanguage: null,
      creation_dialog: false,
      textEditionDialog: false,
      newTranslation: null,
      indexInList: null,
      edit: false,
    }
  },
  created() {
    this.$store.getHomePageInfo();
    this.reinitializeNewTr();
  },
  computed: {
    languages() {
      return this.$store.home_page_info.languages
    }
  },
  methods: {
    editInPopup(text, languageCode) {
      this.editableText = text;
      this.textEditionDialog = true;
      this.editableTextLanguage = languageCode
    },
    updateTr() {
      this.editableTranslationRow.translation[this.editableTextLanguage] = this.editableText;
      this.$store.saveTranslation(this.editableTranslationRow, this.indexInList);
      this.textEditionDialog = false;
    },
    reinitializeNewTr() {
      this.newTranslation = this.$utils.setNewTranslation()
    },
    addTr() {
      this.$store.saveTranslation(this.newTranslation);
      this.creation_dialog = false;
    },
    createNewTranslation() {
      this.reinitializeNewTr();
      this.creation_dialog = true;
    },
    editTranslation(tr, index) {
      this.indexInList = index
      this.editableTranslationRow = tr;
    },
  }
}
</script>

<style scoped>

.fixed-actions {
  position: fixed;
  right: 1vh;
  bottom: 0.3vh;
  background: white;
}

.small-col {
  width: 20%;
}

.fixed-header {
  position: sticky;
  top: 0;
  z-index: 1; /* Ensure the header stays above the table body */
  height: 6vh;
}

.table-container {
  overflow-x: auto; /* Enable horizontal scrolling if needed */
  height: 80vh;
  width: 100%;
}

</style>