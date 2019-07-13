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
      users: []
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
    let page = 1
    let data = await this.getUsers(page)
    this.populateUsers(data['hydra:member'])
    let hv = data['hydra:view']
    while (hv && hv['@id'] !== hv['hydra:last']) {
      page++
      data = await this.getUsers(page)
      this.populateUsers(data['hydra:member'])
      hv = data['hydra:view']
    }
    this.mercureMount(['/matches/{id}', '/chat_users/{id}'])
    this.eventSource.onmessage = this.mercureMessage
  },
  methods: {
    populateUsers(users) {
      const userEntities = {}
      // const min = -1
      // const max = 1
      users.forEach(user => {
        // const offsetLeftPercent =
        //   (Math.random() * (max - min + 1) + 1).toFixed(1) / 1
        userEntities[user['@id']] = user // Object.assign({ offsetLeftPercent }, user)
      })
      this.$bwstarter.setEntities(userEntities)
      this.users.push(...users.map(user => user['@id']))
    },
    async getUsers(page) {
      const { data } = await this.$axios.get('/chat_users?page=' + page)
      return data
    },
    getRoute(path) {
      return this.routePrefix + path
    },
    mercureMessage({ data: json }) {
      const data = this.receiveEntityData({ data: json })
      const userIndex = this.users.indexOf(data['@id'])
      if (Object.keys(data).length === 1 && userIndex !== -1) {
        this.users.splice(userIndex, 1)
      } else if (
        data['@context'] === '/contexts/ChatUser' &&
        userIndex === -1
      ) {
        this.users.unshift(data['@id'])
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
