<template>
  <zone-page :route-prefix="routePrefix">
    <h1 class="title">
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
  }
}
</script>
