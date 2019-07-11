<template>
  <div class="load-more-row">
    <div class="container has-text-centered">
      <button
        :class="[
          'button is-primary is-rounded is-fullwidth is-medium',
          arrowDown ? 'has-arrow-down' : 'has-arrow'
        ]"
        :disabled="disabled"
        @click="$emit('click')"
      >
        <slot />
      </button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    arrowDown: {
      type: Boolean,
      required: false,
      default: true
    },
    disabled: {
      type: Boolean,
      required: false,
      default: false
    }
  }
}
</script>

<style lang="sass">
.load-more-row
  padding: 0 2.5rem
  margin-bottom: 4rem
  .button
    display: inline-block
    max-width: 400px
</style>
