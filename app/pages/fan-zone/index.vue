<template>
  <zone-page :route-prefix="routePrefix">
    <h1 class="title">
      <div class="is-inline-block white-circle icon">
        <img
          src="~assets/images/icon-fanzone.svg"
          alt="Club Zone Icon"
          class="icon"
        />
      </div>
      Fan Zone
    </h1>
  </zone-page>
</template>

<script>
import ZonePage from '~/components/ZonePage'

export default {
  components: {
    ZonePage
  },
  asyncData({ route, redirect }) {
    const routePrefix = '/fan-zone'
    if (route.name === routePrefix.substr(1)) {
      redirect(routePrefix + '/twitter')
    }
    return {
      routePrefix
    }
  }
}
</script>

<style lang="sass">
@import "assets/sass/utilities"
.white-circle
  .icon
    margin-top: 5px
</style>
