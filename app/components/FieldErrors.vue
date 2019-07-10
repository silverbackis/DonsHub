<template>
  <ul v-if="errors" class="help is-danger">
    <li v-for="(error, index) in errors" :key="'error-' + index">
      {{ error.message || error }}
    </li>
  </ul>
</template>

<script>
export default {
  props: {
    errors: {
      type: Array,
      required: false,
      default: null
    }
  }
}
</script>
