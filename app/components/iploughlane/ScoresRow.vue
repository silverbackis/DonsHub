<template>
  <div class="scores-row">
    <div class="container">
      <div class="columns is-mobile is-fullwidth">
        <div
          class="column has-text-right"
          :class="{
            'has-text-weight-bold': match.matchHomeTeamId === clubTeamId
          }"
        >
          {{ match.matchHomeTeamName }}
        </div>
        <div class="column is-2 has-text-centered">
          {{ match.matchHomeTeamScore }} - {{ match.matchAwayTeamScore }}<br />
          <span class="status">{{ match.matchStatus }}</span>
        </div>
        <div class="column has-text-left">
          {{ match.matchAwayTeamName }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'

export default {
  props: {
    score: {
      type: String,
      required: true
    }
  },
  computed: {
    ...mapState({
      clubTeamId: 'clubTeamId'
    }),
    match() {
      return this.getEntity(this.score)
    }
  }
}
</script>

<style lang="sass">
@import "assets/sass/utilities"
.status
  font-size: .7rem
  color: $grey-light
  font-weight: bold
</style>
