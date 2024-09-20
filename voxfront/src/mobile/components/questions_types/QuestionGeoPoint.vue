<template>
  <div>
    <v-select
        v-model="selected_country"
        label="Countries"
        :items="countries"
        item-title="name"
        return-object
        outlined
    ></v-select>
    <v-select
        v-model="selected_province"
        label="Provinces"
        :items="selected_country.provinces"
        item-title="name"
        return-object
        outlined
    ></v-select>
    <v-select
        v-model="selected_district"
        label="Districts"
        :items="selected_province.districts"
        item-title="name"
        value="id"
        return-object
        outlined
        @update:modelValue="setModelValue"
    ></v-select>
  </div>
</template>

<script>
export default {
 props: ['question', 'modelValue', 'general_rules'],
  emits: [
    "update:modelValue"
  ],
  created(){
    this.$store.getHomePageInfo();
    this.countries = this.$store.home_page_info.countries
    this.selected_country = this.countries[0]
    this.selected_province = this.selected_country['provinces'][0]
    this.selected_district = this.selected_province['districts'][0]
  },
  data() {
    return {
      selected_province: {},
      selected_district: {},
      selected_country: {},
      countries: [],
    };
  },
  methods: {
    setModelValue(){
      console.log(JSON.stringify(this.selected_district))
      this.$emit("update:modelValue", JSON.stringify(this.selected_district))
    }
  },
}
</script>