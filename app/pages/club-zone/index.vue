<template>
  <zone-page :route-prefix="routePrefix">
    <h1 class="title">
      <img
        src="~assets/images/icon-clubzone.svg"
        alt="Club Zone Icon"
        class="icon"
      />
      Club Zone
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
    const routePrefix = '/club-zone'
    if (route.name === routePrefix.substr(1)) {
      redirect(routePrefix + '/twitter')
    }
    return {
      routePrefix
    }
  },
  head: {
    titleTemplate: '%s - Club Zone - Dons Hub'
  }
}
</script>
