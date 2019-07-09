<template>
  <section class="section league-table">
    <div class="container has-text-centered">
      <h1 class="title has-text-primary">{{ match.leagueName }}</h1>
    </div>
    <div v-if="!match.matchLeague" class="has-text-centered">
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
            <div class="column is-1 is-hidden-mobile">
              GP
            </div>
            <div class="column is-1 is-hidden-mobile">
              GD
            </div>
            <div class="column is-2">
              PTS
            </div>
          </div>
        </div>
      </div>
      <div v-for="x in 20" :key="'tr-' + x" class="row">
        <div class="container">
          <div class="columns is-mobile is-fullwidth">
            <div class="column is-1">
              {{ x }}
            </div>
            <div class="column">
              Team
            </div>
            <div class="column is-1 is-hidden-mobile">
              GP
            </div>
            <div class="column is-1 is-hidden-mobile">
              GD
            </div>
            <div class="column is-2">
              PTS
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  computed: {
    ...mapGetters({
      match: 'currentMatch'
    })
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
