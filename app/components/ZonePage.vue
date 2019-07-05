<template>
  <div class="columns flex-direction-column white-page is-gapless">
    <div class="column is-narrow">
      <hero>
        <slot />
      </hero>
    </div>
    <div class="column page-content">
      <div class="tabs is-centered is-medium is-marginless">
        <ul>
          <app-link tag="li" :to="getRoute('/twitter')">
            <a>Twitter</a>
          </app-link>
          <app-link tag="li" :to="getRoute('/youtube')">
            <a>Youtube</a>
          </app-link>
          <app-link tag="li" :to="getRoute('/instagram')">
            <a>Instagram</a>
          </app-link>
        </ul>
      </div>
      <nuxt-child :route-prefix="routePrefix" />
    </div>
  </div>
</template>

<script>
import Hero from '~/components/Hero'
import AppLink from '~/components/AppLink'

export default {
  components: {
    AppLink,
    Hero
  },
  props: {
    routePrefix: {
      type: String,
      required: true
    }
  },
  methods: {
    getRoute(path) {
      return this.routePrefix + path
    }
  }
}
</script>
