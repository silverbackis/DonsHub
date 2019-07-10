<template>
  <section class="section league-table">
    <div class="container has-text-centered">
      <h1 class="title has-text-primary">{{ match.leagueName }}</h1>
    </div>
    <div v-if="leagueData === null" class="has-text-centered">
      <div class="loader is-medium is-primary">
        <span class="is-sr-only">Loading</span>
      </div>
    </div>
    <div v-else-if="!leagueData.length" class="has-text-centered">
      <h2 class="subtitle">No league information available</h2>
    </div>
    <div v-else class="striped-table">
      <div class="row is-header">
        <div class="container">
          <div class="columns is-mobile is-fullwidth">
            <div class="column is-1">
              P
            </div>
            <div class="column">
              Team
            </div>
            <div class="column is-1 is-hidden-mobile has-text-centered">
              GP
            </div>
            <div class="column is-1 is-hidden-mobile has-text-centered">
              GD
            </div>
            <div class="column is-1 has-text-centered">
              PTS
            </div>
          </div>
        </div>
      </div>
      <league-table-row
        v-for="team in leagueData"
        :key="team"
        class="row"
        :team="team"
      />
    </div>
  </section>
</template>

<script>
import { mapGetters } from 'vuex'
import LeagueTableRow from '~/components/iploughlane/LeagueTableRow'
import MercureMixin from '~/components/MercureMixin'

export default {
  components: { LeagueTableRow },
  mixins: [MercureMixin],
  data() {
    return {
      leagueData: null
    }
  },
  computed: {
    ...mapGetters({
      match: 'currentMatch'
    })
  },
  async mounted() {
    if (!this.match.matchLeague) {
      this.leagueData = []
      return
    }
    const {
      data: { matchLeagueTeams: teams }
    } = await this.$axios.get(this.match.matchLeague)
    const leagueTeams = {}
    teams.forEach(team => {
      leagueTeams[team['@id']] = team
    })
    this.$bwstarter.setEntities(leagueTeams)
    this.leagueData = teams.map(team => team['@id'])

    this.mercureMount(['/match_league_teams/{id}'])
  },
  head: {
    title: 'Scores'
  }
}
</script>

<style lang="sass">
@import "assets/sass/utilities"
.section.league-table
  padding-left: 0
  padding-right: 0
  h1
    margin-bottom: .5rem
  .striped-table
    font-size: 1.15rem
    +mobile
      font-size: 1rem
    .row
      color: $blue
      .column.is-1
        min-width: 65px
      .columns
        margin: 0
      &:nth-child(2n+2)
        background: $white
      &.is-header
        color: $grey
      &:not(.is-header)
        .column:nth-child(2)
          color: $grey-dark
</style>
