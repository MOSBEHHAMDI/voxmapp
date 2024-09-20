<script>
import {compile,h} from "vue"

export default {
    props: {
        class: String,
        content: String
    },
  render() {
    return this.content ? h(compile(this.content)) : this.content
  }
}
</script>
