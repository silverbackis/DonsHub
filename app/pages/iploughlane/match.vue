<template>
  <div
    class="columns flex-direction-column white-page is-gapless match-page is-mobile"
  >
    <div class="column is-narrow">
      <scoreboard />
      <terraces :users="users" />
    </div>
    <div class="column page-content">
      <div
        class="columns flex-direction-column is-gapless is-mobile tabs-vertical-columns"
      >
        <div class="column is-narrow tabs is-centered is-medium is-marginless">
          <ul>
            <app-link tag="li" :to="getRoute('/chat')">
              <a>Chat</a>
            </app-link>
            <app-link tag="li" :to="getRoute('/live')">
              <a>Live</a>
            </app-link>
            <app-link tag="li" :to="getRoute('/scores')">
              <a>Scores</a>
            </app-link>
            <app-link tag="li" :to="getRoute('/table')">
              <a>Table</a>
            </app-link>
          </ul>
        </div>
        <nuxt-child class="column" />
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Scoreboard from '~/components/iploughlane/Scoreboard'
import Terraces from '~/components/iploughlane/Terraces'
import AppLink from '~/components/AppLink'
import MercureMixin from '~/components/MercureMixin'

export default {
  components: {
    Scoreboard,
    Terraces,
    AppLink
  },
  mixins: [MercureMixin],
  computed: {
    ...mapGetters({
      gatesOpen: 'gatesOpen'
    })
  },
  async asyncData({ $axios, route, redirect }) {
    const routePrefix = '/iploughlane/match'
    if (route.name === 'iploughlane-match') {
      redirect(routePrefix + '/chat')
      return {}
    }

    const { data: users } = await $axios.get('/chat_users')
    return {
      routePrefix,
      users
    }
  },
  mounted() {
    this.mercureMount(['/matches/{id}'])
  },
  methods: {
    getRoute(path) {
      return this.routePrefix + path
    }
  },
  head: {
    titleTemplate: '%s - iPloughLane - Dons Hub'
  },
  validate({ store, redirect, $bwstarter }) {
    if (!store.getters.gatesOpen || !$bwstarter.user) {
      redirect('/iploughlane')
      return false
    }
    return true
  }
}
</script>

<style lang="sass">
@import "assets/sass/utilities"
.match-page
  .page-content
    background: $grey-lightest
    .tabs-vertical-columns
      height: 100%
    .tabs
      position: sticky
      top: 0
      background: $white
      z-index: 10
    .has-text-centered .loader
      display: inline-block
    .section > .container:first-child > h1
      margin-bottom: 1.5rem
</style>
