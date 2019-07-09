<template>
  <ul
    class="boarding columns is-mobile is-gapless is-centered is-hidden-touch is-marginless"
  >
    <li
      v-for="x in 20"
      :key="'board' + x"
      class="column is-narrow has-background-grey-lighter"
    >
      <img
        :src="placeholderImage"
        alt="square placeholder"
        class="avatar-placeholder"
      />
    </li>
  </ul>
</template>

<script>
export default {
  data() {
    return {
      placeholderImage:
        'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewBox="0 0 230 100" width="230px" height="100px"></svg>'
    }
  }
}
</script>

<style lang="sass">
@import "assets/sass/utilities"
.boarding
  width: 100%
  white-space: nowrap
  overflow: hidden
  height: 90px
  .column
    height: 100%
    img
      height: 100%
      border: solid $grey
      border-width: 0 2px 0 0
</style>
