<template>
  <div>
    <component :is="question.config.choice.multiple ? 'v-checkbox' : 'v-radio-group'"
               v-model="answer"
               :choices="question.choices"
               :choice-label="choiceLabel"
               color="orange">
    </component>
  </div>
</template>

<script>
export default {
  data() {
    return {
      selectedChoices: [],
    }
  },
  props: ['question', 'modelValue', 'general_rules'],
  emits: [
    "update:modelValue"
  ],
  computed: {
    answer: {
      get() {
        return this.modelValue
      },
      set(value) {
        //to do validation target
        this.$emit("update:modelValue", value)
      }
    }
  },
  methods: {
    choiceLabel(choice) {
      return this.$utils.getLabel(choice.label, null, false, true);
    },
  },
  components: {
    'v-checkbox': {
      props: ['choices', 'choiceLabel', 'modelValue'],
      computed: {
        selected: {
          get() {
            return this.modelValue ? this.modelValue.split('|') : []
          },
          set(value) {
            this.$emit("update:modelValue", value.join('|'))
          }
        }
      },
      template: `
        <v-checkbox v-for="choice in choices"
                    v-model="selected"
                    color="orange"
                    density="compact"
                    class="border-5 grey-border mb-2 pl-3 pr-3 pt-1 pb-2 choice_check"
                    hide-details
                    :label="choiceLabel(choice)"
                    :value="choiceLabel(choice)"
                    :key="choice.id">
        </v-checkbox>
      `,
    },
    'v-radio-group': {
      props: ['choices', 'choiceLabel', 'value'],
      template: `
        <v-radio-group>
          <v-radio :label="choiceLabel(choice)"
                   :value="choiceLabel(choice)"
                   v-for="choice in choices"
                   class="border-5 grey-border mb-2 pl-3 pr-3 pt-1 pb-2 choice_check"
                   :key="choice.id">
          </v-radio>
        </v-radio-group>
      `,
    },
  },
}
</script>

<style>
.border-5 {
  border-radius: 5px;
}

.grey-border {
  border: solid 1px gray;
}

.choice_check:has(.text-orange) {
  border: solid 1px #fdaa0d;
}
</style>
