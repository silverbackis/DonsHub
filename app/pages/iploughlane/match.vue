<template>
  <div class="columns flex-direction-column white-page is-gapless match-page">
    <div class="column is-narrow">
      <scoreboard />
      <terraces :users="users" />
    </div>
    <div class="column page-content">
      <div class="tabs is-centered is-medium is-marginless">
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
      <nuxt-child :route-prefix="routePrefix" />
    </div>
  </div>
</template>

<script>
import { mapState, mapGetters } from 'vuex'
import { name as entitiesModuleName } from '~/.nuxt/bwstarter/core/storage/entities'
import Scoreboard from '~/components/iploughlane/Scoreboard'
import Terraces from '~/components/iploughlane/Terraces'
import AppLink from '~/components/AppLink'

export default {
  components: {
    Scoreboard,
    Terraces,
    AppLink
  },
  data() {
    return {
      eventSource: null
    }
  },
  computed: {
    ...mapState({
      mercureHub: 'mercureHub'
    }),
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
    const mercureUrl = new URL(this.mercureHub)
    const baseUrl = new URL(this.$store.state.baseUrl)
    const topicBaseUrl = baseUrl.protocol + '//' + baseUrl.hostname
    mercureUrl.searchParams.append('topic', topicBaseUrl + '/matches/{id}')
    this.eventSource = new EventSource(mercureUrl.toString())
    this.eventSource.onmessage = this.receiveMatchData
  },
  beforeDestroy() {
    this.eventSource.onmessage = null
    this.eventSource = null
  },
  methods: {
    receiveMatchData({ data: json }) {
      const data = JSON.parse(json)
      this.$bwstarter.$storage.commit(
        'setEntity',
        [{ id: data['@id'], data }],
        entitiesModuleName
      )
    },
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
    .has-text-centered .loader
      display: inline-block
    .section > .container:first-child > h1
      margin-bottom: 1.5rem
</style>
