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
        <nuxt-child :route-prefix="routePrefix" class="column" />
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
  data() {
    return {
      users: null
    }
  },
  computed: {
    ...mapGetters({
      gatesOpen: 'gatesOpen'
    })
  },
  asyncData({ route, redirect }) {
    const routePrefix = '/iploughlane/match'
    if (route.name === 'iploughlane-match') {
      redirect(routePrefix + '/chat')
      return {}
    }
    return {
      routePrefix
    }
  },
  async mounted() {
    const { data: users } = await this.$axios.get('/chat_users')
    const userEntities = {}
    users['hydra:member'].forEach(user => {
      userEntities[user['@id']] = user
    })
    this.$bwstarter.setEntities(userEntities)
    this.users = users['hydra:member'].map(user => user['@id'])

    this.mercureMount(['/matches/{id}', '/chat_users/{id}'])
    this.eventSource.onmessage = this.mercureMessage
  },
  methods: {
    getRoute(path) {
      return this.routePrefix + path
    },
    mercureMessage({ data: json }) {
      const data = this.receiveEntityData({ data: json })
      if (data['@context'] === '/contexts/ChatUser') {
        if (this.users.indexOf(data['@id']) === -1) {
          this.users.unshift(data['@id'])
        }
      }
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
      margin-top: 2.5rem
      margin-bottom: 2rem
</style>
