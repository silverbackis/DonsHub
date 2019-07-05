<template>
  <li class="menu-link-list-item">
    <nuxt-link v-bind="$props" active-class="is-active" class="menu-link">
      <slot />
    </nuxt-link>
  </li>
</template>

<script>
export default {
  props: {
    to: {
      type: [Object, String],
      required: true
    },
    exact: {
      type: Boolean,
      required: false,
      default: false
    }
  }
}
</script>

<style lang="sass">
@import 'assets/sass/utilities'
.menu-link-list-item
  font-size: 1.1rem
  +mobile
    font-size: .95rem
  &:not(:first-child)
    margin-top: .5rem
</style>
