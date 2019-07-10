<template>
  <section class="section scores">
    <div class="container has-text-centered">
      <h1 class="title has-text-primary">{{ match.leagueName }}</h1>
    </div>
    <div v-if="scores === null" class="has-text-centered">
      <div class="loader is-medium is-primary">
        <span class="is-sr-only">Loading</span>
      </div>
    </div>
    <div v-else-if="!scores.length" class="has-text-centered">
      <h2 class="subtitle">No scores available</h2>
    </div>
    <div v-else class="striped-table">
      <scores-row
        v-for="score in scores"
        :key="score['@id']"
        class="row"
        :score="score"
      />
    </div>
  </section>
</template>

<script>
import { mapGetters } from 'vuex'
import ScoresRow from '~/components/iploughlane/ScoresRow'

export default {
  components: { ScoresRow },
  data() {
    return {
      scores: null
    }
  },
  computed: {
    ...mapGetters({
      match: 'currentMatch',
      matchDateTime: 'matchDateTime'
    })
  },
  async mounted() {
    const date = this.matchDateTime.format('YYYY-MM-DD')
    const searchParams = {
      'matchDateTime[after]': date + ' 00:00:00',
      'matchDateTime[before]': date + ' 23:59:59'
    }
    const qs = Object.keys(searchParams)
      .map(key => {
        return (
          encodeURIComponent(key) + '=' + encodeURIComponent(searchParams[key])
        )
      })
      .join('&')
    const { data } = await this.$axios.get('/matches?' + qs)
    const scoreEntities = {}
    data['hydra:member'].forEach(match => {
      scoreEntities[match['@id']] = match
    })
    this.$bwstarter.setEntities(scoreEntities)
    this.scores = data['hydra:member'].map(match => match['@id'])
  },
  head: {
    title: 'Scores'
  }
}
</script>

<style lang="sass">
@import "assets/sass/utilities"
.section.scores
  padding-left: 0
  padding-right: 0
  .striped-table
    font-size: 1.15rem
    +mobile
      font-size: 1rem
    .row
      color: $grey-dark
      &:nth-child(2n+2)
        background: $white
      .columns
        margin: 0
        .column:nth-child(2)
          color: $blue
</style>
